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

jimport('joomla.application.component.model');
require_once dirname(__FILE__) . '/modelhelper.php';  

/**
 * Uvelir model.
 */
class UvelirModelOrder extends JModel
{
    
    var $_item = null;
    var $_user = null;
    
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
            // Заказ
            $this->setState('order.id', JRequest::getInt('id',JRequest::getInt('order_id',0)));
	}
        

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getItem($id = null)
	{
            $user = $this->getUser();
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id)) {
				$id = $this->getState('order.id');
			}

			// Get a level row instance.
			$table = $this->getTable('Order');

			// Attempt to load the row.
			if (!$table->load(array('id'=>$id,'userid'=>$user->id)))
			{
                            $this->setError($table->getError());
			}
                        else
                        {
                            $this->_item =& $table;
                        }
		}
                
//            var_dump(array('id'=>$id,'userid'=>$user->id), $table->getError(), $this->_item);exit;
		return $this->_item;
	}
    
	public function getTable($type = 'Order', $prefix = 'UvelirTable', $config = array())
	{   
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
        return JTable::getInstance($type, $prefix, $config);
	}     
        
        /**
         * Возвращаем строку таблицы по ее ИД
         * @param type $id
         * @return boolean 
         */
        public function get_row($table_name, $id)
        {
            $table = $this->getTable($table_name);
            if($table->load($id))
            {
//            var_dump($table->name);exit;
                return $table;
            }
            return FALSE;
        }
        

        /**
	 * Get the user
	 * @return object The message to be displayed to the user
	 */
	public function getUser() 
	{
            if ($this->_user === null)
            {
                $this->_user = ModelHelper::getUser();
            }
            return $this->_user;
	}

}