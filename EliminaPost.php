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
    $post = $_POST['Id_Post'];

    if ($livello== 0) {
            try {
                $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "DELETE FROM post 
                        WHERE Id_Post= ?";
                $preparata = $connessione->prepare($sql);
                $preparata->execute([$post]);
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