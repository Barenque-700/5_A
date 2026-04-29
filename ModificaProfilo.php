<html>
    <head>
        <title>Modifica Profilo</title>
        <link rel="icon" type="image/x-icon" href="LogoIcona.png">
    </head>
<link rel="stylesheet" href="StileModifica.css">
<?php
    session_start();
    $NomeUtente = $_SESSION['user'];
    $accesso=$_SESSION['accesso'];
    if($accesso!= 1){
        header("location: Index.php");
    }
    else{
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
            function validaEInvia(event) {
                event.preventDefault(); 

                let passwordAttuale = document.getElementById("password").value;
                let indicatore = document.getElementById("IndPass");
                let form = document.getElementById("formPassword");
                let passwordNuova = document.getElementById("passwordNuova").value;
                if (passwordNuova.indexOf(' ') >= 0) {
                    alert("La password non può contenere spazi!");
                    event.preventDefault();
                    return false;
                }
                if (passwordNuova.trim().length === 0) {
                    alert("La password non può essere vuota o composta solo da spazi!");
                    event.preventDefault();
                    return false;
                }
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        let risposta = this.responseText.trim();
                        
                        if (risposta === "") {
                            indicatore.innerHTML = "";
                            form.submit(); 
                        } else {
                            indicatore.innerHTML = risposta;
                        }
                    }
                };
                
                xmlhttp.open("GET", "controlloPassword.php?q=" + encodeURIComponent(passwordAttuale), true);
                xmlhttp.send();

                return false;
            };
            function controllo(event){
                event.preventDefault(); 
                let nomeutente = document.getElementById("nomeutente").value;
                let form = document.getElementById("form");
                if (nomeutente.indexOf(' ') >= 0 || nomeutente.trim().length === 0) {
                    alert("Il nome utente non può contenere spazi o essere vuoto!");
                    event.preventDefault();
                    return false;
                }
                form.submit();
                return false;
            }
    </script>
    <body>
        <div class="main">
            <div class="title-wrapper">
                <div class="title">
                    <div class="IndietroDiv">
                        <button class="indietro" onclick="location.href='Profilo.php'">Indietro</button>
                    </div>
                    <h1>Modifica Profilo</h1>
                    <div class="vuoto"></div>
                </div>
                <div class="wrapper">
                    <div class="box left">      
                        <form action="ModificaProfilo2.php" method="POST" onsubmit="return controllo(event)" id="form">
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
                            <input type="text" name="utente" value="<?=$riga['NomeUtente']?>" onkeyup="controlloNome(this.value)" id="nomeutente"><br/><br/>
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
                            <form action="CambiaFoto.php" method="POST" class="formAlto" enctype="multipart/form-data">
                                <img src="UploadProfili/<?=$riga['Foto']?>" width="150" height="150" alt="FotoProfilo">
                                <div class="upload-container">
                                    <input type="file" name="fileToUpload" id="fileToUpload" class="input-hidden" >
                                    <label for="fileToUpload" class="custom-file-upload">Scegli un file</label>
                                    <span id="file-chosen">Nessun file selezionato</span>
                                    <input type="submit" value="Cambia Foto Profilo">
                                </div>
                            </form>
                            <script>
                                const actualBtn = document.getElementById('fileToUpload');
                                const fileChosen = document.getElementById('file-chosen');

                                actualBtn.addEventListener('change', function(){
                                  fileChosen.textContent = this.files[0].name
                                })
                            </script>
                        </div>
                        <div class="basso">
                            <form method="POST" action="ModificaPassword.php" class="formBasso" id="formPassword" onsubmit="return validaEInvia(event)">
                                <label for="password">Inserisci la tua attuale password:</label><br>
                                <input type="password" name="password" id="password"><br/><br/>
    
                                <div id="IndPass" class="indicatore"></div>
    
                                <label for="passwordNuova">Nuova Password:</label><br>
                                <input type="password" name="passwordNuova" id="passwordNuova"><br/><br/>
    
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
<?php
}
?>