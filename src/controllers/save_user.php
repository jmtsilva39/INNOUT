<?php
// Inicia a sessão
session_start();
// Requer autenticação válida, redirecionando para a página de login se necessário
requireValidSession(true);

// Inicializa uma variável para armazenar exceções
$exception = null;
// Inicializa um array para armazenar os dados do usuário
$userData = [];

// Verifica se não há dados de formulário enviados e se há uma chave 'update' definida na query string
if (count($_POST) === 0 && isset($_GET['update'])) {
    // Obtém os dados do usuário a ser atualizado do banco de dados
    $user = User::getOne(['id' => $_GET['update']]);
    // Atribui os valores do usuário obtidos aos dados do usuário
    $userData = $user->getValues();
    // Define a senha como null para evitar que seja exibida no formulário
    $userData['password'] = null;
} elseif (count($_POST) > 0) { // Se houver dados de formulário enviados
    try {
        // Cria um novo objeto User com os dados do formulário
        $dbUser = new User($_POST);
        // Se o usuário já tiver um ID, significa que está sendo atualizado
        if ($dbUser->id) {
            // Atualiza o usuário no banco de dados
            $dbUser->update();
            // Adiciona uma mensagem de sucesso
            addSuccessMsg('Usuário alterado com sucesso!');
            // Redireciona para a página de usuários após a atualização
            header('Location: users.php');
            // Finaliza o script
            exit();
        } else { // Se o usuário não tiver um ID, significa que é um novo usuário sendo cadastrado
            // Insere o novo usuário no banco de dados
            $dbUser->insert();
            // Adiciona uma mensagem de sucesso
            addSuccessMsg('Usuário cadastrado com sucesso!');
        }
        // Limpa os dados do formulário para evitar que sejam reenviados
        $_POST = [];
    } catch (Exception $e) { // Captura qualquer exceção ocorrida durante o processamento
        // Armazena a exceção para exibição posterior
        $exception = $e;
    } finally { // Independente de exceções, atualiza os dados do usuário com os dados do formulário
        $userData = $_POST;
    }
}

// Carrega o template de visualização 'save_user', passando os dados do usuário e qualquer exceção ocorrida
loadTemplateView('save_user', $userData + ['exception' => $exception]);
