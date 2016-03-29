<?php
/**
 * @version     1.0.0
 * @package     com_uvelir
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Products list controller class.
 */
class UvelirControllerProducts extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'product', $prefix = 'UvelirModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
        
        /**
         * Показывать логотип
         */
        public function multy_set_show_logo()
        {
            $this->call_method('show_logo',1);
            $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
        }
        public function set_show_logo()
        {
            echo $this->call_method('show_logo',1);
            exit;
        }
        /**
         * Не показывать логотип
         */
        public function multy_unset_show_logo()
        {
            echo $this->call_method('show_logo',0);
            $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
        }
        /**
         * Изменение категории
         */
        public function category_change()
        {
            // Check for request forgeries
            JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
            $cid = JRequest::getVar('cid', array(), '', 'array');
            $category_id = JRequest::getInt('category_change_id', 0);
            $this->getModel('Products')->category_change($cid,$category_id);
            $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
        }
        public function unset_show_logo()
        {
            echo $this->call_method('show_logo',0);
            exit;
        }
        /**
         * Устанавливаем товар в наличие
         */
        public function set_available()
        {
            echo $this->call_method('set_available',1);
            exit;
        }
        /**
         * Снимаем товар с наличия 
         */
        public function unset_available()
        {
            echo $this->call_method('set_available',0);
            exit;
        }
        public function call_method($task, $value)
        {
            // Check for request forgeries
            JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
            
            $cid = JRequest::getVar('cid', array(), '', 'array');
            // Get the model.
            $model = $this->getModel('Products');

            return (int)$model->$task($cid, $value);
        }
        
        public function fill_cenas()
        {
            $model = $this->getModel('Products');
            $model->fill_cenas();
            
            $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
        }
}