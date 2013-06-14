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
	public function getModel($name = 'products', $prefix = 'UvelirModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
        
        /**
         * Устанавливаем товар в наличие
         */
        public function set_available()
        {
            // Check for request forgeries
            JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
            
            $cid = JRequest::getVar('cid', array(), '', 'array');
            // Get the model.
            $model = $this->getModel();

            // Make sure the item ids are integers
            JArrayHelper::toInteger($cid);
            
            echo (int)$model->set_available($cid, 1);
            exit;
        }
        
        /**
         * Снимаем товар с наличия 
         */
        public function unset_available()
        {
            // Check for request forgeries
            JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
            
            $cid = JRequest::getVar('cid', array(), '', 'array');
            // Get the model.
            $model = $this->getModel();

            // Make sure the item ids are integers
            JArrayHelper::toInteger($cid);
            
            echo (int)$model->set_available($cid, 0);
            exit;
        }
        
}