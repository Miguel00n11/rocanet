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

    $_SESSION['firebase_uid'] = $uid;


    // // 🔍 DEBUG TEMPORAL (AQUÍ SÍ SE EJECUTA)
    // file_put_contents(
    //     __DIR__ . '/debug_session.txt',
    //     print_r($_SESSION, true)
    // );

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
