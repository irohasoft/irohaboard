<?php
/**
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 */

// ユーティリティクラス
class Utils
{
	//------------------------------//
	//	コンストラクタ				//
	//------------------------------//
	public function Utils()
	{
	}
	
	public static function getYMD($str)
	{
		return substr($str, 0, 10);
	}

	public static function getYMDHN($str)
	{
		return substr($str, 0, 16);
	}
	
	public static function getHNSBySec($sec)
	{
		$hour	= floor($sec / 3600);
		$min	= floor(($sec / 60) % 60);
		$sec	= $sec % 60;
		
		$hms = sprintf("%02d:%02d:%02d", $hour, $min, $sec);
		
		return $hms;
	}
	
	public static function getCsvData($file_path)
	{
		setlocale(LC_ALL, 'ja_JP.UTF-8');
		
		$data = file_get_contents($file_path);
		$data = mb_convert_encoding($data, 'UTF-8', 'sjis-win');
		$temp = tmpfile();
		$meta = stream_get_meta_data($temp);
		
		fwrite($temp, $data);
		rewind($temp);
		
		$file = new SplFileObject($meta['uri']);
		$file->setFlags(SplFileObject::READ_CSV);
		
		$csv  = [];
		 
		foreach($file as $line) {
			$csv[] = $line;
		}
		
		fclose($temp);
		$file = null;
		
		return $csv;
	}
	/*
	public static function isAllowed($user, $roles)
	{
		return (array_search($user['role'], $roles) > -1);
	}
	*/
	
	public static function getKeyByValue($configure, $value)
	{
		$list = Configure::read($configure);
		
		foreach ($list as $key => $val)
		{
			if($value==$val)
				return $key;
		}
		
		return null;
	}
	
	public static function getIdByTitle($list, $title)
	{
		foreach ($list as $key => $value)
		{
			if($value==$title)
				return $key;
		}
		
		return null;
	}
	
	public static function getNewPassword($digit)
	{
		return substr(str_shuffle('23456789abcdefghijkmnopqrstuvwxyz'), 0, $digit);
	}
}

