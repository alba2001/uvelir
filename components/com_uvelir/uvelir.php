<?php
/**
 * @version     1.0.0
 * @package     com_uvelir
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */

defined('_JEXEC') or die;

// Подключаем хелпер
require_once JPATH_COMPONENT.'/helpers/uvelir.php';

// Include dependancies
jimport('joomla.application.component.controller');
// Execute the task.
$controller	= JController::getInstance('Uvelir');
//var_dump(JFactory::getApplication()->input->get('task'));

$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
