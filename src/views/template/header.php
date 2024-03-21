<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,charset=utf-8">
    <link rel="stylesheet" href="assets/css/comum.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/icofont.min.css">
    <link rel="stylesheet" href="assets/css/template.css">
    <title>Ponto G</title>
</head>
<!--A CLASSE HIDE-SIDEBAR OCULTA O MENU -->

<body class="">

    <header class="header">
        <!--lOGO -->
        <div class="logo">
            <i class="icofont-travelling mr-2"></i>
            <span class="font-weight-light">Ponto</span>
            <span class="font-weight-bold mr-2">G</span>
            <i class="icofont-runner-alt-1"></i>
        </div>

        <div class="menu-toggle mx-3">
            <i class="icofont-navigation-menu"></i>
        </div>
        <div class="spacer"></div>

        <!--MENU DROP DOWN -->
        <div class="dropdown">
            <div class="dropdown-button">
                <!--imagem aqui -->
                <span class="ml-3">
                    <?= $_SESSION['user']->name ?>
                </span>
                <i class="icofont-simple-down mx-2"></i>
            </div>
            <div class="dropdown-content">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="#">
                            <i class="icofont-ssl-security mr-2"></i>
                            Trocar Senha
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php">
                            <i class="icofont-logout mr-2"></i>
                            Sair
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>