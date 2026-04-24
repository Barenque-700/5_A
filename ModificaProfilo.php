<html>
<link rel="stylesheet" href="StileModifica.css">
<?php
    session_start();
    $NomeUtente = $_SESSION['user'];
    $Password = $_SESSION['password'];
?>
<script>
            function controlloNome(str) {
                let btn = document.getElementById("ConfermaInfo");
                if (str.length == 0 || str== "<?=$NomeUtente?>") {
                    document.getElementById("indicatore").innerHTML = "";
                    return;
                } else {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            let risposta = this.responseText;
                            document.getElementById("indicatore").innerHTML = risposta;
                            if (risposta.trim().length > 0) {
                                btn.disabled = true;
                            } else {
                                btn.disabled = false;
                            }
                        }
                    };
                    xmlhttp.open("GET", "controlloNome.php?q=" + str, true);
                    xmlhttp.send();
                }
            }
            function controlloPassword(str) {
                let btn = document.getElementById("ConfermaPassword");
                if (str== "<?=$Password?>") {
                    document.getElementById("IndPass").innerHTML = "";
                    return;
                } else {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            let risposta = this.responseText;
                            document.getElementById("IndPass").innerHTML = risposta;
                            if (risposta.trim().length > 0) {
                                btn.disabled = true;
                            } else {
                                btn.disabled = false;
                            }
                        }
                    };
                    xmlhttp.open("GET", "controlloPassword.php?q=" + str, true);
                    xmlhttp.send();
                }
            }
    </script>
    <body>
        <div class="main">
            <div class="title-wrapper">
                <div class="title">
                    <h1>Modifica Profilo</h1>
                </div>
                <div class="wrapper">
                    <div class="box left">      
                        <form action="ModificaProfilo2.php" method="POST">
                            <?php 
                            include "Connessione.php";
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
                            <input type="text" name="utente" value="<?=$riga['NomeUtente']?>" onkeyup="controlloNome(this.value)"><br/><br/>
                            <a id="indicatore" class="indicatore"> </a>

                            <label for="nascita">Data di Nascita:</label><br>
                            <input type="date" name="data" value="<?=$riga['DataNascita']?>"><br/><br/>

                            <label for="descrizione">Descrizione:</label><br>
                            <input type="text" name="descrizione" value="<?=$riga['Descrizione']?>"><br/><br/>

                            <input type="submit" value="Conferma Modifiche" id="ConfermaInfo">
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
                                <input type="password" name="password" onkeyup="controlloPassword(this.value)>
                                <a id="IndPass" class="indicatore"> </a>
                                <br/><br/>
                                <label for="passwordNuova">Nuova Password:</label><br>
                                <input type="password" name="passwordNuova"><br/><br/>
                                <input type="submit" value="Cambia Password" id="ConfermaPassword">
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
    </body>
</html>
