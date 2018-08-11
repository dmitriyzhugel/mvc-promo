<?php

/**
* Singltone для работы с базой данных
*/

class MYDB {

    private static $objInstance;

    private function __construct() {}
    private function __clone() {}

    /*
     * Returns DB instance or create initial connection
     * @param
     * @return $objInstance;
     */
    public static function getInstance() {

        if(!self::$objInstance){
            self::$objInstance = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            self::$objInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$objInstance;

    } # end method

    public static function getRow(){

    }

    public static function getAll($sql){
        if(!self::$objInstance) return false;
        return self::$objInstance->query($sql);
    }

    /**
     * Получение значения первого столбца первой строки
     */
    public static function getOne($sql,$params){
        if(!self::$objInstance) return false;
        $sth = self::$objInstance->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute($params);
        $result = $sth->fetchColumn(0);
        return $result;
    }

    /**
     * Выполнение запросов не возвращающих данные
     */
    public static function query($sql,$params = []){
        if(!self::$objInstance) return false;
        //$count = self::$objInstance->exec($sql);
        $sth = self::$objInstance->prepare($sql);
        foreach($params as $key=>$value){
            $sth->bindValue($key, $value);
        }
        return $sth->execute();
    }

    public static function getByParams($sql,$params){
        $sth = self::$objInstance->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute($params);
        $result = $sth->fetchAll();
        return $result;
    }

}
