<?php
// Desabilitar a exibição de erros
error_reporting(0);
// Função para renderizar os dados em formato CSV
function renderCSV($report, $sumOfWorkedTime, $balance, $userName)
{
    // Cabeçalhos do CSV
    $csv = "Dia,Entrada 1,Saída 1,Entrada 2,Saída 2,Saldo\n";

    // Loop através dos registros e adicionar ao CSV
    foreach ($report as $registry) {
        // Formatando a data para o formato desejado (por exemplo, DD/MM/YYYY)
        $formattedDate = formatDateWithLocale($registry->work_date, '%d/%m/%Y');

        // Linha do CSV para este registro
        $csv .= "{$formattedDate},{$registry->time1},{$registry->time2},{$registry->time3},{$registry->time4},{$registry->getBalance()}\n";
    }

    // Adicionar linhas finais para as informações de horas trabalhadas e saldo mensal
    $csv .= "Horas Trabalhadas,{$sumOfWorkedTime}\n";
    $csv .= "Saldo Mensal,{$balance}\n";

    return $csv;
}

// Supondo que $report, $sumOfWorkedTime, $balance e $userName estejam definidos em algum lugar

// Gerar os dados em formato CSV
$csvData = renderCSV($report, $sumOfWorkedTime, $balance, $user->name);

// Definir cabeçalhos para forçar o download do arquivo CSV
$filename = "Registro de pontos " . $userName . " período " . $selectedPeriod.".csv";
header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename=\"$filename\"");

// Escrever os dados CSV para a saída
echo $csvData;
// Desabilitar a exibição de erros
error_reporting(1);
