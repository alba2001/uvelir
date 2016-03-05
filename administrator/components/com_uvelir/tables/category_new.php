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
require_once dirname(__FILE__) . '/ntable.php'; 

/**
 * product Table class
 */
class UvelirTableCategory_new extends UvelirTableNtable {

    protected $asset_name;

    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(&$db) {
        $this->asset_name = 'category_new';
        parent::__construct('#__uvelir_categories_new', 'id', $db);
        
    }
    public function bind($array, $ignore = '') {
        $array['alias'] = JApplication::stringURLSafe($array['name']);
        list($array['level'],$array['path']) = $this->_get_new_level_path($array['parent_id'],$array['alias']);
        $this->setLocation( $array['parent_id'], 'last-child' );
        return parent::bind($array, $ignore);
    }
    private function _get_new_level_path($id,$alias)
    {
        $data = array(1,$alias);
        $queri = $this->_db->getQuery(TRUE)
                ->select('level','path')
                ->from($this->_tbl)
                ->where('`id` = '.$id);
        $this->_db->setQuery($queri);
        if($row = $this->_db->loadObject())
        {
            $data = array($row->level+1,$row->path?$row->path.'/'.$alias:$alias);
        }
        return $data;
    }
    
}
