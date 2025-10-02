<?php
echo "=== TESTE DE CONFIGURAÇÕES DE BANCO ===\n\n";

// Configurações para testar
$configs = [
    'Local MySQL' => [
        'host' => 'localhost',
        'port' => 3306,
        'dbname' => 'agendamento_system',
        'user' => 'root',
        'pass' => ''
    ],
    'WAMP Local' => [
        'host' => 'localhost',
        'port' => 3306,
        'dbname' => 'agendamento_system',
        'user' => 'root',
        'pass' => ''
    ],
    'Config Atual' => [
        'host' => 'srv1844',
        'port' => 3306,
        'dbname' => 'u492577848_agenda',
        'user' => 'u492577848_agenda',
        'pass' => 'Kellys0n_123'
    ]
];

foreach ($configs as $name => $config) {
    echo "Testando: $name\n";
    echo "Host: {$config['host']}\n";
    echo "Port: {$config['port']}\n";
    echo "Database: {$config['dbname']}\n";
    echo "User: {$config['user']}\n";
    echo "Password: " . (strlen($config['pass']) > 0 ? str_repeat('*', strlen($config['pass'])) : 'VAZIO') . "\n";
    
    try {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4";
        $pdo = new PDO($dsn, $config['user'], $config['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        
        echo "✅ CONEXÃO ESTABELECIDA!\n";
        
        // Testar tabela services
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM services");
        $result = $stmt->fetch();
        echo "✅ Tabela 'services': {$result['count']} registros\n";
        
    } catch (PDOException $e) {
        echo "❌ ERRO: " . $e->getMessage() . "\n";
    }
    
    echo str_repeat("-", 50) . "\n\n";
}

echo "=== SUGESTÕES ===\n";
echo "1. Se nenhuma conexão funcionou, verifique se o MySQL está rodando\n";
echo "2. Para WAMP: C:\\wamp64\\bin\\mysql\\mysql9.1.0\\bin\\mysql.exe\n";
echo "3. Para XAMPP: C:\\xampp\\mysql\\bin\\mysql.exe\n";
echo "4. Para servidor remoto, verifique o hostname correto\n";
echo "5. Execute: mysql -u root -p para testar conexão local\n";
?>
