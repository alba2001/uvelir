<?php

/**
 * @version     1.0.0
 * @package     com_uvelir
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Uvelir records.
 */
class UvelirModelProducts extends JModelList {

    /**
     * Массив групп изделий при которых показывается меню групп изделий
     * @var array 
     */
    private $_ar_groups_shown;

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        // 
        $this->_ar_groups_shown = array('','0','1','2');
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        
        // Показываем меню продуктов или нет
        $show_menu_groups = JRequest::getInt('show_menu_groups', TRUE);
        $group = 0;
        if($show_menu_groups)
        {
            // Вычисляем группу продуктов
            $menu = JSite::getMenu();
            $active = $menu->getActive();
            $params = isset($active)?$active->params:NULL;
            $product_group = isset($params)?$params->get('product_group'):0;
            $group = JRequest::getInt('product_group',
                    $product_group);
            
        }
        $this->setState('products_group', $group);
        $this->setState('show_menu_groups', $show_menu_groups);
        
        // Если это не показ пунктов главного меню, то включаем фильтр
        if(!$show_menu_groups)
        {
            // Обработка данных модуля фильтрации 
            $usearch_data = JRequest::getVar('usearch_data', 
                    JFactory::getApplication()->getUserState('com_uvelir.usearch', array()),
                    '','array');
            if($usearch_data)
            {
                if(!isset($usearch_data['available']))
                {
                    $usearch_data['available'] = 0;
                }
                $this->setState('usearch_data.izdelie', $usearch_data['izdelie']);
                $this->setState('usearch_data.metal', $usearch_data['metal']);
                $this->setState('usearch_data.vstavki', $usearch_data['vstavki']);
                $this->setState('usearch_data.razmer', $usearch_data['razmer']);
                $this->setState('usearch_data.proba', $usearch_data['proba']);
                $this->setState('usearch_data.cost_1', $usearch_data['cost_1']);
                $this->setState('usearch_data.cost_2', $usearch_data['cost_2']);
                $this->setState('usearch_data.available', $usearch_data['available']);
                $group = 0;
            }
            JFactory::getApplication()->setUserState('com_uvelir.usearch', $usearch_data);
        }
        
        
        
        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);
        
        
        if(empty($ordering)) {
                $ordering = 'a.ordering';
        }
        
        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
     /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState('list.select', 'a.*')
        );
        
        $query->from('`#__uvelir_products` AS a');
        $query->where('`a`.`state` = 1');

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
                if (stripos($search, 'id:') === 0) {
                        $query->where('a.id = '.(int) substr($search, 3));
                } else {
                        $search = $db->Quote('%'.$db->escape($search, true).'%');
        $query->where('( a.name LIKE '.$search.' )');
                }
        }
        
        // Обрабртка данных модуля фильтра 
            // Фильтр по виду изделия
            if($productvid_id = $this->getState('usearch_data.izdelie', 0))
            {
                $category_ids = $this->_get_product_vid_categories($productvid_id);
                if($category_ids)
                {
//                        var_dump($category_ids);
                    $query->where('category_id IN ('.  implode(', ', $category_ids).')');
                }
            }
            // Фильтр по наличию
            if($metal = $this->getState('usearch_data.available', ''))
            {
                $query->where('available = 1');
            }
            // Фильтр по металлу
            if($metal = $this->getState('usearch_data.metal', ''))
            {
                $query->where('material_id = "'.$metal.'"');
            }
            // Фильтр по вставкам
            if($vstavki = $this->getState('usearch_data.vstavki', ''))
            {
                $query->where('vstavki_id = "'.$vstavki.'"');
            }
            // Фильтр по размеру
            if($razmer = $this->getState('usearch_data.razmer', ''))
            {
                $query->where('razmer LIKE "%'.$razmer.'%"');
            }
            // Фильтр по пробе
            if($proba = $this->getState('usearch_data.proba', ''))
            {
                $query->where('proba = "'.$proba.'"');
            }
            // Фильтр по цене
            if($cost_1 = (int)$this->getState('usearch_data.cost_1', ''))
            {
                $query->where('cena_tut >= "'.$cost_1.'"');
            }
            if($cost_2 = (int)$this->getState('usearch_data.cost_2', ''))
            {
                $query->where('cena_tut <= "'.$cost_2.'"');
            }
            // Если установлена вторая цена в поиске, а первая или 0 или не
            // установлена, то не включаем товары с нулевой стоимостью
            if($cost_2 AND !$cost_1)
            {
                $query->where('cena_tut >= "0.01"');
                
            }
            // Фильтр по группам товаров
            $group_flt = $this->_group_flt();
            if($group_flt)
            {
                $query->where($group_flt);
            }
//            var_dump((string)$query);
        return $query;
    }
    /**
     * Заглавие страницы 
     * @return string 
     */
    public function getTitle()
    {
        $group = (int) $this->getState('products_group');
        switch ($group)
        {
            case 1:
                $title = JText::_('COM_UVELIR_PRODUCT_NEW');
                break;
            case 2:
                $title = JText::_('COM_UVELIR_PRODUCT_SPETS');
                break;
            default :
                $title = JText::_('COM_UVELIR_PRODUCT_ALL');
        }
        return $title;
    }
    /**
     * Фильтр по группам изделий
     * @return string 
     */
    private function _group_flt()
    {
        $group = (int) $this->getState('products_group');
        switch ($group)
        {
            case 1: // Новинки
                $where = '`novinka_dt` > "'.date('Y-m-d').'"';
                break;
            case 2: // Спецпредложения
                $where = '`spets_predl` = "1"';
                break;
            case 3: // В наличии
                $where = '`available` = "1"';
                break;
            default : // Все изделия
                $where = '';
        }
        return $where;
    }
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Products', $prefix = 'UvelirTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

        /**
         *  Проверка принадлежит ли изделие к кольцам?
         * ID вида продукта = 1
         * @return boolean
         */
        public function isKoltsa($product_id)
        {
            $list_koltsa_categories = $this->_get_product_vid_categories('1');
            $query = &$this->_db->getQuery(true);
            $query->select('category_id');
            $query->from('#__uvelir_products');
            $query->where('id = '.$product_id);
            $this->_db->setQuery($query);
            $category_id = $this->_db->loadResult();
            
            return in_array($category_id, $list_koltsa_categories);
        }

        /**
         * Получение списка категорий от вида продукта
         * @param type $productvid_id
         * @return type 
         */
        private function _get_product_vid_categories($productvid_id)
        {
            $category_ids = array();
            $product_vid = &$this->getTable('Productvid');
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
}
