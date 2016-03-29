<?php
/**
 * @version     1.0.0
 * @package     com_uvelir
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */

defined('_JEXEC') or die;

abstract class UvelirHelper
{
	public static function isKoltsa($product_id)
	{
            require_once JPATH_COMPONENT.'/models/products.php';
            $products = new UvelirModelProducts;
            return $products->isKoltsa($product_id);
	}

}

