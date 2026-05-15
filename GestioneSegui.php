<?php
session_start();
include "Connessione.php";
$accesso=$_SESSION['accesso'];
if($accesso!= 1){
    header("location: Index.php");
}else{
    $follower = $_POST['follower'];
    $seguito = $_POST['seguito'];

    try {
        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        
        $sql = "SELECT * FROM follow WHERE Seguente = ? AND Seguito = ?";
        $preparata = $connessione->prepare($sql);
        $preparata->execute([$follower, $seguito]);

        $sqlNotifica= "SELECT Id_Notifica FROM notifiche WHERE Mittente=? AND Destinatario=? AND tipo=?";
        $stmt= $connessione->prepare($sqlNotifica);
        $stmt->execute([$follower, $seguito, 'follow']);
        $ris= $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($preparata->rowCount() > 0) {
            $query = "DELETE FROM follow WHERE Seguente = ? AND Seguito = ?";
            $prep = $connessione->prepare($query);
            $prep->execute([$follower, $seguito]);

            $queryNotifica = "DELETE FROM notifiche WHERE Id_Notifica=?";
            $prepNotifica = $connessione->prepare($queryNotifica);
            $prepNotifica->execute([$ris['Id_Notifica']]);
            $stato = "rimosso";
        } else { 
            $query = "INSERT INTO follow (Seguente, Seguito) VALUES (?, ?)";
            $prep = $connessione->prepare($query);
            $prep->execute([$follower, $seguito]);

            $queryNotifica = "INSERT INTO notifiche (Mittente, Destinatario, tipo) VALUES (?,?,?)";
            $prepNotifica = $connessione->prepare($queryNotifica);
            $prepNotifica->execute([$follower, $seguito, 'follow']);
            $stato = "seguito";
        }

        $sql_count = "SELECT COUNT(*) as totale FROM follow WHERE Seguito = ?";
        $prep_count = $connessione->prepare($sql_count);
        $prep_count->execute([$seguito]);
        $risultato = $prep_count->fetch(PDO::FETCH_ASSOC);
        $nuovoConteggio = $risultato['totale'];

        header('Content-Type: application/json');
        echo json_encode([
            "stato" => $stato,
            "conteggio" => $nuovoConteggio
        ]);

        $connessione = null;

    } catch(PDOException $e) {
        echo json_encode(["errore" => $e->getMessage()]);
    }
}
?>