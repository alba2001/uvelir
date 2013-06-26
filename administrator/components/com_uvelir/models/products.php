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
class UvelirModelProducts extends UvelirModelKModelList
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
                'artikul', 'a.artikul',

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

        // Load the filter state.
        $app = JFactory::getApplication();

        // Устанавливаем наименование контекста
        $app->setUserState('com_uvelir.this_context', $this->context);
        
        // Фильтр по заводу
        $zavod = $app->getUserStateFromRequest($this->context.'.filter.zavod', 'filter_zavod', '2', 'string');
        $this->setState('filter.zavod', $zavod);        
        
        // Фильтр по категории
        $category = $app->getUserStateFromRequest($this->context.'.filter.category', 'filter_category', '0', 'string');
        $this->setState('filter.category', $category);        

        // Поиск по артикулу
        $search = $app->getUserStateFromRequest($this->context.'.filter.search_artikul', 'filter_search_artikul');
        $this->setState('filter.search_artikul', $search);

//        // Поле сортировки
//        $order_field = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);
////        $ordering = $this->getState('list.ordering');
//        var_dump($order_field);
//        
//        // Направление сортировки
//        $sort_dir = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', 'ASC');
//        var_dump($sort_dir);
        
        
        parent::populateState($ordering, $direction);
    }

    /**
     * Overload parent getStoreId function
     * @param type $id
     * @return type 
     */
    protected function getStoreId($id = '') {
        $id .= parent::getStoreId($id);
        $id.= ':' . $this->getState('filter.zavod');

        return parent::getStoreId($id);
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
            
            $query->from('`#__uvelir_products` AS a');
            
            // Фильтр по заводу
            $zavod = $this->getState('filter.zavod', '2');
            $query->where('zavod_id = '.$zavod);

            // Фильтр по категории
            $category = $this->getState('filter.category', '0');
            if($category)
            {
                $query->where('category_id = '.$category);
            }

            // Filter by search in title
            $search = $this->getState('filter.search');
            $search_artikul = $this->getState('filter.search_artikul');
            if (!empty($search_artikul)) 
            {
                $search_artikul = $this->_db->Quote('%'.$this->_db->escape($search_artikul, true).'%');
                $query->where('( a.artikul LIKE '.$search_artikul.' )');
            }
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
         * Меняем статус наличия товара
         * @param array $cids
         * @param int $value
         * @return array
         */
        public function set_available($cids, $value)
        {
            $result = 1;
            foreach($cids as $cid)
            {
                $result = (int)$this->_availabled($cid, $value) * $result;
            }
            return $result;
        }
        
       
        /**
         * Установка наличия товара
         * @param int $cid
         * @param int $value
         * @return bolean
         */
        private function _availabled($cid, $value)
        {
            $table = $this->getTable('Product','UvelirTable');
            if($table->load($cid))
            {
                $table->available = $value;
                if($table->store())
                {
                    return TRUE;
                }
            }
            return FALSE;
        }
        
}
