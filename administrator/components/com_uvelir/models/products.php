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
        $zavod = $app->getUserStateFromRequest($this->context.'.filter.zavod', 'filter_zavod', '', 'string');
        $this->setState('filter.zavod', $zavod);        
        
        // Фильтр по категории
        $category = $app->getUserStateFromRequest($this->context.'.filter.category', 'filter_category', '0', 'string');
        $this->setState('filter.category', $category);        

        // Поиск по артикулу
        $search = $app->getUserStateFromRequest($this->context.'.filter.search_artikul', 'filter_search_artikul');
        $this->setState('filter.search_artikul', $search);
        
        // Фильтр по новой категории
        $category_new = $app->getUserStateFromRequest($this->context.'.filter.category_new_flt', 'category_new_flt', '0', 'integer');
        $this->setState('filter.category_new', $category_new);        
        $app->setUserState($this->context.'.filter.category_new_flt', $category_new);
        
        // Фильтр по изделию и металлу
        $usearch_data = $app->getUserStateFromRequest($this->context.'.filter.usearch', 'usearch_data', array(), 'array');
        $this->setState('filter.metal', $usearch_data['metal']?$usearch_data['metal']:'');
        $this->setState('filter.izdelie', $usearch_data['izdelie']?$usearch_data['izdelie']:'');
        $app->setUserState($this->context.'.filter.usearch', $usearch_data);
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
            $this->_check_zavods();
            
            $query = parent::getListQuery();
            
            $query->from('`#__uvelir_products` AS a');
            
            // Фильтр по заводу
            $zavod = $this->getState('filter.zavod', '');
            if($zavod)
            {
                $query->where('zavod_id = '.$zavod);
            }

            
            // Фильтр по новой категории
            $category_new = $this->getState('filter.category_new', '0');
            if($category_new)
            {
                $query->join('INNER', '`jos_uvelir_products_categories` pc ON pc.product_id = a.id');
                $query->where('pc.category_id = '.$category_new);
                $this->setState('filter.category', '0');
            }
            // Фильтр по категории
            $category = $this->getState('filter.category', '0');
            if($category)
            {
                $query->where('a.category_id = '.$category);
            }
            // Фильтр по изделию и металлу
            $usearch_data = $this->getState('filter.usearch');
            // Фильтр по металлу
            if($metal = $this->getState('filter.metal', ''))
            {
                $query->where('material_id = "'.$metal.'"');
            }
            if($izdelie = $this->getState('filter.izdelie', ''))
            {
                $category_ids = $this->_get_product_vid_categories($izdelie);
                if($category_ids)
                {
                    $query->where('a.category_id IN ('.  implode(', ', $category_ids).')');
                }
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
                $result = (int)$this->_availabled($cid, $value, 'available') * $result;
            }
            return $result;
        }
        /**
         * Меняем статус наличия товара
         * @param array $cids
         * @param int $value
         * @return array
         */
        public function show_logo($cids, $value)
        {
            $result = 1;
            foreach($cids as $cid)
            {
                $result = (int)$this->_availabled($cid, $value, 'show_logo') * $result;
            }
            return $result;
        }
        
       
        /**
         * Установка наличия товара
         * @param int $cid
         * @param int $value
         * @return bolean
         */
        private function _availabled($cid, $value, $field)
        {
            $table = $this->getTable('Product','UvelirTable');
            if($table->load($cid))
            {
                $table->$field = $value;
                if($table->store())
                {
                    return TRUE;
                }
            }
            return FALSE;
        }
        
        public function fill_cenas()
        {
            require_once JPATH_ROOT.'/administrator/components/com_uvelir/helpers/component.php';
            $query = $this->_db->getQuery(TRUE);
            $query->select('id');
            $query->from('#__uvelir_products');
            $this->_db->setQuery($query);
            $keys = $this->_db->loadResultArray();
            foreach($keys as $key)
            {
                $prises = ComponentHelper::getPrices($key);
                //Временная заглушка не переписывать цену, если она уже прописана
                $query = 'SELECT cena_tut from #__uvelir_products WHERE id='.$key;
                $this->_db->setQuery($query);
                $sum_in_table = $this->_db->loadResult();
                if(!(int)$sum_in_table)
                {
                    $query = 'UPDATE  `#__uvelir_products` SET  `cena_mag` =  '.$prises['cena_mag'].
                            ', `cena_tut` ='.$prises['cena_tut'].
                            ' WHERE  `id` ='.$key;
                    $this->_db->setQuery($query);
                    $this->_db->query();
                }
            }
            return TRUE;
        }
        
        /**
         * Заплатка проверки завода у продукта 
         */
        private function _check_zavods()
        {
            $query = 'SELECT c.zavod , c.name, p.*';
            $query .= ' FROM jos_uvelir_categories c, jos_uvelir_products p';
            $query .= ' WHERE c.id = p.category_id';
            $query .= ' AND p.zavod_id = 0';
            
            $this->_db->setQuery($query);
            $products = $this->_db->loadObjectList();
            
            foreach($products as $product)
            {
                $query = 'UPDATE  `#__uvelir_products`';
                $query .= ' SET  `zavod_id` =  "'.$product->zavod.'"';
                $query .= ' WHERE  `id` = '.$product->id;
                $this->_db->setQuery($query);
                $this->_db->query();
            }
        }
        public function category_change($cid,$category_id)
        {
            foreach ($cid as $product_id)
            {
                $this->_db->setQuery('DELETE FROM `jos_uvelir_products_categories` WHERE `product_id` = '.$product_id);
                if($this->_db->query())
                {
                    $query = 'INSERT INTO `jos_uvelir_products_categories` (`product_id`, `category_id`)'
                        .' VALUES ('.$product_id.','.$category_id.')';
                    $this->_db->setQuery($query);
                    $this->_db->query();
                }
            }
        }
        /*=================FIND IZDELIE====================*/
        /**
         * Получение списка категорий от вида продукта
         * @param type $productvid_id
         * @return type 
         */
        private function _get_product_vid_categories($productvid_id)
        {
            $category_ids = array();
            $product_vid = $this->getTable('Productvid','UvelirTable');
            if($product_vid->load($productvid_id))
            {
                $category_ids = $this->_get_categiry_ids($product_vid->alias);
            }
            
            return $category_ids;
        }

        /**
         * Находим категории и их подкатегории
         * с наименованием совпадающим с псевдонимом вида изделия
         * @param string $alias
         * @return array
         */
        private function _get_categiry_ids($alias)
        {
            $_query = &$this->_db->getQuery(true);
            $_query->select('id');
            $_query->from('#__uvelir_categories');
            $_query->where('`alias` LIKE "%'.$alias.'%"');
            $this->_db->setQuery($_query);
            $ar_parents = $this->_db->loadResultArray();
            $ar_childrens = $this->_get_childrens($ar_parents);
            
//            var_dump((string)$_query);
            return array_merge($ar_parents, $ar_childrens);
        }
        
        /**
         * Находим подкатегории списка категорий
         * @param array $ar_parents
         * @return array
         */
        private function _get_childrens($ar_parents)
        {
            $_query = &$this->_db->getQuery(true);
            $_query->select('id');
            $_query->from('#__uvelir_categories');
            $_query->where('`parent_id` IN ('.  implode(',', $ar_parents).')');
            $this->_db->setQuery($_query);
            $ar_children = $this->_db->loadResultArray();
            return $ar_children;
            
        }
        /*===============/FIND IZDELIE======================*/
}
