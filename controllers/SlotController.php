<?php
class SlotController {
    public static function getAvailableSlots($serviceId, $date) {
        try {
            if (!$serviceId || !$date) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'service_id e date são obrigatórios'
                ]);
                return;
            }

            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("SELECT duration FROM services WHERE id = ? AND is_active = 1");
            $stmt->execute([$serviceId]);
            $service = $stmt->fetch();

            if (!$service) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'error' => 'Serviço não encontrado'
                ]);
                return;
            }

            $dayOfWeek = date('w', strtotime($date));
            $stmt = $db->prepare("SELECT open_time, close_time FROM business_hours WHERE day_of_week = ? AND is_active = 1");
            $stmt->execute([$dayOfWeek]);
            $businessHours = $stmt->fetch();

            if (!$businessHours) {
                echo json_encode([
                    'success' => true,
                    'data' => []
                ]);
                return;
            }

            $stmt = $db->prepare("
                SELECT appointment_time, duration 
                FROM appointments a 
                JOIN services s ON a.service_id = s.id 
                WHERE a.appointment_date = ? 
                AND a.status IN ('pending', 'confirmed')
            ");
            $stmt->execute([$date]);
            $bookedSlots = $stmt->fetchAll();

            $availableSlots = self::calculateAvailableSlots(
                $businessHours['open_time'],
                $businessHours['close_time'],
                $service['duration'],
                $bookedSlots
            );

            echo json_encode([
                'success' => true,
                'data' => $availableSlots
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erro ao buscar horários disponíveis: ' . $e->getMessage()
            ]);
        }
    }

    private static function calculateAvailableSlots($openTime, $closeTime, $serviceDuration, $bookedSlots) {
        $slots = [];
        $interval = 30;
        
        $openTimestamp = strtotime($openTime);
        $closeTimestamp = strtotime($closeTime);
        $serviceDurationSeconds = $serviceDuration * 60;

        $bookedTimes = [];
        foreach ($bookedSlots as $slot) {
            $startTime = strtotime($slot['appointment_time']);
            $endTime = $startTime + ($slot['duration'] * 60);
            $bookedTimes[] = ['start' => $startTime, 'end' => $endTime];
        }

        for ($time = $openTimestamp; $time < $closeTimestamp; $time += ($interval * 60)) {
            $slotEndTime = $time + $serviceDurationSeconds;
            
            if ($slotEndTime > $closeTimestamp) {
                break;
            }

            $isAvailable = true;
            foreach ($bookedTimes as $booked) {
                if (($time < $booked['end'] && $slotEndTime > $booked['start'])) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $slots[] = [
                    'time' => date('H:i', $time),
                    'datetime' => date('Y-m-d H:i:s', $time)
                ];
            }
        }

        return $slots;
    }
}
?>
