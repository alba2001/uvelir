<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
/**
 * Order Controller
 */
class UvelirControllerCategory_new extends JControllerForm
{
    function __construct() {
        $this->view_list = 'categories_new';
        parent::__construct();
    }

}
