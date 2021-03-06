<?php

/**
 * @version     1.0.0
 * @package     com_uvelir
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */
// No direct access
defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/ntable.php'; 

/**
 * product Table class
 */
class UvelirTableCategory extends UvelirTableNtable {

    protected $asset_name;

    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(&$db) {
        $this->asset_name = 'category';
        parent::__construct('#__uvelir_categories', 'id', $db);
        
    }
	/**
	 * Override check function
	 *
	 * @return  boolean
	 *
	 * @see     JTable::check
	 * @since   11.1
	 */
	public function check_()
	{
		return parent::check();
	}
 
	/**
	 * Override save function
	 *
	 * @return  boolean
	 *
	 */
        public function save($src, $orderingFilter = '', $ignore = '') 
        {
            if(!parent::save($src, $orderingFilter, $ignore))
            {
                
                return FALSE;
            }
            // Если это парсинг
            if(isset($src['level']))
            {
                // Создаем пункт меню с этой категорией
                $menu = &$this->getTable('menu');
                // Если еще не создан пункт меню - создаем, если создан - переписываем алиас и путь
                $menu_parent_id = JFactory::getApplication()->getUserState('com_uvelir.menu_parent_id', 1);
                // Nesteed tree
                $menu->setLocation( $menu_parent_id, 'last-child' );
                $component = JTable::getInstance('extension');
                $component->load(array('name'=>'com_uvelir'));
                $data = array(
                            'id'=>$this->_find_menu_id($this->path),
                            'title'=>$this->name,
                            'alias'=>$this->alias,
                            'path'=>$this->path,
                            'menutype' => 'com_uvelir',
                            'link' => 'index.php?option=com_uvelir&view=category',
                            'type' => 'component',
                            'component_id' => $component->extension_id,
                            'published' => '1',
                            'parent_id' => $menu_parent_id,
                            'level' => $this->level,
                            'access' => '1',
                            );
                // Convert to the JObject before adding the params.
                $properties = $menu->getProperties(1);
                $result = JArrayHelper::toObject($properties, 'JObject');
                // Convert the params field to an array.
                $registry = new JRegistry;
                $registry->loadString($menu->params);
                $result->params = $registry->toArray();
                $result->params = array_merge($result->params, array('item_id'=>$this->id));
                $data['params'] = json_encode($result->params);
                if(!$menu->save($data))
                {
                    JFactory::getApplication()
                            ->enqueueMessage(JText::_('COM_UVELIR_ERROR_EDIT_MENU_RECORD'), 'error');
                    return FALSE;
                }
                // Обновляем путь, т.к. он подставляется не правильно
//                $query = 'UPDATE  `#__menu` SET  `path` =  "'.$this->path.'" , `level` =  "'.$src['level'].'" , `parent_id` =  "'.$menu_parent_id.'" WHERE  `id` ='.$menu->id;
//                $this->_db->setQuery($query);
//                $this->_db->query();
            }
            return TRUE;
        }
        
        /**
         * Находим ИД пункта меню по его алиасу
         * @param string $alias
         * @return int 
         */
        private function _find_menu_id($path)
        {
            $menu = $this->getTable('menu');
            if($menu->load(array('path'=> $path)))
            {
                return $menu->id;
            }
            return 0;
        }

        /**
	 * Override delete function
	 *
	 * @return  boolean
	 *
	 */
        
        public function delete_($pk = null) {
            if (!parent::delete($pk))
            {
                return FALSE;
            }
            // Удаляем соотв запись в меню
            if($this->menu_id)
            {
                return $this->getTable('menu')->delete($this->menu_id);
            }
            return TRUE;
        }
}
