<?php

// Подключаем конфиг приложения
require_once '../config/config.php';

spl_autoload_register(function ($class_name) {
    // Список директорий для загрузки
    $directories = array(
                '../components/',
                '../models/',
                '../controllers/'
    );
    foreach($directories as $directory){
            //see if the file exsists
            if(file_exists($directory . $class_name . '.php'))
            {
                require_once($directory . $class_name . '.php');
                //only require the class once, so quit after to save effort (if you got more, then name them something else
                return;
            }
    }
});

class HttpKernel {

    private $_defaultController = 'Default';
    private $_defaultAction = 'Index';

    public function handle(){
        $uri = $_SERVER['REQUEST_URI'];
        // Разбираем строку запроса
        if($uri == '/'){
            $controllerName = $this->_defaultController;
            $actionName = $this->_defaultAction;
        }else{
            $uri = trim($uri,'/');
            $uri_buf = explode('/',$uri);
            $controllerName = ucfirst( isset($uri_buf[0]) && !empty($uri_buf[0]) ? $uri_buf[0] : $this->_defaultController ) . 'Controller';
            $actionName = 'action' . ucfirst( isset($uri_buf[1]) && !empty($uri_buf[1]) ? $uri_buf[1] : $this->_defaultAction );
        }
        // Создаем экземпляр контроллера
        $controller = new $controllerName();
        // Запускаем экшн
        $controller->$actionName();
    }

}

// Подключаемся к БД
$mydb = MYDB::getInstance();

// Создаем экземпляр обработчика Http запросов
$httpKernel = new HttpKernel();
$httpKernel->handle();
