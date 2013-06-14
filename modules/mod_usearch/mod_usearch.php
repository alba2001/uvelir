<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$usearch_data = JFactory::getApplication()->getUserState('com_uvelir.usearch', array());

if(!$usearch_data)
{
    $usearch_data = array(
        'izdelie' => '1',
        'metal' => '',
        'vstavki' => '',
        'razmer' => '',
        'proba' => '',
        'cost_1' => '',
        'cost_2' => '',
        'available' => '',
    );
}
require_once dirname(__FILE__).'/helper.php';

$izdelie = modUsearchHelper::getListIzdelie($usearch_data['izdelie']);
$metal = modUsearchHelper::getListMetal($usearch_data['metal']);
$vstavki = modUsearchHelper::getListVstavki($usearch_data['vstavki']);
$razmer = modUsearchHelper::getListRazmer($usearch_data['razmer'], $usearch_data['izdelie']);
$proba = modUsearchHelper::getListProba($usearch_data['proba']);
$available = modUsearchHelper::getCheckboxAvailable($usearch_data['available']);

//echo '111';exit;
require JModuleHelper::getLayoutPath('mod_usearch', $params->get('layout', 'default'));
