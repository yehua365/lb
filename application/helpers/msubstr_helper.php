<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 字符截取，同时支持中英文
 * @param  string $string 原字符串
 * @param  int $start  截取开始位置
 * @param  int $end    截取结束位置
 * @param  string $tail   尾巴
 * @return string         处理后字符串
 */
if(!function_exists('msubstr')){
	function msubstr($string, $start, $end,$tail='...') {
		$spac = mb_detect_encoding($string) == 'UTF-8' ? 3 : 2;
		$len = strlen($string);
		$loop = 0;
		$st = 0;
		
		//get start positon
		for ($i = 0; $i < $len; $i++) {
			$loop++;
			if ($loop > $start)
				break;

			if (ord(substr($string, $i, 1)) > 0xa0) {
				$st += $spac;
				$i += $spac -1;
			} else {
				$st += 1;
			}

		}

		$loop = 0;
		$ed = 0;
		
		//get end positon
		for ($i = $st; $i < $len; $i++) {
			$loop++;
			if ($loop > $end)
				break;

			if (ord(substr($string, $i, 1)) > 0xa0) {
				$ed += $spac;
				$i += $spac -1;
			} else {
				$ed += 1;
			}

		}

		$str = substr($string, $st, $ed);

		if ($ed + $st < $len)
			$str .= $tail;
		return $str;
	}
}