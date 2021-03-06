<?php
namespace App\Plugin;


trait AppHelper
{


    function nlKill($string, $replace='') {

        return str_replace(array("\\r\\n", "\\r", "\\n", "\\t"), $replace, $string);
    }

    function seoDesc($content, $offset=140) {

        if ($content == '')
            return '';

        $content = strip_tags($this->nlKill($content));

        if ($content == '' || strlen($content) <= $offset) {

            return $content;
        }


        $trimPos = strpos($content, '.', $offset)
            or
        $trimPos = strpos($content, ' ', $offset);

        if (strlen($content) > $trimPos)
            $content = substr($content, 0, $trimPos);

        return $content;
    }


	function urlTitle($str, $replace='-', $lowercase=true, $transConvert=true) {

		$trans = array(
				'&\#\d+?;' => '',
				'&\S+?;' => '',
				'\s+' => $replace,
				'\.' => $replace,
				'[^a-z0-9\-_]' => '',
				$replace . '+' => $replace,
				$replace . '$' => $replace,
				'^' . $replace => $replace,
				'\.+$' => ''
		);

		$search_tr = array(
				'ı',
				'İ',
				'Ğ',
				'ğ',
				'Ü',
				'ü',
				'Ş',
				'ş',
				'Ö',
				'ö',
				'Ç',
				'ç'
		);
		$replace_tr = array(
				'i',
				'I',
				'G',
				'g',
				'U',
				'u',
				'S',
				's',
				'O',
				'o',
				'C',
				'c'
		);

		$str = str_replace($search_tr, $replace_tr, $str);

		$str = strip_tags($str);

		if ($transConvert)
			foreach ($trans as $key => $val)
				$str = preg_replace("#" . $key . "#i", $val, $str);
		else
			$str = str_replace(' ', $replace, $str);

		if ($lowercase)
			$str = strtolower($str);

		return trim(stripslashes($str));
	}
}