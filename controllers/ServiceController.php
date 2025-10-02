<?php
class ServiceController {
    public static function getServices() {
        try {
            $db = Database::getInstance()->getConnection();
            
            $stmt = $db->prepare("SELECT * FROM services WHERE is_active = 1 ORDER BY name");
            $stmt->execute();
            $services = $stmt->fetchAll();

            echo json_encode([
                'success' => true,
                'data' => $services
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erro ao buscar serviços: ' . $e->getMessage()
            ]);
        }
    }
}
?>
