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
        
        if($preparata->rowCount() > 0) {
            $query = "DELETE FROM follow WHERE Seguente = ? AND Seguito = ?";
            $prep = $connessione->prepare($query);
            $prep->execute([$follower, $seguito]);
            $stato = "rimosso";
        } else { 
            $query = "INSERT INTO follow (Seguente, Seguito) VALUES (?, ?)";
            $prep = $connessione->prepare($query);
            $prep->execute([$follower, $seguito]);
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