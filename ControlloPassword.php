<?php
session_start(); 
include "Connessione.php";
$q = $_REQUEST["q"];
$indicatore = "";

try {
        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $sql= 'SELECT Password 
            FROM utenti';

        $preparata = $connessione->prepare($sql);
        $preparata->execute();

        if($preparata->rowCount() > 0){
            $ris = $preparata->fetchAll(PDO::FETCH_ASSOC);

            if ($q !== "") {
			    $len=strlen($q);
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

echo $indicatore === 0 ? "" : "La Password non è la stessa"."<br>"."<br>";




?>