<?php

/***
 * Модель региона
 * @author Zhugel Dmitriy
 */
class Region {

    /**
     * Вывод всех регионов
     */
    public static function listAll(){
        $sql = "SELECT * FROM `region`";
        $rows = MYDB::getAll($sql);
        $regions = [];
        foreach($rows as $row){
            $regions[ $row['id'] ] = [
                'id'=>$row['id'],
                'name'=>$row['region_name'],
                'durability'=>$row['durability'],
                'durability_back'=>$row['durability_back']
            ];
        }
        return $regions;
    }

}
