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
 * product Table class
 */
class UvelirTableProductvid extends UvelirTableKtable {

    protected $asset_name;

    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(&$db) {
        $this->asset_name = 'productvid';
        $this->_check_fields = array('title');
        parent::__construct('#__uvelir_productvids', 'id', $db);
    }
    
    
    public function store($updateNulls = false) {
        // Переписываем псевдоним
        if($this->title)
        {
            $this->alias = JApplication::stringURLSafe($this->title);
        }
        return parent::store($updateNulls);
//        var_dump($this);
    }
}
