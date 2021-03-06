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
require_once JPATH_COMPONENT.'/helpers/component.php';

/**
 * View class for a list of Uvelir.
 */
class UvelirViewProducts extends JView
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
		require_once JPATH_COMPONENT.'/helpers/uvelir.php';

                $canDo	= UvelirHelper::getActions('com_uvelir.products');

		JToolBarHelper::title(JText::_('COM_UVELIR_TITLE_PRODUCTS'), 'products.png');

        //Check if the form exists before showing the add/edit buttons
            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('product.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('product.edit','JTOOLBAR_EDIT');
		    }
		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('products.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('products.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('products.multy_set_show_logo', 'publish.png', 'publish_f2.png','COM_UVELIR_SET_SHOW_LOGO', true);
			    JToolBarHelper::custom('products.multy_unset_show_logo', 'unpublish.png', 'unpublish_f2.png', 'COM_UVELIR_SET_NOT_SHOW_LOGO', true);
			    JToolBarHelper::divider();
                            JToolBarHelper::deleteList('', 'products.delete','JTOOLBAR_DELETE');
			    JToolBarHelper::divider();
                            JToolBarHelper::custom( 'products.fill_cenas', 'unpublish.png', '', 'FILL_CENAS', FALSE, false );
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'products.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('products.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('products.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_uvelir');
		}
	}
        
        /**
         * Наименование категории с путем
         * @param int $category_id
         * @return string
         */
        protected function get_category_path($category_id)
        {
            return ComponentHelper::getCategory_path($category_id);
        }
        /**
         * Наименование категории с путем
         * @param int $category_id
         * @return string
         */
        protected function get_categories($id)
        {
            return ComponentHelper::getCategories_new($id);
        }
}
