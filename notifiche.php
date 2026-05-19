<?php
session_start();
include "Connessione.php";

// Salviamo l'URI corrente se necessario per i reindirizzamenti
$_SESSION['last_main_page'] = $_SERVER['REQUEST_URI'];

$accesso = $_SESSION['accesso'];
$NomeUtente = $_SESSION['user'];

if ($accesso != 1) {
    header("location: Index.php");
    exit();
} else {
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asteria - Notifiche</title>
    <link rel="icon" type="image/x-icon" href="LogoIcona.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="StileNotifica.css">
</head>
<body> 
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-0"> 
            <div class="nav-section-left d-flex align-items-center justify-content-center">
                <a class="navbar-brand d-flex align-items-center m-0" href="Asteria.php">
                    <img src="LogoTrasparente.png" alt="Asteria Logo" class="logo-img">
                    <small id="current-date" class="text-secondary ms-2" style="font-size: 0.7rem; letter-spacing: 1px; white-space: nowrap;"></small>
                </a>
            </div>

            <div class="flex-grow-1"></div>

            <div class="nav-section-right d-flex align-items-center justify-content-center">
                <div class="theme-switch-wrapper me-3">
                    <button id="theme-toggle"> 🌙 </button>
                </div>
                <button class="btn btn-custom rounded-pill fw-bold" onclick="location.href='Profilo.php?user=<?=urlencode($NomeUtente)?>'">Profilo Utente</button>
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
                <?php 
                $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                $queryNotifiche = "SELECT Id_Notifica FROM notifiche WHERE Letto = 0 AND Destinatario = ?";
                $stmtNotifiche = $connessione->prepare($queryNotifiche);
                $stmtNotifiche->execute([$NomeUtente]);
                ?>
                <li class="nav-item"><a class="nav-link px-3" href="Asteria.php"><i class="fa fa-house me-2"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="Eventi.php"><i class="fa-solid fa-calendar-days me-2"></i>Eventi</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="notifiche.php" style="color: var(--primary-color) !important;"><i class="fa-solid fa-bell me-2"></i>Notifiche <?php if($stmtNotifiche->rowCount() > 0){?> <span class="dot"></span><?php }?></a></li>
                <li class="nav-item"><a class="nav-link px-3" href="Ricerca.php"><i class="fa-solid fa-magnifying-glass me-2"></i>Ricerca</a></li>
            </div>
        </div>

        <div class="sezione centro">
            
            <div class="feed-esplora" style="display: block; width: 100%;">
                <div class="container bootstrap snippets bootdey">
                    <div class="col-sm-12">
                        <?php 
                        try {
                            $sql = "SELECT notifiche.*, utenti.Foto, utenti.Nome, utenti.Cognome 
                                    FROM notifiche  
                                    LEFT JOIN utenti ON notifiche.Mittente = utenti.NomeUtente 
                                    WHERE notifiche.Destinatario = ? 
                                    ORDER BY notifiche.Id_Notifica DESC"; // Modifica con la colonna temporale se presente (es. Data_notifica)
                            
                            $preparata = $connessione->prepare($sql);
                            $preparata->execute([$NomeUtente]);
                            
                            if ($preparata->rowCount() > 0) {
                                $risultati = $preparata->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($risultati as $riga) {
                                                                      
                                    $messaggio = "";
                                    $icona = "";
                                    $stato=false;
                                    switch ($riga['tipo']) {
                                        case 'follow':
                                            $messaggio = "ha iniziato a seguirti.";
                                            $icona = "fa-user-plus text-info";                                          
                                            break;
                                        case 'like':
                                            $messaggio = "ha messo like ad un tuo post.";
                                            $icona = "fa-heart text-danger";
                                            $stato=true;
                                            break;
                                        case 'commento':
                                            $messaggio = "ha commentato un tuo post.";
                                            $icona = "fa-comment text-success";
                                            $stato=true;
                                            break;
                                        case 'menzione':
                                            $messaggio = "ti ha menzionato in un post.";
                                            $icona = "fa-at text-warning";
                                            $stato=true;
                                            break;
                                        default:
                                            $messaggio = "ha interagito con te.";
                                            $icona = "fa-bell";
                                            break;
                                    }
                                    ?>
                                    
                                    <div class="panel panel-white post panel-shadow" style="margin-bottom: 10px;">
                                        <div class="post-content-wrapper align-items-center"> 
                                            <div class="post-left-column">
                                                <img src="UploadProfili/<?=htmlspecialchars($riga['Foto'] ?? 'default.png')?>" class="img-circle avatar" alt="user profile image">
                                            </div>

                                            <div class="post-right-column">
                                                <div class="post-heading m-0 d-flex align-items-center flex-wrap">
                                                    <a href="Profilo.php?user=<?=urlencode($riga['Mittente'])?>">
                                                        <b class="me-1"><?=htmlspecialchars($riga['Nome'] . " " . $riga['Cognome'])?></b>
                                                    </a>
                                                    <span class="text-muted me-2">@<?=htmlspecialchars($riga['Mittente'])?></span>
                                                    <span class="text-dark-theme-adjusted"><?=htmlentities($messaggio)?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="ms-auto px-3" style="font-size: 1.2rem;">
                                            	<?php 
                                            		if($stato){ 
                                            	?>  
                                            		<a class="redirezione" href="post.php?post=<?=$riga['Id_Post']?>"><i class="fa-solid fa-share-from-square me-1"></i></a>
                                            	<?php
                                            	 	} 
                                            	 ?>                                             	
                                                <i class="fa <?=$icona?>"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <?php
                                }

                                $updateQuery = "UPDATE notifiche SET Letto = 1 WHERE Destinatario = ? AND Letto = 0";
                                $updateStmt = $connessione->prepare($updateQuery);
                                $updateStmt->execute([$NomeUtente]);

                            } else {
                                echo "<p class='text-center mt-4'>Non hai ancora ricevuto nessuna notifica.</p>";
                            }
                        } catch (PDOException $e) {
                            die("Errore nella gestione del database: " . $e->getMessage());
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
                    try {
                        // Stessa query di raccomandazione basata sui tag in comune
                        $sqlConsigliati = "SELECT NomeUtente, Foto
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
                        
                        $preparataConsigliati = $connessione->prepare($sqlConsigliati);
                        $preparataConsigliati->execute([$NomeUtente, $NomeUtente]);
                        
                        if ($preparataConsigliati->rowCount() > 0) {
                            $risConsigliati = $preparataConsigliati->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($risConsigliati as $rigaConsigliato) {
                                ?>
                                <div class="follower-card">
                                    <div class="user-info">
                                        <div class="avatar-container">
                                            <img src="UploadProfili/<?=$rigaConsigliato['Foto']?>" alt="Profilo" class="follower-img">
                                        </div>
                                        <span class="username">@<?=htmlspecialchars($rigaConsigliato['NomeUtente']) ?></span>
                                    </div>
                                    <button class="btn-profilo" onclick="location.href='Profilo.php?user=<?php echo urlencode($rigaConsigliato['NomeUtente']); ?>'">
                                        Vedi Profilo
                                    </button>
                                </div>
                                <br>
                                <?php
                            }
                        } else {
                            echo "<small class='text-muted'>Nessun suggerimento disponibile</small>";
                        }
                        $connessione = null;
                    } catch (PDOException $e) {
                        die("Errore nella gestione del database: " . $e->getMessage());
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
}
?>