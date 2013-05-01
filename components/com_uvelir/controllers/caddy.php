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

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Company controller class.
 */
class UvelirControllerCaddy extends UvelirController
{
    /**
     * Добавить товар в корзину 
     */
    function add()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = $this->getModel('Caddy');
        $result = json_encode($model->add());
        echo $result;
        exit;
    }
    /**
     * Добавить новый заказ 
     */
    function order_add()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = $this->getModel('Caddy');
        if($model->order_add())
        {
            echo JTEXT::_('COM_UVELIR_ORDER_SAVER');
        }
        else
        {
            echo JTEXT::_('COM_UVELIR_ERROR_SAVE_ORDER');
        }
        return true;
    }
    /**
     * Удалить товар из корзины
     */
    function del()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = $this->getModel('Caddy');
        $result = json_encode($model->del());
        echo $result;
        exit;
    }
    
    /**
     * корректировка данных в корзине 
     */
    function correction()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = $this->getModel('Caddy');
        $result = json_encode($model->correction());
        echo $result;
        exit;
        
    }
}