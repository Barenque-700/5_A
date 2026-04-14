<!DOCTYPE html>
<?php 
	session_start();
	include "Connessione.php";
	$NomeUtente = $_SESSION['nomeutente'];
?>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asteria - Profilo Utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="StileProfilo.css">
</head>
<body>

<div class="container">
	<?php
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
	            <div class="profile-container">
	                <div class="profile-header">
	                    <img src="UploadProfili/<?=$riga['Foto'] ?>" class="profile-pic" alt="Foto Profilo">
	                    
	                    <div class="user-info">
	                        <h2><?= htmlspecialchars($riga['Nome'] . " " . $riga['Cognome']) ?></h2>
	                        <p class="username">@<?=$riga['NomeUtente'] ?></p>
	                        
	                        <div class="stats">
	                            <div class="stat-item">
	                                <span class="stat-value"><?= $riga['NumPost'] ?? 0 ?></span>
	                                <span class="stat-label">Post</span>
	                            </div>
	                            <div class="stat-item">
	                                <span class="stat-value"><?= $riga['Follower'] ?></span>
	                                <span class="stat-label">Follower</span>
	                            </div>
	                            <div class="stat-item">
	                                <span class="stat-value"><?= $riga['Seguiti'] ?></span>
	                                <span class="stat-label">Seguiti</span>
	                            </div>
	                        </div>
	                        
	                        <p class="mt-3"><?= $riga['Descrizione'] ?></p>
	                    </div>
	                </div>
	            </div>
	            <?php
	        }
	    }
	    $connessione = null;
	    } catch(PDOException $e){
	        die("Errore nella gestione del database $db: " . $e->getMessage());
	    }
	}
    ?>
</div>

</body>
</html>}