<?php
ini_set('session.cookie_path', '/');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_start();

require __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['token'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No token']);
    exit;
}

try {
    $factory = (new Factory)
        ->withServiceAccount(__DIR__ . '/config/registrocompactacioneroca-firebase-adminsdk-2rnz9-b335471836.json');

    $auth = $factory->createAuth();

    $verifiedIdToken = $auth->verifyIdToken($data['token']);

    $claims = $verifiedIdToken->claims();
    $uid = $claims->get('sub');
    $email = $claims->get('email');
    $name = $claims->get('name');

    $_SESSION['firebase_uid'] = $uid;
    $_SESSION['user_email'] = $email ?? '';
    
    // Buscar el usuario en la base de datos remota fortastudio_roca
    require __DIR__ . '/conexion_forta.php';
    
    $user_display_name = $email ?? 'Usuario';
    $user_puesto = 'No asignado';
    
    if ($email && $conexion_forta) {
        $stmt = $conexion_forta->prepare("SELECT Nombre, Puesto FROM personal WHERE mail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $user_display_name = $row['Nombre'];
            $user_puesto = $row['Puesto'];
        }
        
        $stmt->close();
    }
    
    $_SESSION['user_name'] = $user_display_name;
    $_SESSION['user_puesto'] = $user_puesto;

    echo json_encode(['status' => 'ok']);
    exit;
} catch (Throwable $e) {

    http_response_code(401);
    echo json_encode([
        'error' => 'Token inválido',
        'message' => $e->getMessage()
    ]);
    exit;
}
