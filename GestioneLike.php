<?php
session_start();
include "Connessione.php";

if(isset($_POST['id_post']) && isset($_SESSION['user'])){
    $id_post = $_POST['id_post'];
    $utente = $_SESSION['user'];

    try {
        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $sql="SELECT * FROM likepost WHERE Utente = ? AND Id_Post = ?";
        $sqlNotifiche="SELECT Id_Notifica FROM notifiche WHERE Mittente=? AND tipo='like' AND Id_Post=?";
        $sqlPost="SELECT Utente FROM post WHERE Id_Post=?";

        $preparata= $connessione->prepare($sqlNotifiche);
        $preparata->execute([$utente,$id_post]);
        $ris = $preparata->fetch(PDO::FETCH_ASSOC);

        $preparataPost= $connessione->prepare($sqlPost);
        $preparataPost->execute([$id_post]);
        $risPost=$preparataPost->fetch(PDO::FETCH_ASSOC);
        
        $check = $connessione->prepare($sql);
        $check->execute([$utente, $id_post]);

        if ($check->rowCount() > 0) {
            $connessione->prepare("DELETE FROM likepost WHERE Utente = ? AND Id_Post = ?")->execute([$utente, $id_post]);
            $connessione->prepare("UPDATE post SET NumLike = NumLike - 1 WHERE Id_Post = ?")->execute([$id_post]);
            $connessione->prepare("DELETE FROM notifiche WHERE Id_Notifica = ?")->execute([$ris['Id_Notifica']]);
            $stato = "rimosso";
        } else {
            $connessione->prepare("INSERT INTO likepost (Utente, Id_Post) VALUES (?, ?)")->execute([$utente, $id_post]);
            $connessione->prepare("UPDATE post SET NumLike = NumLike + 1 WHERE Id_Post = ?")->execute([$id_post]);
            $connessione->prepare("INSERT INTO notifiche (Mittente, Destinatario, tipo, Id_Post) VALUES (?,?,?,?)")->execute([$utente, $risPost['Utente'], 'like', $id_post]);
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