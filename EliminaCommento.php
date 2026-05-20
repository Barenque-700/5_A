<?php
session_start();
include "Connessione.php";
$accesso=$_SESSION['accesso'];
$utente = $_SESSION['user'];
if($accesso!= 1){
    header("location: Index.php");
    exit;
}
else{
    $livello = $_SESSION['livello'];
    $commento = $_POST['Id_Commento'];
    try {
        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT Utente FROM commenti WHERE Id_Commento=? AND Utente=?";
        $preparata = $connessione->prepare($sql);
        $preparata->execute([$commento, $utente]);
        if($preparata->rowCount()>0 || $livello== 0){
            try {
                $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "DELETE FROM commenti 
                        WHERE Id_Commento= ?";
                $preparata = $connessione->prepare($sql);
                $preparata->execute([$commento]);

                header("Location: Asteria.php");
                exit;
            } catch(PDOException $e){
                die("Errore: " . $e->getMessage());
            }
        } else {
            echo ("Azione non autorizzata");
            header("location: Asteria.php");
            exit;
        }
    } catch(PDOException $e){
        die("Errore: " . $e->getMessage());
    }
            
}