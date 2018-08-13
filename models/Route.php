<?php

/**
 * Модель для работы с маршрутами
 * @author Zhugel Dmitriy
 */
class Route {

    /**
     * Вывод всех маршрутов
     */
    public static function list(){
        $sql = "SELECT r.id,
                       c.firstname,
                       c.surname,
                       c.lastname,
                       r.start_date,
                       r.arrival_date,
                       r.back_date,
                       region.region_name FROM `route` r
                LEFT JOIN `courier` c ON r.courier_id = c.id
                LEFT JOIN `region` ON `region`.id = r.region_id
                ORDER BY r.id";
        $rows = MYDB::getAll($sql);
        $routes = [];
        foreach($rows as $row){
            $routes[] = [
                'id'=>$row['id'],
                'region_id'=>$row['region_id'],
                'firstname'=>$row['firstname'],
                'surname'=>$row['surname'],
                'lastname'=>$row['lastname'],
                'start_date'=>$row['start_date'],
                'arrival_date'=>$row['arrival_date'],
                'back_date'=>$row['back_date'],
                'region'=>$row['region_name']
            ];
        }
        return $routes;
    }

    /**
     * Вывод маршрутов на текущую дату
     */
    public static function listByDate($date){
        $sql = "SELECT r.id
                ,c.firstname
                ,c.surname
                ,c.lastname
                ,r.start_date
                ,r.arrival_date
                ,r.back_date
                ,region.region_name FROM `route` r
                LEFT JOIN `courier` c ON r.courier_id = c.id
                LEFT JOIN `region` ON `region`.id = r.region_id
                WHERE :cur_date BETWEEN r.start_date AND r.back_date
                ORDER BY r.id";
        $rows = MYDB::getByParams($sql,[':cur_date'=>$date]);
        $routes = [];
        foreach($rows as $row){
            $routes[] = [
                'id'=>$row['id'],
                'region_id'=>$row['region_id'],
                'firstname'=>$row['firstname'],
                'surname'=>$row['surname'],
                'lastname'=>$row['lastname'],
                'start_date'=>$row['start_date'],
                'arrival_date'=>$row['arrival_date'],
                'back_date'=>$row['back_date'],
                'region'=>$row['region_name']
            ];
        }
        return $routes;
    }

    /**
     * Вывод маршрутов за период
     */
    public static function listByPeriod($date_from,$date_to){
        $sql = "SELECT r.id
                ,c.firstname
                ,c.surname
                ,c.lastname
                ,r.start_date
                ,r.arrival_date
                ,r.back_date
                ,region.region_name FROM `route` r
                LEFT JOIN `courier` c ON r.courier_id = c.id
                LEFT JOIN `region` ON `region`.id = r.region_id
                WHERE r.start_date<=:date_to AND r.back_date>=:date_from
                ORDER BY r.id";
        $rows = MYDB::getByParams($sql,[':date_from'=>$date_from,':date_to'=>$date_to]);
        $routes = [];
        foreach($rows as $row){
            $routes[] = [
                'id'=>$row['id'],
                'region_id'=>$row['region_id'],
                'firstname'=>$row['firstname'],
                'surname'=>$row['surname'],
                'lastname'=>$row['lastname'],
                'start_date'=>$row['start_date'],
                'arrival_date'=>$row['arrival_date'],
                'back_date'=>$row['back_date'],
                'region'=>$row['region_name']
            ];
        }
        return $routes;
    }

    /**
     * Вычисление даты прибытия
     */
    public static function calcArrivalDate($region_id,$start_date){
        $sql = "SELECT DATE_ADD(:start_date,INTERVAL durability DAY) as arrival_date FROM `region` WHERE id = :region_id";
        $result = MYDB::getOne($sql,[
            ':start_date'=>$start_date,
            ':region_id'=>$region_id
        ]);
        return $result;
    }

    /**
     * Вычисление даты возвращения
     */
    public static function calcBackDate($region_id,$start_date){
        $sql = "SELECT DATE_ADD(:start_date,INTERVAL durability+durability_back DAY) as back_date FROM `region` WHERE id = :region_id";
        $result = MYDB::getOne($sql,[
            ':start_date'=>$start_date,
            ':region_id'=>$region_id
        ]);
        return $result;
    }

    /**
     * Добавление нового маршрута
     */
    public static function add($region_id,$start_date,$courier_id){
        $arrival_date = self::calcArrivalDate($region_id,$start_date);
        $back_date = self::calcBackDate($region_id,$start_date);
        $result = new stdClass;
        if(!Courier::check($courier_id,$start_date,$back_date)){
            $result->status = 'ERROR';
            $result->message = 'Курьер будет занят!';
            return $result;
        }
        $sql = "INSERT INTO `route` (`region_id`,`courier_id`,`start_date`,`arrival_date`,`back_date`) VALUES(:region_id,:courier_id,:start_date,:arrival_date,:back_date)";
        if(MYDB::query($sql,[
                ':region_id'=>$region_id,
                ':courier_id'=>$courier_id,
                ':start_date'=>$start_date,
                ':arrival_date'=>$arrival_date,
                ':back_date'=>$back_date
            ])){
            $result->status = 'OK';
            $result->message = 'Маршрут успешно создан';
        }
        return $result;
    }

}
