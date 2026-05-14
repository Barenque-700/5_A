<?php
session_start();
include "Connessione.php";
$accesso=$_SESSION['accesso'];
$NomeUtente = $_SESSION['user'];
$livello= $_SESSION['livello'];
$Id_Post= $_GET['post'];
$from= $_POST['link-originale'] ?? $_SERVER['HTTP_REFERER'] ?? 'Asteria.php';
$fotoProfilo = $_SESSION['foto'];
    if($accesso!= 1){
        header("location: Index.php");
    }
    else{
    	if(isset($_POST['commento'])){
    		$commento= $_POST['commento'];
    		$data = date("Y-m-d H:i:s");
    		try{
    			$connessione= new PDO("mysql:host=$host;dbname=$db", $user, $password);
    			$sql= "INSERT INTO commenti(Utente, Id_Post, Contenuto, Data) VALUES (?,?,?,?)";
    			$preparata = $connessione->prepare($sql);
                $preparata->execute([$NomeUtente, $Id_Post, $commento, $data]);
                $connessione = null;
    		}catch(PDOException $e){
                die("Errore nella gestione del database $db: " . $e->getMessage());
            }
    	}
        ?>
<html>
<head>
	<title> Asteria - Commenti</title>
	<link rel="icon" type="image/x-icon" href="LogoIcona.ico">
	<link rel="stylesheet" href="StileCommenti.css">
	<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
	<a onclick="location.href= '<?=$from?>'" class="btn-back" style="cursor:pointer;">
        <i class="fa fa-arrow-left"></i> Indietro
    </a>
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST" enctype="multipart/form-data" class="main-form">
	    <div class="post-wrapper">
	        <div class="post-left">
	            <img src="<?=$fotoProfilo?>" class="avatar-small" alt="Profilo">
	        </div>
	        
	        <div class="post-right">
	            <div class="textarea-container">
	                <textarea name="commento" placeholder="Scrivi il tuo commento" required></textarea>
	                <input type="hidden" name="link-originale" value="<?=$from?>">
	                <input type="submit" name="pubblica" id="pubblica" value="Pubblica" class="input-hidden">
	                <label for="pubblica" class="custom-file-upload">
	                    <i class="fa fa-paper-plane-o"></i>
	                </label>
	            </div>                                                  
	        </div>
	    </div>
	</form>
<h1> Commenti </h1>
	<div class="comments-list">
	    <?php 
	    try{
			$connessione= new PDO("mysql:host=$host;dbname=$db", $user, $password);
			$sql= "SELECT Id_Commento, Nome, Cognome, Foto, Utente, Id_Post, Contenuto, Data 
				   FROM commenti JOIN utenti ON utenti.NomeUtente = commenti.Utente 
				   WHERE Id_Post = ?";
			$preparata = $connessione->prepare($sql);
            $preparata->execute([$Id_Post]);
            if($preparata->rowCount() > 0){
                            $ris = $preparata->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($ris as $riga) {
	    ?>
	    <div class="comment-card">
		    <?php if($NomeUtente=== $riga['Utente'] || $livello==0){ ?>
		        <form action="EliminaCommento.php" method="POST" onsubmit="return confirm('Vuoi eliminare questo commento?');" style="margin: 0;">
		            <input type="hidden" name="Id_Commento" value="<?=$riga['Id_Commento']?>">
		            <button type="submit" class="btn-delete-comment" title="Elimina commento">
		                ✕
		            </button>
		        </form>
		    <?php 
			}
		    ?>

		    <div class="comment-left">
		        <img src="UploadProfili/<?=$riga['Foto']?>" class="avatar-micro" alt="User">
		    </div>
		    
		    <div class="comment-right">
		        <div class="comment-header">
		            <div class="comment-info">
		                <span class="comment-user">@<?=$riga['Utente']?></span>
		                <span class="comment-date"><?=$riga['Data']?></span>
		            </div>
		        </div>
		        <div class="comment-text">
		            <?= nl2br(htmlspecialchars($riga['Contenuto'])) ?>
		        </div>
		    </div>
		</div>
	    <?php 
			}
		}else{
			echo "Sembra che nessuno abbia ancora commentato...";
		}
	    $connessione = null;
		}catch(PDOException $e){
            die("Errore nella gestione del database $db: " . $e->getMessage());
        }
	    ?>
	</div>
</body>
<script>
	const body = document.body;
	const temaSalvato = localStorage.getItem('tema') || 'dark';
	body.setAttribute('data-theme', temaSalvato);
</script>
</html>
<?php 
}
?>