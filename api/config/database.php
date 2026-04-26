<?php

class Database {
    public function connect() {
        $host = "localhost";
        $db = "pampamind";
        $user = "root";
        $pass = "";

        try {
            $pdo = new PDO(
                "mysql:host=$host;dbname=$db;charset=utf8",
                $user,
                $pass
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
            exit();
        }
    }
}