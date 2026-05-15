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
        $pattern= '/#([^\s!,?]+)/';
        preg_match_all($pattern, $descrizione, $matches);
        $tagsEstratti = array_unique($matches[1]);
        $patternMenzioni= '/@([^\s!,?]+)/';
        preg_match_all($patternMenzioni, $descrizione, $matchesMenzioni);
        $menzioniEstratte= array_unique($matchesMenzioni[1]);

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
        try {
            $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
            $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO post (Allegato, Descrizione, Data_post, Utente) VALUES (?,?,?,?)";
            $preparata = $connessione->prepare($sql);
            $preparata->execute([$nomeDB, $descrizione, $data, $NomeUtente]);

            $postId = $connessione->lastInsertId();

            if (!empty($tagsEstratti)) {
                
                $sqlTag = "INSERT INTO tag (NomeTag) VALUES (?) 
                           ON DUPLICATE KEY UPDATE Id_Tag = LAST_INSERT_ID(Id_Tag)";
                $stmtTag = $connessione->prepare($sqlTag);

                $tagIds = [];
                foreach ($tagsEstratti as $nomeTag) {
                    $nomeTag = strtolower(trim($nomeTag));
                    $stmtTag->execute([$nomeTag]);
                    
                    $tagIds[] = $connessione->lastInsertId();
                }

                $placeholders = array_fill(0, count($tagIds), "(?, ?)");
                $sqlRel = "INSERT IGNORE INTO tagpost (Id_Post, Id_Tag) VALUES " . implode(',', $placeholders);
                
                $params = [];
                foreach ($tagIds as $idTag) {
                    $params[] = $postId;
                    $params[] = $idTag;
                }

                $stmtRel = $connessione->prepare($sqlRel);
                $stmtRel->execute($params);
            }
            if(!empty($menzioniEstratte)){
                $sqlMenzioni= "INSERT INTO notifiche (Mittente, Destinatario, tipo, Id_Post) VALUES (?,?,?,?)";
                $preparataMenzioni = $connessione->prepare($sqlMenzioni);
                $tipo='menzione';
                $query = "SELECT COUNT(*) FROM utenti WHERE NomeUtente=?";
                $stmt= $connessione->prepare($query);
                foreach($menzioniEstratte as $nomeMenzione){
                    $stmt->execute([$nomeMenzione]);
                    $ris= $stmt->fetchColumn();
                    if($ris>0){
                        $preparataMenzioni->execute([$NomeUtente, $nomeMenzione, $tipo, $postId]);
                    }
                }
            }

            $connessione = null;
            header("location: Asteria.php");

        } catch(PDOException $e){
            die("Errore nella gestione del database: " . $e->getMessage());
        }
    }
exit;
?>