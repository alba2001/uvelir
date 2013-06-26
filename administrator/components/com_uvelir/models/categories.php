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
class UvelirModelCategories extends UvelirModelKModelList
{

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
    
    protected function populateState($ordering = null, $direction = null) {
        
        $app = JFactory::getApplication();
        
        // Устанавливаем наименование контекста
        $app->setUserState('com_uvelir.this_context', $this->context);
        
        // Load the filter state.
        $zavod = $app->getUserStateFromRequest($this->context.'.filter.zavod', 'filter_zavod', '2', 'string');
        $this->setState('filter.zavod', $zavod);        

        
        parent::populateState('lft', 'asc');
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
            
            $query->from('`#__uvelir_categories` AS a');
            
            // Фильтр по заводу
            $zavod = $this->getState('filter.zavod', '2');
            $query->where('zavod = '.$zavod);
            
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
                    $search = $this->_db->Quote('%'.$this->_db->escape($search, true).'%');
                    $query->where('( a.name LIKE '.$search.' )');
                }
            }
//            var_dump((string)$query);
            return $query;
        }
        
        /**
         * Возвращаем наименование типа продукта
         * @param int $producttype_id 
         * @return string 
         */
        public function get_producttype_id($producttype_id) 
        {
            $name = '';
            $table = $this->getTable('Producttype', 'UvelirTable');
            if($table->load($producttype_id))
            {
                $name = $table->name;
            }
            return $name;
        }
}
