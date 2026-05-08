<?php
// 1. Leggi la chiave dal file .env
$chiave = '';
$righe = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($righe as $riga) {
    if (str_starts_with($riga, 'NASA_API_KEY=')) {
        $chiave = explode('=', $riga, 2)[1];
        break;
    }
}

// 2. Chiama NASA con la chiave (tutto avviene sul server)
$risposta = file_get_contents("https://api.nasa.gov/planetary/apod?api_key=$chiave");

// 3. Rimanda il risultato al browser (solo dati, niente chiave)
header('Content-Type: application/json');
echo $risposta;
