<?php
// Define o fuso horário padrão para o horário de São Paulo
date_default_timezone_set('America/Sao_Paulo');

// Define a localidade para o idioma português do Brasil
setlocale(LC_TIME, 'pt_BR', 'pt-BR.utf-8', 'portuguese');

// Constantes gerais
define('DAILY_TIME', 60 * 60 * 8); // Define a quantidade de segundos em um dia de trabalho padrão (8 horas)

// Definição dos caminhos para as pastas do projeto
define('MODEL_PATH', realpath(dirname(__FILE__) . '/../models')); // Caminho para a pasta dos modelos (classes PHP)
define('VIEW_PATH', realpath(dirname(__FILE__) . '/../views')); // Caminho para a pasta das visualizações (arquivos de interface do usuário)
define('TEMPLATE_PATH', realpath(dirname(__FILE__) . '/../views/template')); // Caminho para a pasta dos templates (layouts)
define('CONTROLLER_PATH', realpath(dirname(__FILE__) . '/../controllers')); // Caminho para a pasta dos controladores (arquivos PHP que manipulam a lógica de negócio)
define('EXCEPTION_PATH', realpath(dirname(__FILE__) . '/../exceptions')); // Caminho para a pasta das exceções (classes de exceção)

// Arquivos a serem importados (requeridos) para o funcionamento do projeto
require_once(realpath(dirname(__FILE__) . '/database.php')); // Arquivo responsável pela configuração e conexão com o banco de dados
require_once(realpath(dirname(__FILE__) . '/loader.php')); // Arquivo que contém funções para carregar modelos, visualizações e templates
require_once(realpath(dirname(__FILE__) . '/session.php')); // Arquivo responsável pela manipulação da sessão do usuário
require_once(realpath(dirname(__FILE__) . '/date_utils.php')); // Arquivo com utilitários para manipulação de datas
require_once(realpath(dirname(__FILE__) . '/utils.php'));
require_once(realpath(MODEL_PATH . '/Model.php')); // Arquivo base de modelo (classe Model)
require_once(realpath(MODEL_PATH . '/User.php')); // Arquivo que contém a definição da classe User
require_once(realpath(MODEL_PATH . '/WorkingHours.php')); // Arquivo que contém a definição da classe WorkingHours
require_once(realpath(EXCEPTION_PATH . '/AppException.php')); // Arquivo que contém a definição da classe de exceção genérica AppException
require_once(realpath(EXCEPTION_PATH . '/ValidationException.php')); // Arquivo que contém a definição da classe de exceção de validação ValidationException
