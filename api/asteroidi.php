<?php
$chiave = '';
$envFile = __DIR__ . '/../.env';

if (file_exists($envFile)) {
    $righe = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($righe as $riga) {
        if (str_starts_with(trim($riga), 'NASA_API_KEY=')) {
            $chiave = trim(explode('=', $riga, 2)[1]);
            break;
        }
    }
}

if (empty($chiave)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Chiave API non configurata.']);
    exit;
}

$start = $_GET['start'] ?? date('Y-m-d');
$end   = $_GET['end']   ?? date('Y-m-d', strtotime('+6 days'));

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start) ||
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Formato data non valido.']);
    exit;
}

$diff = (strtotime($end) - strtotime($start)) / 86400;
if ($diff < 0 || $diff > 7) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => "L'intervallo deve essere tra 1 e 7 giorni."]);
    exit;
}

$url = "https://api.nasa.gov/neo/rest/v1/feed?start_date=$start&end_date=$end&api_key=$chiave";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$risposta = curl_exec($ch);
$errore   = curl_error($ch);
curl_close($ch);

if ($risposta === false || !empty($errore)) {
    http_response_code(502);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Impossibile contattare la NASA: ' . $errore]);
    exit;
}

header('Content-Type: application/json');
echo $risposta;