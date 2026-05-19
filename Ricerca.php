<?php
session_start();
include "Connessione.php";
$_SESSION['last_main_page'] = $_SERVER['REQUEST_URI'];
$accesso    = $_SESSION['accesso'];
$NomeUtente = $_SESSION['user'];

if ($accesso != 1) {
    header("location: Index.php");
    exit;
}

$query      = trim($_GET['q']   ?? '');
$filtro     = $_GET['f']        ?? 'tutto';
$tagCercato = trim($_GET['tag'] ?? '');

if ($tagCercato !== '') {
    $query  = $tagCercato;
    $filtro = 'tag';
}

$utenti = [];
$post   = [];
$tags   = [];
$trending = [];

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlTrending = "SELECT t.NomeTag, COUNT(tp.Id_Post) AS totale
                    FROM tag t
                    JOIN tagpost tp ON t.Id_Tag = tp.Id_Tag
                    GROUP BY t.Id_Tag
                    ORDER BY totale DESC
                    LIMIT 10";
    $trending = $conn->query($sqlTrending)->fetchAll(PDO::FETCH_ASSOC);

    if ($query !== '') {
        $like = '%' . $query . '%';

        if ($filtro === 'tutto' || $filtro === 'utenti') {
            $sqlU = "SELECT NomeUtente, Nome, Cognome, Foto, Descrizione
                     FROM utenti
                     WHERE NomeUtente LIKE ? OR Nome LIKE ? OR Cognome LIKE ?
                     ORDER BY NomeUtente ASC
                     LIMIT 20";
            $stmtU = $conn->prepare($sqlU);
            $stmtU->execute([$like, $like, $like]);
            $utenti = $stmtU->fetchAll(PDO::FETCH_ASSOC);
        }
        if ($filtro === 'tutto' || $filtro === 'post') {
            $sqlP = "SELECT p.Id_Post, p.Descrizione, p.Allegato, p.Data_post,
                            p.NumLike, p.Condivisioni, p.Utente,
                            (SELECT COUNT(*) FROM commenti WHERE Id_Post = p.Id_Post) AS NumCommenti,
                            u.Nome, u.Cognome, u.Foto
                     FROM post p
                     JOIN utenti u ON u.NomeUtente = p.Utente
                     WHERE p.Descrizione LIKE ?
                     ORDER BY p.Data_post DESC
                     LIMIT 30";
            $stmtP = $conn->prepare($sqlP);
            $stmtP->execute([$like]);
            $post = $stmtP->fetchAll(PDO::FETCH_ASSOC);
        }
        if ($filtro === 'tutto' || $filtro === 'tag') {
            $sqlT = "SELECT t.NomeTag, COUNT(tp.Id_Post) AS totale
                     FROM tag t
                     JOIN tagpost tp ON t.Id_Tag = tp.Id_Tag
                     WHERE t.NomeTag LIKE ?
                     GROUP BY t.Id_Tag
                     ORDER BY totale DESC
                     LIMIT 20";
            $stmtT = $conn->prepare($sqlT);
            $stmtT->execute([$like]);
            $tags = $stmtT->fetchAll(PDO::FETCH_ASSOC);
        }
        if ($filtro === 'tag' && count($tags) === 0) {
        }
        if ($filtro === 'tag') {
            $sqlPT = "SELECT p.Id_Post, p.Descrizione, p.Allegato, p.Data_post,
                             p.NumLike, p.Condivisioni, p.Utente,
                             (SELECT COUNT(*) FROM commenti WHERE Id_Post = p.Id_Post) AS NumCommenti,
                             u.Nome, u.Cognome, u.Foto
                      FROM post p
                      JOIN utenti u ON u.NomeUtente = p.Utente
                      JOIN tagpost tp ON tp.Id_Post = p.Id_Post
                      JOIN tag t ON t.Id_Tag = tp.Id_Tag
                      WHERE t.NomeTag LIKE ?
                      ORDER BY p.Data_post DESC
                      LIMIT 30";
            $stmtPT = $conn->prepare($sqlPT);
            $stmtPT->execute([$like]);
            $postTag = $stmtPT->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($postTag)) {
                $post = array_merge($post, $postTag);
                $seen = [];
                $post = array_filter($post, function($p) use (&$seen) {
                    if (in_array($p['Id_Post'], $seen)) return false;
                    $seen[] = $p['Id_Post'];
                    return true;
                });
            }
        }
    }

    $conn = null;
} catch (PDOException $e) {
    die("Errore database: " . $e->getMessage());
}

