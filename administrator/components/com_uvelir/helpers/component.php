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

/**
 * Uvelir helper.
 */
class ComponentHelper
{
        /**
        * ФИО клиента
        * @param int $user_id
        * @return string 
        */
	public static function getUserFio($user_id)
	{
            $fio = '';
            $table = self::getTable('Users');
            if($table->load($user_id))
            {
                $fio = $table->fam.' '.$table->im.' '.$table->ot;
            }

            return $fio;
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
         * Сумма к оплате ч-з Робокассу
         * К оплате ч-з Робокассу подлежит 50%($proc_robokassa_sum)
         * от суммы заказа, но не более 15т.р.($max_robokassa_sum)
         * @param type $sum
         * @return string 
         */
	public static function getCheckoutSum($sum)
	{
            
            $params = JComponentHelper::getParams('com_uvelir');
            $max_robokassa_sum = $params->get('max_robokassa_sum');
            $proc_robokassa_sum = $params->get('proc_robokassa_sum');
            $checkout_sum = ($sum * $proc_robokassa_sum)/100;
            $checkout_sum = $checkout_sum>$max_robokassa_sum?$max_robokassa_sum:$checkout_sum;
            
            return self::getSum($checkout_sum);
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
         * Стоимость изделия с учетом его среднего веса и типа товара
         * @param int $product_id
         * @param int $category_id
         * @return array 
         */
	public static function getPrices($product_id, $key=0)
	{
            $result = array(
                'cena_mag'=>'0',
                'cena_tut'=>'0',
                'cena_manager'=>'0',
                'price_mag'=>'',
                'price_tut'=>'',
                'producttype_name'=>'',
            );
            $product = self::getTable('Product');
            $category = self::getTable('Category');
            $producttype = self::getTable('Producttype');
            if($product->load($product_id))
            {
                if($category->load($product->category_id))
                {
                    if($category->producttype_id AND $product->average_weight AND $producttype->load($category->producttype_id))
                    {
                        $average_weights = explode(',', $product->average_weight);
                        $cena_mag_per_g = $producttype->cena_mag;
                        $cena_tut_per_g = $producttype->cena_tut;
                        $cena_mag = round($average_weights[$key]*$cena_mag_per_g,2);
                        $result['cena_mag'] = $cena_mag?$cena_mag:$product->cena_mag;
                        $cena_tut = round($average_weights[$key]*$cena_tut_per_g,2);
                        $result['cena_tut'] = $cena_tut?$cena_tut:$product->cena_tut;
                        $result['price_mag'] = $producttype->cena_mag;
                        $result['price_tut'] = $producttype->cena_tut;
                        $result['producttype_name'] = $producttype->name;
                    }
                    else
                    {
                        $result['cena_mag'] = $product->cena_mag;
                        $result['cena_tut'] = $product->cena_tut;
                    }
                }
                else
                {
                        $result['cena_mag'] = $product->cena_mag;
                        $result['cena_tut'] = $product->cena_tut;
                }
            }
            
            // Корректировка цены с учетом максимальной показываемой на сайте суммы
            $result['cena_manager'] = $result['cena_tut'];
            $params = JComponentHelper::getParams('com_uvelir');
            $max_shown_sum = $params->get('max_shown_sum');
            
            if( $result['cena_tut'] > $max_shown_sum)
            {
                $result['cena_tut'] = 0;
            }

            return $result;
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
}
