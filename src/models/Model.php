<?php

class Model
{
    // Nome da tabela no banco de dados
    protected static $tableName = '';

    // Array contendo os nomes das colunas da tabela
    protected static $columns = [];

    // Array contendo os valores dos campos do registro atual
    protected $values = [];

    // Construtor da classe Model
    function __construct($arr, $sanitize = true)
    {
        $this->loadFromArray($arr, $sanitize);
        // Define o valor da chave primária como null se não estiver presente no array
        if (!array_key_exists('id', $arr)) {
            $this->id = null;
        }
    }

    // Carrega os valores do registro a partir de um array associativo
    public function loadFromArray($arr, $sanitize = true)
    {
        // Verifica se o array está vazio
        if (empty($arr)) {
            // Define valores padrão para os campos
            $this->values['time1'] = '---';
            $this->values['time2'] = '---';
            $this->values['time3'] = '---';
            $this->values['time4'] = '---';
            // Outras Propriedades
        } else {
            // Percorre o array e sanitiza os valores se necessário
            foreach ($arr as $key => $value) {
                $cleanValue = $value;
                if ($sanitize && isset($cleanValue)) {
                    $cleanValue = strip_tags(trim($cleanValue));
                    $cleanValue = htmlentities($cleanValue, ENT_NOQUOTES);
                }
                $this->values[$key] = $cleanValue;
            }
        }
    }

    // Método mágico para obter valores de propriedades protegidas
    public function __get($key)
    {
        return $this->values[$key] ?? null;
    }

    // Método mágico para definir valores de propriedades protegidas
    public function __set($key, $value)
    {
        $this->values[$key] = $value;
    }

    // Retorna um array com os valores do registro atual
    public function getValues()
    {
        return $this->values;
    }

    // Obtém um único registro com base nos filtros fornecidos
    public static function getOne($filters = [], $columns = '*')
    {
        // Obtém o nome da classe atual
        $class = get_called_class();

        // Obtém o resultado da consulta SQL
        $result = static::getResultSetFromSelect($filters, $columns);

        // Retorna um objeto da classe atual com os valores do registro encontrado
        return $result ? new $class($result->fetch_assoc()) : null;
    }

    // Obtém vários registros com base nos filtros fornecidos
    public static function get($filters = [], $columns = '*')
    {
        $objects = [];
        $result = static::getResultSetFromSelect($filters, $columns);
        if ($result) {
            $class = get_called_class();
            while ($row = $result->fetch_assoc()) {
                array_push($objects, new $class($row));
            }
        }
        return $objects;
    }

    // Executa uma consulta SQL e retorna o resultado
    public static function getResultSetFromSelect($filters = [], $columns = '*')
    {
        $sql = "SELECT $columns FROM "
            . static::$tableName
            . static::getFilters($filters);
        $result = Database::getResultFromQuery($sql);
        if ($result->num_rows === 0) {
            return null;
        } else {
            return $result;
        }
    }

    // Insere um novo registro no banco de dados
    public function insert()
    {
        $sql = "INSERT INTO " . static::$tableName . " ("
            . implode(",", static::$columns) . ") VALUES (";
        foreach (static::$columns as $col) {
            $sql .= static::getFormatedValue($this->$col) . ",";
        }
        $sql[strlen($sql) - 1] = ')';
        $id = Database::executeSQL($sql);
        $this->id = $id;
    }

    // Atualiza o registro atual no banco de dados
    public function update()
    {
        $sql = "UPDATE " . static::$tableName . " SET ";
        foreach (static::$columns as $col) {
            $sql .= " ${col} = " . static::getFormatedValue($this->$col) . ",";
        }
        $sql[strlen($sql) - 1] = ' ';
        $sql .= "WHERE id = {$this->id}";
        Database::executeSQL($sql);
    }

    // Obtém o número de registros com base nos filtros fornecidos
    public static function getCount($filters = [])
    {
        $result = static::getResultSetFromSelect(
            $filters,
            'count(*) as count'
        );
        return $result->fetch_assoc()['count'];
    }

    // Exclui o registro atual do banco de dados
    public function delete()
    {
        static::deleteById($this->id);
    }

    // Exclui um registro do banco de dados com base no ID
    public static function deleteById($id)
    {
        $sql = "DELETE FROM " . static::$tableName . " WHERE id = {$id}";
        Database::executeSQL($sql);
    }

    // Retorna a cláusula WHERE com base nos filtros fornecidos
    protected static function getFilters($filters)
    {
        $sql = '';
        if (count($filters) > 0) {
            $sql .= " WHERE 1 = 1";
            foreach ($filters as $column => $value) {
                if ($column == 'raw') {
                    $sql .= " AND {$value}";
                } else {
                    $sql .= " AND ${column} = " . static::getFormatedValue($value);
                }
            }
        }
        return $sql;
    }

    // Formata o valor para inclusão na consulta SQL
    private static function getFormatedValue($value)
    {
        if (is_null($value)) {
            return "null";
        } elseif (gettype($value) === 'string') {
            return "'${value}'";
        } else {
            return $value;
        }
    }
}
