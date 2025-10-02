<?php
class Auth {
    public static function checkAuth() {
        $headers = getallheaders();
        $apiKey = null;

        if (isset($headers['X-API-Key'])) {
            $apiKey = $headers['X-API-Key'];
        } elseif (isset($headers['Authorization'])) {
            $auth = $headers['Authorization'];
            if (strpos($auth, 'Bearer ') === 0) {
                $apiKey = substr($auth, 7);
            }
        } elseif (isset($_GET['api_key'])) {
            $apiKey = $_GET['api_key'];
        }

        if (!$apiKey) {
            return false;
        }

        $config = self::loadConfig();
        $validApiKey = $config['API_KEY'];

        return $apiKey === $validApiKey;
    }

    private static function loadConfig() {
        $config = [];
        $lines = file('config.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $config[trim($key)] = trim($value);
            }
        }
        
        return $config;
    }
}
?>
