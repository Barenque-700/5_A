<?php
session_start();
$vecchioNomeUtente = $_SESSION['user']; 
include "Connessione.php";
$accesso=$_SESSION['accesso'];
    if($accesso!= 1){
        header("location: Index.php");
    }
    else{
        if(isset($_POST['utente'], $_POST['cognome'], $_POST['nome'], $_POST['descrizione'], $_POST['data'])) {
            
            $nuovoUtente = $_POST['utente'];
            $cognome     = $_POST['cognome'];
            $nome        = $_POST['nome'];
            $descrizione = $_POST['descrizione'];
            $data        = $_POST['data'];

            try {
                $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE utenti 
                        SET NomeUtente = ?, Cognome = ?, Nome = ?, Descrizione = ?, DataNascita = ? 
                        WHERE NomeUtente = ?";
                $preparata = $connessione->prepare($sql);
                $preparata->execute([$nuovoUtente, $cognome, $nome, $descrizione, $data, $vecchioNomeUtente]);
                $_SESSION['user'] = $nuovoUtente;
                header("Location: Profilo.php?user=$nuovoUtente");
                exit;
            } catch(PDOException $e){
                die("Errore: " . $e->getMessage());
            }
        }
    }
?>