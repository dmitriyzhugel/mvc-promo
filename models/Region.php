<?php

/***
 * Модель региона
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
            $regions[] = [
                'id'=>$row['id'],
                'name'=>$row['region_name']
            ];
        }
        return $regions;
    }

}
