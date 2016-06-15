<?php

function is_valid_date($date, $format = 'Y-m-d') {
    if (!is_array($date)) {
        $date = (array) $date;
    }
    return array_filter($date, function($var) use($format) {
                $d = DateTime::createFromFormat($format, $var);
                return $d && $d->format($format) == $var;
            }) === $date;
}

if(!function_exists('format_date')){
	function format_date($date, $format = 'Y-m-d'){
		return date($format, strtotime($date));
	}
}

if(!function_exists('phase')){
    function phase($phase){
        $data = [];
        if($phase == 1){
           return [ date('Y-m-1'), date('Y-m-15') ];
        }
        return [ date('Y-m-16'), date('Y-m-t') ];
    }
}


