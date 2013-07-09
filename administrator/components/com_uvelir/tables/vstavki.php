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
require_once dirname(__FILE__) . '/ktable.php'; 
/**
 * city Table class
 */
class UvelirTableVstavki extends UvelirTableKtable {

    protected $asset_name;

    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(&$db) {
        $this->asset_name = 'vstavki';
        parent::__construct('#__uvelir_vstavkis', 'id', $db);
    }
    

    /**
     * Override parent store function
     * @param bool $updateNulls
     * @return bool
     */
    public function store($updateNulls = false) {
        if(is_array($this->vstavki_list))
        {
            $this->vstavki_list = json_encode($this->vstavki_list);
        }
        return parent::store($updateNulls);
    }
    
    /**
     * Override parent save function
     * @param type $keys
     * @param type $reset
     * @return boolean 
     */
    public function load($keys = null, $reset = true) {
        if(!parent::load($keys, $reset))
        {
            return FALSE;
        }
        if(!is_array($this->vstavki_list))
        {
            $this->vstavki_list = json_decode($this->vstavki_list);
        }
        return TRUE;
    }
}
