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
class UvelirModelCategories_new extends UvelirModelKModelList
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
                'parent_id', 'a.parent_id',
                'name', 'a.name',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',

            );
        }

        parent::__construct($config);
    }
    
    public function getTable($name = 'Category_new', $prefix = 'UvelirTable', $options = array()) {
        return parent::getTable($name, $prefix, $options);
    }
    
    protected function populateState() 
    {
        $app = JFactory::getApplication();
        // Устанавливаем наименование контекста
        $app->setUserState('com_uvelir.this_context', $this->context);
        // Load the filter state.
        $parent_id = $app->getUserStateFromRequest($this->context.'.filter.parent_id', 'flt_parent_id', '0', 'integer');
        $this->setState('filter.parent_id', $parent_id);        
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
            $query->from('`#__uvelir_categories_new` AS a');
            // Фильтр по родительской категории
            $parent_id = $this->getState('filter.parent_id', '0');
            if($parent_id)
            {
                list($lft,$rgt) = $this->getTable()->get_shoulders($parent_id);
                $query->where('lft >= '.$lft.' AND rgt <= '.$rgt);
            }
            
            // Filter by search in title
            $search = $this->getState('filter.search');
            if (!empty($search)) 
            {
                $search = $this->_db->Quote('%'.$this->_db->escape($search, true).'%');
                $query->where('( a.name LIKE '.$search.' )');
            }
//            var_dump((string)$query);
            return $query;
        }
}
