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

/**
 * View to edit
 */
class UvelirViewCategory_new extends JView
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
            $this->state	= $this->get('State');
            $this->item		= $this->get('Item');
            if($this->item->id)
            {
                JFactory::getApplication()->setUserState('com_uvelir.category_new_id',$this->item->id);
            }
            $this->form		= $this->get('Form');

            // Check for errors.
            if (count($errors = $this->get('Errors'))) {
                throw new Exception(implode("\n", $errors));
            }

            $this->addToolbar();
            parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

            $user		= JFactory::getUser();
            if (isset($this->item->checked_out)) {
                        $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
            } else {
                $checkedOut = false;
            }
            JToolBarHelper::title(JText::_('COM_UVELIR_TITLE_CATEGORY'), 'category_new.png');
            JToolBarHelper::apply('category_new.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('category_new.save', 'JTOOLBAR_SAVE');
            JToolBarHelper::custom('category_new.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            JToolBarHelper::custom('category_new.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
            if (empty($this->item->id)) {
                    JToolBarHelper::cancel('category_new.cancel', 'JTOOLBAR_CANCEL');
            }
            else {
                    JToolBarHelper::cancel('category_new.cancel', 'JTOOLBAR_CLOSE');
            }

	}
}
