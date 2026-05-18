<?php
session_start();
include "Connessione.php";
$accesso=$_SESSION['accesso'];
$NomeUtente = $_SESSION['user'];
    if($accesso!= 1){
        header("location: Index.php");
    }
    else{
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Asteria - Notifiche</title>
	<link rel="icon" type="image/x-icon" href="LogoIcona.ico">
</head>
<body>
	<?php
		try{
		$connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
		$sql= "SELECT * FROM notifiche WHERE Destinatario=?";
		$preparata = $connessione->prepare($sql);
		$preparata->execute([$NomeUtente]);
		if($preparata->rowCount()>0){
			$ris = $preparata->fetchAll(PDO::FETCH_ASSOC);
			foreach($ris as $riga){
				switch ($riga['tipo']) {
					case 'follow':
						echo $riga['Mittente']." ti ha seguito <br>";
						break;
					case 'like':
						echo $riga['Mittente']." ha messo like ad un tuo post <br>";
						break;
					case 'commento':
						echo $riga['Mittente']." ha commentato un tuo post <br>";
					break;
					case 'menzione':
						echo $riga['Mittente']." ti ha menzionato in un post <br>";
						break;
					default:
						break;
				}
			}
		}
		$connessione = null;
		}catch(PDOException $e){
            die("Errore nella gestione del database $db: " . $e->getMessage());
        }
	?>
</body>
</html>
<?php 
}
?>
