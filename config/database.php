<?php 
    // BANCO DE DADOS LOCAL:
        $host = 'localhost';
        $port = 3306;
        $dbname = 'tourconnect';
        $user = 'root';
        $password = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Conexão bem-sucedida!";
        $pdo->exec("SET time_zone = '-04:00'");
    } catch (PDOException $e) {
        echo "Erro na conexão: " . $e->getMessage();
    }
?>
