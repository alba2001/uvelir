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

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class UvelirViewCaddy extends JView {

    protected $items;
    protected $caddy_data;
    protected $zakaz;


    /**
     * Display the view
     */
    public function display($tpl = null) {
        
        $this->action = JRequest::getString('action', 'step1');
        if($this->action == 'step4') // Checkout
        {
            $mainframe = JFactory::getApplication();
            $mainframe->setUserState('com_uvelir.old_uri',JUri::base().'zavershenie-zakaza');
            // Проверяем пользователя
            $this->user = $this->get('User');
    //        var_dump($this->user);exit;
            if(!$this->user->id)
            {
                // Redirect to login
                $url = JURI::base().'/lichnye-dannye';
                $mainframe->redirect($url, JText::_('You must login first'));

            }
            
        }
        
        $this->items = $this->get('Items');
        $model = $this->getModel();
        $caddy = JFactory::getApplication()->getUserState('com_uvelir.caddy', array());
        $this->zakaz = JFactory::getApplication()->getUserState('com_uvelir.zakaz', array());
        $this->caddy_data = $model->get_caddy_data($caddy);

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
        $this->_prepareDocument();

        parent::display($tpl);
    }


	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
                $doc = JFactory::getDocument();
		$doc->setTitle(JText::_('COM_UVELIR_CADDY'));
                $doc->addScript(JURI::root()."components/com_uvelir/assets/js/caddy.js");
	}
   
}
