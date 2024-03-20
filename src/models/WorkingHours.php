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

    // Método estático para carregar o registro das horas trabalhadas de um usuário em uma determinada data
    public static function loadFromUserAndDate($userId, $workDate)
    {
        // Obtém o registro das horas trabalhadas do usuário na data especificada
        $registry = self::getOne(['user_id' => $userId, 'work_date' => $workDate]);

        // Se não houver registro, cria um novo registro com os valores padrão
        if (!$registry) {
            $registry = new WorkingHours([
                'user_id' => $userId,
                'work_date' => $workDate,
                'worked_time' => 0
            ]);
        }

        // Retorna o registro das horas trabalhadas
        return $registry;
    }
}
