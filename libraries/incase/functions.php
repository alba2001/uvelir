<?php

	  // *************** ********** *************** //
	 // *************** 18.07.2013 *************** //
	// *************** ********** *************** //

	// jimport('incase.init'); //

	class incase {
		public $test ='(-o-)';

		protected static $incase;

		public static function getInstance() {
		    if (self::$incase === null) {
		    	self::$incase = new self();
		    }

		   	return self::$incase;
		}

		private function __construct() {
		}

		/* 1 */
		function getTemplate(){
			$app = JFactory::getApplication();
			return $app->getTemplate();
		}
		function getMenu(){
			$app = JFactory::getApplication();
			return $app->getMenu();
		}
		function getActive(){
			$app = JFactory::getApplication();
			return $app->getMenu()->getActive();
		}
		/* 1 */

		/* 2 */
		/*<link rel="stylesheet" type="text/css" href="<?=inase::noCache('/templates/gantry/css-compiled/screen.css')?>" />*/
		function noCache($what){
			$change=md5(filemtime( ltrim($what, '/') ));
			return $what . '?' . $change;
		}
		/* 2 */

		function getAlias(){
			$this->app = JFactory::getApplication();
			$active = $this->app->getMenu()->getActive();
			return $active->alias;
		}
		// подстрока в строке
		function strpos($str,$substr){
			$result = strpos ($str, $substr);
			if ($result === FALSE)
				return false;
			else
			return true;
		}
		function clog($what){
			echo '<script type="text/javascript">console.log("'.$what.'");</script>';
		}
		//remote exist
		function exist($input){
			if (file_exists($input)){
				return true;
			}else{
				$Headers = get_headers($input);
				if( strpos($Headers[0], '200') )
					return true;
			}
			return false;
		}
		//remote filesize
		function filesize($url){
			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, TRUE);
			curl_setopt($ch, CURLOPT_NOBODY, TRUE);

			$data = curl_exec($ch);
			$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

			curl_close($ch);
			return $size;
		}
		/*<img src="<?=incase::thumb('http://dummyimage.com/800x600/4d494d/686a82.jpg', 'resize', 200, 200)?>">*/
		function thumb($input, $param, $a, $b, $c=null, $d=null){
			$dir = "cache/thumbs/";
			$host = 'http://' . $_SERVER['SERVER_NAME'] . '/';
			(strpos($input, ':') === false) ? $input = $host . $input : '';
			if ( !self::exist($input) ){
				$dummy = $host . '/images/dummy.png';
				if ( self::exist($dummy) ){
					$input = $dummy;
				}else{
					return 'http://dummyimage.com/'.$a.'x'.$b.'/000/fff.png&text=/images/dummy.png';
				}
			}
			if (!file_exists($dir)) @mkdir($dir, 0777, true);
			$ext = '.' . strtolower(substr(strrchr($input, '.'), 1));
			// $fname = basename($input, '.'.$ext) .'_'. $a . 'x' . $b . $param{0} . $ext;
			$fname = basename($input, '.'.$ext) . $a . $b . $param{0} . self::filesize($input);
			$output = $dir . md5($fname) . $ext;
			if(file_exists($output)){
				return $output;
			}else{
				$thumb = PhpThumbFactory::create($input);
				if($param=="crop"){
					$thumb->crop($a, $b, $c, $d);
				}elseif($param=="adaptiveResize"){
					$thumb->adaptiveResize($a, $b);
				}elseif($param=="resize"){
					$thumb->resize($a, $b);
				}
				$thumb->save($output);
				return $output;
			}
		}
	}
?>