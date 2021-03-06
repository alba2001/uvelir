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
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/component.php';
/**
 * View to edit
 */
class UvelirViewProduct extends JView {

    protected $state;
    protected $item;
    protected $_model;


    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->_model = $this->getModel();
        $this->item = $this->get('Item');
        $this->caddy = JFactory::getApplication()->getUserState('com_uvelir.caddy', array());

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
        $this->_prepareDocument();
        $this->_set_pathway();
        parent::display($tpl);
    }
    private function _set_pathway()
    {
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();
        if(!$pathway->getPathWay())
        {
            $ar_pathways = $this->_model->get_pathways($this->item);
            foreach($ar_pathways as $ar_pathway)
            {
                $pathway->addItem($ar_pathway['name'], $ar_pathway['link']);
            }
        }
        $pathway->addItem($this->item->name, '');
    }

        /**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
                $doc = JFactory::getDocument();
		$doc->setTitle($this->item->name);
                $doc->addStyleSheet(JURI::root()."components/com_uvelir/assets/jquery.fancybox.css");
                $doc->addScript(JURI::root()."components/com_uvelir/assets/js/jquery.fancybox.js");
                $doc->addScript(JURI::root()."components/com_uvelir/assets/js/number.min.js");
                $doc->addScript(JURI::root()."components/com_uvelir/assets/js/caddy.js");
	}
   
}
