<?php

// Подключаем конфиг приложения
require_once '../config/config.php';

spl_autoload_register(function ($class_name) {
    // Список директорий для загрузки
    $directories = array(
                '../components/',
                '../models/',
                '../commands/'
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

class ConsoleKernel {

    public function handle(){
        $argv = $_SERVER['argv'];
        if(!isset($argv[1])) return;

        $controllerName = ucfirst($argv[1]) . 'Command';
        $actionName = 'action' . ( isset($argv[2]) ? ucfirst($argv[2]) : 'Index');

        // Создаем экземпляр контроллера
        $controller = new $controllerName();
        // Запускаем экшн
        $controller->$actionName();
    }

}

// Подключаемся к БД
$mydb = MYDB::getInstance();

// Создаем экземпляр обработчика Http запросов
$consoleKernel = new ConsoleKernel();
$consoleKernel->handle();
