<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/comum.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/icofont.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <title>Ponto G</title>
</head>

<body>

    <form class="form-login" action="#" method="post">
        <div class="login-card card">
            <div class="card-header">
                <i class="icofont-travelling mr-2"></i>
                <span class="font-weight-light">Ponto</span>
                <span class="font-weight-bold mr-2">G</span>
                <i class="icofont-runner-alt-1"></i>
            </div>
            <div class="card-body">
                <?php include(TEMPLATE_PATH . '/messages.php') ?>
                <div class="form-group">
                    <label for="email">Usuário</label>
                    <input type="text" id="email" name="email" class="form-control <?= (isset($errors['email']) ? $errors['email'] : '') ? 'is-invalid' : '' ?>" value="<?= (isset($email)) ? $email : '' ?>" placeholder="Informe o e-mail" autofocus>
                    <div class="invalid-feedback">
                        <?= $errors['email'] ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" class="form-control <?= (isset($errors['password']) ? $errors['password'] : '') ? 'is-invalid' : '' ?>" placeholder="Informe a senha">
                    <div class="invalid-feedback">
                        <?= $errors['password'] ?>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-lg btn-primary">Entrar</button>
            </div>

        </div>
    </form>
</body>

</html>