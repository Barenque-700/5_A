<?php 
session_start(); 

include "Connessione.php";
$_SESSION['accesso']=0;
if(isset($_POST['user']) && isset($_POST['password'])) {
    try {
        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $sql= 'SELECT * 
            FROM utenti';

        $preparata = $connessione->prepare($sql);
        $preparata->execute();

        if($preparata->rowCount() > 0){
            $ris = $preparata->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['user']=$_POST['user'];
            foreach ($ris as $riga) {
                if (($_POST['user']==$riga['NomeUtente']) && $_POST['password']==$riga['Password']){
                    $_SESSION['accesso']=1;
                    $_SESSION['livello']= $riga['Livello'];
                    $_SESSION['foto']= "UploadProfili/".$riga['Foto'];
                    header("location: Asteria.php");
                }
            }
        }

	    $connessione = null;
    } catch(PDOException $e){
        die("Errore nella gestione del database $db: " . $e->getMessage());
    }
}

?>
<html>
<link rel="stylesheet" href="StileLogin.css">
    <head>
        <title>Login</title>
        <link rel="icon" type="image/x-icon" href="LogoIcona.ico">
        <meta name='viewport' content='width=device-width, initial-scale=1'>
    </head>
    <body>
        <div class="main">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <h1>Login Utente</h1>
                <label for="user">Nome Utente:</label> <br>
                <input type="text" name="user"><br/><br/>

                <label for="user">Password:</label> <br> 
                <input type="password" name="password"><br/><br/>
                
                <input type="submit" value="Accedi">
                <p> Non hai ancora un account?  <a href="Iscrizione.php"> Iscriviti </a> </p>
            </form>
            <img src="LogoQuadrato.png" width="500" height="500" alt="">
            <br>
        </div>
    </body>
</html>