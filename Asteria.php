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
    <link rel="icon" type="image/x-icon" href="LogoIcona.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="StileAsteria.css">
</head>
<body> <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <div class="d-flex flex-column">
                <img src="LogoTrasparente.png" alt="Asteria Logo" class="logo-img mb-1">
                <small id="current-date" class="text-secondary ms-1" style="font-size: 0.7rem; letter-spacing: 1px;"></small>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item theme-switch-wrapper">
                    <button id="theme-toggle"> 🌙 </button>
                </li>
                <li class="nav-item"><a class="nav-link px-3" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#">Eventi</a></li>
                <li class="nav-item ms-lg-2">
                    <button class="btn btn-custom rounded-pill fw-bold" onclick="location.href='Profilo.php?user=<?=$NomeUtente?>'">Profilo Utente</button>
                </li>
            </ul>
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
    <!-- Altri contenuti della barra sinistra -->
    <div class="mt-3 ps-3">
        <p class="text-secondary small">Menu navigazione</p>
        <!-- Qui potrai aggiungere altri link in futuro -->
    </div>

        </div>
        <div class="sezione centro">
            <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
            <div class="container bootstrap snippets bootdey">
                    <div class="col-sm-12">
                        <div class="panel panel-white post panel-shadow">
                            <div class="post-content-wrapper"> <div class="post-left-column">
                                    <img src="https://bootdey.com/img/Content/user_1.jpg" class="img-circle avatar" alt="user profile image">
                                </div>

                                <div class="post-right-column">
                                    <div class="post-heading">
                                        <a href="#"><b>Ciccio Brutto</b></a>
                                        <span class="text-muted time">@ciccio_brutto · 5s</span>
                                    </div>

                                    <div class="post-description">
                                        <p>Put here your foto description</p>
                                    </div>

                                    <div class="post-image">
                                        <img src="https://www.bootdey.com/image/400x200/FFB6C1/000000" class="image" alt="image post">
                                    </div>

                                    <div class="stats">
                                        <a href="#" class="stat-item"><i class="fa fa-comment-o"></i> 165</a>
                                        <a href="#" class="stat-item"><i class="fa fa-heart-o"></i> 5.5K</a>
                                        <a href="#" class="stat-item"><i class="fa fa-share"></i></a>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="sezione destra"></div>
    </div>


<script src="config.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php
    }
?>