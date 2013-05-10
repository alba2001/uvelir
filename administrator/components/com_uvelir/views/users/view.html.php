<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Users View
 */
class UvelirViewUsers extends JView
{
	/**
	 * Users view display method
	 * @return void
	 */
	function display($tpl = null) 
	{

		// Get data from the model
		$items = $this->get('Items');
		$pagination = $this->get('Pagination');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->items = $items;
		$this->pagination = $pagination;
 
		// Set the toolbar
		$this->addToolBar();
 
                // Add submenu
                $input = JFactory::getApplication()->input;
                $view = $input->getCmd('view', '');
                UvelirHelper::addSubmenu($view);

		// Display the template
		parent::display($tpl);
 
		// Set the document
		$this->setDocument();
	}
 
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
            JToolBarHelper::title(JText::_('COM_USER_ADMINISTRATION'), 'user.png');
            JToolBarHelper::addNew('user.add', 'JTOOLBAR_NEW');
            JToolBarHelper::editList('user.edit', 'JTOOLBAR_EDIT');
            JToolBarHelper::deleteList('', 'users.delete', 'JTOOLBAR_DELETE');
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_user');
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_USER_ADMINISTRATION'));
	}
}
