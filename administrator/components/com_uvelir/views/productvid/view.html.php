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
 * View to edit
 */
class UvelirViewProductvid extends JView
{
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->item		= $this->get('Item');
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

            JToolBarHelper::title(JText::_('COM_JUGRAAUTO_TITLE_PRODUCTVID'), 'productvid.png');

            JToolBarHelper::apply('productvid.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('productvid.save', 'JTOOLBAR_SAVE');
            JToolBarHelper::custom('productvid.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            JToolBarHelper::custom('productvid.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
            JToolBarHelper::cancel('productvid.cancel', 'JTOOLBAR_CANCEL');

	}
}
