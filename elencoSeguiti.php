<?php
    session_start();
    include "Connessione.php";

    // Controllo accesso
    if(!isset($_SESSION['accesso']) || $_SESSION['accesso'] != 1){
        header("location: Index.php");
        exit();
    }

    // Recupero l'utente di cui mostrare i seguiti
    $userPagina = isset($_GET['user']) ? $_GET['user'] : '';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utenti Seguiti - Asteria</title>
    <link rel="stylesheet" href="StileFollower.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <div class="header-follower">
            <button class="btn-indietro" onclick="location.href='Profilo.php?user=<?=$userPagina?>'">
                <i class="fa-solid fa-chevron-left"></i> Indietro
            </button>
            
            <h1>Utenti Seguiti</h1>

            <div class="theme-switch-wrapper">
                <button id="theme-toggle" title="Cambia Tema"></button>
            </div>
        </div>

        <div class="lista-container">
            <?php
            try {
                $connessione = new PDO("mysql:host=$host;dbname=$db", $user, $password);
                $connessione->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // LOGICA CAMBIATA:
                // Selezioniamo il 'Seguito' (la persona seguita) filtrando per 'Seguente' (l'utente attuale)
                $sql = "SELECT f.Seguito, u.Foto 
                        FROM follow f 
                        JOIN utenti u ON f.Seguito = u.NomeUtente 
                        WHERE f.Seguente = ?";
                
                $preparata = $connessione->prepare($sql);
                $preparata->execute([$userPagina]);
                
                if($preparata->rowCount() > 0) {
                    $ris = $preparata->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($ris as $riga) {
                        $fotoSeguito = !empty($riga['Foto']) ? $riga['Foto'] : 'Utente.png';
                        ?>
                        <div class="follower-card">
                            <div class="user-info">
                                <div class="avatar-container">
                                    <img src="<?php echo "UploadProfili/".$fotoSeguito; ?>" alt="Profilo" class="follower-img">
                                </div>
                                <span class="username">@<?php echo htmlspecialchars($riga['Seguito']); ?></span>
                            </div>
                            <button class="btn-profilo" onclick="location.href='Profilo.php?user=<?php echo urlencode($riga['Seguito']); ?>'">
                                Vedi Profilo
                            </button>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='empty-msg'>Questo utente non segue ancora nessuno.</p>";
                }
                $connessione = null;
            } catch(PDOException $e){
                echo "<p class='error'>Errore nel caricamento: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('theme-toggle');
        const body = document.body;

        const applicaTema = (tema) => {
            body.setAttribute('data-theme', tema);
            toggleBtn.innerHTML = tema === 'dark' ? '🌙' : '☀️';
            localStorage.setItem('tema', tema);
        };

        const temaIniziale = localStorage.getItem('tema') || 'dark';
        applicaTema(temaIniziale);

        toggleBtn.addEventListener('click', () => {
            const nuovoTema = body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            applicaTema(nuovoTema);
        });
    </script>
</body>
</html>