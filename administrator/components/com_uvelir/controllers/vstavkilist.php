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

jimport('joomla.application.component.controllerform');

/**
 * vstavkilist controller class.
 */
class UvelirControllerVstavkilist extends JControllerForm
{

    function __construct() {
        $this->view_list = 'vstavkilists';
        parent::__construct();
    }
    
    /**
     * Публикация
     */
    public function publish()
    {
        $cids = JRequest::getVar('cid',array(),'','array');
        $this->getModel()->publish($cids, 1);
        parent::display();
    }
   
    /**
     * Снятие с публикации 
     */
    public function unpublish()
    {
        $cids = JRequest::getVar('cid',array(),'','array');
        $this->getModel()->publish($cids, 0);
        parent::display();
    }

    /**
     * Снятие с публикации 
     */
    public function add_all()
    {
        $this->getModel()->add_all();
        parent::display();
    }

}