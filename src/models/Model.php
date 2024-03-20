<?php

class Model
{
    // Nome da tabela no banco de dados
    protected static $tableName = '';

    // Colunas da tabela no banco de dados
    protected static $columns = [];

    // Valores dos atributos do modelo
    protected $values = [];


    // Construtor da classe

    function __construct($arr, $sanitize = true)
    {
        // Carrega os valores do array associativo para os atributos do modelo
        $this->loadFromArray($arr, $sanitize);
        // Verifica se a chave 'id' existe no array
        if (!array_key_exists('id', $arr)) {
            // Define a propriedade 'id' como null se não estiver presente
            $this->id = null;
        }
    }


    // Carrega os valores do array associativo para os atributos do modelo
    public function loadFromArray($arr, $sanitize = true)
    {
        if ($arr) {
            foreach ($arr as $key => $value) {
                $cleanValue = $value;
                // Sanitiza os valores, se necessário
                if ($sanitize && isset($cleanValue)) {
                    $cleanValue = strip_tags(trim($cleanValue));
                    $cleanValue = htmlentities($cleanValue, ENT_NOQUOTES);
                }
                // Define o valor do atributo
                $this->$key = $cleanValue;
            }
        }
    }

    // Método mágico para obtenção de valores dos atributos
    public function __get($key)
    {
        return $this->values[$key];
    }

    // Método mágico para definição de valores dos atributos
    public function __set($key, $value)
    {
        $this->values[$key] = $value;
    }

    // Obtém os valores dos atributos
    public function getValues()
    {
        return $this->values;
    }

    // Obtém um único registro do banco de dados com base nos filtros fornecidos
    public static function getOne($filters = [], $columns = '*')
    {
        $class = get_called_class();
        $result = static::getResultSetFromSelect($filters, $columns);
        return $result ? new $class($result->fetch_assoc()) : null;
    }

    // Obtém vários registros do banco de dados com base nos filtros fornecidos
    public static function get($filters = [], $columns = '*')
    {
        $objects = [];
        $result = static::getResultSetFromSelect($filters, $columns);
        if ($result) {
            $class = get_called_class();
            // Instancia objetos do modelo para cada registro retornado
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
        // Executa a consulta SQL e retorna o resultado
        $result = Database::getResultFromQuery($sql);
        // Retorna null se nenhum registro for encontrado
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
        // Executa a consulta SQL
        $id = Database::executeSQL($sql);
        $this->id = $id;
    }

    // Atualiza um registro no banco de dados
    public function update()
    {
        $sql = "UPDATE " . static::$tableName . " SET ";
        foreach (static::$columns as $col) {
            $sql .= " ${col} = " . static::getFormatedValue($this->$col) . ",";
        }
        $sql[strlen($sql) - 1] = ' ';
        $sql .= "WHERE id = {$this->id}";
        // Executa a consulta SQL
        Database::executeSQL($sql);
    }

    // Obtém a contagem de registros do banco de dados com base nos filtros fornecidos
    public static function getCount($filters = [])
    {
        $result = static::getResultSetFromSelect(
            $filters,
            'count(*) as count'
        );
        return $result->fetch_assoc()['count'];
    }

    // Exclui um registro do banco de dados
    public function delete()
    {
        static::deleteById($this->id);
    }

    // Exclui um registro do banco de dados com base no ID fornecido
    public static function deleteById($id)
    {
        $sql = "DELETE FROM " . static::$tableName . " WHERE id = {$id}";
        // Executa a consulta SQL
        Database::executeSQL($sql);
    }

    // Obtém os filtros para serem usados em uma consulta SQL
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

    // Formata o valor para ser usado em uma consulta SQL
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
