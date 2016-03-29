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
require_once JPATH_ROOT.'/administrator/components/com_uvelir/helpers/component.php';

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
            // Завод
            if(isset($params_array['zavod'])){
                $this->setState('product.zavod', $params_array['zavod']);
            }
            else
            {
                $zavod = JRequest::getInt('zavod',0);
                $this->setState('product.zavod', $zavod);
            }
            // Продукт
            $alias = JRequest::getString('alias','');

            if ($alias) // если есть артикул, то сразу загружаем продукт
            {
//                $table = $this->getTable('Product_'.$zavod);
//                if($table->load(array('alias'=>$alias)))
//                {
                   $this->setState('product.id', $alias);
//                   $this->setState('product.id', $table->id);
//                }
            }
            elseif(isset($params_array['item_id'])){
                $this->setState('product.id', $params_array['item_id']);
            }
            else
            {
                $item_id = JRequest::getInt('item_id',0);
                $this->setState('product.id', $item_id);
            }
            
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
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id)) {
				$id = $this->getState('product.id');
			}

			// Get a level row instance.
			$table = $this->getTable('Product');

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
                        $params = JComponentHelper::getParams('com_uvelir');
                        $this->_item->logo_on_product = $params->get('logo_on_product');
                        
		}
                
		return $this->_item;
	}
    
	public function getTable($type = 'Product', $prefix = 'UvelirTable', $config = array())
	{   
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
        return JTable::getInstance($type, $prefix, $config);
	}     
 
        /**
         * Возвращаем пересчитанные средний вес и сумму изделия
         * @return array Средний вес и сумма
         */
        public function change_size()
        {
            $result = array(
                'average_weight' => 0,
                'cena_tut' => 0,
                'cena_mag' => 0,
                'count' => 0,
            );
            $cid = JRequest::getInt('cid', 0);
            if($cid)
            {
                $razmer_key = JRequest::getInt('razmer_key', 0);
                $table = $this->getTable('Product');
                if($table->load($cid))
                {
                    $average_weights = explode(',', $table->average_weight);
                    if(isset($average_weights[$razmer_key]))
                    {
                        $caddy = JFactory::getApplication()->getUserState('com_uvelir.caddy', array());
                        if(isset($caddy[$cid.'_'.$razmer_key]))
                        {
                            $result['count'] = $caddy[$cid.'_'.$razmer_key]['count'];
                        }
                        $result['average_weight'] = $average_weights[$razmer_key];
                        $prises = ComponentHelper::getPrices($cid, $razmer_key);
                        $result['cena_tut'] = $prises['cena_tut'];
                        $result['cena_mag'] = $prises['cena_mag'];
                    }
                }
            }
            
            return $result;
        }
        public function get_pathways($item)
        {
            $parent_name_ids = $this->getTable('Category')->get_parent_name_ids($item->category_id);
            $pathways = array();
            foreach ($parent_name_ids as $parent_name_id)
            {
                $pathways[] = array(
                    'name'=>$parent_name_id['name'],
                    'link'=>JURI::base().'index.php?option=com_uvelir&view=category&id='.$parent_name_id['id'],
                );
            }
            return $pathways;
        }
}