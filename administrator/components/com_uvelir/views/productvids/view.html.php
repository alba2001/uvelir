<?php
/**
 * @version     1.0.0
 * @package     com_uvelir
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
require_once JPATH_COMPONENT.'/helpers/uvelir.php';

/**
 * View class for a list of Uvelir.
 */
class UvelirViewProductvids extends JView
{
	protected $items;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{

		$this->items		= $this->get('Items');
//                var_dump($this->items);exit;
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		$this->addToolbar();
                
        $input = JFactory::getApplication()->input;
        $view = $input->getCmd('view', '');
        UvelirHelper::addSubmenu($view);
        
        
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		

            JToolBarHelper::title(JText::_('COM_UVELIR_TITLE_PRODUCTVIDS'), 'productvid.png');

            JToolBarHelper::addNew('productvid.add','JTOOLBAR_NEW');
            JToolBarHelper::editList('productvid.edit','JTOOLBAR_EDIT');

            JToolBarHelper::deleteList('', 'productvid.delete','JTOOLBAR_DELETE');
	}
}
