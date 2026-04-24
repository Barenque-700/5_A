<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asteria - Social Astronomy</title>
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
                    <button class="btn btn-custom rounded-pill fw-bold" onclick="location.href='Profilo.php'">Profilo Utente</button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!--<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center mb-5">
            <h1 class="display-4 fw-bold" style="color: var(--primary-color);">Social Network astronomico</h1>
            <p class="lead">TestTest</p>
        </div>
    </div>
</main>-->
     <div class="contenitore">
        <div class="sezione sinistra">Test Sinistra</div>
        <div class="sezione centro">Test centro
            <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
            <div class="container bootstrap snippets bootdey">
                <di class="col-md-8">
                    <div class="col-sm-12">
                        <div class="panel panel-white post panel-shadow">
                            <div class="post-heading">
                                <div class="pull-left image">
                                    <img src="https://bootdey.com/img/Content/user_1.jpg" class="img-circle avatar" alt="user profile image">
                                </div>
                                <div class="pull-left meta">
                                    <div class="title h5">
                                        <a href="#"><b>Ciccio Brutto</b></a>
                                    </div>
                                    <h6 class="text-muted time">5 seconds ago</h6>
                                </div>
                            </div>
                            <div class="post-image">
                                <img src="https://www.bootdey.com/image/400x200/FFB6C1/000000" class="image" alt="image post">
                            </div>
                            <div class="post-description">
                                <h4>Foto title</h4>
                                <p>Put here your foto description</p>
                                <div class="stats">
                                    <a href="#" class="btn btn-default stat-item">
                                        <i class="fa fa-thumbs-up icon"></i>228
                                    </a>
                                    <a href="#" class="btn btn-default stat-item">
                                        <i class="fa fa-share icon"></i>128
                                    </a>
                                </div>
                            </div>
                            <div class="post-footer">
                                <div class="input-group"> 
                                    <input class="form-control" placeholder="Add a comment" type="text">
                                    <span class="input-group-addon">
                                        <a href="#"><i class="fa fa-edit"></i></a>  
                                    </span>
                                </div>
                                <ul class="comments-list">
                                </ul>
                            </div>
                        </div>
                    </div>
                </di>
            </div>
        </div>
        <div class="sezione destra">Test destra</div>
    </div>


<script src="config.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>