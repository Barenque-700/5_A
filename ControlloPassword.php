<?php
session_start(); 
include "Connessione.php";
$accesso=$_SESSION['accesso'];
    if($accesso!= 1){
        header("location: Index.php");
    }
    else{
		$q = $_REQUEST["q"];
		$indicatore = "";
		$NomeUtente = $_SESSION['user'];

		try {
		        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
		        $sql= "SELECT Password 
		            FROM utenti
		            WHERE NomeUtente=?";

		        $preparata = $connessione->prepare($sql);
		        $preparata->execute([$NomeUtente]);

		        if($preparata->rowCount() > 0){
		            $ris = $preparata->fetchAll(PDO::FETCH_ASSOC);

		            if ($q !== "") {
					    foreach($ris as $name) {
					    	foreach ($name as $stringa) {
					        if (strcmp($q, $stringa)== 0){
					                $indicatore= 0;
					                break;
					            } else {
					                $indicatore="";
					            }
					        }
					    }
					}
				}
			    $connessione = null;
		    } catch(PDOException $e){
		        die("Errore nella gestione del database $db: " . $e->getMessage());
		    }

		echo $indicatore === 0 ? "" : "La Password non è corretta"."<br>"."<br>";

	}


?>