<!DOCTYPE html>
<?php 
	session_start();
	include "Connessione.php";
	$NomeUtente = $_SESSION['user'];
	if(isset($_GET['user']))
	$userPagina = $_GET['user'];
	$accesso=$_SESSION['accesso'];
	if($accesso!= 1){
	    header("location: Index.php");
	}
	else{
?>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asteria - Profilo Utente</title>
	<link rel="icon" type="image/x-icon" href="LogoIcona.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="StileProfilo.css">
</head>
<body>
	<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <div class="d-flex flex-column">
				<a href="Asteria.php">
                	<img src="LogoTrasparente.png" alt="Asteria Logo" class="logo-img mb-1">
				</a>
                <small id="current-date" class="text-secondary ms-1" style="font-size: 0.7rem; letter-spacing: 1px;"></small>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item theme-switch-wrapper">
                    <button id="theme-toggle"> </button>
                </li>
                <li class="nav-item"><a class="nav-link px-3" href="Asteria.php">Home</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#">Eventi</a></li>
                <li class="nav-item ms-lg-2">
                	<?php 
                	if($NomeUtente == $userPagina){
                	?>
                    <button class="btn btn-custom rounded-pill fw-bold" onclick="location.href='logout.php'">Logout</button>
                    <?php 
                	}else{
                	?>
                	<button class="btn btn-custom rounded-pill fw-bold" onclick="location.href='Profilo.php?user=<?=$NomeUtente?>'">Profilo Utente</button>
                	<?php 
                	}
                	?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
	<?php
		if(isset($userPagina)){
			try{
				$connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
	        	$sql= "SELECT Nome, Cognome, NomeUtente, Descrizione, NumPost, Foto, (SELECT COUNT(Seguente) FROM follow WHERE Seguente=?) AS Seguiti, (SELECT COUNT(Seguito) FROM follow WHERE Seguito=?) AS Follower
	            		FROM utenti
	           			WHERE NomeUtente=?";
	        $preparata = $connessione->prepare($sql);
	        $preparata->execute([$userPagina, $userPagina, $userPagina]);

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
	                                <a href="elencoFollower.php?user=<?=$userPagina?>"><span class="stat-value" id="count-follower"><?= $riga['Follower'] ?></span></a>
	                                <span class="stat-label">Follower</span>
	                            </div>
	                            <div class="stat-item">
	                                <a href="elencoSeguiti.php?user=<?=$userPagina?>"><span class="stat-value" id="count-seguiti"><?= $riga['Seguiti'] ?></span></a>
	                                <span class="stat-label">Seguiti</span>
	                            </div>
	                        </div>
	                        
	                        <p class="mt-3"><?= $riga['Descrizione'] ?></p>
	        <?php
				}
			}
			$connessione = null;
			} catch(PDOException $e){
				die("Errore nella gestione del database $db: " . $e->getMessage());
			}
			if($NomeUtente == $userPagina){ 
			?>
				<button class="btn-follow fw-bold" onclick="location.href='ModificaProfilo.php'">Modifica Profilo</button>
			<?php 
			} else {	
				try{
					$connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
					$sql = "SELECT * FROM follow WHERE Seguente = ? AND Seguito = ?";
					$preparata = $connessione->prepare($sql);
	        		$preparata->execute([$NomeUtente, $userPagina]);
							    
					if($preparata->rowCount() > 0) {
						$testo = "Seguito";
						$classe = "seguito-attivo";
					} else {
						$testo = "Segui";
						$classe = "";
					}
					$connessione = null;
				} catch(PDOException $e){
					die("Errore nella gestione del database $db: " . $e->getMessage());
				}
			?>
				<button class="btn-follow fw-bold <?= $classe ?>" id="segui" data-user="<?= $userPagina ?>" data-follower="<?= $NomeUtente ?>">
					<?= $testo ?>
				</button>
				<script>
				document.addEventListener('DOMContentLoaded', function() {
				    const btnSegui = document.getElementById('segui');
				    const countFollower = document.getElementById('count-follower');

				    if (btnSegui) {
				        btnSegui.addEventListener('click', function() {
				            const seguito = this.getAttribute('data-user');
				            const follower = this.getAttribute('data-follower');

				            const formData = new FormData();
				            formData.append('seguito', seguito);
				            formData.append('follower', follower);

				            fetch('GestioneSegui.php', {
				                method: 'POST',
				                body: formData
				            })
				            .then(response => {
				                if (!response.ok) {
				                    throw new Error('Errore nella rete o risposta del server non valida');
				                }
				                return response.json();
				            })
				            .then(data => {
				                if (data.errore) {
				                    console.error("Errore lato server:", data.errore);
				                    alert("Si è verificato un errore: " + data.errore);
				                    return;
				                }

				                if (data.stato === "seguito") {
				                    btnSegui.textContent = "Seguito";
				                    btnSegui.classList.add('seguito-attivo');
				                } else if (data.stato === "rimosso") {
				                    btnSegui.textContent = "Segui";
				                    btnSegui.classList.remove('seguito-attivo');
				                }

				                if (countFollower && data.conteggio !== undefined) {
				                    countFollower.textContent = data.conteggio;
				                }
				            })
				            .catch(error => {
				                console.error('Errore durante la richiesta fetch:', error);
				            });
				        });
				    }
				});
				</script>
			<?php 
			} 
			?>
	            </div>

	        </div>
	    </div>
</div>

</body>
<script src="config.js"></script>
</html>
<?php 
	}
}
?>