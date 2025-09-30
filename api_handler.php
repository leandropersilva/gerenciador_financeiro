<?php
header('Content-Type: application/json');

$apiKey = getenv('GEMINI_API_KEY') ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); 
    echo json_encode(['error' => 'Método não permitido. Use POST.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$prompt = $input['prompt'] ?? '';

if (empty($prompt)) {
    http_response_code(400);
    echo json_encode(['error' => 'O prompt não pode estar vazio.']);
    exit;
}

$apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $apiKey;

$data = [
    'contents' => [
        [
            'parts' => [
                ['text' => $prompt]
            ]
        ]
    ]
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 6. Processe e retorne a resposta
if ($httpCode === 200) {
    $responseData = json_decode($response, true);
    // Extrai o texto da resposta da estrutura do Gemini
    $text = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'Não foi possível obter uma resposta.';
    echo json_encode(['reply' => $text]);
} else {
    http_response_code($httpCode);
    echo json_encode(['error' => 'Erro ao chamar a API do Gemini.', 'details' => json_decode($response)]);
}
