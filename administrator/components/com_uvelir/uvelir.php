<?php
/**
 * @version     1.0.0
 * @package     com_uvelir
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */


// no direct access
defined('_JEXEC') or die;
// Import CSS
$document = JFactory::getDocument();
$document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js');
$document->addScript(JURI::base().'components/com_uvelir/assets/scripts/jquery.maskedinput-1.3.min.js');
// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_uvelir')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');
$controller	= JController::getInstance('Uvelir');


$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
