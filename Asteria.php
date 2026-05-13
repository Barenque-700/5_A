<?php
session_start();
include "Connessione.php";
$accesso=$_SESSION['accesso'];
$NomeUtente = $_SESSION['user'];
    if($accesso!= 1){
        header("location: Index.php");
    }
    else{
        ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asteria - Social Astronomy</title>
    <link rel="icon" type="image/x-icon" href="LogoIcona.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="StileAsteria.css">
</head>
<body> 
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-0"> <div class="nav-section-left d-flex align-items-center justify-content-center">
                <a class="navbar-brand d-flex align-items-center m-0" href="#">
                    <img src="LogoTrasparente.png" alt="Asteria Logo" class="logo-img">
                    <small id="current-date" class="text-secondary ms-2" style="font-size: 0.7rem; letter-spacing: 1px; white-space: nowrap;"></small>
                </a>
            </div>

            <div class="flex-grow-1"></div>

            <div class="nav-section-right d-flex align-items-center justify-content-center">
                <div class="theme-switch-wrapper me-3">
                    <button id="theme-toggle"> 🌙 </button>
                </div>
                <button class="btn btn-custom rounded-pill fw-bold" onclick="location.href='Profilo.php?user=<?=$NomeUtente?>'">Profilo Utente</button>
            </div>

        </div>
    </nav>
    <div class="contenitore">
        <div class="sezione sinistra">
            <div class="d-grid gap-2 p-3">
                <button class="btn btn-custom rounded-pill fw-bold" onclick="location.href='CreaPost.php'">
                    <i class="fa fa-plus-circle me-2"></i>Crea
                </button>
            </div>
        <div class="mt-3">
                <li class="nav-item"><a class="nav-link px-3" href="Asteria.php" style="color: var(--primary-color) !important;">Home</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="Eventi.php">Eventi</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="Asteria.php">Notifiche</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="Asteria.php">Ricerca</a></li>
        </div>

        </div>
        <div class="sezione centro">
            <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
            <div class="container-pulsanti">
                <button class="bottone-feed" id="bottone-seguiti">Seguiti</button>
                <button class="bottone-feed" id="bottone-esplora">Esplora</button>
            </div>
            <div class="feed-esplora" id="feed-esplora">
                <div class="container bootstrap snippets bootdey">
                        <div class="col-sm-12">
                            <?php 
                            try{
                                $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                                $sql= "SELECT Id_Post, NumLike, Condivisioni, Allegato, Descrizione, Data_post, Utente,
                                    (SELECT COUNT(*) FROM commenti WHERE commenti.Id_Post = post.Id_Post) AS NumCommenti, 
                                    (SELECT Nome FROM utenti WHERE utenti.NomeUtente= post.Utente) AS Nome, 
                                    (SELECT Cognome FROM utenti WHERE utenti.NomeUtente= post.Utente) AS Cognome,
                                    (SELECT Foto FROM utenti WHERE utenti.NomeUtente= post.Utente) AS Foto,
                                    (SELECT COUNT(*) FROM likepost WHERE likepost.Id_Post = post.Id_Post AND likepost.Utente = ?) AS MioLike
                                    FROM post;";
                                        
                            $preparata = $connessione->prepare($sql);
                            $preparata->execute([$NomeUtente]);
                            $postConScore = [];
                            if($preparata->rowCount() > 0){
                                $ris = $preparata->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($ris as $riga) {
                                    $tempoTrascorso= (time() - strtotime($riga['Data_post']))/3600;
                                    $score = ($riga['NumLike'] + $riga['Condivisioni'] + $riga['NumCommenti'] + 1) / pow($tempoTrascorso+2, 1.5);
                                    $riga['score'] = $score;
                                    $postConScore[] = $riga;
                                }
                                usort($postConScore, function($a, $b) {
                                    return $b['score'] <=> $a['score'];
                                });
                                $top30Post = array_slice($postConScore, 0, 30);
                                foreach($top30Post as $post) {
                                    $descrizione = $post['Descrizione'];
                                    $pattern = '/#([^\s!,?]+)/';
                                    $patternUtenti = '/@([^\s!,?]+)/';
                                    $DescrTag = preg_replace($pattern, '<a class="tag" href="ricerca.php?tag=$1">#$1</a>', $descrizione);
                                    $UserTagDescr= preg_replace($patternUtenti, '<a class="tag" href="Profilo.php?user=$1">@$1</a>', $DescrTag);
                            ?>
                            <div class="panel panel-white post panel-shadow">
                                <div class="post-content-wrapper"> <div class="post-left-column">
                                        <img src="UploadProfili/<?=$post['Foto']?>" class="img-circle avatar" alt="user profile image">
                                    </div>

                                    <div class="post-right-column">
                                        <div onclick="location.href='post.php?post=<?=$post['Id_Post']?>'"  style="cursor:pointer;">
                                            <div class="post-heading">
                                                <a href="Profilo.php?user=<?=$post['Utente']?>"><b><?=$post['Nome']?> <?=$post['Cognome']?></b></a>
                                                <span class="text-muted time">@<?=$post['Utente']?> · <?=$post['Data_post']?></span>
                                            </div>

                                            <div class="post-description">
                                                <p><?=$UserTagDescr?></p>
                                            </div>
                                            <?php 
                                            if(is_null($post['Allegato'])){
                                            }else{
                                            ?>
                                            <div class="post-image">
                                                <img src="UploadFoto/<?=$post['Allegato']?>" class="image" alt="image post">
                                            </div>
                                            <?php 
                                            }
                                            ?>
                                        </div>
                                        <div class="stats">
                                            <a href= "Commenti.php?post=<?=$post['Id_Post']?>" class="stat-item"><i class="fa fa-comment-o"></i> <?=$post['NumCommenti']?></a>
                                            <a class="stat-item like-button" data-postid="<?=$post['Id_Post']?>" style="text-decoration:none; cursor:pointer;">
                                                <i class="fa <?=($post['MioLike'] > 0) ? 'fa-heart' : 'fa-heart-o'?>" 
                                                   id="icon-<?=$post['Id_Post']?>" 
                                                   style="<?=($post['MioLike'] > 0) ? 'color:red;' : ''?>"></i> 
                                                <span id="like-count-<?=$post['Id_Post']?>"><?=$post['NumLike']?></span>
                                            </a>                                            
                                            <a class="stat-item"><i class="fa fa-share"></i> <?=$post['Condivisioni']?></a>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <hr>
                            <?php 
                            }

                            }else{
                                echo "<p> Sembra che nessuno abbia ancora postato...</p>";
                            }
                            $connessione = null;
                            } catch(PDOException $e){
                                die("Errore nella gestione del database $db: " . $e->getMessage());
                            }
                            ?>
                        </div> 
                </div>
            </div>
            <div class="feed-seguiti" id="feed-seguiti">
                <div class="container bootstrap snippets bootdey">
                    <div class="col-sm-12">
                        <?php 
                        try{
                            $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                            $sql= "SELECT Id_Post, NumLike, Condivisioni, Allegato, Descrizione, Data_post, Utente,
                                (SELECT COUNT(*) FROM commenti WHERE commenti.Id_Post = post.Id_Post) AS NumCommenti, 
                                (SELECT Nome FROM utenti WHERE utenti.NomeUtente= post.Utente) AS Nome, 
                                (SELECT Cognome FROM utenti WHERE utenti.NomeUtente= post.Utente) AS Cognome,
                                (SELECT Foto FROM utenti WHERE utenti.NomeUtente= post.Utente) AS Foto,
                                (SELECT COUNT(*) FROM likepost WHERE likepost.Id_Post = post.Id_Post AND likepost.Utente = ?) AS MioLike
                                FROM post
                                WHERE Utente IN (SELECT Seguito FROM follow WHERE Seguente=?)
                                ORDER BY post.Data_post DESC;";
                                    
                        $preparata = $connessione->prepare($sql);
                        $preparata->execute([$NomeUtente, $NomeUtente]);
                        if($preparata->rowCount() > 0){
                            $ris = $preparata->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($ris as $riga) {
                                $descrizione = $riga['Descrizione'];
                                    $pattern = '/#([^\s!,?]+)/';
                                    $DescrTag = preg_replace($pattern, '<a class="tag" href="ricerca.php?tag=$1">#$1</a>', $descrizione);
                                    $patternUtenti = '/@([^\s!,?]+)/';
                                    $UserTagDescr= preg_replace($patternUtenti, '<a class="tag" href="Profilo.php?user=$1">@$1</a>', $DescrTag);
                        ?>
                        <div class="panel panel-white post panel-shadow">
                            <div class="post-content-wrapper"> 
                                <div class="post-left-column">
                                    <img src="UploadProfili/<?=$riga['Foto']?>" class="img-circle avatar" alt="user profile image">
                                </div>

                                <div class="post-right-column">
                                    <div onclick="location.href='post.php?post=<?=$riga['Id_Post']?>'"  style="cursor:pointer;">
                                        <div class="post-heading">
                                            <a href="Profilo.php?user=<?=$riga['Utente']?>"><b><?=$riga['Nome']?> <?=$riga['Cognome']?></b></a>
                                            <span class="text-muted time">@<?=$riga['Utente']?> · <?=$riga['Data_post']?></span>
                                        </div>

                                        <div class="post-description">
                                            <p><?=$UserTagDescr?></p>
                                        </div>
                                        <?php 
                                        if(is_null($riga['Allegato'])){
                                        }else{
                                        ?>
                                        <div class="post-image">
                                            <img src="UploadFoto/<?=$riga['Allegato']?>" class="image" alt="image post">
                                        </div>
                                        <?php 
                                        }
                                        ?>
                                    </div>
                                    <div class="stats">
                                        <a href="Commenti.php?post=<?=$riga['Id_Post']?>" class="stat-item"><i class="fa fa-comment-o"></i> <?=$riga['NumCommenti']?></a>
                                        <a class="stat-item like-button" data-postid="<?=$riga['Id_Post']?>" style="text-decoration:none; cursor:pointer;">
                                            <i class="fa <?=($riga['MioLike'] > 0) ? 'fa-heart' : 'fa-heart-o'?>" 
                                               id="icon-<?=$riga['Id_Post']?>" 
                                               style="<?=($riga['MioLike'] > 0) ? 'color:red;' : ''?>"></i> 
                                            <span id="like-count-<?=$riga['Id_Post']?>"><?=$riga['NumLike']?></span>
                                        </a>
                                        <a href="#" class="stat-item"><i class="fa fa-share"></i> <?=$riga['Condivisioni']?></a>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <hr>
                        <?php 
                        }

                        }else{
                            echo "<p> Sembra che chi segui non abbia ancora postato nulla...</p>";
                        }
                        $connessione = null;
                        } catch(PDOException $e){
                            die("Errore nella gestione del database $db: " . $e->getMessage());
                        }
                        ?>
                    </div> 
                </div>
            </div>
        </div>
        <div class="sezione destra">
            <div style="padding: 16px; width:100%;">
                <div style="font-size:0.78rem; font-weight:800; color:var(--primary-color); letter-spacing:0.8px; text-transform:uppercase; margin-bottom:12px;">
                    <i class="fa fa-users me-1"></i>Utenti Consigliati
                </div>
                <div class="profiliScroll">
                    <?php 
                    try{
                        $connessione= new PDO("mysql:host=$host;dbname=$db", $user, $password);
                        $sql= "SELECT NomeUtente, Foto
                                FROM utenti
                                WHERE NomeUtente IN(
                                    SELECT Utente
                                    FROM post 
                                    WHERE Id_Post IN (
                                        SELECT Id_Post 
                                        FROM tagpost 
                                        WHERE Id_Tag IN (
                                            SELECT Id_Tag 
                                            FROM tagpost 
                                            WHERE Id_Post IN (
                                                SELECT Id_Post 
                                                FROM likepost 
                                                WHERE Utente = ?
                                            ) 
                                            GROUP BY Id_Tag
                                        )
                                    )
                                    GROUP BY Utente
                                ) AND NomeUtente <> ?;";
                        $preparata= $connessione->prepare($sql);
                        $preparata->execute([$NomeUtente, $NomeUtente]);
                        if($preparata->rowCount() > 0){
                            $ris = $preparata->fetchAll(PDO::FETCH_ASSOC);
                            foreach($ris as $riga){
                                ?>
                                <div class="follower-card">
                                    <div class="user-info">
                                        <div class="avatar-container">
                                            <img src="<?="UploadProfili/".$riga['Foto']?>" alt="Profilo" class="follower-img">
                                        </div>
                                        <span class="username">@<?=htmlspecialchars($riga['NomeUtente']) ?></span>
                                    </div>
                                    <button class="btn-profilo" onclick="location.href='Profilo.php?user=<?php echo urlencode($riga['NomeUtente']); ?>'">
                                        Vedi Profilo
                                    </button>
                                </div>
                                <br>
                                <?php
                            }
                        }
                        $connessione = null;
                    }catch(PDOException $e){
                        die("Errore nella gestione del database $db: " . $e->getMessage());
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<script src="config.js"></script>
<script src="feed.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php
    }
?>