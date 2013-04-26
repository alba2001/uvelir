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
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
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
    protected function getListQuery() {
        
        $zavod = JRequest::getInt('zavod',2);
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState('list.select', 'a.*')
        );
        
        $query->from('`#__uvelir_products_'.$zavod.'` AS a');
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
        
        return $query;
    }

}
