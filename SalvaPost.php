<?php
session_start();
include "Connessione.php";
$accesso=$_SESSION['accesso'];
    if($accesso!= 1){
        header("location: Index.php");
    }
    else{
        $descrizione = $_POST['descrizione'];
        $data = date("Y/m/d H:i:s");
        $NomeUtente = $_SESSION['user'];

        if(isset($_POST['filePost'])) {
            $target_dir = "UploadFoto/";
            $target_file = $target_dir . basename($_FILES["filePost"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $fileTmpPath= $_FILES["filePost"]["tmp_name"];
            $nomeHash= hash_file("sha256", $fileTmpPath);
            $nomeDB= $nomeHash.".".$imageFileType;
            $target_file = $target_dir . $nomeHash. ".".$imageFileType;

            // Check file size
            if ($_FILES["filePost"]["size"] > 5000000) {
                echo "Ci dispiace, il file è troppo grande.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                echo "Ci dispiace, sono ammessi solo file JPG, JPEG, PNG e GIF.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo " Il file non è stato caricato. Errore 409";
                header("refresh:3;url=CreaPost.php");
                // if everything is ok, try to upload file
            }else{
                if (file_exists($target_file)) {
                    try{
                        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                            $sql= "INSERT INTO post (Allegato, Descrizione, Data_post, Utente)
                                    VALUES (?,?,?,?)";
                        $preparata = $connessione->prepare($sql);
                        $preparata->execute([$nomeDB, $descrizione, $data, $NomeUtente]);
                        $connessione = null;
                    } catch(PDOException $e){
                        die("Errore nella gestione del database $db: " . $e->getMessage());
                    }
                    header("location: Asteria.php");
                }else if (move_uploaded_file($_FILES["filePost"]["tmp_name"], $target_file)) {
                    echo "The file ". htmlspecialchars( basename( $_FILES["filePost"]["name"])). " has been uploaded.";
                    try{
                        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                        $sql= "INSERT INTO post (Allegato, Descrizione, Data_post, Utente)
                                VALUES (?,?,?,?)";
                        $preparata = $connessione->prepare($sql);
                        $preparata->execute([$nomeDB, $descrizione, $data, $NomeUtente]);
                        $connessione = null;
                    } catch(PDOException $e){
                        die("Errore nella gestione del database $db: " . $e->getMessage());
                    }
                    header("location: Asteria.php");
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    header("refresh:3;url=CreaPost.php");
                }
            }
        }else{
            try{
                $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                $sql= "INSERT INTO post (Descrizione, Data_post, Utente)
                        VALUES (?,?,?)";
                $preparata = $connessione->prepare($sql);
                $preparata->execute([$descrizione, $data, $NomeUtente]);
                $connessione = null;
            } catch(PDOException $e){
                die("Errore nella gestione del database $db: " . $e->getMessage());
            }
            header("location: Asteria.php");
        }
    }
exit;
?>