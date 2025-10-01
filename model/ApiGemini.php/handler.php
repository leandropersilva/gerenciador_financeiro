<?php

final class GeminiHandler
{
    private $apiKey;
    private $model;
    private $baseUrl;
    private $timeout;

    public function __construct(
        $apiKey = 'AIzaSyD_cnheHtx1k4Hebg3VZFjECsdwhKFgvRw',
        $model = 'gemini-pro',
        $baseUrl = 'https://generativelanguage.googleapis.com/v1beta',
        $timeout = 30
    ) {
        $this->apiKey  = 'AIzaSyD_cnheHtx1k4Hebg3VZFjECsdwhKFgvRw';
        $this->model   = $model;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
    }

    public function handle()
    {
        // return $this->send(200, ['reply' => 'OK']);
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
            // Erro ao falar com o provedor
            $this->send($reply['status'], [
                'error' => $reply['error'],
                'details' => $reply['details'] ?? null
            ]);
            return;
        }

        $this->send(200, [
            'success' => true,
            'prompt'  => $prompt,
            'reply'   => $reply['text'],
        ]);
    }

    private function generateContent(string $prompt)
    {
        $url = sprintf(
            '%s/models/%s:generateContent?key=%s',
            $this->baseUrl,
            $this->model,
            urlencode($this->apiKey)
        );

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $json,
            CURLOPT_TIMEOUT        => $this->timeout,
            // Em produção, mantenha a verificação SSL ativada (true por padrão)
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $err = curl_error($ch);
            curl_close($ch);
            return [
                'ok' => false,
                'status' => 504,
                'error' => 'UPSTREAM_TIMEOUT',
                'details' => $err,
            ];
        }

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE) ?: 0;
        curl_close($ch);

        $data = json_decode($response, true);

        if ($status !== 200) {
            return [
                'ok' => false,
                'status' => $status ?: 502,
                'error' => 'UPSTREAM_ERROR',
                'details' => $data ?: $response,
            ];
        }

        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (!is_string($text) || $text === '') {
            return [
                'ok' => false,
                'status' => 502,
                'error' => 'INVALID_UPSTREAM_RESPONSE',
                'details' => $data,
            ];
        }

        return [
            'ok'   => true,
            'text' => $text,
        ];
    }

    private function send(int $status, array $body): void
    {
        http_response_code($status);
        echo json_encode($body, JSON_UNESCAPED_UNICODE);
        // Opcional: exit; se este script for o endpoint final
    }

    private function sendError(int $status, string $message): void
    {
        $this->send($status, ['error' => $message]);
    }
}
