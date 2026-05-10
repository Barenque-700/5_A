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
 
$url = "https://eonet.gsfc.nasa.gov/api/v3/events?status=open&limit=50&api_key=$chiave";
 
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
 
// Restituisce il JSON al browser (senza chiave)
header('Content-Type: application/json');
echo $risposta;