<?php
session_start();
include "Connessione.php";
$accesso=$_SESSION['accesso'];
    if($accesso!= 1){
        header("location: Index.php");
    }
    else{
        $descrizione = $_POST['descrizione'];
        $data = date("Y-m-d H:i:s");
        $NomeUtente = $_SESSION['user'];
        $nomeDB = null;

        if(isset($_FILES['filePost']) && $_FILES['filePost']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "UploadFoto/";
            $fileTmpPath= $_FILES["filePost"]["tmp_name"];
            $imageFileType = strtolower(pathinfo($_FILES["filePost"]["name"],PATHINFO_EXTENSION));
            $nomeHash= hash_file("sha256", $fileTmpPath);
            $nomeDB= $nomeHash.".".$imageFileType;
            $target_file = $target_dir . $nomeDB;

            $uploadOk = 1;
            if ($_FILES["filePost"]["size"] > 5000000) {
                echo "Ci dispiace, il file è troppo grande.";
                $uploadOk = 0;
            }

            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                echo "Ci dispiace, sono ammessi solo file JPG, JPEG, PNG e GIF.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                echo " Il file non è stato caricato. Errore 409";
                header("refresh:3;url=CreaPost.php");
                exit;
            }else{
                if (!file_exists($target_file)) {
                    move_uploaded_file($fileTmpPath, $target_file);
                }
            }
        }

        try{
            $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
            $sql= "INSERT INTO post (Allegato, Descrizione, Data_post, Utente) VALUES (?,?,?,?)";
            $preparata = $connessione->prepare($sql);
            $preparata->execute([$nomeDB, $descrizione, $data, $NomeUtente]);
            $connessione = null;
            header("location: Asteria.php");
        } catch(PDOException $e){
            die("Errore nella gestione del database $db: " . $e->getMessage());
        }
    }
exit;
?>