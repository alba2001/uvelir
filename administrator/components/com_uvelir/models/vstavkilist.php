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
require_once dirname(__FILE__) . '/category.php'; 
/**
 * Uvelir model.
 */
class UvelirModelVstavkilist extends JModelAdmin
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
	public function getTable($type = 'Vstavkilist', $prefix = 'UvelirTable', $config = array())
	{
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

            // Get the form.
            $form = $this->loadForm('com_uvelir.vstavkilist', 'vstavkilist', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_uvelir.edit.vstavkilist.data', array());

		if (empty($data)) 
                {
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {

			//Do any procesing on fields here if needed

		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM vstavkilist');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}
		}
	}
        
        public function add_all()
        {
            // Create a new query object.
            $query = 'SELECT DISTINCT `vstavki` ';
            $query .= ' FROM `#__uvelir_products`';
            $query .= ' ORDER BY `vstavki`';
            $this->_db->setQuery($query);
            $vstavki_names = $this->_db->loadResultArray();
            $table = $this->getTable();
            foreach ($vstavki_names as $vstavki_name)
            {
                $data = array('name'=>$vstavki_name);
                if(!$table->load($data))
                {
                    $query = "INSERT INTO  `derevo72_uvelir`.`jos_uvelir_vstavkilists` (
                        `id` ,
                        `vstavki_id` ,
                        `name` ,
                        `ordering` ,
                        `state` ,
                        `checked_out` ,
                        `checked_out_time` ,
                        `created_by`
                        )
                        VALUES (
                        NULL ,  '',  '$vstavki_name',  '',  '1',  '',  '0000-00-00 00:00:00',  ''
                        );
                    ";
                    $this->_db->setQuery($query);
                    $this->_db->query();
                }
            }
            return TRUE;
        }
}