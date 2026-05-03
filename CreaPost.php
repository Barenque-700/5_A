<?php
session_start();
include "Connessione.php";
if(!isset($_SESSION['accesso']) || $_SESSION['accesso'] != 1){
    header("location: Index.php");
    exit();
}
$NomeUtente = $_SESSION['user'];

try {
    $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $stmt = $connessione->prepare("SELECT Foto FROM utenti WHERE NomeUtente = ?");
    $stmt->execute([$NomeUtente]);
    $userRow = $stmt->fetch();
    $fotoProfilo = $userRow['Foto'] ?? 'default.png';
} catch(PDOException $e) {
    $fotoProfilo = 'default.png';
}
?>

<html>
<head>
    <title>Crea Post - Asteria</title>
    <link rel="stylesheet" href="StileCrea.css">
    <link rel="stylesheet" href="StilePost.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="main post-main">
        <div class="title-wrapper">
            <div class="title">
                <div class="IndietroDiv">
                    <button class="indietro" onclick="location.href='Asteria.php'">✕</button>
                </div>
                <h1 style="flex:1">Nuovo Post Astronomico</h1>
                <div class="vuoto"></div>
            </div>

            <form action="SalvaPost.php" method="POST" enctype="multipart/form-data">
                <div class="post-wrapper">
                    <div class="post-left">
                        <img src="UploadProfili/<?=$fotoProfilo?>" class="avatar-small" alt="Profilo">
                    </div>
                    
                    <div class="post-right">
                        <div class="textarea-container">
                            <textarea name="testo" placeholder="Cosa hai scoperto nel cosmo oggi?" required></textarea>
                            
                            <div class="icon-wrapper">
                                <input type="file" name="filePost" id="filePost" class="input-hidden" accept="image/*">
                                <label for="filePost" class="custom-file-upload">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                                <span id="file-chosen">Carica la tua galassia</span>
                            </div>
                        </div>

                        <img id="img-preview" src="">

                        <div class="footer-post">
                            <div style="flex:1"></div>
                            <input type="submit" value="Pubblica">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('filePost');
        const imgPreview = document.getElementById('img-preview');
        const fileName = document.getElementById('file-name');

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                    imgPreview.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
                fileName.textContent = this.files[0].name;
            }
        });
    </script>
</body>
</html>