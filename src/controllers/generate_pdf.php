<?php
// Desabilitar a exibição de erros
error_reporting(0);
// Início da sessão
session_start();

// Verifica se a sessão é válida
requireValidSession();

require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Crie uma instância do Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true); // Habilitar o carregamento de imagens remotas
$dompdf = new Dompdf($options);
$userName = $registry->user_name;

// Função para renderizar a tabela
// Função para renderizar a tabela
// Função para renderizar a tabela

function renderTable($report, $sumOfWorkedTime, $balance, $userName)
{
    $period = $_POST['period'];
    $invertedPeriod = date('m-Y', strtotime($period));

    $table = '<div style="position: fixed; top: 0; left: 0; width: 100%; margin: 0; padding: 0px;">';
    $table .= '<img src="https://i.ibb.co/cbFL9JH/logo.png" alt="Logo" style="width: 200px; height: auto; float: left;">' . '<h3 style="color: blue; text-align: center; margin-top: 10px;">Superintendência de Água e Esgoto de Ourinhos</h3><hr>'; // Adicionando a imagem (altere o caminho conforme necessário)
    $table .= '</div>';

    $table .= '<main style="text-align: center; margin-top: 70px;">';
    $table .= '<h3 style="margin-bottom: 10px;">Relatório de Horas Trabalhadas no mês: ' . $invertedPeriod . '<br>' . 'Funcionário: '  . $userName . '</h3>';
    $table .= '<table style="width: auto; border-collapse: collapse; font-size: 14px; margin: 0 auto;">';
    $table .= '<thead>';
    $table .= '<tr style="background-color: #f0f0f0;">';
    $table .= '<th style="border: 1px solid #ddd; padding: 3px; min-width: 200px; text-align: center;">Dia</th>';
    $table .= '<th style="border: 1px solid #ddd; padding: 3px; min-width: 70px; text-align: center;">Entrada 1</th>';
    $table .= '<th style="border: 1px solid #ddd; padding: 3px; min-width: 70px; text-align: center;">Saída 1</th>';
    $table .= '<th style="border: 1px solid #ddd; padding: 3px; min-width: 70px; text-align: center;">Entrada 2</th>';
    $table .= '<th style="border: 1px solid #ddd; padding: 3px; min-width: 70px; text-align: center;">Saída 2</th>';
    $table .= '<th style="border: 1px solid #ddd; padding: 3px; min-width: 70px; text-align: center;">Saldo</th>';
    $table .= '</tr>';
    $table .= '</thead>';
    $table .= '<tbody>';
    foreach ($report as $registry) {
        $table .= '<tr>';
        $table .= '<td style="border: 1px solid #ddd; padding: 3px; min-width: 200px; text-align: left;">' . formatDateWithLocale($registry->work_date, ' %A, %d/%m/%Y') . '</td>';
        $table .= '<td style="border: 1px solid #ddd; padding: 3px; min-width: 70px; text-align: center;">' . $registry->time1 . '</td>';
        $table .= '<td style="border: 1px solid #ddd; padding: 3px; min-width: 70px; text-align: center;">' . $registry->time2 . '</td>';
        $table .= '<td style="border: 1px solid #ddd; padding: 3px; min-width: 70px; text-align: center;">' . $registry->time3 . '</td>';
        $table .= '<td style="border: 1px solid #ddd; padding: 3px; min-width: 70px; text-align: center;">' . $registry->time4 . '</td>';
        $table .= '<td style="border: 1px solid #ddd; padding: 3px; min-width: 70px; text-align: center;">' . $registry->getBalance() . '</td>';
        $table .= '</tr>';
    }
    $table .= '<tr style="background-color: #f0f0f0;">';
    $table .= '<td colspan="5" style="border: 1px solid #ddd; padding: 6px; min-width: 70px; text-align: right;">Horas Trabalhadas</td>';
    $table .= '<td style="border: 1px solid #ddd; padding: 6px; min-width: 70px; text-align: center;">' . $sumOfWorkedTime . '</td>';
    $table .= '</tr>';
    $table .= '<tr>';
    $table .= '<td colspan="5" style="border: 1px solid #ddd; padding: 6px; min-width: 70px; text-align: right;">Saldo Mensal</td>';
    $table .= '<td style="border: 1px solid #ddd; padding: 6px; min-width: 70px; text-align: center;">' . $balance . '</td>';
    $table .= '</tr>';
    $table .= '</tbody>';
    $table .= '</table>';
    $table .= '</main>';

    $table .= '<footer style="position: fixed; bottom: 0; width: 100%; border-top: 1px solid #dee2e6; text-align: left; padding: 0px; margin-top: 70px;color: blue; font-size: 12px;">SUPERINTENDÊNCIA DE ÁGUA E ESGOTO DE OURINHOS<br>Av. Altino Arantes, 369, Centro – Fone (14) 3302-1000 – CEP 19900-031 – Ourinhos – SP - CNPJ 49.131.287/0001-88</footer>';

    return $table;
}


// Renderize a view como HTML
ob_start();
$tableHtml = renderTable($report, $sumOfWorkedTime, $balance, $userName);
ob_end_clean();

// Carregue o HTML no Dompdf
$dompdf->loadHtml($tableHtml);

// Renderize o PDF
$dompdf->render();

// Saída do PDF para o navegador
$nomeDoArquivo = "Registro de pontos " . $userName . " período " . $selectedPeriod;
$dompdf->stream($nomeDoArquivo);

// Abilitar a exibição de erros
error_reporting(1);
