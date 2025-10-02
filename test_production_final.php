<?php
echo "=== TESTE PÓS-DEPLOY DA API EM PRODUÇÃO ===\n\n";

$baseUrl = 'https://api.estagiopaudosferros.com';
$apiKey = 'agendamento_api_key_2024';

function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $headers[] = 'Content-Type: application/json';
    }
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return [
            'code' => 0,
            'body' => ['error' => 'CURL Error: ' . $error],
            'raw' => $response
        ];
    }
    
    return [
        'code' => $httpCode,
        'body' => json_decode($response, true),
        'raw' => $response
    ];
}

echo "1. Testando roteamento básico...\n";
$result = makeRequest($baseUrl);
echo "Status: {$result['code']}\n";
if ($result['code'] === 200) {
    echo "✅ Roteamento funcionando!\n";
} else {
    echo "❌ Roteamento ainda com problemas\n";
}
echo "Resposta: " . substr($result['raw'], 0, 200) . "\n\n";

echo "2. Testando endpoint de serviços...\n";
$result = makeRequest("{$baseUrl}/api/services");
echo "Status: {$result['code']}\n";
if ($result['code'] === 200 && isset($result['body']['success'])) {
    echo "✅ API de serviços funcionando!\n";
    echo "Serviços encontrados: " . count($result['body']['data']) . "\n";
} else {
    echo "❌ API de serviços com problemas\n";
}
echo "Resposta: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "3. Testando endpoint de slots...\n";
$result = makeRequest("{$baseUrl}/api/slots?service_id=1&date=2025-10-02");
echo "Status: {$result['code']}\n";
if ($result['code'] === 200 && isset($result['body']['success'])) {
    echo "✅ API de slots funcionando!\n";
    echo "Slots encontrados: " . count($result['body']['data']) . "\n";
} else {
    echo "❌ API de slots com problemas\n";
}
echo "Resposta: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "4. Testando autenticação...\n";
$result = makeRequest("{$baseUrl}/api/admin/schedule", 'GET', null, ["X-API-Key: {$apiKey}"]);
echo "Status: {$result['code']}\n";
if ($result['code'] === 200 && isset($result['body']['success'])) {
    echo "✅ Autenticação funcionando!\n";
} else {
    echo "❌ Autenticação com problemas\n";
}
echo "Resposta: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "5. Testando criação de agendamento...\n";
$appointmentData = [
    'service_id' => 1,
    'date' => '2025-10-02',
    'time' => '09:00',
    'client_name' => 'Teste Produção',
    'client_phone' => '(11) 99999-9999',
    'client_email' => 'teste@producao.com',
    'is_online' => true
];
$result = makeRequest("{$baseUrl}/api/appointments", 'POST', $appointmentData);
echo "Status: {$result['code']}\n";
if ($result['code'] === 200 && isset($result['body']['success'])) {
    echo "✅ Criação de agendamento funcionando!\n";
    echo "ID do agendamento: " . $result['body']['data']['id'] . "\n";
} else {
    echo "❌ Criação de agendamento com problemas\n";
}
echo "Resposta: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== RESUMO ===\n";
echo "Se todos os testes passaram, a API está funcionando em produção!\n";
echo "Acesse: https://api.estagiopaudosferros.com/docs/\n";
echo "API Key: agendamento_api_key_2024\n";
?>
