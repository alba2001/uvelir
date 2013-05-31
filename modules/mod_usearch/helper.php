<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @since		1.5
 */
class modUsearchHelper
{
        function getListIzdelie($selected)
        {
            $attribs = array();
            $db =& JFactory::getDBO();
            $table = $db->NameQuote('#__uvelir_productvids');
            $fields[] = $db->NameQuote('id');
            $fields[] = $db->NameQuote('title');
            $query = 'SELECT '.implode(',',$fields);
            $query .= ' FROM '.$table;
            
            $db->setQuery($query);
            $state = array();
            $state[] = JHTML::_('select.option'
                    , 0
                    , JText::_('MOD_USEARCH_NOT_IMPORTANT')
            );
            if ($list = $db->LoadObjectList())
            {
                foreach ($list as $row)
                {
                    $state[] = JHTML::_('select.option'
                            , $row->id
                            , JText::_($row->title)
                    );
                }
            }
            return JHTML::_('select.genericlist'
                            , $state
                            , 'usearch_data[izdelie]'
                            , $attribs
                            , 'value'
                            , 'text'
                            , $selected
                            , 'mod_usearch_izdelie'
                            , false );
         }
         
        function getListMetal($selected)
        {
            $attribs = array();
            $db =& JFactory::getDBO();
            $table = $db->NameQuote('#__uvelir_products');
            $fields[] = $db->NameQuote('material');
            $query = 'SELECT DISTINCT '.implode(',',$fields);
            $query .= ' FROM '.$table;
            
            $db->setQuery($query);
            $state = array();
            $state[] = JHTML::_('select.option'
                    , 0
                    , JText::_('MOD_USEARCH_NOT_IMPORTANT')
            );
            if ($list = $db->LoadObjectList())
            {
                foreach ($list as $row)
                {
                    if($row->material)
                    {
                        $state[] = JHTML::_('select.option'
                                , $row->material
                                , JText::_($row->material)
                        );
                    }
                }
            }
            return JHTML::_('select.genericlist'
                            , $state
                            , 'usearch_data[metal]'
                            , $attribs
                            , 'value'
                            , 'text'
                            , $selected
                            , 'mod_usearch_metal'
                            , false );
         }
         
         
        function getListVstavki($selected)
        {
            $attribs = array();
            $db =& JFactory::getDBO();
            $table = $db->NameQuote('#__uvelir_products');
            $fields[] = $db->NameQuote('vstavki');
            $query = 'SELECT DISTINCT '.implode(',',$fields);
            $query .= ' FROM '.$table;
            
            $db->setQuery($query);
            $state = array();
            $state[] = JHTML::_('select.option'
                    , 0
                    , JText::_('MOD_USEARCH_NOT_IMPORTANT')
            );
            if ($list = $db->LoadObjectList())
            {
                foreach ($list as $row)
                {
                    if($row->vstavki)
                    {
                        $state[] = JHTML::_('select.option'
                                , $row->vstavki
                                , JText::_($row->vstavki)
                        );
                    }
                }
            }
            return JHTML::_('select.genericlist'
                            , $state
                            , 'usearch_data[vstavki]'
                            , $attribs
                            , 'value'
                            , 'text'
                            , $selected
                            , 'mod_usearch_vstavki'
                            , false );
         }
         
        function getListProba($selected)
        {
            $attribs = array();
            $db =& JFactory::getDBO();
            $table = $db->NameQuote('#__uvelir_products');
            $fields[] = $db->NameQuote('proba');
            $query = 'SELECT DISTINCT '.implode(',',$fields);
            $query .= ' FROM '.$table;
            
            $db->setQuery($query);
            $state = array();
            $state[] = JHTML::_('select.option'
                    , 0
                    , JText::_('MOD_USEARCH_NOT_IMPORTANT')
            );
            if ($list = $db->LoadObjectList())
            {
                foreach ($list as $row)
                {
                    if($row->proba)
                    {
                        $state[] = JHTML::_('select.option'
                                , $row->proba
                                , JText::_($row->proba)
                        );
                    }
                }
            }
            return JHTML::_('select.genericlist'
                            , $state
                            , 'usearch_data[proba]'
                            , $attribs
                            , 'value'
                            , 'text'
                            , $selected
                            , 'mod_usearch_proba'
                            , false );
         }
         
        function getListRazmer($selected, $productvid_id = 0)
        {
            $attribs = array();
            $db =& JFactory::getDBO();
            $table = $db->NameQuote('#__uvelir_productvids');
            $fields[] = $db->NameQuote('sizes');
            $where[] = $db->NameQuote('id').' = '.$productvid_id;
            $query = 'SELECT '.implode(',',$fields);
            $query .= ' FROM '.$table;
            $query .= ' WHERE '.implode(' AND ',$where);
            
            $db->setQuery($query);
            $state = array();
            $state[] = JHTML::_('select.option'
                    , 0
                    , JText::_('MOD_USEARCH_NOT_IMPORTANT')
            );
            if ($list = $db->LoadResult())
            {
                $list = explode(';', $list);
                foreach ($list as $row)
                {
                    $state[] = JHTML::_('select.option'
                            , $row
                            , JText::_($row)
                    );
                }
            }
            return JHTML::_('select.genericlist'
                            , $state
                            , 'usearch_data[razmer]'
                            , $attribs
                            , 'value'
                            , 'text'
                            , $selected
                            , 'mod_usearch_razmer'
                            , false );
         }
    
}