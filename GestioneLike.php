<?php
session_start();
include "Connessione.php";

if(isset($_POST['id_post']) && isset($_SESSION['user'])){
    $id_post = $_POST['id_post'];
    $utente = $_SESSION['user'];

    try {
        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        
        $check = $connessione->prepare("SELECT * FROM likepost WHERE Utente = ? AND Id_Post = ?");
        $check->execute([$utente, $id_post]);

        if ($check->rowCount() > 0) {
            $connessione->prepare("DELETE FROM likepost WHERE Utente = ? AND Id_Post = ?")->execute([$utente, $id_post]);
            $connessione->prepare("UPDATE post SET NumLike = NumLike - 1 WHERE Id_Post = ?")->execute([$id_post]);
            $stato = "rimosso";
        } else {
            $connessione->prepare("INSERT INTO likepost (Utente, Id_Post) VALUES (?, ?)")->execute([$utente, $id_post]);
            $connessione->prepare("UPDATE post SET NumLike = NumLike + 1 WHERE Id_Post = ?")->execute([$id_post]);
            $stato = "aggiunto";
        }

        $resCount = $connessione->prepare("SELECT NumLike FROM post WHERE Id_Post = ?");
        $resCount->execute([$id_post]);
        $nuovoTotale = $resCount->fetchColumn();

        echo json_encode(['totale' => $nuovoTotale, 'stato' => $stato]);

    } catch(PDOException $e) {
        echo json_encode(['stato' => 'errore']);
    }
}
?>