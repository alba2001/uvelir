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
 * @param	array	A named array
 * @return	array
 */
function UvelirBuildRoute(&$query)
{
	$segments = array();
    
	if (isset($query['task'])) {
		$segments[] = implode('/',explode('.',$query['task']));
		unset($query['task']);
	}
	if (isset($query['id'])) {
		$segments[] = $query['id'];
		unset($query['id']);
	}
	if (isset($query['alias'])) {
		$segments[] = $query['alias'];
		unset($query['alias']);
	}
       return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/uvelir/task/id/Itemid
 *
 * index.php?/uvelir/id/Itemid
 */
function UvelirParseRoute($segments)
{
	$vars = array();
    
	// view is always the first element of the array
	$count = count($segments);
        if($count)
        {
//            $uris = explode('/',$_SERVER["REQUEST_URI"]);
//            switch ($uris[1]) {
//                case 'yuveliry-urala':
//                    $vars['zavod'] = '1';
//                    break;
//                case 'atoll-g-novosibirsk':
//                    $vars['zavod'] = '2';
//                    break;
//                default:
//            }    
            $zavods = array(
                '1'=>'yuveliry-urala',
                '2'=>'atoll-g-novosibirsk',
            );
            foreach ($zavods as $key=>$value)
            {
                if(preg_match("/$value/", $_SERVER["REQUEST_URI"], $regs))
                {
                    $vars['zavod'] = $key;
                }
            }

            $segment = array_pop($segments) ;
            $vars['alias'] = $segment;
            $vars['view'] = 'product';
        }
    	return $vars;
}
