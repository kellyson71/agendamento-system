<?php
class AppointmentController {
    public static function createAppointment($data) {
        try {
            $requiredFields = ['service_id', 'date', 'time', 'client_name', 'client_phone'];
            
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'error' => "Campo obrigatório: {$field}"
                    ]);
                    return;
                }
            }

            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("SELECT * FROM services WHERE id = ? AND is_active = 1");
            $stmt->execute([$data['service_id']]);
            $service = $stmt->fetch();

            if (!$service) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'error' => 'Serviço não encontrado'
                ]);
                return;
            }

            $appointmentDateTime = $data['date'] . ' ' . $data['time'];
            $stmt = $db->prepare("
                SELECT COUNT(*) as count 
                FROM appointments 
                WHERE appointment_date = ? 
                AND appointment_time = ? 
                AND status IN ('pending', 'confirmed')
            ");
            $stmt->execute([$data['date'], $data['time']]);
            $conflict = $stmt->fetch();

            if ($conflict['count'] > 0) {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'error' => 'Horário já ocupado'
                ]);
                return;
            }

            $stmt = $db->prepare("
                INSERT INTO appointments 
                (service_id, professional_id, client_name, client_phone, client_email, 
                 appointment_date, appointment_time, status, is_online, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?)
            ");

            $stmt->execute([
                $data['service_id'],
                $data['professional_id'] ?? null,
                $data['client_name'],
                $data['client_phone'],
                $data['client_email'] ?? null,
                $data['date'],
                $data['time'],
                $data['is_online'] ?? false,
                $data['notes'] ?? null
            ]);

            $appointmentId = $db->lastInsertId();

            echo json_encode([
                'success' => true,
                'data' => [
                    'id' => $appointmentId,
                    'message' => 'Agendamento criado com sucesso'
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erro ao criar agendamento: ' . $e->getMessage()
            ]);
        }
    }

    public static function cancelAppointment($appointmentId) {
        try {
            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("SELECT * FROM appointments WHERE id = ?");
            $stmt->execute([$appointmentId]);
            $appointment = $stmt->fetch();

            if (!$appointment) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'error' => 'Agendamento não encontrado'
                ]);
                return;
            }

            if ($appointment['status'] === 'cancelled') {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Agendamento já foi cancelado'
                ]);
                return;
            }

            $stmt = $db->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ?");
            $stmt->execute([$appointmentId]);

            echo json_encode([
                'success' => true,
                'data' => [
                    'message' => 'Agendamento cancelado com sucesso'
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erro ao cancelar agendamento: ' . $e->getMessage()
            ]);
        }
    }

    public static function updateStatus($appointmentId, $data) {
        try {
            if (!isset($data['new_status'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Campo obrigatório: new_status'
                ]);
                return;
            }

            $validStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];
            if (!in_array($data['new_status'], $validStatuses)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Status inválido. Valores aceitos: ' . implode(', ', $validStatuses)
                ]);
                return;
            }

            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("SELECT * FROM appointments WHERE id = ?");
            $stmt->execute([$appointmentId]);
            $appointment = $stmt->fetch();

            if (!$appointment) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'error' => 'Agendamento não encontrado'
                ]);
                return;
            }

            $stmt = $db->prepare("UPDATE appointments SET status = ? WHERE id = ?");
            $stmt->execute([$data['new_status'], $appointmentId]);

            echo json_encode([
                'success' => true,
                'data' => [
                    'message' => 'Status atualizado com sucesso'
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erro ao atualizar status: ' . $e->getMessage()
            ]);
        }
    }
}
?>
