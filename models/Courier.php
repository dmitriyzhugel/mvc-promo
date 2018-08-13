<?php

/***
 * Модель курьера
 * @author Zhugel Dmitriy
 */
class Courier {

    /**
     * Вывод всех курьеров
     */
     public static function listAll(){
         $sql = "SELECT * FROM `courier`";
         $rows = MYDB::getAll($sql);
         $couriers = [];
         foreach($rows as $row){
             $couriers[] = [
                 'id'=>$row['id'],
                 'surname'=>$row['surname'],
                 'firstname'=>$row['firstname'],
                 'lastname'=>$row['lastname']
             ];
         }
         return $couriers;
     }

     /**
      * Проверка на занятость курьера
      * @param $courier_id - идентификатор курьера
      * @param $date_from - дата старта маршрута
      * @param $date_to - дата конца маршрута
      */
     public static function check($courier_id,$date_from,$date_to){
         $sql = "SELECT * FROM `route` WHERE
         (`start_date` < :date_to AND `back_date` > :date_from)
         AND courier_id = :courier_id";
         $rows = MYDB::getByParams($sql,[
             ':date_from'=>$date_from,
             ':date_to'=>$date_to,
             ':courier_id'=>$courier_id
         ]);
         return !sizeof($rows);
     }

}
