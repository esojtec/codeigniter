<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function url_title($str, $separator = 'dash', $lowercase = FALSE) {
    $a = array('á', 'é', 'í', 'ó', 'ú', 'ñ','Á','É','Í','Ó','Ú','À',
	           'ã','â','à','Ç','ç','Ñ','ß','ò','Ò','.');
 
    $b = array('a', 'e', 'i', 'o', 'u', 'n','A','E','I','O','U','A',
	           'a','a','a','C','c','N','B','o','O','-');
 
    $str = str_ireplace($a, $b, $str);
 
    if ($separator == 'dash') {
        $search = '_';
        $replace = '-';
    } else {
        $search = '-';
        $replace = '_';
    }
 
    $trans = array(
        '&\#\d+?;' => '',
        '&\S+?;' => '',
        '\s+' => $replace,
        '[^a-z0-9\-\._]' => '',
        $replace . '+' => $replace,
        $replace . '$' => $replace,
        '^' . $replace => $replace,
        '\.+$' => ''
    );
 
    $str = strip_tags($str);
 
    foreach ($trans as $key => $val) {
        $str = preg_replace("#" . $key . "#i", $val, $str);
    }
 
    if ($lowercase === TRUE) {
        $str = strtolower($str);
    }
 
    return trim(stripslashes($str));
}

function url_title_reverse($str,$separator = 'dash', $lowercase = FALSE)
{
	if ($separator == 'dash') {
        $search = '-';
        $replace = ' ';
    } else {
        $search = '_';
        $replace = ' ';
    }
	
	return str_replace($search,$replace, $str);
}
 
?>