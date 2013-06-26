<?php
/**
 * @version     1.0.0
 * @package     com_uvelir
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Uvelir model.
 */
class UvelirModelProduct extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_UVELIR';


	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = '', $prefix = 'UvelirTable', $config = array())
	{
            if(!$type)
            {
                $type = 'Product';
            }
            return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_uvelir.product', 'product', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_uvelir.edit.product.data', array());

		if (empty($data)) {
			$data = $this->getItem();
            
		}

		return $data;
	}

        public function save_product($data)
        {
            $table = $this->getTable('Product');
            $table->load(array('artikul'=>$data['artikul']));
            return $table->save($data);
        }
        
        /**
         * Overload parent save method
         * @param type $data 
         */
        public function save($data) 
        {
            // Вставляем ИД завода
            if($data['category_id'] AND !isset($data['zavod_id']))
            {
                $data['zavod_id'] = $this->_find_zavod_id($data['category_id']);
            }
            
            // Вставляем дату создания
            if(!$data['created_dt'])
            {
                $data['created_dt'] = date('Y-m-d');
            }
            
            // Вставляем рисунки
            if(isset($data['product_image']) AND $data['product_image'])
            {
                $uri_base = str_replace('administrator/', '',  JURI::base());
                $img_src = $uri_base.$data['product_image'];
                $desc = array(
                        'img_medium'=>$img_src,
                        'img_large'=>$img_src,
                        'img_small'=>$img_src,
                    );
                $data['desc'] = json_encode($desc);
                unset($data['product_image']);
            }
            return parent::save($data);
        }
        
        /**
         * Находим ИД завода по ИД категории
         * @param type $category_id
         * @return int 
         */
        private function _find_zavod_id($category_id)
        {
            $table = $this->getTable('category');
            if($table->load($category_id))
            {
                return $table->zavod;
            }
            return 0;
        }
}