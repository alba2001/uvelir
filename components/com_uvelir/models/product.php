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

/**
 * Uvelir model.
 */
class UvelirModelProduct extends JModel
{
    
    var $_item = null;
    
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
            $app = JFactory::getApplication('com_uvelir');
            // Load the parameters.
            $params = $app->getParams();
            $params_array = $params->toArray();
            if(isset($params_array['item_id'])){
                $this->setState('product.id', $params_array['item_id']);
            }
            else
            {
                $item_id = JRequest::getInt('item_id',0);
                $this->setState('product.id', $item_id);
            }
            
            if(isset($params_array['zavod'])){
                $this->setState('product.zavod', $params_array['zavod']);
            }
            else
            {
                $zavod = JRequest::getInt('zavod',0);
                $this->setState('product.zavod', $zavod);
            }
//            var_dump($params_array);
            $this->setState('params', $params);

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
            $zavod = $this->getState('product.zavod');
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id)) {
				$id = $this->getState('product.id');
			}

			// Get a level row instance.
			$table = $this->getTable('Product_'.$zavod);

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if ($table->state != $published) {
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties = $table->getProperties(1);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
			} elseif ($error = $table->getError()) {
				$this->setError($error);
			}
		}
                $this->_item->zavod = $zavod;
                
		return $this->_item;
	}
    
	public function getTable($type = 'Product', $prefix = 'UvelirTable', $config = array())
	{   
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
        return JTable::getInstance($type, $prefix, $config);
	}     
 
}