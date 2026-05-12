<!DOCTYPE html>
<?php 
    session_start();
    include "Connessione.php";
    
    // Controllo accesso
    if(!isset($_SESSION['accesso']) || $_SESSION['accesso'] != 1){
        header("location: Index.php");
        exit();
    }

    $NomeUtente = $_SESSION['user'];
    $post = isset($_GET['post']) ? $_GET['post'] : null;

    if(!$post) {
        header("location: Asteria.php");
        exit();
    }
?>
<html lang="it">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asteria - Post</title>
    <link rel="stylesheet" href="StilePost.css">
    <link rel="icon" type="image/x-icon" href="LogoIcona.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-8">
                <?php 
                try {
                    $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                    $sql = "SELECT Id_Post, NumLike, Condivisioni, Allegato, Descrizione, Data_post, Utente,
                            (SELECT COUNT(*) FROM commenti WHERE commenti.Id_Post = post.Id_Post) AS NumCommenti, 
                            (SELECT Nome FROM utenti WHERE utenti.NomeUtente= post.Utente) AS Nome, 
                            (SELECT Cognome FROM utenti WHERE utenti.NomeUtente= post.Utente) AS Cognome,
                            (SELECT Foto FROM utenti WHERE utenti.NomeUtente= post.Utente) AS Foto,
                            (SELECT COUNT(*) FROM likepost WHERE likepost.Id_Post = post.Id_Post AND likepost.Utente = ?) AS MioLike
                            FROM post
                            WHERE Id_Post = ?;";
                                
                    $preparata = $connessione->prepare($sql);
                    $preparata->execute([$NomeUtente, $post]);

                    if($riga = $preparata->fetch(PDO::FETCH_ASSOC)){
                        $descrizione = $riga['Descrizione'];
                        $pattern = '/#([^\s!,?]+)/';
                        $DescrTag = preg_replace($pattern, '<a class="tag" href="ricerca.php?tag=$1">#$1</a>', $descrizione);
                        $patternUtenti = '/@([^\s!,?]+)/';
                        $UserTagDescr = preg_replace($patternUtenti, '<a class="tag" href="Profilo.php?user=$1">@$1</a>', $DescrTag);
                ?>
                    <div class="post-card">
                        <div class="post-content-wrapper"> 
                            <!-- Colonna Avatar -->
                            <div class="post-left-column">
                                <img src="UploadProfili/<?=$riga['Foto']?>" class="avatar" alt="user profile image">
                            </div>

                            <!-- Colonna Contenuto -->
                            <div class="post-right-column">
                                <div class="post-heading">
                                    <a href="Profilo.php?user=<?=$riga['Utente']?>">
                                        <b class="user-full-name"><?=$riga['Nome']?> <?=$riga['Cognome']?></b>
                                    </a>
                                    <span class="text-utente time">@<?=$riga['Utente']?> · <?=$riga['Data_post']?></span>
                                </div>

                                <div class="post-description">
                                    <p><?=$UserTagDescr?></p>
                                </div>
                                
                                <?php if(!is_null($riga['Allegato'])): ?>
                                <div class="post-image">
                                    <img src="UploadFoto/<?=$riga['Allegato']?>" class="image" alt="image post">
                                </div>
                                <?php endif; ?>

                                <div class="stats">
                                    <a href="Commenti.php?post=<?=$riga['Id_Post']?>" class="stat-item">
                                        <i class="fa fa-comment-o"></i> <?=$riga['NumCommenti']?>
                                    </a>
                                    
                                    <a class="stat-item like-button" data-postid="<?=$riga['Id_Post']?>">
                                        <i class="fa <?=($riga['MioLike'] > 0) ? 'fa-heart' : 'fa-heart-o'?>" 
                                           id="icon-<?=$riga['Id_Post']?>" 
                                           style="<?=($riga['MioLike'] > 0) ? 'color:red;' : ''?>"></i> 
                                        <span id="like-count-<?=$riga['Id_Post']?>"><?=$riga['NumLike']?></span>
                                    </a>
                                    
                                    <a href="#" class="stat-item">
                                        <i class="fa fa-share"></i> <?=$riga['Condivisioni']?>
                                    </a>
                                </div>
                            </div> 
                        </div>
                    </div>
                <?php 
                    } else {
                        echo "<div class='post-card text-center'><p>Post non trovato.</p></div>";
                    }
                    $connessione = null;
                } catch(PDOException $e){
                    die("Errore: " . $e->getMessage());
                }
                ?>
            </div> 
        </div>
    </div>

    <script> 
        // Gestione Like via Fetch
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.like-button');
            if (btn) {
                e.preventDefault();
                const postId = btn.getAttribute('data-postid');
                btn.style.pointerEvents = 'none'; 

                const formData = new FormData();
                formData.append('id_post', postId);

                fetch('GestioneLike.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.stato !== 'errore') {
                        document.getElementById(`like-count-${postId}`).innerText = data.totale;
                        const icon = document.getElementById(`icon-${postId}`);
                        if (data.stato === 'aggiunto') {
                            icon.classList.replace('fa-heart-o', 'fa-heart');
                            icon.style.color = "red";
                        } else {
                            icon.classList.replace('fa-heart', 'fa-heart-o');
                            icon.style.color = "";
                        }
                    }
                })
                .catch(error => console.error('Errore:', error))
                .finally(() => {
                    btn.style.pointerEvents = 'auto';
                });
            }
        });

        // Applicazione Tema
        const temaSalvato = localStorage.getItem('tema') || 'dark';
        document.body.setAttribute('data-theme', temaSalvato);
    </script>
</body>
</html>