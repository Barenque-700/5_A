<?php
session_start();
include "Connessione.php";
if(!isset($_SESSION['accesso']) || $_SESSION['accesso'] != 1){
    header("location: Index.php");
    exit();
}
$NomeUtente = $_SESSION['user'];
$fotoProfilo = $_SESSION['foto'];
?>

<html>
<head>
    <title>Crea Post - Asteria</title>
    <link rel="stylesheet" href="StileCrea.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="main post-main">
        <div class="title-wrapper">
            <div class="title">
                <div class="IndietroDiv">
                    <button class="indietro" onclick="location.href='Asteria.php'">✕</button>
                </div>
                <h1 style="flex:1">Nuovo Post</h1>
                <div class="vuoto"></div>
            </div>

            <form action="SalvaPost.php" method="POST" enctype="multipart/form-data">
                <div class="post-wrapper">
                    <div class="post-left">
                        <img src="<?=$fotoProfilo?>" class="avatar-small" alt="Profilo">
                    </div>
                    
                    <div class="post-right">
                        <div class="textarea-container">
                            <textarea name="descrizione" placeholder="Cosa hai scoperto oggi?" required></textarea>
                            
                            <div class="icon-wrapper">
                                <input type="file" name="filePost" id="filePost" class="input-hidden" accept="image/*">
                                <label for="filePost" class="custom-file-upload">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                                <span id="file-chosen">Carica la tua foto</span>
                            </div>
                        
                            <div id="preview-container" style="position: relative; display: inline-block;">
                                <img id="img-preview" class="anteprima" src="">
                                <button type="button" id="remove-photo" style="display: none;">✕</button>
                            </div>
                        </div>

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
        const actualBtn = document.getElementById('filePost');
        const fileChosen = document.getElementById('file-chosen');
        const preview = document.getElementById("img-preview");
        const removeBtn = document.getElementById("remove-photo");

        actualBtn.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                fileChosen.textContent = this.files[0].name;
                let file = this.files[0];
                let urlTemporaneo = URL.createObjectURL(file);
                preview.src = urlTemporaneo;
                preview.style.display = "block";
                removeBtn.style.display = "block";
            }
        });
        removeBtn.addEventListener('click', function() {
            actualBtn.value = "";
            fileChosen.textContent = "Carica la tua foto";
            preview.src = "";
            preview.style.display = "none";
            removeBtn.style.display = "none";
        });
    </script>
</body>
</html>