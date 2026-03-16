<?php

class Conexao
{
    public static function conectar()
    {
        $host = 'localhost';
        $dbname = 'db_projeto';
        $usuario = 'root';
        $senha = '';

        try
        {
            return new PDO("mysql:host=$host;dbname=$dbname", $usuario, $senha);
        }
        catch (PDOException $e)
        {
            die("Erro ao conectar: " . $e->getMessage());
        }
    }
}
?>