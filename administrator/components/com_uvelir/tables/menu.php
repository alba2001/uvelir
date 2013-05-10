<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Import JTableMenu
JLoader::register('JTableMenu', JPATH_PLATFORM . '/joomla/database/table/menu.php');

//require_once dirname(__FILE__) . '/ktable.php'; 
jimport('joomla.database.tablenested');
/**
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 */
//class UvelirTableMenu extends JTableMenu
class UvelirTableMenu extends JTableMenu
{
    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
	public function delete($pk = null, $children = false)
	{
		return parent::delete($pk, $children);
	}
        
       
}
