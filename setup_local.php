<?php
echo "=== CONFIGURAÇÃO DO AMBIENTE LOCAL ===\n\n";

echo "1. Iniciando WAMP...\n";
echo "   - Abra o WAMP Server\n";
echo "   - Clique com botão direito no ícone do WAMP\n";
echo "   - Selecione 'Start All Services'\n";
echo "   - Aguarde ficar verde\n\n";

echo "2. Testando conexão MySQL...\n";
$config = [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'agendamento_system',
    'user' => 'root',
    'pass' => ''
];

try {
    $dsn = "mysql:host={$config['host']};port={$config['port']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "✅ MySQL conectado!\n\n";
    
    echo "3. Criando banco de dados...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS agendamento_system");
    $pdo->exec("USE agendamento_system");
    echo "✅ Banco 'agendamento_system' criado!\n\n";
    
    echo "4. Executando script SQL...\n";
    $sql = file_get_contents('database.sql');
    
    // Remover a linha CREATE DATABASE e USE do script
    $sql = preg_replace('/CREATE DATABASE IF NOT EXISTS agendamento_system;\s*USE agendamento_system;\s*/', '', $sql);
    
    $statements = explode(';', $sql);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                // Ignorar erros de tabela já existente
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "⚠️  Aviso: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "✅ Tabelas criadas!\n\n";
    
    echo "5. Testando dados...\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM services");
    $result = $stmt->fetch();
    echo "✅ Serviços: {$result['count']} registros\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM professionals");
    $result = $stmt->fetch();
    echo "✅ Profissionais: {$result['count']} registros\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM business_hours");
    $result = $stmt->fetch();
    echo "✅ Horários: {$result['count']} registros\n\n";
    
    echo "6. Atualizando config.env...\n";
    $configContent = "DB_HOST=localhost\nDB_PORT=3306\nDB_NAME=agendamento_system\nDB_USER=root\nDB_PASS=\nAPI_KEY=agendamento_api_key_2024";
    file_put_contents('config.env', $configContent);
    echo "✅ config.env atualizado para ambiente local!\n\n";
    
    echo "🎉 AMBIENTE LOCAL CONFIGURADO COM SUCESSO!\n";
    echo "Agora você pode:\n";
    echo "- Executar: php -S localhost:8000\n";
    echo "- Acessar: http://localhost:8000/docs/\n";
    echo "- Testar: php test_api.php\n";
    
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n\n";
    echo "SOLUÇÕES:\n";
    echo "1. Inicie o WAMP Server\n";
    echo "2. Verifique se o MySQL está rodando (ícone verde)\n";
    echo "3. Execute este script novamente\n";
}
?>
