<?php

final class GeminiHandler
{
    private $apiKey;
    private $model;
    private $baseUrl;
    private $timeout;

    public function __construct(
        $apiKey = null,
        $model = 'gemini-2.0-flash',
        $baseUrl = 'https://generativelanguage.googleapis.com/v1beta',
        $timeout = 30
    ) {
        $this->apiKey  = trim($apiKey ?? 'AIzaSyCvHbeSVLsWydTxl6ALoXfL7bM7NLy17lA');
        $this->model   = $model;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
    }

    public function handle()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendError(405, 'Método não permitido. Use POST.');
            return;
        }

        if ($this->apiKey === '') {
            $this->sendError(500, 'Chave da API ausente. Defina GEMINI_API_KEY no servidor.');
            return;
        }

        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendError(400, 'JSON inválido no corpo da requisição.');
            return;
        }

        $prompt = trim((string)($input['prompt'] ?? ''));

        if ($prompt === '') {
            $this->sendError(400, 'O prompt não pode estar vazio.');
            return;
        }

        $reply = $this->generateContent($prompt);
        if ($reply['ok'] === false) {
            $this->send($reply['status'], [
                'error'   => $reply['error'],
                'details' => $reply['details'] ?? null,
            ]);
            return;
        }

        $this->send(200, [
            'success' => true,
            'prompt'  => $prompt,
            'reply'   => $reply['text'],
            'usage'   => $reply['usage'] ?? null,
            'model'   => $reply['modelVersion'] ?? null,
            'id'      => $reply['responseId'] ?? null,
        ]);
    }

    private function generateContent($prompt)
    {
        $url = sprintf(
            '%s/models/%s:generateContent',
            $this->baseUrl,
            $this->model
        );

        $payload = [
            'contents' => [
                [
                    'role'  => 'user',
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'x-goog-api-key: ' . $this->apiKey,
            ],
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $json,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_CAINFO => __DIR__ . '/../../cacert.pem',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $errNo = curl_errno($ch);
            $err   = curl_error($ch);
            curl_close($ch);

            // LOG TEMPORÁRIO - REMOVER DEPOIS
            error_log("CURL ERROR - Code: $errNo | Message: $err");

            // Mapeia erros específicos
            $errorMap = [
                6 => ['status' => 502, 'error' => 'DNS_RESOLUTION_FAILED'],
                7 => ['status' => 502, 'error' => 'CONNECTION_REFUSED'],
                28 => ['status' => 504, 'error' => 'UPSTREAM_TIMEOUT'],
                35 => ['status' => 502, 'error' => 'SSL_HANDSHAKE_FAILED'],
                51 => ['status' => 502, 'error' => 'SSL_PEER_VERIFICATION_FAILED'],
                60 => ['status' => 502, 'error' => 'SSL_CACERT_MISSING'],
                77 => ['status' => 502, 'error' => 'SSL_CACERT_BADFILE'],
            ];

            $mapped = $errorMap[$errNo] ?? ['status' => 502, 'error' => 'UPSTREAM_NETWORK_ERROR'];

            return [
                'ok'      => false,
                'status'  => $mapped['status'],
                'error'   => $mapped['error'],
                'details' => [
                    'curl_code'    => $errNo,
                    'curl_message' => $err,
                    'url'          => $url
                ],
            ];
        }

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE) ?: 0;
        curl_close($ch);

        $data = json_decode($response, true);

        if ($status !== 200) {
            return [
                'ok'     => false,
                'status' => $status ?: 502,
                'error'  => 'UPSTREAM_ERROR',
                'details' => is_array($data) ? $data : ['raw' => $response],
            ];
        }

        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        if (!is_string($text) || $text === '') {
            return [
                'ok'     => false,
                'status' => 502,
                'error'  => 'INVALID_UPSTREAM_RESPONSE',
                'details' => $data,
            ];
        }

        return [
            'ok'           => true,
            'text'         => $text,
            'usage'        => $data['usageMetadata'] ?? null,
            'modelVersion' => $data['modelVersion'] ?? null,
            'responseId'   => $data['responseId'] ?? null,
        ];
    }


    private function send($status, $body)
    {
        http_response_code($status);
        echo json_encode($body, JSON_UNESCAPED_UNICODE);
    }

    private function sendError($status, $message)
    {
        $this->send($status, ['error' => $message]);
    }
}
