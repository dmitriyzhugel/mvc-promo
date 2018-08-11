<?php

class SheduleController extends BaseController {

    /**
     * Расписание поездок на текущую дату
     */
    public function actionIndex(){
        $cur_date = date('Y-m-d');
        $routes = Route::listByDate($cur_date);
        $regions = Region::listAll();
        $couriers = Courier::listAll();
        return $this->render('index',[
            'routes'=>$routes,
            'regions'=>$regions,
            'couriers'=>$couriers,
            'cur_date'=>$cur_date
        ]);
    }

    /**
     * Расписание поездок за период
     */
    public function actionDetail(){
        $date_from = $_POST['date_from'];
        $date_to = $_POST['date_to'];
        $routes = Route::listByPeriod($date_from,$date_to);
        return $this->render('_detail',[
            'routes'=>$routes,
            'date_from'=>$date_from,
            'date_to'=>$date_to
        ]);
    }

    /**
     * Рассчет даты прибытия
     */
    public function actionCalc_arrival_date(){
        $region_id = (int)$_POST['region_id'];
        $start_date = $_POST['start_date'];
        $arrival_date = Route::calcArrivalDate($region_id,$start_date);
        echo json_encode(['status'=>'OK','arrival_date'=>Utils::format_date($arrival_date)]);
    }

    /**
     * Сохранение нового маршрута
     */
    public function actionSave(){
        $region_id = (int)$_POST['region_id'];
        $start_date = $_POST['start_date'];
        $courier_id = (int)$_POST['courier_id'];
        $result = Route::add($region_id,$start_date,$courier_id);
        echo json_encode(['status'=>$result->status,'message'=>$result->message]);
    }

}
