<?php

/**
 * Вспомогательные функции
 */

class Utils {

    public static function format_date($date){
        return date('d.m.Y',strtotime($date));
    }

}
