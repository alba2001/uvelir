<?php
	// variables
	// global $menu;
	// global $active;
	// $app = JFactory::getApplication();
	// $doc = JFactory::getDocument();
	// $params = $app->getParams();
	// $headdata = $doc->getHeadData();
	// $menu = $app->getMenu();
	// $active = $app->getMenu()->getActive();
	// $pageclass = $params->get('pageclass_sfx');
	// $tpath = $this->baseurl.'/templates/'.$this->template;
	// variables

	require_once ("lib/phpthumb/ThumbLib.inc.php");
	require_once ("lib/smart_resize_image.php");
	require_once ("functions.php");

	global $incase;	$incase = incase::getInstance();
	define( 'TEMPLATEPATH', 'templates/' . $incase->getTemplate());


	/*
		<div class="<?php echo (($incase->getMenu()->getActive() == $incase->getMenu()->getDefault()) ? ('front') : ('page')).' '.$incase->getActive()->alias.' '.$pageclass; ?>">
	*/
?>