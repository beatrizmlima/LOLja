<?php

    define('DB_HOST', 'localhost');
    define('DB_NAME', '');
    define('DB_USER', 'root');
    define('DB_PASS', '');

    function dbConnect(){
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if(!$conn){
            die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
        }
        return $conn;
    }

    dbConnect();

?>