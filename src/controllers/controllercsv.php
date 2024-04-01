<?php
// Início da sessão
session_start();

// Verifica se a sessão é válida
requireValidSession();

// Obtém a data atual
$currentDate = new DateTime();

// Obtém o usuário da sessão
$selectedUserId = $_POST['user'];
$users = null;
// Define o período selecionado (padrão: mês atual)
$selectedPeriod = $_POST['period'];

// Array associativo com os meses formatados
$periods = [];
$currentYear = date('Y'); // Ano atual

for ($month = 1; $month <= 12; $month++) {
    $date = new DateTime("{$currentYear}-{$month}-1");
    $periods[$date->format('Y-m')] = $date->format('m / Y');
}

// Ordena os meses em ordem decrescente (mais recente para o mais antigo)
ksort($periods);


// Obtém os registros mensais de horas trabalhadas
$registries = WorkingHours::getMonthlyReport($selectedUserId, $selectedPeriod);
// Inicializa o relatório mensal
$report = [];
$workDay = 0;
$sumOfWorkedTime = 0;
$lastDay = getLastDayOfMonth($selectedPeriod)->format('d');

// Itera pelos dias do mês
for ($day = 1; $day <= $lastDay; $day++) {
    $date = $selectedPeriod . '-' . sprintf('%02d', $day);

    // Verifica se há registro para o dia atual
    if (isset($registries[$date])) {
        $registry = $registries[$date];

        // Contabiliza os dias úteis
        if (isPastWorkday($date)) $workDay++;

        // Soma o tempo trabalhado
        $sumOfWorkedTime += $registry->worked_time;
        array_push($report, $registry);
    } else {
        // Se não houver registro, adiciona um registro com tempo zero
        array_push($report, new WorkingHours([
            'work_date' => $date,
            'worked_time' => 0
        ]));
    }
}
// Calcula o tempo esperado de trabalho e o saldo
$expectedTime = $workDay * DAILY_TIME;
$balance = getTimeStringFromSeconds(abs($sumOfWorkedTime - $expectedTime));
$sign = ($sumOfWorkedTime >= $expectedTime) ? '+' : '-';

require_once __DIR__ . '/../controllers/generate_csv.php';
require_once(realpath(MODEL_PATH . '/monthly_report.php'));

?>


