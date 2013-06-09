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
     * Добавить новый заказ
     * @return bolean
     */
    public function order_add()
    {
        $caddy = JFactory::getApplication()->getUserState('com_uvelir.caddy', array());
        $user = $this->getUser();
        $caddy_zakaz = JFactory::getApplication()->getUserState('com_uvelir.zakaz', array());
        $caddy_data = $this->get_caddy_data($caddy);
        $data = array(
            'userid'=>$user->id,
            'order_status_id'=>'1',
            'order_dt'=>JFactory::getDate()->toMySQL(),
            'sum'=>$caddy_data['sum'],
            'caddy'=>  json_encode($caddy),
            'ch_status'=> date('d.m.Y H:i:s').' '.$user->fam.' '.$user->im.' '.$user->ot.' '.JText::_('COM_UVELIR_CHANGE_ORDER_STATUS_TO_INITIAL'),
            'oplata_id' => $caddy_zakaz['oplata'],
            'dostavka_id' => $caddy_zakaz['dostavka'],
        );
        $order = $this->getTable('order');
        if($order->save($data))
        {
            JFactory::getApplication()->setUserState('com_uvelir.caddy', array());
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * Добавить товара в корзину
     * @return type 
     */
    public function add()
    {
        $item_id = JRequest::getInt('item_id');
        
        // Проверк на наличие ИД завода и ИД товара
        if(!$item_id)
        {
            return array(0,  JText::_('COM_UVELIR_DATA_NOT_MATCH'));
        }
        $product = $this->getTable('product');
        
        // Если товар не найден, возвращаем ошибку
        if(!$product->load($item_id))
        {
            return array(0,  JText::_('COM_UVELIR_ITEM_NOT_EXIST'));
        }
        $caddy = JFactory::getApplication()->getUserState('com_uvelir.caddy', array());
//        unset($caddy);
        // Добавляем товар в корзину
        if(!isset($caddy[$product->id]))
        {
            $caddy[$product->id]['count'] = 1;
            $caddy[$product->id]['sum'] = $product->cena_tut;
        }
        else
        {
            $caddy[$product->id]['count']++;
            $caddy[$product->id]['sum'] += $product->cena_tut;
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
        
        // Проверк на наличие ИД завода и ИД товара
        if(!$item_id)
        {
            return array(0,  JText::_('COM_UVELIR_DATA_NOT_MATCH'));
        }
        $product = $this->getTable('product');
        
        // Если товар не найден, возвращаем ошибку
        if(!$product->load($item_id))
        {
            return array(0,  JText::_('COM_UVELIR_ITEM_NOT_EXIST'));
        }
        $caddy = JFactory::getApplication()->getUserState('com_uvelir.caddy', array());
//        unset($caddy);
        // Удаляем товар из корзины
        if(isset($caddy[$product->id]))
        {
            $caddy[$product->id]['count']--;
            $caddy[$product->id]['sum'] -= $product->cena_tut;
            if($caddy[$product->id]['count'] < 1)
            {
                unset($caddy[$product->id]);
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
        public function getItems($caddy=NULL)
        {
            if(!isset($caddy))
            {
                $caddy = JFactory::getApplication()->getUserState('com_uvelir.caddy', array());
            }
            $items = array();
            foreach ($caddy as $id=>$value)
            {
                $product = $this->getTable('product');
                if($product->load($id))
                {
                    $zavod = $this->getTable('zavod');
                    $zavod_name = $zavod->load($product->zavod_id)?$zavod->name:'';
                    $desc = json_decode($product->desc);
                    $item = array(
                        'id'=>$id,
                        'zavod_name'=>$zavod_name,
                        'zavod_id'=>$product->zavod_id,
                        'artikul'=>$product->artikul,
                        'name'=>$product->name,
                        'src'=>$desc->img_small,
                        'price'=>$product->cena_tut,
                        'count'=>$value['count'],
                        'sum'=>$value['sum'],
                        'path'=>$this->_get_path($product->category_id),
                    );
                    $items[] = $item;
                }
            }
            return $items;
        }
        
        /**
         *  Возвращаем путь к категории
         * @param int - ID категории
         * @return string Путь к категории
         */
        private function _get_path($id)
        {
            $result = '';
            $category = JTable::getInstance('Category', 'UvelirTable', array());
            if($category->load($id))
            {
                $result = $category->path;
            }
            return $result;
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
        foreach($counts as $item_id=>$count)
        {
            if($count)
            {
                $product = $this->getTable('product');
                if($product->load($item_id))
                {
                    $caddy[$item_id]['count'] = $count;
                    $caddy[$item_id]['sum'] = $product->cena_tut*$count;
                }
            }
        }
        JFactory::getApplication()->setUserState('com_uvelir.caddy', $caddy);
        
        return array(1, $this->get_caddy_data($caddy));
    }
    
    /**
     * Записываем способ доставки 
     */
    public function dostavka_submit()
    {
        $dostavka = JRequest::getInt('dostavka','1');
        $caddy_zakaz = JFactory::getApplication()->getUserState('com_uvelir.zakaz', array());
        $caddy_zakaz['dostavka'] = $dostavka;
        $caddy_zakaz = JFactory::getApplication()->setUserState('com_uvelir.zakaz', $caddy_zakaz);
        return array(1,'');
    }
    /**
     * Записываем способ оплаты 
     */
    public function oplata_submit()
    {
        $oplata = JRequest::getInt('oplata','1');
        $caddy_zakaz = JFactory::getApplication()->getUserState('com_uvelir.zakaz', array());
        $caddy_zakaz['oplata'] = $oplata;
        $caddy_zakaz = JFactory::getApplication()->setUserState('com_uvelir.zakaz', $caddy_zakaz);
        return array(1,'');
    }
    
    /**
     * Получение наименования способа доставки
     * @param int $id
     * @return string
     */
    public function get_dostavka($id)
    {
        $name = '';
        $table = $this->getTable('dostavka');
        if($table->load($id))
        {
            $name = $table->name;;
        }
        return $name;
    }
    
    /**
     * Получение наименования способа оплаты
     * @param int $id
     * @return string
     */
    public function get_oplata($id)
    {
        $name = '';
        $table = $this->getTable('oplata');
        if($table->load($id))
        {
            $name = $table->name;;
        }
        return $name;
    }
}