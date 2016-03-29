<?php
/**
 * @version     1.0.0
 * @package     com_uvelir
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */

defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/kmodellist.php'; 

/**
 * Methods supporting a list of Uvelir records.
 */
class UvelirModelMetallists extends UvelirModelKModelList
{

    protected $table_name = 'metallists';

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'name', 'a.name',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
            );
        }

        parent::__construct($config);
    }

    /**
     * Overload parent populateState function
     * @param type $ordering
     * @param type $direction 
     */
    protected function populateState($ordering = null, $direction = null) {
        
        $app = JFactory::getApplication();
        
        // Фильтр по группе вставок
        $group = $app->getUserStateFromRequest($this->context.'.filter.group', 'filter_group', '0', 'string');
        $this->setState('filter.group', $group);        
        
        parent::populateState($ordering, $direction);
    }
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
            $query = parent::getListQuery();
            $query->from('`#__uvelir_'.$this->table_name.'` AS a');
            
            $group = $this->getState('filter.group', 0);        
            $query->where('a.metal_id = '.(int) $group);

            // Filter by search in title
            $search = $this->getState('filter.search');
            if (!empty($search)) 
            {
                if (stripos($search, 'id:') === 0) 
                {
                    $query->where('a.id = '.(int) substr($search, 3));
                } 
                else 
                {
                    $search = $this->_db->Quote('%'.  $this->_db->escape($search, true).'%');
                    $query->where('( a.name LIKE '.$search.' )');
                }
            }
            return $query;
        }
        
        /**
         *Пакетное изменение группы вставок
         * @param type $pks 
         */
        public function change_groups(&$pks) 
        {
            $group = JRequest::getInt('change_group', 0);
            echo 'change_groups';var_dump($group);
            
            // Дефолтное значение для присоения группы
            $this->setState('change.group', $group);        
            
            foreach ($pks as $pk)
            {
                $query = "UPDATE `#__uvelir_metallists` SET  `metal_id` =  '$group' WHERE  `jos_uvelir_metallists`.`id` =$pk;";
                $this->_db->setQuery($query);
                if($this->_db->query())
                {
                    if(!$this->_update_product_metal_ids($pk, $group))
                    {
                        JError::raiseError('500', JText::_('COM_UVELIR_ERROR_UPDATE_PRODUCTS_METAL_ID'));
                    }
                }
            }
            return $group;
        }

        /**
         * Обновляем ИД материалов в таблице продуктов
         * @param type $metallist_id
         * @param type $metal_id 
         * @return bool
         */
        private function _update_product_metal_ids($metallist_id, $metal_id)
        {
            // Находим имя вставки, которое присвоено товарам при парсинге
            $table = $this->getTable('Metallist', 'UvelirTable');
            if(!$table->load($metallist_id))
            {
                return FALSE;
            }
            // Находим все записи в таблице продуктов у которфх имя вставки совпадает с найденным именем вставки
            $query = "UPDATE `#__uvelir_products` SET  `material_id` =  '$metal_id' WHERE  `material` = '$table->name'";
//            echo $query;exit;
            $this->_db->setQuery($query);
            return $this->_db->query();
        }
}
