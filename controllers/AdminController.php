<?php
class AdminController {
    public static function getSchedule($startDate, $endDate) {
        try {
            if (!$startDate) {
                $startDate = date('Y-m-d');
            }
            if (!$endDate) {
                $endDate = date('Y-m-d', strtotime('+7 days'));
            }

            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("
                SELECT 
                    a.*,
                    s.name as service_name,
                    s.duration,
                    s.price,
                    p.name as professional_name
                FROM appointments a
                JOIN services s ON a.service_id = s.id
                LEFT JOIN professionals p ON a.professional_id = p.id
                WHERE a.appointment_date BETWEEN ? AND ?
                ORDER BY a.appointment_date, a.appointment_time
            ");
            $stmt->execute([$startDate, $endDate]);
            $appointments = $stmt->fetchAll();

            echo json_encode([
                'success' => true,
                'data' => $appointments
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erro ao buscar agenda: ' . $e->getMessage()
            ]);
        }
    }

    public static function getProfessionals() {
        try {
            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("SELECT * FROM professionals WHERE is_active = 1 ORDER BY name");
            $stmt->execute();
            $professionals = $stmt->fetchAll();

            echo json_encode([
                'success' => true,
                'data' => $professionals
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erro ao buscar profissionais: ' . $e->getMessage()
            ]);
        }
    }

    public static function getSettings() {
        try {
            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("SELECT * FROM business_hours WHERE is_active = 1 ORDER BY day_of_week");
            $stmt->execute();
            $businessHours = $stmt->fetchAll();

            $settings = [
                'business_hours' => $businessHours,
                'timezone' => 'America/Sao_Paulo',
                'default_interval' => 30
            ];

            echo json_encode([
                'success' => true,
                'data' => $settings
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erro ao buscar configurações: ' . $e->getMessage()
            ]);
        }
    }

    public static function updateSettings($data) {
        try {
            if (!isset($data['business_hours'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Campo obrigatório: business_hours'
                ]);
                return;
            }

            $db = Database::getInstance()->getConnection();

            $db->beginTransaction();

            $stmt = $db->prepare("UPDATE business_hours SET is_active = 0");
            $stmt->execute();

            foreach ($data['business_hours'] as $hour) {
                if (!isset($hour['day_of_week']) || !isset($hour['open_time']) || !isset($hour['close_time'])) {
                    throw new Exception('Campos obrigatórios: day_of_week, open_time, close_time');
                }

                $stmt = $db->prepare("
                    INSERT INTO business_hours (day_of_week, open_time, close_time, is_active) 
                    VALUES (?, ?, ?, 1)
                    ON DUPLICATE KEY UPDATE 
                    open_time = VALUES(open_time), 
                    close_time = VALUES(close_time), 
                    is_active = VALUES(is_active)
                ");
                $stmt->execute([
                    $hour['day_of_week'],
                    $hour['open_time'],
                    $hour['close_time']
                ]);
            }

            $db->commit();

            echo json_encode([
                'success' => true,
                'data' => [
                    'message' => 'Configurações atualizadas com sucesso'
                ]
            ]);
        } catch (Exception $e) {
            $db->rollBack();
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erro ao atualizar configurações: ' . $e->getMessage()
            ]);
        }
    }
}
?>
