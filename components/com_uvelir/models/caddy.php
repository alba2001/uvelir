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

//jimport('joomla.application.component.model');
require_once dirname(__FILE__) . '/kmodelform.php'; 
/**
 * Uvelir model.
 */
//class UvelirModelCaddy extends JModel
class UvelirModelCaddy extends ModelKModelform
{
    
    /**
     * Добавить товара в корзину
     * @return type 
     */
    public function add()
    {
        $item_id = JRequest::getInt('item_id');
        $zavod = JRequest::getInt('zavod');
        
        // Проверк на наличие ИД завода и ИД товара
        if(!($item_id AND $zavod))
        {
            return array(0,  JText::_('COM_UVELIR_DATA_NOT_MATCH'));
        }
        $product = $this->getTable('product_'.$zavod);
        
        // Если товар не найден, возвращаем ошибку
        if(!$product->load($item_id))
        {
            return array(0,  JText::_('COM_UVELIR_ITEM_NOT_EXIST'));
        }
        $caddy = JFactory::getApplication()->getUserState('com_uvelir.caddy', array());
//        unset($caddy);
        // Добавляем товар в корзину
        if(!isset($caddy[$zavod.'_'.$product->id]))
        {
            $caddy[$zavod.'_'.$product->id]['count'] = 1;
            $caddy[$zavod.'_'.$product->id]['sum'] = $product->cena_tut;
        }
        else
        {
            $caddy[$zavod.'_'.$product->id]['count']++;
            $caddy[$zavod.'_'.$product->id]['sum'] += $product->cena_tut;
        }
        JFactory::getApplication()->setUserState('com_uvelir.caddy', $caddy);
//        var_dump($_SESSION);
//        echo '<hr/>';
//        var_dump($caddy);exit;
        
        return array(1, $this->get_caddy_data($caddy));
    }

    /**
     * Удалить товара из корзины
     * @return type 
     */
    public function del()
    {
        $item_id = JRequest::getInt('item_id');
        $zavod = JRequest::getInt('zavod');
        
        // Проверк на наличие ИД завода и ИД товара
        if(!($item_id AND $zavod))
        {
            return array(0,  JText::_('COM_UVELIR_DATA_NOT_MATCH'));
        }
        $product = $this->getTable('product_'.$zavod);
        
        // Если товар не найден, возвращаем ошибку
        if(!$product->load($item_id))
        {
            return array(0,  JText::_('COM_UVELIR_ITEM_NOT_EXIST'));
        }
        $caddy = JFactory::getApplication()->getUserState('com_uvelir.caddy', array());
//        unset($caddy);
        // Удаляем товар из корзины
        if(isset($caddy[$zavod.'_'.$product->id]))
        {
            $caddy[$zavod.'_'.$product->id]['count']--;
            $caddy[$zavod.'_'.$product->id]['sum'] -= $product->cena_tut;
            if($caddy[$zavod.'_'.$product->id]['count'] < 1)
            {
                unset($caddy[$zavod.'_'.$product->id]);
            }
        }
        JFactory::getApplication()->setUserState('com_uvelir.caddy', $caddy);
        
        return array(1, $this->get_caddy_data($caddy));
    }

    /**
     * Итоговые данные по корзине
     * @param type $caddy
     * @return type 
     */
    public function get_caddy_data($caddy)
    {
        $sum = 0;
        $count = 0;
        foreach ($caddy as $row)
        {
            $sum += $row['sum']; 
            $count += $row['count']; 
        }
        return array(
            'sum'=>$sum,
            'count'=>$count,
        );
    }

    /**
     * Возвращаем таблицу
     * @param type $type
     * @param type $prefix
     * @param type $config
     * @return type 
     */
    public function getTable($type = 'Caddy', $prefix = 'UvelirTable', $config = array())
	{   
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
        return JTable::getInstance($type, $prefix, $config);
	}     
 
        /**
         * Список товаров в корзине
         * @return array 
         */
        public function getItems()
        {
            $caddy = JFactory::getApplication()->getUserState('com_uvelir.caddy', array());
            $items = array();
            foreach ($caddy as $key=>$value)
            {
                list($zavod_id, $id) = explode('_', $key);
                $product = $this->getTable('product_'.$zavod_id);
                $zavod_name = '';
                $zavod = $this->getTable('zavod');
                if($zavod->load($zavod_id))
                {
                    $zavod_name = $zavod->name;
                }
                if($product->load($id))
                {
                    $desc = json_decode($product->desc);
                    $item = array(
                        'id'=>$id,
                        'zavod_name'=>$zavod_name,
                        'zavod_id'=>$zavod_id,
                        'artikul'=>$product->artikul,
                        'src'=>$desc->img_small,
                        'price'=>$product->cena_tut,
                        'count'=>$value['count'],
                        'sum'=>$value['sum'],
                    );
                    $items[] = $item;
                }
            }
            return $items;
        }
    /**
     * Пересчет товара в корзине
     * @return type 
     */
    public function correction()
    {
        $counts = JRequest::getVar('count', array());
        
        // Проверк на наличие ИД завода и ИД товара
        if(!($counts))
        {
            return array(0,  JText::_('COM_UVELIR_DATA_NOT_MATCH'));
        }
        
        $caddy = array();
        foreach($counts as $key=>$count)
        {
            list($zavod, $item_id) = explode('_', $key);
            $product = $this->getTable('product_'.$zavod);
            if($product->load($item_id))
            {
                $caddy[$zavod.'_'.$item_id]['count'] = $count;
                $caddy[$zavod.'_'.$item_id]['sum'] = $product->cena_tut*$count;
            }
        }
        JFactory::getApplication()->setUserState('com_uvelir.caddy', $caddy);
        
        return array(1, $this->get_caddy_data($caddy));
    }
    
        
}