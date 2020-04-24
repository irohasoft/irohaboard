<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

// ファイルアップクラス
class FileUpload
{
	var $_file;																	//	ファイル実体
	var $_file_name;															//	ファイル名
	var $_file_name_ext;														//	ファイル名の拡張子
	var $_file_size;															//	ファイルサイズ

	var $upload_dir;															//	アップロードディレクトリ
	var $extensions;															//	アップロード許可拡張子リスト
	var $max_size;																//	アップロード許可ファイルサイズ

	//------------------------------//
	//	コンストラクタ				//
	//------------------------------//
	public function FileUpload()
	{
		$this->upload_dir 	= "./";												//	アップロード用ディレクトリ
		$this->extensions	= (array)Configure::read('upload_extensions');		//	デフォルト　許可拡張子リスト
		$this->max_size		= Configure::read('upload_maxsize');				//	デフォルト　許可ファイルサイズ 2MB
		
		$upload_max_filesize = $this->getBytes(ini_get('upload_max_filesize'));
		$post_max_size		 = $this->getBytes(ini_get('post_max_size'));
		
		// upload_max_filesize が設定サイズより小さい場合、upload_max_filesize を優先する
		if($upload_max_filesize < $this->max_size)
			$this->max_size	= $upload_max_filesize;
		
		// post_max_size が設定サイズより小さい場合、post_max_size を優先する
		if($post_max_size < $this->max_size)
			$this->max_size	= $post_max_size;
	}

	//------------------------------//
	//	ファイルの読み込み			//
	//------------------------------//
	public function readFile( $file )
	{
		$this->_file			= $file['tmp_name'];							//	ファイルの実体を取得
		$this->_file_name 		= $file['name'];								//	ファイルの名前の取得
		$this->_file_name_ext 	= $this->getExtension( $this->_file_name );	//	ファイルの拡張子を取得
		$this->_file_size 		= $file['size'];								//	ファイルサイズの取得
	}

	//------------------------------//
	//	ファイルのエラーチェック	//
	//------------------------------//
	public function checkFile()
	{
		if(!$this->checkExtenstion() )											//	拡張子のチェック
			return 1001;
		
		if($this->_file_size == 0)												//	ファイルサイズのチェック
			return 1002;
		
		if($this->_file_size > $this->max_size)									//	ファイルサイズのチェック
			return 1003;
		
		return 0;
	}

	//------------------------------//
	//	ファイルの保存				//
	//------------------------------//
	public function saveFile( $file_path )
	{
		if($this->checkFile() > 0)
			return false;														//	アップロード失敗
		
		$result = move_uploaded_file($this->_file, $file_path);					//	ファイルの生成
		
		return $result;
	}

	//------------------------------//
	//	ファイルの拡張子チェック	//
	//	true OK, false:NG			//
	//------------------------------//
	public function checkExtenstion()
	{
		$extension = $this->_file_name_ext;										//	拡張子の取得
		$ext_array = $this->extensions;											//	許可拡張子を配列で取得

		if(in_array( $extension, $ext_array ) )						 			//	許可拡張子に含まれるかどうかを判定
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	//------------------------------//
	//	ディレクトリ存在確認		//
	//------------------------------//
	public function checkDir()
	{
		if( !is_dir( $this->upload_dir ) )
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	//------------------------------//
	//	拡張子の取得				//
	//------------------------------//
	public function getExtension( $from_file )
	{
		$ext = strtolower( strrchr( $from_file, "." ) );
		return $ext;
	}

	//------------------------------//
	//	拡張子の設定				//
	//------------------------------//
	public function setExtension( $extensions )
	{
		$this->extensions = $extensions;
	}

	//------------------------------//
	//	最大ファイルサイズの設定	//
	//------------------------------//
	public function setMaxSize( $max_size )
	{
		/*
		ini_set('upload_max_filesize', $max_size);
		ini_set('post_max_size',       $max_size);
		
		echo ini_get('upload_max_filesize');
		echo ini_get('post_max_size');
		*/
		$this->max_size = $max_size;
	}

	//------------------------------//
	//	ファイルの取得				//
	//------------------------------//
	public function get_file_name()
	{
		return $this->_file_name;
	}

	//------------------------------//
	//	MB等の単位をバイト数に変換	//
	//------------------------------//
	function getBytes($str)
	{
		preg_match('/(\d+)(\w+)/', $str, $matches);
		
		$type = strtolower($matches[2]);
		$bytes = 0;
		//echo $type;
		
		switch ($type)
		{
			case "b":
				$bytes = $matches[1];
				break;
			case "k":
			case "kb":
				$bytes = $matches[1]*1024;
				break;
			case "m":
			case "mb":
				$bytes = $matches[1]*1024*1024;
				break;
			case "g":
			case "gb":
				$bytes = $matches[1]*1024*1024*1024;
				break;
			case "t":
			case "tb":
				$bytes = $matches[1]*1024*1024*1024;
				break;
		}
		
		return $bytes;
	}

}

