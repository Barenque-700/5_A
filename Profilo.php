<?php 
session_start();
include "Connessione.php";
$NomeUtente = $_SESSION['nomeutente'];
if(isset($NomeUtente)){
	try{
		$connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $sql= "SELECT Nome, Cognome, NomeUtente, Seguiti, Follower, Descrizione, NumPost, Foto
            FROM utenti
            WHERE NomeUtente=?";

        $preparata = $connessione->prepare($sql);
        $preparata->execute([$NomeUtente]);

        if($preparata->rowCount() > 0){
            $ris = $preparata->fetchAll(PDO::FETCH_ASSOC);
            foreach ($ris as $riga) {
            	?>
            	<html>
					<head>
					    <meta charset="UTF-8">
					    <meta name="viewport" content="width=device-width, initial-scale=1.0">
					    <title>Asteria - Profilo Utente</title>
					    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
					    <link rel="stylesheet" href="StileAsteria.css">
					</head>
					<body>
						<?php 
							echo $riga['Nome'];
							echo $riga['Cognome'];
							echo $riga['NomeUtente'];
						?>
						<img src="UploadProfili/<?=$riga['Foto']?>" width="100" height="150">
					</body>
				</html>
            	<?php
            }
        }
	    $connessione = null;
    } catch(PDOException $e){
        die("Errore nella gestione del database $db: " . $e->getMessage());
    }
}
?>