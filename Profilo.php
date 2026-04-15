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
	<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <div class="d-flex flex-column">
                <img src="LogoTrasparente.png" alt="Asteria Logo" class="logo-img mb-1">
                <small id="current-date" class="text-secondary ms-1" style="font-size: 0.7rem; letter-spacing: 1px;"></small>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item theme-switch-wrapper">
                    <button id="theme-toggle"> 🌙 </button>
                </li>
                <li class="nav-item"><a class="nav-link px-3" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#">Eventi</a></li>
                <li class="nav-item ms-lg-2">
                    <button class="btn btn-custom rounded-pill fw-bold" onclick="location.href='Profilo.php'">Profilo Utente</button>
                </li>
            </ul>
        </div>
    </div>
</nav>

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
<script src="config.js"></script>
</html>