<?php

// Função para carregar um modelo (classe) específico
function loadModel($modelName)
{
    require_once(MODEL_PATH . "/{$modelName}.php");
}

// Função para carregar uma visualização (arquivo de visualização) com parâmetros opcionais
function loadView($viewName, $params = array())
{
    // Verifica se há parâmetros para passar para a visualização
    if (count($params) > 0) {
        foreach ($params as $key => $value) {
            // Define variáveis com os nomes e valores dos parâmetros para a visualização
            if (strlen($key) > 0) {
                ${$key} = $value;
            }
        }
    }

    // Inclui o arquivo de visualização especificado
    require_once(VIEW_PATH . "/{$viewName}.php");
}

// Função para carregar uma visualização de template, que inclui cabeçalho, barra lateral, visualização e rodapé
function loadTemplateView($viewName, $params = array())
{
    // Verifica se há parâmetros para passar para a visualização de template
    if (count($params) > 0) {
        foreach ($params as $key => $value) {
            // Define variáveis com os nomes e valores dos parâmetros para a visualização de template
            if (strlen($key) > 0) {
                ${$key} = $value;
            }
        }
    }

    // Get the logged-in user
    $user = $_SESSION['user'];

    // Load working hours records for the logged-in user and today's date
    $workingHours = WorkingHours::loadFromUserAndDate($user->id, date('Y-m-d'));
    $workedInterval = $workingHours->getWorkedInterval()->format('%H:%I:%S');
    $exitTime = $workingHours->getExitTime()->format('H:i:s');
    $activeClock = $workingHours->getActiveClock();


    // Inclui o cabeçalho do template
    require_once(TEMPLATE_PATH . "/header.php");
    // Inclui a barra lateral do template
    require_once(TEMPLATE_PATH . "/left.php");
    // Inclui o arquivo de visualização especificado
    require_once(VIEW_PATH . "/{$viewName}.php");
    // Inclui o rodapé do template
    require_once(TEMPLATE_PATH . "/footer.php");
}

// Função para renderizar um título na página, com opções para subtítulo e ícone
function renderTitle($title, $subtitle, $icon = null)
{
    // Inclui o arquivo de título do template
    require_once(TEMPLATE_PATH . "/title.php");
}
