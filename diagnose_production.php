<?php
echo "=== DIAGNÓSTICO DETALHADO DA API EM PRODUÇÃO ===\n\n";

$baseUrl = 'https://api.estagiopaudosferros.com';

function testEndpoint($url, $description) {
    echo "Testando: $description\n";
    echo "URL: $url\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ CURL Error: $error\n";
        return;
    }
    
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    
    echo "Status: $httpCode\n";
    echo "Headers:\n";
    echo $headers . "\n";
    echo "Body (primeiros 500 chars):\n";
    echo substr($body, 0, 500) . "\n";
    echo str_repeat("-", 80) . "\n\n";
}

// Testar diferentes endpoints
testEndpoint($baseUrl, "Raiz do servidor");
testEndpoint($baseUrl . "/", "Raiz com barra");
testEndpoint($baseUrl . "/index.php", "Index.php direto");
testEndpoint($baseUrl . "/api", "API sem barra");
testEndpoint($baseUrl . "/api/", "API com barra");
testEndpoint($baseUrl . "/api/services", "Endpoint de serviços");
testEndpoint($baseUrl . "/docs/", "Documentação");

echo "=== ANÁLISE ===\n";
echo "1. Se todos retornam 404, o servidor não está configurado para roteamento\n";
echo "2. Se index.php funciona, falta configuração de .htaccess\n";
echo "3. Se API retorna HTML, há problema de roteamento\n";
echo "4. Se documentação funciona, o servidor está OK, falta configurar API\n\n";

echo "=== SOLUÇÕES ===\n";
echo "1. Adicionar arquivo .htaccess na raiz\n";
echo "2. Configurar virtual host para roteamento\n";
echo "3. Verificar se index.php está na raiz correta\n";
echo "4. Testar acesso direto ao index.php\n";
?>
