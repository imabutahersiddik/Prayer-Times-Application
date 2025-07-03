<?php
function getPrayerTimes($latitude, $longitude) {
    $date = date('d-m-Y');
    $url = "http://api.aladhan.com/v1/timings/$date?latitude=$latitude&longitude=$longitude&method=3";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    
    if ($error = curl_error($ch)) {
        error_log("API Error: " . $error);
        curl_close($ch);
        return getFallbackTimes($latitude, $longitude);
    }
    
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if (!$data || !isset($data['data']['timings'])) {
        error_log("Invalid API response: " . $response);
        return getFallbackTimes($latitude, $longitude);
    }
    
    return $data;
}

function getFallbackTimes($latitude, $longitude) {
    return [
        'code' => 200,
        'status' => 'OK',
        'data' => [
            'timings' => [
                'Fajr' => '04:30',
                'Sunrise' => '06:00',
                'Dhuhr' => '12:00',
                'Asr' => '15:30',
                'Maghrib' => '18:00',
                'Isha' => '19:30'
            ],
            'date' => [
                'readable' => date('d M Y'),
                'timestamp' => time()
            ],
            'meta' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'timezone' => 'UTC'
            ]
        ]
    ];
}

function validateInput($input) {
    return filter_var($input, FILTER_SANITIZE_STRING);
}

function checkRateLimit($ip) {
    $rates = @json_decode(file_get_contents('rate_limits.json'), true) ?? [];
    $now = time();
    
    if (isset($rates[$ip])) {
        if ($rates[$ip]['count'] > 100 && 
            $now - $rates[$ip]['timestamp'] < 3600) {
            return false;
        }
    }
    
    $rates[$ip] = [
        'count' => isset($rates[$ip]) ? $rates[$ip]['count'] + 1 : 1,
        'timestamp' => $now
    ];
    
    @file_put_contents('rate_limits.json', json_encode($rates));
    return true;
}
?>