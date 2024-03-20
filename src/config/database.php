<?php

class Database
{
    // Método estático para obter uma conexão com o banco de dados
    public static function getConnection()
    {
        // Caminho do arquivo de configuração do ambiente (env.ini)
        $envPath = realpath(dirname(__FILE__) . '/../env.ini');
        // Parse do arquivo env.ini para obter as informações de conexão
        $env = parse_ini_file($envPath);
        // Estabelecimento da conexão com o banco de dados usando as informações do arquivo de configuração
        $conn = new mysqli($env['host'], $env['username'], $env['password'], $env['database']);

        // Verifica se ocorreu algum erro durante a conexão
        if ($conn->connect_error) {
            die("Erro: " . $conn->connection_error);
        }

        // Retorna a conexão estabelecida
        return $conn;
    }

    // Método estático para executar uma consulta SQL e retornar o resultado
    public static function getResultFromQuery($sql)
    {
        // Obtém uma conexão com o banco de dados
        $conn = self::getConnection();
        // Executa a consulta SQL
        $result = $conn->query($sql);
        // Fecha a conexão
        $conn->close();
        // Retorna o resultado da consulta
        return $result;
    }

    // Método estático para executar uma instrução SQL que não retorna um conjunto de resultados (INSERT, UPDATE, DELETE)
    public static function executeSQL($sql)
    {
        // Obtém uma conexão com o banco de dados
        $conn = self::getConnection();
        // Executa a instrução SQL
        if (!mysqli_query($conn, $sql)) {
            // Lança uma exceção se ocorrer um erro durante a execução da instrução SQL
            throw new Exception(mysqli_error($conn));
        }
        // Obtém o ID gerado pela última consulta INSERT
        $id = $conn->insert_id;
        // Fecha a conexão
        $conn->close();
        // Retorna o ID gerado
        return $id;
    }
}
