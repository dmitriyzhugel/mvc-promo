<?php

/**
 * Команда для инициализации приложения
 * @author Dmitriy Zhugel
 */
class InitCommand {

    public function actionIndex(){
        echo '...run init command';
        $begin_date = new DateTime('2015-06-01');
        $end_date = new DateTime();
        $courier_dates = [];
        $regions = Region::listAll();
        // Заполним данные по каждому курьеру
        for($i_courier=1;$i_courier<=10;$i_courier++){
            $courier_dates[ $i_courier ] = new DateTime('2015-06-01');
            do {
                $region_id = rand(1,10);
                $start_date = $courier_dates[ $i_courier ]->format('Y-m-d');
                $arrival_date = Route::calcArrivalDate($region_id,$start_date);
                $back_date = Route::calcBackDate($region_id,$start_date);
                Route::add($region_id,$start_date,$i_courier);
                $durability = $regions[ $region_id ]['durability'] + $regions[ $region_id ]['durability_back'];
                $courier_dates[ $i_courier ]->modify("+{$durability} day");
            } while ( $end_date > $courier_dates[ $i_courier ] );
        }
    }
}
