<?php

class Db {
    private static PDO $PDOInstance;

    private PDO $pdo;

    public function __construct(){
        $this->pdo = self::getPDOInstance();
    }

    public static function getPDOInstance(): PDO {
        if (!isset(self::$PDOInstance)){
            try {
                self::$PDOInstance = new PDO("mysql:host=".DB_HOSTNAME.";dbname=".DB_NAME.";charset=".DB_CHARSET, DB_USERNAME, DB_PASSWORD,
                [
                    PDO::ATTR_EMULATE_PREPARES  => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false
                ]);
            }catch (PDOException $e){
                echo $e->getMessage();
                echo '500';
            }
        }
        return self::$PDOInstance;
    }

    public function getById($table, $id, $mode = PDO::FETCH_ASSOC){
        $db = $this->pdo->prepare("SELECT * FROM $table WHERE id = ?");
        $db->bindParam(1, $id);
        $db->execute();
        $db->setFetchMode($mode);
        return $db->fetch();
    }

    public function getAll($table){
        $db = $this->pdo->prepare("SELECT * FROM $table");
        $db->execute();
        $db->setFetchMode(PDO::FETCH_OBJ);
        return $db->fetchAll();
    }

    public function insert($table, $data = []) {
        if (count($data) == 0)
            throw new Exception("Invalid insert data.");

        $strData = "";
        $strValue = "";

        foreach ($data as $column => $value) {
            $strData .= $column . ",";
            $strValue .= "?,";
        }

        $i = 1;

        $strData = substr($strData, 0, strlen($strData) - 1);
        $strValue = substr($strValue, 0, strlen($strValue) - 1);

        $db = $this->pdo->prepare("INSERT INTO $table ($strData) VALUES ($strValue)");
        foreach($data as $value)
            $db->bindValue($i++, $value);

        return $db->execute();
    }

    public function update($table, $data = []) {
        if (count($data) == 0)
            throw new Exception("Invalid update data.");

    }
}