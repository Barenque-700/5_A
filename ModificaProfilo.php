<html>
<link rel="stylesheet" href="StileModifica.css">
    <div class="main">
        <div class="title-wrapper">
            <div class="title">
                <h1>Modifica Profilo</h1>
            </div>
            <div class="wrapper">
                <div class="box left">      
                    <form action="ModificaProfilo2.php" method="POST">
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

                        <input type="submit" value="Conferma Modifiche">
                    </form>
                </div>
                <div class="box right">
                    <div class="alto">
                        <form action="CambiaFoto.php" method="POST" class="formAlto">
                            <img src="UploadProfili/<?=$riga['Foto']?>" width="150" height="150" alt="FotoProfilo">
                            <input type="submit" value="Cambia Foto Profilo">
                        </form>
                    </div>
                    <div class="basso">
                        <form action="CambiaPassword.php" method="POST" class="formBasso">
                            <label for="password">Inserisci la tua attuale password:</label><br>
                            <input type="password" name="password"><br/><br/>
                            <label for="passwordNuova">Nuova Password:</label><br>
                            <input type="password" name="passwordNuova"><br/><br/>
                            <input type="submit" value="Cambia Password">
                        </form>
                    </div>
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
</html>
