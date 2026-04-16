<html>
<link rel="stylesheet" href="StileModifica.css">
    <div class="main">
    <form action="ModificaProfilo2.php" method="POST">
        <h1>Modifica Profilo</h1>
        <?php 
        session_start();
        include "Connessione.php";
        $NomeUtente = $_SESSION['nomeutente'];
        if(isset($NomeUtente)){
            try{
                $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                $sql= "SELECT Nome, Cognome, NomeUtente, Descrizione, DataNascita, Foto, Password
                        FROM utenti
                        WHERE NomeUtente=?";
                $preparata = $connessione->prepare($sql);
                $preparata->execute([$NomeUtente]);
                if($preparata->rowCount() > 0){
                    $ris = $preparata->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($ris as $riga) {
        ?>
        <div class="sinistra">
            <div class="form-row">
                <div class="form-group">
                    <label for="nome">Nome:</label><br>
                    <input type="text" name="nome" value="<?=$riga['Nome']?>">
                </div>
                <div class="form-group">
                    <label for="cognome">Cognome:</label><br>
                    <input type="text" name="cognome" value="<?=$riga['Cognome']?>">
                </div>
            </div>

            <label for="utente">Nome Utente:</label><br>
            <input type="text" name="utente" value="<?=$riga['NomeUtente']?>"><br/><br/>

            <label for="nascita">Data di Nascita:</label><br>
            <input type="date" name="data" value="<?=$riga['DataNascita']?>"><br/><br/>

            <label for="descrizione">Descrizione:</label><br>
            <input type="text" name="descrizione" value="<?=$riga['Descrizione']?>"><br/><br/>

            <label for="password">Password:</label><br>
            <input type="password" name="password" value="<?=$riga['Password']?>"><br/><br/>

            <input type="submit" value="Conferma Modifiche">
        </div>
        <div class="destra">
            </form>
            <img src="UploadProfili/<?=$riga['Foto']?>" width="150" height="150" alt="FotoProfilo">
             <input type="submit" value="Cambia Foto Profilo">
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
</html>
