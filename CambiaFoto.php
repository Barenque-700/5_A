<?php
session_start();
include "Connessione.php";
$FotoVecchia = $_SESSION['foto'];
$NomeUtente = $_SESSION['user'];
$target_dir = "UploadProfili/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$fileTmpPath= $_FILES["fileToUpload"]["tmp_name"];
$nomeHash= hash_file("sha256", $fileTmpPath);
$nomeDB= $nomeHash.".".$imageFileType;
$target_file = $target_dir . $nomeHash. ".".$imageFileType;
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}


// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "Ci dispiace, il file è troppo grande.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Ci dispiace, sono ammessi solo file JPG, JPEG, PNG e GIF.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo " Il file non è stato uploadato. Errore 409";
  header("refresh:3;url=ModificaProfilo.php");
// if everything is ok, try to upload file
} else {
  if (file_exists($target_file)) {
    try{
        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
            $sql= "UPDATE utenti 
                SET Foto = ?
                WHERE NomeUtente = ?";
          $preparata = $connessione->prepare($sql);
          $preparata->execute([$nomeDB, $NomeUtente]);
          $connessione = null;
      } catch(PDOException $e){
          die("Errore nella gestione del database $db: " . $e->getMessage());
      }
      if($FotoVecchia=="Utente.png"){

      }else{
        unlink("UploadProfili/".$FotoVecchia);
      }
    header("location: Profilo.php");
  }else if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
    try{
        $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
            $sql= "UPDATE utenti 
                SET Foto = ?
                WHERE NomeUtente = ?";
          $preparata = $connessione->prepare($sql);
          $preparata->execute([$nomeDB, $NomeUtente]);
          $connessione = null;
      } catch(PDOException $e){
          die("Errore nella gestione del database $db: " . $e->getMessage());
      }
      if($FotoVecchia=="Utente.png"){

      }else{
        unlink("UploadProfili/".$FotoVecchia);
      }
    header("location: Profilo.php");
  } else {
    echo "Sorry, there was an error uploading your file.";
    header("refresh:3;url=ModificaProfilo.php");

  }
}
exit;
?>
