<?php
session_start();
$accesso=$_SESSION['accesso'];
    if($accesso!= 1){
        header("location: Index.php");
    }
    else{
        $NomeUtente = $_SESSION['user']; 

        include "Connessione.php";

        if(isset($_POST['passwordNuova'])) {
            
            $nuovaPassword = $_POST['passwordNuova'];

            try {
                $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE utenti 
                        SET Password= ? 
                        WHERE NomeUtente = ?";
                $preparata = $connessione->prepare($sql);
                $preparata->execute([$nuovaPassword, $NomeUtente]);
                header("Location: Profilo.php");
                exit;
            } catch(PDOException $e){
                die("Errore: " . $e->getMessage());
            }
        }
    }
?>