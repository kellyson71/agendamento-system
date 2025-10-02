<?php
echo "=== TESTE DE CONEXÃO COM BANCO DE DADOS ===\n\n";

// Carregar configurações
$config = [];
$lines = file('config.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        $config[trim($key)] = trim($value);
    }
}

echo "Configurações carregadas:\n";
echo "Host: " . $config['DB_HOST'] . "\n";
echo "Port: " . $config['DB_PORT'] . "\n";
echo "Database: " . $config['DB_NAME'] . "\n";
echo "User: " . $config['DB_USER'] . "\n";
echo "Password: " . (strlen($config['DB_PASS']) > 0 ? str_repeat('*', strlen($config['DB_PASS'])) : 'VAZIO') . "\n\n";

// Testar conexão
try {
    $dsn = "mysql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};charset=utf8mb4";
    echo "DSN: $dsn\n\n";
    
    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    echo "✅ Conexão com banco de dados estabelecida com sucesso!\n\n";
    
    // Testar se as tabelas existem
    $tables = ['services', 'professionals', 'appointments', 'business_hours', 'api_keys'];
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $result = $stmt->fetch();
            echo "✅ Tabela '$table': {$result['count']} registros\n";
        } catch (Exception $e) {
            echo "❌ Tabela '$table': ERRO - " . $e->getMessage() . "\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Erro de conexão: " . $e->getMessage() . "\n";
    echo "Código do erro: " . $e->getCode() . "\n";
}

echo "\n=== FIM DO TESTE ===\n";
?>
