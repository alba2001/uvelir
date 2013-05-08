<?php defined('_JEXEC') or die;

// index.php
/*
	require_once ('lib/incase/index.php');

	<div class="<?php echo (($menu->getActive() == $menu->getDefault()) ? ('front') : ('page')).' '.$active->alias.' '.$pageclass; ?>">

	<link rel="stylesheet" href="<?=$this->baseurl . '/templates/' . $this->template . '/css/styless.css'?>" type="text/css" media="screen,projection" />
*/
// index.php

// variables
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$params = $app->getParams();
$headdata = $doc->getHeadData();
$menu = $app->getMenu();
$active = $app->getMenu()->getActive();
$pageclass = $params->get('pageclass_sfx');
$tpath = $this->baseurl.'/templates/'.$this->template;
// variables

define( 'YOURBASEPATH', dirname(__FILE__) . '/../../' );
define( 'TEMPLATEPATH', 'templates/' . $app->getTemplate());
require (YOURBASEPATH . DS . "lib/incase/functions.php");
require_once (YOURBASEPATH . DS . "lib/incase/phpthumb/ThumbLib.inc.php");
// require_once (YOURBASEPATH . DS . "lib/incase/spritegenerator/css-sprite-generator.php");


// production

	// require_once (YOURBASEPATH . DS . "lib/incase/lessphp/lessc.inc.php");
	// $less = new lessc(YOURBASEPATH . DS . 'lib/incase/less/styless.less');
	// file_put_contents(YOURBASEPATH . DS . 'css/styless.css', $less->parse());
	// JHTML::script($filename, $path, $mootools);
	// $doc->addStyleSheet($this->baseurl.'/templates/'.$this->template. '/css/styless.css');
	JHTML::stylesheet('screen.css', TEMPLATEPATH . DS . 'lib/incase/compass/stylesheets/');
	// $doc->addStyleSheet(TEMPLATEPATH . DS . 'css/styless.less');

// production


// development

	// $doc->addCustomTag("<link rel=\"stylesheet/less\" href= \"/" . TEMPLATEPATH . DS . 'lib/incase/less/styless.less' . '"/>');
	// $doc->addCustomTag('<script src="'. TEMPLATEPATH . DS .'lib/incase/js/less.js" type="text/javascript"></script>');
	// $doc->addCustomTag('<script type="text/javascript">less.watch();</script>');

// development

?>