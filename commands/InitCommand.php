<?php

/**
 * Команда для инициализации приложения
 */
class InitCommand {

    public function actionIndex(){
        echo '...run init command';
        $begin_date = new DateTime('2015-06-01');
        $cur_date = new DateTime();
        // Заполним данные по каждому курьеру
        for($i_courier=1;$i_courier<=10;$i_courier++){
        }
    }

}
