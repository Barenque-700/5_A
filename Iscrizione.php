<?php 
session_start();
include "Connessione.php";
    if(isset($_POST['utente']) && isset($_POST['password']) && isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['data'])) {
        $utente = $_POST['utente'];
        $passwordUtente= $_POST['password'];
        $nome= $_POST['nome'];
        $cognome= $_POST['cognome'];
        $data= $_POST['data'];
    try {
        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $sql = "INSERT INTO utenti(Nome, Cognome, NomeUtente, DataNascita, Password) VALUES (?,?,?,?,?)";
        $preparata = $connessione->prepare($sql);
        if ( $preparata->execute([$nome,$cognome,$utente,$data,$passwordUtente]) ) {
            header("Location: Index.php");
            exit;  
        }
	    $connessione = null;
    } catch(PDOException $e){
        die("Errore nella gestione del database $db: " . $e->getMessage());
    }
}
?>
<html>
<link rel="stylesheet" href="StileIscrizione.css">
    <div class="main">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <h1>Iscriviti</h1>
        
        <div class="form-row">
            <div class="form-group">
                <label for="nome">Nome:</label><br>
                <input type="text" name="nome">
            </div>
            <div class="form-group">
                <label for="cognome">Cognome:</label><br>
                <input type="text" name="cognome">
            </div>
        </div>

        <label for="utente">Nome Utente:</label><br>
        <input type="text" name="utente"><br/><br/>

        <label for="nascita">Data di Nascita:</label><br>
        <input type="date" name="data"><br/><br/>

        <label for="password">Password:</label><br>
        <input type="password" name="password"><br/><br/>

        <input type="submit" value="Iscriviti">
        <p> Hai già un account?  <a href="Index.php"> Accedi </a> </p>
    </form>
    <img src="LogoQuadrato.png" width="500" height="500" alt="Logo">
</div>
</html>
