<?php
	function mpr($what)	{
		print "<pre>";
		print_r($what);
		print "</pre>";
	}
	class incase {
		public function __construct() {
		}
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
		function mpr($what)		{
			print "<pre>";
			print_r($what);
			print "</pre>";
		}
		function clog($what){
			echo '<script type="text/javascript">console.log("'.$what.'");</script>';
		}
		function exist($input){
			$Headers = get_headers($input);
			if( strpos($Headers[0], '200') )
				return true;
		}
		// 2013.02.15
		/*<img src="<?=incase::thumb('http://dummyimage.com/800x600/4d494d/686a82.jpg', 'resize', 200, 200)?>">*/
		function thumb($input, $param, $a, $b, $c=null, $d=null){
			(strpos($input, ':') === false) ? $input = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $input : '';
			$dir = "cache/thumbs/";
			if (!file_exists($dir)) @mkdir($dir, 0777, true);
			$ext = strtolower(substr(strrchr($input, '.'), 1));
			$fname = basename($input, '.'.$ext) .'_'. $a . 'x' . $b . $param{0} . '.' . $ext;
			$output = $dir . $fname;
			if(file_exists($output)){
				return $output;
			}else{
				if ( self::exist($input) ) {
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
				}else{
					return $input;
				}
			}
		}
	}
?>