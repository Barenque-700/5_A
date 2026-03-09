<?php 
session_start(); 

include "Connessione.php";

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
    <head>
        <title>Login</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
    </head>
    <body>
        <div>
            <h1>Login Utente</h1>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <label for="user">Nome Utente:</label> 
                <input type="text" name="user"><br/><br/>

                <label for="user">Password:</label>  
                <input type="password" name="password"><br/><br/>
                
                <input type="submit" value="Accedi">
            </form>
            
            <br>
        </div>
    </body>
</html>