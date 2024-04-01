<?php

class WorkingHours extends Model
{
    // Nome da tabela no banco de dados
    protected static $tableName = 'working_hours';
    
    // Colunas da tabela no banco de dados
    protected static $columns = [
        'id',
        'user_id',
        'work_date',
        'time1',
        'time2',
        'time3',
        'time4',
        'worked_time'
    ];

    // Carrega registro do usuário e data especificados
    public static function loadFromUserAndDate($userId, $workDate)
    {
        $registry = self::getOne(['user_id' => $userId, 'work_date' => $workDate]);

        if (!$registry) {
            $registry = new WorkingHours([
                'user_id' => $userId,
                'work_date' => $workDate,
                'worked_time' => 0
            ]);
        }

        return $registry;
    }

    // Obtém o próximo horário vazio
    public function getNextTime()
    {
        if (!$this->time1) return 'time1';
        if (!$this->time2) return 'time2';
        if (!$this->time3) return 'time3';
        if (!$this->time4) return 'time4';
        return null;
    }

    // Obtém o tipo de batida de ponto ativo (hora de saída ou intervalo trabalhado)
    public function getActiveClock()
    {
        $nextTime = $this->getNextTime();
        if ($nextTime === 'time1' || $nextTime === 'time3') {
            return 'exitTime';
        } elseif ($nextTime === 'time2' || $nextTime === 'time4') {
            return 'workedInterval';
        } else {
            return null;
        }
    }

    
    // Registra a entrada ou saída do usuário
    public function innout($time)
    {
        $timeColumn = $this->getNextTime();
        if (!$timeColumn) {
            throw new AppException("Você já fez os 4 batimentos do dia!");
        }
        $this->$timeColumn = $time;
        $this->worked_time = getSecondsFromDateInterval($this->getWorkedInterval());
        if ($this->id) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    // Calcula o intervalo trabalhado no dia
    function getWorkedInterval()
    {
        [$t1, $t2, $t3, $t4] = $this->getTimes();

        $part1 = new DateInterval('PT0S');
        $part2 = new DateInterval('PT0S');

        if ($t1) $part1 = $t1->diff(new DateTime());
        if ($t2) $part1 = $t1->diff($t2);
        if ($t3) $part2 = $t3->diff(new DateTime());
        if ($t4) $part2 = $t3->diff($t4);

        return sumIntervals($part1, $part2);
    }

    // Obtém o intervalo de almoço
    function getLunchInterval()
    {
        [, $t2, $t3,] = $this->getTimes();
        $lunchInterval = new DateInterval('PT0S');

        if ($t2) $lunchInterval = $t2->diff(new DateTime());
        if ($t3) $lunchInterval = $t2->diff($t3);

        return $lunchInterval;
    }

    // Calcula o horário de saída
    function getExitTime()
    {
        [$t1,,, $t4] = $this->getTimes();
        $workday = DateInterval::createFromDateString('8 hours');

        if (!$t1) {
            return (new DateTimeImmutable())->add($workday);
        } elseif ($t4) {
            return $t4;
        } else {
            $total = sumIntervals($workday, $this->getLunchInterval());
            return $t1->add($total);
        }
    }

    // Calcula o saldo de horas trabalhadas
    function getBalance()
    {
        if (!$this->time1 && !isPastWorkday($this->work_date)) return '';
        if ($this->worked_time == DAILY_TIME) return '-';

        $balance = $this->worked_time - DAILY_TIME;
        $balanceString = getTimeStringFromSeconds(abs($balance));
        $sign = $this->worked_time >= DAILY_TIME ? '+' : '-';
        return "{$sign}{$balanceString}";
    }

    // Obtém os usuários ausentes no dia
    public static function getAbsentUsers()
    {
        $today = new DateTime();
        $result = Database::getResultFromQuery("
            SELECT name FROM users
            WHERE end_date is NULL
            AND id NOT IN (
                SELECT user_id FROM working_hours
                WHERE work_date = '{$today->format('Y-m-d')}'
                AND time1 IS NOT NULL
            )
        ");

        $absentUsers = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($absentUsers, $row['name']);
            }
        }

        return $absentUsers;
    }

    // Obtém o tempo trabalhado no mês especificado
    public static function getWorkedTimeInMonth($yearAndMonth)
    {
        $startDate = (new DateTime("{$yearAndMonth}-1"))->format('Y-m-d');
        $endDate = getLastDayOfMonth($yearAndMonth)->format('Y-m-d');
        $result = static::getResultSetFromSelect([
            'raw' => "work_date BETWEEN '{$startDate}' AND '{$endDate}'"
        ], "sum(worked_time) as sum");
        return $result->fetch_assoc()['sum'];
    }

    // Obtém o relatório mensal do usuário para o mês especificado
    public static function getMonthlyReport($userId, $date)
    {
        $registries = [];
        $startDate = getFirstDayOfMonth($date)->format('Y-m-d');
        $endDate = getLastDayOfMonth($date)->format('Y-m-d');

        // Consulta para obter o relatório mensal do usuário, incluindo o nome do usuário
        $sql = "SELECT wh.*, u.name as user_name FROM working_hours wh
            LEFT JOIN users u ON wh.user_id = u.id
            WHERE wh.user_id = '{$userId}'
            AND wh.work_date BETWEEN '{$startDate}' AND '{$endDate}'";
        $result = Database::getResultFromQuery($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $registries[$row['work_date']] = new WorkingHours($row);
            }
        }

        return $registries;
    }


    // Obtém os horários registrados
    private function getTimes()
    {
        $times = [];

        $this->time1 ? array_push($times, getDateFromString($this->time1)) : array_push($times, null);
        $this->time2 ? array_push($times, getDateFromString($this->time2)) : array_push($times, null);
        $this->time3 ? array_push($times, getDateFromString($this->time3)) : array_push($times, null);
        $this->time4 ? array_push($times, getDateFromString($this->time4)) : array_push($times, null);

        return $times;
    }
}
