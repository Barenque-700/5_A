<?php
session_start(); 
include "Connessione.php";
$q = $_REQUEST["q"];
$NomeUtenteLoggato = $_SESSION['user']; 
$indicatore = "";

if ($q !== "" && $q !== $NomeUtenteLoggato) {
    try {
        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        
        $sql = "SELECT NomeUtente FROM utenti WHERE NomeUtente = ?";
        $preparata = $connessione->prepare($sql);
        $preparata->execute([$q]);

        if($preparata->rowCount() > 0){
            $indicatore = "Il nome utente è già utilizzato";
        }

        $connessione = null;
    } catch(PDOException $e){
        die("Errore: " . $e->getMessage());
    }
}

echo $indicatore !== "" ? $indicatore."<br><br>" : "";
?>