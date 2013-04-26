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
class UvelirViewZavods extends JView
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
		

            $state	= $this->state;
            $canDo	= UvelirHelper::getActions('com_uvelir.zavods');
            JToolBarHelper::title(JText::_('COM_UVELIR_TITLE_ZAVODS'), 'zavod.png');

            //Check if the form exists before showing the add/edit buttons
            $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/zavod';
            if (file_exists($formPath)) 
            {

                    if ($canDo->get('core.create')) 
                    {
                        JToolBarHelper::addNew('zavod.add','JTOOLBAR_NEW');
                    }

                    if ($canDo->get('core.edit') && isset($this->items[0])) 
                    {
                            JToolBarHelper::editList('zavod.edit','JTOOLBAR_EDIT');
                    }
            }
            JToolBarHelper::divider();
            JToolBarHelper::custom( 'zavod.parse', 'parse', '', 'PARSE', TRUE, false );

            if ($canDo->get('core.edit.state')) 
            {

                if (isset($this->items[0]->state)) {
                                JToolBarHelper::divider();
                                JToolBarHelper::custom('zavod.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
                                JToolBarHelper::custom('zavod.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
                } else if (isset($this->items[0])) {
                    //If this component does not use state then show a direct delete button as we can not trash
                    JToolBarHelper::deleteList('', 'zavod.delete','JTOOLBAR_DELETE');
                }

                if (isset($this->items[0]->state)) {
                                JToolBarHelper::divider();
                                JToolBarHelper::archiveList('zavod.archive','JTOOLBAR_ARCHIVE');
                }
                if (isset($this->items[0]->checked_out)) {
                    JToolBarHelper::custom('zavod.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
                }
            }

            //Show trash and delete for components that uses the state field
            if (isset($this->items[0]->state)) 
            {
                    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                            JToolBarHelper::deleteList('', 'zavod.delete','JTOOLBAR_EMPTY_TRASH');
                            JToolBarHelper::divider();
                    } else if ($canDo->get('core.edit.state')) {
                            JToolBarHelper::trash('zavod.trash','JTOOLBAR_TRASH');
                            JToolBarHelper::divider();
                    }
            }

            if ($canDo->get('core.admin')) 
            {
                    JToolBarHelper::preferences('com_uvelir');
            }


	}
}
