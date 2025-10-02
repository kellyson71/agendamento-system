<?php
echo "=== TESTE DA API EM PRODUÇÃO ===\n\n";

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

echo "1. Testando conectividade básica...\n";
$result = makeRequest($baseUrl);
echo "Status: {$result['code']}\n";
echo "Resposta: " . substr($result['raw'], 0, 200) . "...\n\n";

echo "2. Testando endpoint de serviços...\n";
$result = makeRequest("{$baseUrl}/api/services");
echo "Status: {$result['code']}\n";
echo "Resposta: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "3. Testando endpoint de slots...\n";
$result = makeRequest("{$baseUrl}/api/slots?service_id=1&date=2024-01-15");
echo "Status: {$result['code']}\n";
echo "Resposta: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "4. Testando endpoint administrativo (sem auth)...\n";
$result = makeRequest("{$baseUrl}/api/admin/schedule");
echo "Status: {$result['code']}\n";
echo "Resposta: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "5. Testando endpoint administrativo (com auth)...\n";
$result = makeRequest("{$baseUrl}/api/admin/schedule", 'GET', null, ["X-API-Key: {$apiKey}"]);
echo "Status: {$result['code']}\n";
echo "Resposta: " . json_encode($result['body'], JSON_PRETTY_PRINT) . "\n\n";

echo "6. Testando documentação...\n";
$result = makeRequest("{$baseUrl}/docs/");
echo "Status: {$result['code']}\n";
echo "Resposta: " . substr($result['raw'], 0, 200) . "...\n\n";

echo "=== DIAGNÓSTICO ===\n";
if ($result['code'] === 200 && strpos($result['raw'], 'API de Sistema de Agendamento') !== false) {
    echo "✅ Documentação está funcionando\n";
} else {
    echo "❌ Documentação não está funcionando\n";
}

if ($result['code'] === 200 && strpos($result['raw'], 'Painel de Autenticação') !== false) {
    echo "✅ Painel interativo está funcionando\n";
} else {
    echo "❌ Painel interativo não está funcionando\n";
}

echo "\n=== PRÓXIMOS PASSOS ===\n";
echo "1. Se a API não estiver funcionando, verifique o servidor\n";
echo "2. Se a documentação não estiver funcionando, verifique o deploy\n";
echo "3. Para ambiente local, inicie o WAMP e execute: php setup_local.php\n";
?>
