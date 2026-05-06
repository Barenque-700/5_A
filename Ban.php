<?php
session_start();
include "Connessione.php";
$accesso=$_SESSION['accesso'];
if($accesso!= 1){
    header("location: Index.php");
    exit;
}
else{
    $livello = $_SESSION['livello'];
    $userPagina = $_GET['user'];

    if ($livello== 0) {
            try {
                $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "DELETE FROM utenti 
                        WHERE NomeUtente= ?";
                $preparata = $connessione->prepare($sql);
                $preparata->execute([$userPagina]);
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
}