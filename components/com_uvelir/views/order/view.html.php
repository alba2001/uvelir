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
class UvelirViewOrder extends JView {

    protected $items;
    protected $total_sum=0;
    protected $model;
    protected $sposob_oplaty;
    protected $sposob_dostavki;
    protected $products;


    /**
     * Display the view
     */
    public function display($tpl = null) {

        // Проверяем пользователя
        $this->user = $this->get('User');
        if(!$this->user->id)
        {
            $mainframe = JFactory::getApplication();
            // Redirect to login
            $url = JRoute::_(JURI::base().'index.php');
            $mainframe->redirect($url, JText::_('You must login first'));

        }
        
        $this->model = $this->getModel();
        $this->item = $this->get('Item');
        
        
        // Способ оплаты
        $oplata = $this->model->get_row('Oplata', $this->item->oplata_id);
        $this->sposob_oplaty = $oplata?$oplata->name:'';
        
        // Способ доставки
        $dostavka = $this->model->get_row('Dostavka', $this->item->dostavka_id);
        $this->sposob_dostavki = $dostavka?$dostavka->name:'';
        
        // Список товаров
        $products = json_decode($this->item->caddy,TRUE);
        $this->products = array();
        foreach($products as $key=>$value)
        {
            $product = $this->model->get_row('Product', $key);
            $desc = json_decode($product->desc);
            $this->products[] = array(
                'id'=>$product->id,
                'zavod_id'=>$product->zavod_id,
                'name'=>$product->name,
                'artikul'=>$product->artikul,
                'img_src'=>$desc->img_small,
                'zavod_name'=>$this->model->get_row('Zavod', $product->zavod_id)->name,
                'price'=>$product->cena_tut,
                'count' => $value['count'],
                'sum' => $value['sum'],
            );
            $this->total_sum += $value['sum'];
        }

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
		$doc->setTitle(JText::_('COM_UVELIR_ORDER'));
	}
   
}