function highlight($testo, $query) {
    if ($query === '') return htmlspecialchars($testo);
    $escaped = htmlspecialchars($testo);
    $q = preg_quote(htmlspecialchars($query), '/');
    return preg_replace('/(' . $q . ')/i', '<mark style="background:rgba(82,113,255,0.25);color:inherit;border-radius:3px;padding:0 2px;">$1</mark>', $escaped);
}

function parseDescr($testo, $query = '') {
    $testo = htmlspecialchars($testo);
    $testo = preg_replace('/#([^\s!,?]+)/', '<a class="tag" href="ricerca.php?tag=$1">#$1</a>', $testo);
    $testo = preg_replace('/@([^\s!,?]+)/', '<a class="tag" href="Profilo.php?user=$1">@$1</a>', $testo);
    return $testo;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asteria - Ricerca</title>
    <link rel="icon" type="image/x-icon" href="LogoIcona.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="StileAsteria.css">
    <link rel="stylesheet" href="StileRicerca.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-0">
            <div class="nav-section-left d-flex align-items-center justify-content-center">
                <a class="navbar-brand d-flex align-items-center m-0" href="#">
                    <img src="LogoTrasparente.png" alt="Asteria Logo" class="logo-img">
                    <small id="current-date" class="text-secondary ms-2"
                           style="font-size:0.7rem; letter-spacing:1px; white-space:nowrap;"></small>
                </a>
            </div>
            <div class="flex-grow-1"></div>
            <div class="nav-section-right d-flex align-items-center justify-content-center">
                <div class="theme-switch-wrapper me-3">
                    <button id="theme-toggle">🌙</button>
                </div>
                <button class="btn btn-custom rounded-pill fw-bold"
                        onclick="location.href='Profilo.php?user=<?= $NomeUtente ?>'">
                    Profilo Utente
                </button>
            </div>
        </div>
    </nav>

    <div class="contenitore">

        <div class="sezione sinistra">
            <div class="d-grid gap-2 p-3">
                <button class="btn btn-custom rounded-pill fw-bold"
                        onclick="location.href='CreaPost.php'">
                    <i class="fa fa-plus-circle me-2"></i>Crea
                </button>
            </div>
            <div class="mt-3">
                <li class="nav-item"><a class="nav-link px-3" href="Asteria.php"><i class="fa fa-house me-2"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="eventi.php"><i class="fa-solid fa-calendar-days me-2"></i>Eventi</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="notifiche.php"><i class="fa-solid fa-bell me-2"></i>Notifiche</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="Ricerca.php" style="color: var(--primary-color) !important;"><i class="fa-solid fa-magnifying-glass me-2"></i>Ricerca</a></li>
            </div>
        </div>

        <div class="sezione centro">

            <div class="ricerca-header">
                <form method="GET" action="ricerca.php" id="form-ricerca">
                    <div class="ricerca-input-wrapper">
                        <i class="fa fa-search"></i>
                        <input type="text"
                               name="q"
                               id="input-ricerca"
                               placeholder="Cerca persone, post, #tag…"
                               value="<?= htmlspecialchars($query) ?>"
                               autocomplete="off">
                        <input type="hidden" name="f" id="filtro-hidden" value="<?= htmlspecialchars($filtro) ?>">
                        <?php if ($tagCercato !== ''): ?>
                            <input type="hidden" name="tag" value="<?= htmlspecialchars($tagCercato) ?>">
                        <?php endif; ?>
                        <button type="button" class="clear-btn" id="clear-btn" onclick="clearRicerca()">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </form>

                <div class="filtri-tabs">
                    <button class="<?= $filtro === 'tutto'   ? 'active' : '' ?>"
                            onclick="setFiltro('tutto')">Tutto</button>
                    <button class="<?= $filtro === 'utenti'  ? 'active' : '' ?>"
                            onclick="setFiltro('utenti')">
                        <i class="fa fa-user me-1"></i>Persone
                    </button>
                    <button class="<?= $filtro === 'post'    ? 'active' : '' ?>"
                            onclick="setFiltro('post')">
                            <i class="fa-regular fa-message me-1"></i>Post
                    </button>
                    <button class="<?= $filtro === 'tag'     ? 'active' : '' ?>"
                            onclick="setFiltro('tag')">
                        <i class="fa fa-hashtag me-1"></i>Tag
                    </button>
                </div>
            </div>

            <div class="ricerca-risultati">

                <?php if ($query === ''): ?>
                    <div class="ricerca-placeholder">
                        <i class="fa fa-search"></i>
                        <p>Cerca persone, post o hashtag</p>
                    </div>

                    <?php if (!empty($trending)): ?>
                    <div class="trending-section">
                        <div class="trending-title"><i class="fa-solid fa-tags me-1"></i></i>Tag più usati</div>
                        <div class="trending-tags">
                            <?php foreach ($trending as $t): ?>
                                <button class="trending-tag"
                                        onclick="cercaTag('<?= htmlspecialchars($t['NomeTag']) ?>')">
                                    #<?= htmlspecialchars($t['NomeTag']) ?>
                                    <span style="opacity:0.6; font-weight:400; margin-left:4px;"><?= $t['totale'] ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                <?php else: ?>
                    <?php
                    $totale = count($utenti) + count($post) + count($tags);
                    if ($totale === 0):
                    ?>
                        <div class="nessun-risultato">
                            <i class="fa fa-inbox"></i>
                            <span>Nessun risultato per "<strong><?= htmlspecialchars($query) ?></strong>"</span>
                        </div>

                        <?php if (!empty($trending)): ?>
                        <div class="trending-section" style="margin-top:24px;">
                            <div class="trending-title"><i class="fa fa-fire me-1"></i>Tag più usati</div>
                            <div class="trending-tags">
                                <?php foreach ($trending as $t): ?>
                                    <button class="trending-tag"
                                            onclick="cercaTag('<?= htmlspecialchars($t['NomeTag']) ?>')">
                                        #<?= htmlspecialchars($t['NomeTag']) ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                    <?php else: ?>

                        <?php if (!empty($utenti)): ?>
                        <div class="sezione-label"><i class="fa fa-user me-1"></i>Persone</div>
                        <div class="utenti-lista">
                            <?php foreach ($utenti as $u): ?>
                            <a href="Profilo.php?user=<?= urlencode($u['NomeUtente']) ?>" class="utente-card">
                                <img src="UploadProfili/<?= htmlspecialchars($u['Foto']) ?>"
                                     alt="avatar" class="utente-avatar">
                                <div class="utente-info">
                                    <div class="utente-nome">
                                        <?= highlight($u['Nome'] . ' ' . $u['Cognome'], $query) ?>
                                    </div>
                                    <div class="utente-username">
                                        @<?= highlight($u['NomeUtente'], $query) ?>
                                    </div>
                                    <?php if (!empty($u['Descrizione'])): ?>
                                    <div class="utente-bio"><?= htmlspecialchars($u['Descrizione']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <button class="btn-vedi-profilo"
                                        onclick="event.preventDefault(); location.href='Profilo.php?user=<?= urlencode($u['NomeUtente']) ?>'">
                                    Profilo
                                </button>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($tags)): ?>
                        <div class="sezione-label"><i class="fa fa-hashtag me-1"></i>Tag</div>
                        <div class="tag-lista">
                            <?php foreach ($tags as $t): ?>
                            <a href="ricerca.php?tag=<?= urlencode($t['NomeTag']) ?>&f=tag" class="tag-card">
                                <span class="tag-nome">#<?= highlight($t['NomeTag'], $query) ?></span>
                                <span class="tag-count"><?= $t['totale'] ?> post</span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($post)): ?>
                        <div class="sezione-label"><i class="fa fa-file-text-o me-1"></i>Post</div>
                        <div class="post-lista">
                            <?php foreach ($post as $p): ?>
                            <div class="post-risultato">
                                <div class="post-ris-header">
                                    <img src="UploadProfili/<?= htmlspecialchars($p['Foto']) ?>"
                                         alt="avatar" class="post-ris-avatar">
                                    <div class="post-ris-meta">
                                        <div class="post-ris-nome">
                                            <a href="Profilo.php?user=<?= urlencode($p['Utente']) ?>"
                                               style="color:inherit;">
                                                <?= htmlspecialchars($p['Nome'] . ' ' . $p['Cognome']) ?>
                                            </a>
                                        </div>
                                        <div class="post-ris-info">
                                            @<?= htmlspecialchars($p['Utente']) ?>
                                            · <?= htmlspecialchars($p['Data_post']) ?>
                                        </div>
                                    </div>
                                    <a href="post.php?post=<?= $p['Id_Post'] ?>"
                                       style="font-size:0.75rem; color:var(--primary-color); font-weight:700;">
                                        Vedi post <i class="fa fa-external-link ms-1"></i>
                                    </a>
                                </div>

                                <div class="post-ris-testo">
                                    <?= parseDescr($p['Descrizione'], $query) ?>
                                </div>

                                <?php if (!empty($p['Allegato'])): ?>
                                <img src="UploadFoto/<?= htmlspecialchars($p['Allegato']) ?>"
                                     alt="allegato" class="post-ris-immagine">
                                <?php endif; ?>

                                <div class="post-ris-stats">
                                    <span><i class="fa fa-heart-o"></i> <?= $p['NumLike'] ?></span>
                                    <span><i class="fa fa-comment-o"></i> <?= $p['NumCommenti'] ?></span>
                                    <span><i class="fa fa-share"></i> <?= $p['Condivisioni'] ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>

        <div class="sezione destra">
            <?php if (!empty($trending)): ?>
            <div style="padding: 16px; width:100%;">
                <div style="font-size:0.78rem; font-weight:800; color:var(--primary-color);
                            letter-spacing:0.8px; text-transform:uppercase; margin-bottom:12px;">
                    <i class="fa fa-fire me-1"></i>Trending
                </div>
                <?php foreach ($trending as $t): ?>
                <div style="margin-bottom:10px;">
                    <button class="trending-tag" style="font-size:0.78rem;"
                            onclick="cercaTag('<?= htmlspecialchars($t['NomeTag']) ?>')">
                        #<?= htmlspecialchars($t['NomeTag']) ?>
                    </button>
                    <span style="font-size:0.72rem; color:rgb(83,100,113); margin-left:6px;">
                        <?= $t['totale'] ?> post
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div style="padding: 16px; width:100%;">
            <div style="font-size:0.78rem; font-weight:800; color:var(--primary-color);
                        letter-spacing:0.8px; text-transform:uppercase; margin-bottom:12px;">
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

    <script src="config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const inputRicerca = document.getElementById('input-ricerca');
    const clearBtn     = document.getElementById('clear-btn');
    const formRicerca  = document.getElementById('form-ricerca');

    function aggiornaClear() {
        clearBtn.style.display = inputRicerca.value.trim() !== '' ? 'block' : 'none';
    }
    aggiornaClear();
    inputRicerca.addEventListener('input', aggiornaClear);

    function clearRicerca() {
        inputRicerca.value = '';
        aggiornaClare();
        location.href = 'ricerca.php';
    }

    function setFiltro(f) {
        document.getElementById('filtro-hidden').value = f;
        formRicerca.submit();
    }

    function cercaTag(tag) {
        location.href = 'ricerca.php?tag=' + encodeURIComponent(tag) + '&f=tag';
    }

    inputRicerca.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            formRicerca.submit();
        }
    });

    function aggiornaClare() { aggiornaClear(); }
    function aggiornaClear() {
        clearBtn.style.display = inputRicerca.value.trim() !== '' ? 'block' : 'none';
    }
    </script>

</body>
</html>
