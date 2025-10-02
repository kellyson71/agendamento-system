<?php
echo "=== TESTE DA API DE AGENDAMENTO ===\n\n";

$baseUrl = 'http://localhost:8000';
$apiKey = 'agendamento_api_key_2024';

function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $headers[] = 'Content-Type: application/json';
    }
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'body' => json_decode($response, true)
    ];
}

echo "1. Testando endpoint de serviços...\n";
$response = makeRequest("{$baseUrl}/api/services");
echo "Status: {$response['code']}\n";
echo "Resposta: " . json_encode($response['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "2. Testando endpoint de horários disponíveis...\n";
$response = makeRequest("{$baseUrl}/api/slots?service_id=1&date=" . date('Y-m-d', strtotime('+1 day')));
echo "Status: {$response['code']}\n";
echo "Resposta: " . json_encode($response['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "3. Testando criação de agendamento...\n";
$appointmentData = [
    'service_id' => 1,
    'date' => date('Y-m-d', strtotime('+1 day')),
    'time' => '09:00',
    'client_name' => 'João Silva Teste',
    'client_phone' => '(11) 99999-1111',
    'client_email' => 'joao@teste.com',
    'is_online' => true
];
$response = makeRequest("{$baseUrl}/api/appointments", 'POST', $appointmentData);
echo "Status: {$response['code']}\n";
echo "Resposta: " . json_encode($response['body'], JSON_PRETTY_PRINT) . "\n\n";

if ($response['code'] === 200 && isset($response['body']['data']['id'])) {
    $appointmentId = $response['body']['data']['id'];
    
    echo "4. Testando atualização de status do agendamento...\n";
    $statusData = ['new_status' => 'confirmed'];
    $response = makeRequest("{$baseUrl}/api/appointments/{$appointmentId}/status", 'PUT', $statusData, ["X-API-Key: {$apiKey}"]);
    echo "Status: {$response['code']}\n";
    echo "Resposta: " . json_encode($response['body'], JSON_PRETTY_PRINT) . "\n\n";
    
    echo "5. Testando cancelamento do agendamento...\n";
    $response = makeRequest("{$baseUrl}/api/appointments/{$appointmentId}", 'DELETE', null, ["X-API-Key: {$apiKey}"]);
    echo "Status: {$response['code']}\n";
    echo "Resposta: " . json_encode($response['body'], JSON_PRETTY_PRINT) . "\n\n";
}

echo "6. Testando endpoint administrativo (sem auth)...\n";
$response = makeRequest("{$baseUrl}/api/admin/schedule");
echo "Status: {$response['code']}\n";
echo "Resposta: " . json_encode($response['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "7. Testando endpoint administrativo (com auth)...\n";
$response = makeRequest("{$baseUrl}/api/admin/schedule", 'GET', null, ["X-API-Key: {$apiKey}"]);
echo "Status: {$response['code']}\n";
echo "Resposta: " . json_encode($response['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "8. Testando endpoint de profissionais...\n";
$response = makeRequest("{$baseUrl}/api/admin/professionals", 'GET', null, ["X-API-Key: {$apiKey}"]);
echo "Status: {$response['code']}\n";
echo "Resposta: " . json_encode($response['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "9. Testando endpoint de configurações...\n";
$response = makeRequest("{$baseUrl}/api/admin/settings", 'GET', null, ["X-API-Key: {$apiKey}"]);
echo "Status: {$response['code']}\n";
echo "Resposta: " . json_encode($response['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== TESTE CONCLUÍDO ===\n";
?>
