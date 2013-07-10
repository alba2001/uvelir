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
class UvelirViewMetallists extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{

		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->metals		= $this->get('Metals');
		$this->pagination	= $this->get('Pagination');
                $this->change_group     = JRequest::getInt('change_group', 0);

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
		

            $state	= $this->state;
            $canDo	= UvelirHelper::getActions('com_uvelir.metallists');
            JToolBarHelper::title(JText::_('COM_UVELIR_TITLE_METALLISTS'), 'metallist.png');

            //Check if the form exists before showing the add/edit buttons

            JToolBarHelper::divider();

            if ($canDo->get('core.edit.state')) 
            {

                if (isset($this->items[0]->state)) {
                                JToolBarHelper::divider();
                                JToolBarHelper::custom('metallist.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
                                JToolBarHelper::custom('metallist.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
                } else if (isset($this->items[0])) {
                    //If this component does not use state then show a direct delete button as we can not trash
                    JToolBarHelper::deleteList('', 'metallist.delete','JTOOLBAR_DELETE');
                }

                if (isset($this->items[0]->state)) {
                                JToolBarHelper::divider();
                                JToolBarHelper::archiveList('metallist.archive','JTOOLBAR_ARCHIVE');
                }
                if (isset($this->items[0]->checked_out)) {
                    JToolBarHelper::custom('metallist.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
                }
            }

            //Show trash and delete for components that uses the state field
            if (isset($this->items[0]->state)) 
            {
                    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                            JToolBarHelper::deleteList('', 'metallist.delete','JTOOLBAR_EMPTY_TRASH');
                            JToolBarHelper::divider();
                    } else if ($canDo->get('core.edit.state')) {
                            JToolBarHelper::trash('metallist.trash','JTOOLBAR_TRASH');
                            JToolBarHelper::divider();
                    }
            }

            if ($canDo->get('core.admin')) 
            {
                    JToolBarHelper::preferences('com_uvelir');
            }
            if ($canDo->get('core.create')) 
            {
                JToolBarHelper::divider();
                JToolBarHelper::custom( 'metallist.add_all', 'new.phg', '', 'COM_UVELIR_METALLISTS_ADD_ADD', FALSE, false );
            }
            
	}
       
}
