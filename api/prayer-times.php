<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../includes/functions.php';

try {
    if (!isset($_GET['latitude']) || !isset($_GET['longitude'])) {
        throw new Exception('Missing coordinates');
    }

    $latitude = filter_var($_GET['latitude'], FILTER_VALIDATE_FLOAT);
    $longitude = filter_var($_GET['longitude'], FILTER_VALIDATE_FLOAT);

    if ($latitude === false || $longitude === false) {
        throw new Exception('Invalid coordinates');
    }

    $prayerTimes = getPrayerTimes($latitude, $longitude);
    
    echo json_encode([
        'status' => 'success',
        'data' => $prayerTimes['data']
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>