<?php

/**
 * Базовый контроллер
 */
class BaseController {

    /**
    * Рендеринг представлений
    * @param $view - название представления
    */
    protected function render($view,$params = []){
        extract($params);
        $folder = strtolower(str_replace('Controller','',get_class($this)));
        $content = include '../views/' . $folder . '/' . $view . '.php';        
    }

}
