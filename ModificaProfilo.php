<html>
    <head>
        <title>Asteria - Modifica Profilo</title>
        <link rel="icon" type="image/x-icon" href="LogoIcona.ico">
    </head>
<link rel="stylesheet" href="StileModifica.css">
<?php
    session_start();
    $NomeUtente = $_SESSION['user'];
    $accesso=$_SESSION['accesso'];
    if($accesso!= 1){
        header("location: Index.php");
        exit;
    }
    else{
?>
<script src="modifiche.js"></script>
    <body>
        <div class="main">
            <div class="title-wrapper">
                <div class="title">
                    <div class="IndietroDiv">
                        <button class="indietro" onclick="location.href='Profilo.php?user=<?=$NomeUtente?>'">Indietro</button>
                    </div>
                    <h1>Modifica Profilo</h1>
                    <div class="vuoto"></div>
                </div>
                <div class="wrapper">
                    <div class="box left">      
                        <form action="ModificaProfilo2.php?user=<?=$NomeUtente?>" method="POST" onsubmit="return controllo(event)" id="form">
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
                            <form action="CambiaFoto.php?user=<?=$NomeUtente?>" method="POST" class="formAlto" enctype="multipart/form-data">
                                <img src="UploadProfili/<?=$riga['Foto']?>" width="150" height="150" alt="FotoProfilo" id="foto">
                                <div class="upload-container">
                                    <input type="file" name="fileToUpload" id="fileToUpload" class="input-hidden" >
                                    <label for="fileToUpload" class="custom-file-upload">Scegli un file</label>
                                    <span id="file-chosen">Nessun file selezionato</span>
                                    <input type="submit" value="Cambia Foto Profilo" id="ConfermaFoto" disabled>
                                </div>
                            </form>
                            <script>
                                const actualBtn = document.getElementById('fileToUpload');
                                const fileChosen = document.getElementById('file-chosen');
                                let ConfermaFoto = document.getElementById('ConfermaFoto');

                                actualBtn.addEventListener('change', function(){
                                    fileChosen.textContent = this.files[0].name;
                                    let file = this.files[0];
                                    let urlTemporaneo = URL.createObjectURL(file);
                                    document.getElementById("foto").src=urlTemporaneo;
                                    ConfermaFoto.disabled=false;
                                })
                            </script>
                        </div>
                        <div class="basso">
                            <form method="POST" action="ModificaPassword.php?user=<?=$NomeUtente?>" class="formBasso" id="formPassword" onsubmit="return validaEInvia(event)">
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