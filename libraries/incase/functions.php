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

		//remote exist (faster)
		function file_exists_remote($url) {
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_NOBODY, true);
			//Check connection only
			$result = curl_exec($curl);
			//Actual request
			$ret = false;
			if ($result !== false) {
				$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			//Check HTTP status code
			if ($statusCode == 200) {
				$ret = true;
				}
			}
			curl_close($curl);
		 return $ret;
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
		/*<img src="<?=incase::thumb('http://dummyimage.com/800x600/4d494d/686a82.jpg', 200, 200, true)?>">*/
		function thumb_($input, $a, $b, $proportional=false){
			return $input;
		}
		function thumb($input, $a, $b, $proportional=false){
			$dummy = 'images/dummy.png';
			$dir = "cache/thumbs/";
			$host = 'http://' . $_SERVER['SERVER_NAME'] . '/';
                        if (!preg_match('/gif|ico|jpg|jpeg|png|tiff|tif$/', $input, $regs))
                        {
                            return $input;
                        }
			// create cache folder
			if (!file_exists($dir)) @mkdir($dir, 0777, true);
			(strpos($input, ':') === false) ? $input = $host . $input : '';
//			$ext = '.' . strtolower(substr(strrchr($input, '.'), 1));
			$ext = '.' . $regs[0];
                        
			$fname = basename($input, '.' . $ext) . $a . $b . $param{0};

			// create remote folders
			$remotehost = parse_url($input, PHP_URL_HOST);
			if ($_SERVER['SERVER_NAME'] == $remotehost) {
				$output = $dir . md5($fname) . $ext;
			}else{
				$dir = $dir . $remotehost . '/';
				$output = $dir . md5($fname) . $ext;
				if (!file_exists($dir)) @mkdir($dir, 0777, true);
			}

			// create thumb
			if(file_exists($output)){
				return $output;
			}else{
                            return $input;
				try {
					$thumb = PhpThumbFactory::create($input);
					if($proportional == true){
						$thumb->adaptiveResize($a, $b);
					}else{
						$thumb->resize($a, $b);
					}
					$thumb->save($output);
					// smart_resize_image ($input, $a, $b, $proportional, $output);
					return $output;
				} catch (Exception $e) {
					if ( file_exists($dummy) ){
						return self::thumb($dummy, $a, $b, true);
					}else{
						return 'http://dummyimage.com/'.$a.'x'.$b.'/000/fff.png&text=/images/dummy.png';
					}
				}
			}
		}

		/*<img src="<?=incase::thumb('http://dummyimage.com/800x600/4d494d/686a82.jpg', 'resize', 200, 200)?>">*/
		function thumb_u($input, $param, $a, $b, $c=null, $d=null){
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
                
            private function _is_image($path)
            {
                $a = getimagesize($path);
                $image_type = $a[2];

                if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
                {
                    return true;
                }
                return false;
            }
	}
?>