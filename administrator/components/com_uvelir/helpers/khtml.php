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
require_once JPATH_ROOT.'/modules/mod_usearch/helper.php';

/**
 * Uvelir helper.
 */
class KhtmlHelper
{
	public static function new_category_roots()
	{
            $db = JFactory::getDbo();
            $query	= $db->getQuery(true);
            // Select the required fields from the table.
            $query->select('`id` AS value, `name` AS text')
                    ->from('`#__uvelir_categories_new`');
            $query->where('`level` <= 1');
            $db->setQuery($query);
            return $db->loadObjectList();
	}
    
        /**
        * Список заводов
        * @param noting
        * @return object list
        */
	public static function categories($zavod = 0)
	{
            $db = JFactory::getDbo();
            $query	= $db->getQuery(true);

            // Select the required fields from the table.
            $query->select('`id` AS value, `name` AS text')
                    ->from('`#__uvelir_categories`');
            if($zavod)
            {
                $query->where('`zavod` = '.$zavod);
            }
                    
            $db->setQuery($query);
            return $db->loadObjectList();
	}

        /**
        * Список категорий с учетом завода
        * @param noting
        * @return object list
        */
	public static function zavods()
	{
            $db = JFactory::getDbo();
            $query	= $db->getQuery(true);

            // Select the required fields from the table.
            $query->select('`id` AS value, `name` AS text')
                    ->from('`#__uvelir_zavods`');
            $db->setQuery($query);
            return $db->loadObjectList();
	}


        /**
        * Список вставок
        * @param noting
        * @return object list
        */
	public static function vstavkis()
	{
            $db = JFactory::getDbo();
            $query	= $db->getQuery(true);

            // Select the required fields from the table.
            $query->select('`id` AS value, `name` AS text')
                    ->from('`#__uvelir_vstavkis`');
            $db->setQuery($query);
            return $db->loadObjectList();
	}

        /**
        * Список металлов
        * @param noting
        * @return object list
        */
	public static function metals()
	{
            $db = JFactory::getDbo();
            $query	= $db->getQuery(true);

            // Select the required fields from the table.
            $query->select('`id` AS value, `name` AS text')
                    ->from('`#__uvelir_metals`');
            $db->setQuery($query);
            return $db->loadObjectList();
	}

        /**
        * Статус заказа
        * @param int $order_status_id
        * @return string 
        */
	public static function getOrderStatus($order_status_id)
	{
            $order_status = '';
            $table = self::getTable('Order_statuses');
            if($table->load($order_status_id))
            {
                $order_status = $table->name;
            }

            return $order_status;
	}

        /**
         * Дата заказа
         * @param type $order_status_id
         * @return string 
         */
	public static function getOrderDt($order_dt)
	{
//            preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})(\.)+$/", $order_dt, $regs);
            preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})(.+)/", $order_dt, $regs);
            if(count($regs))
            {
                $order_dt = $regs[3].'.'.$regs[2].'.'.$regs[1].$regs[4];
            }

            return $order_dt;
	}

        /**
         * Сумма заказа
         * @param type $sum
         * @return string 
         */
	public static function getSum($sum)
	{
            return (int)$sum;
	}
        
        /**
         * Наименование завода
         * @param int $zavod
         * @return string 
         */
	public static function getZavod_name($zavod)
	{
            $zavod_name = '';
            $table = self::getTable('Zavod');
            if($table->load($zavod))
            {
                $zavod_name  = $table->name;
            }
            return $zavod_name;
	}
        
        /**
         * Наименование категории с путем
         * @param int $category_id
         * @return string
         */
	public static function getCategory_path($category_id, $category_path = '')
	{
            $table = self::getTable('Category');
            if($category_id AND $table->load($category_id))
            {
                $_category_path = $category_path?' => '.$category_path:'';
                $category_path = self::getCategory_path($table->parent_id,$table->name.$_category_path);
            }
            return $category_path;
        }

        /**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	private function getTable($type = '', $prefix = 'UvelirTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
        function getListNewCategories($item_id)
        {
            $db = JFactory::getDbo();
            $query	= $db->getQuery(true);
            // Select the required fields from the table.
            $query->select('`id`, `name`, `level`')
                    ->from('`#__uvelir_categories_new`')
                    ->where('`level` > 0')
                    ->order('lft');
            $db->setQuery($query);
            $state = array();
            $state[] = JHTML::_('select.option'
                    , ''
                    , '0'
            );
            if ($list = $db->LoadObjectList())
            {
                foreach ($list as $row)
                {
                    if($row->id)
                    {
                        $state[] = JHTML::_('select.option'
                                , $row->id
                                , str_repeat('--', $row->level-1).$row->name
                        );
                    }
                }
            }
            $category_id = JFactory::getApplication()->getUserState('com_uvelir.products.filter.'.$item_id,0);
            return JHTML::_('select.genericlist'
                            , $state
                            , $item_id
                            , array()
                            , 'value'
                            , 'text'
                            , $category_id
                            , $item_id
                            , false );
        }
        function getListIzdelie()
        {
            $izdelie = JFactory::getApplication()->getUserState('com_uvelir.products.filter.usearch')['izdelie'];
            return modUsearchHelper::getListIzdelie($izdelie);
        }
        function getListMetal()
        {
            $metal = JFactory::getApplication()->getUserState('com_uvelir.products.filter.usearch')['metal'];
            return modUsearchHelper::getListMetal($metal);
        }
        
}
