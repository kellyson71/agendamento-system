<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config/Database.php';
require_once 'config/Auth.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$uri = str_replace('/api', '', $uri);
$uri_segments = explode('/', trim($uri, '/'));

try {
    switch ($uri_segments[0]) {
        case 'services':
            if ($method === 'GET') {
                require_once 'controllers/ServiceController.php';
                ServiceController::getServices();
            } else {
                http_response_code(405);
                echo json_encode(['error' => 'Método não permitido']);
            }
            break;

        case 'slots':
            if ($method === 'GET') {
                require_once 'controllers/SlotController.php';
                SlotController::getAvailableSlots($_GET['service_id'] ?? null, $_GET['date'] ?? null);
            } else {
                http_response_code(405);
                echo json_encode(['error' => 'Método não permitido']);
            }
            break;

        case 'appointments':
            if ($method === 'POST') {
                $data = json_decode(file_get_contents("php://input"), true);
                require_once 'controllers/AppointmentController.php';
                AppointmentController::createAppointment($data);
            } elseif ($method === 'DELETE' && isset($uri_segments[1])) {
                if (!Auth::checkAuth()) {
                    http_response_code(401);
                    echo json_encode(['error' => 'Não autorizado']);
                    exit();
                }
                require_once 'controllers/AppointmentController.php';
                AppointmentController::cancelAppointment($uri_segments[1]);
            } elseif ($method === 'PUT' && isset($uri_segments[1]) && $uri_segments[2] === 'status') {
                if (!Auth::checkAuth()) {
                    http_response_code(401);
                    echo json_encode(['error' => 'Não autorizado']);
                    exit();
                }
                $data = json_decode(file_get_contents("php://input"), true);
                require_once 'controllers/AppointmentController.php';
                AppointmentController::updateStatus($uri_segments[1], $data);
            } else {
                http_response_code(405);
                echo json_encode(['error' => 'Método não permitido']);
            }
            break;

        case 'admin':
            if (!Auth::checkAuth()) {
                http_response_code(401);
                echo json_encode(['error' => 'Não autorizado']);
                exit();
            }

            switch ($uri_segments[1]) {
                case 'schedule':
                    if ($method === 'GET') {
                        require_once 'controllers/AdminController.php';
                        AdminController::getSchedule($_GET['start_date'] ?? null, $_GET['end_date'] ?? null);
                    } else {
                        http_response_code(405);
                        echo json_encode(['error' => 'Método não permitido']);
                    }
                    break;

                case 'professionals':
                    if ($method === 'GET') {
                        require_once 'controllers/AdminController.php';
                        AdminController::getProfessionals();
                    } else {
                        http_response_code(405);
                        echo json_encode(['error' => 'Método não permitido']);
                    }
                    break;

                case 'settings':
                    if ($method === 'GET') {
                        require_once 'controllers/AdminController.php';
                        AdminController::getSettings();
                    } elseif ($method === 'PUT') {
                        $data = json_decode(file_get_contents("php://input"), true);
                        require_once 'controllers/AdminController.php';
                        AdminController::updateSettings($data);
                    } else {
                        http_response_code(405);
                        echo json_encode(['error' => 'Método não permitido']);
                    }
                    break;

                default:
                    http_response_code(404);
                    echo json_encode(['error' => 'Endpoint admin não encontrado']);
                    break;
            }
            break;

        case 'docs':
            if ($method === 'GET') {
                require_once 'docs/index.php';
            } else {
                http_response_code(405);
                echo json_encode(['error' => 'Método não permitido']);
            }
            break;

        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint não encontrado']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor: ' . $e->getMessage()]);
}
?>
