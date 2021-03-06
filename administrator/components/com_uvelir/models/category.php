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

jimport('joomla.application.component.modeladmin');

/**
 * Uvelir model.
 */
class UvelirModelCategory extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_UVELIR';
        
        
        
        /**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Category', $prefix = 'UvelirTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_uvelir.category', 'category', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_uvelir.edit.category.data', array());

		if (empty($data)) {
			$data = $this->getItem();
            
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {

			//Do any procesing on fields here if needed

		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__uvelir_categories');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}

		}
	}

        public function save($data) {
            // Если это только создаваемая категория
            if(!$data['alias'] AND $data['parent_id'])
            {
                $data['alias'] = JApplication::stringURLSafe($data['name']);
                $parent_category_data = $this->get_parent_category_data($data['parent_id']);
                if($parent_category_data['level'])
                {
                    $data['level'] = (int)$parent_category_data['level']+1;
                    $data['path'] = $parent_category_data['path'].'/'.$data['alias'];
                    $data['zavod'] = $parent_category_data['zavod'];
                }
                $menu_parent_id = $this->menu_find_parent_id($parent_category_data['path']); 
                JFactory::getApplication()->setUserState('com_uvelir.menu_parent_id', $menu_parent_id);
                $id = $this->_save($data);
                $this->setState($this->getName() . '.id', $id);
                return $id;
            }
            return parent::save($data);
        }
        
        private function get_parent_category_data($id)
        {
            $table = $this->getTable('Category');
            if($table->load($id))
            {
                return array(
                    'level'=>$table->level,
                    'path'=>$table->path,
                    'zavod'=>$table->zavod,
                );
            }
                return array(
                    'level'=>0,
                    'path'=>'',
                    'zavod'=>0,
                );
        }

        /**
         * Сохраняем категорию, которая создается в автоматическом виде
         * @param type $data
         * @return int
         */
        private function _save($data) 
        {
            
            $table = &$this->getTable('Category');
            $parent_id = $data['parent_id'];
            $table->setLocation( $parent_id, 'last-child' );            
            if(!$table->save($data))
            {
                return 0;
            }
            return $table->id;
        }
        
        /**
         * Находим ИД категрии по ее алиасу
         * @param string $alias
         * @return int 
         */
        private function _find_id($alias = FALSE, $field = 'id', $zavod = 0)
        {
            if(!$alias)
            {
                return 0;
            }
            $table = $this->getTable('Category');
            $keys = array('alias'=>$alias);
            if($zavod)
            {
                $keys['zavod'] = $zavod;
            }
            
      
            if($table->load($keys))
            {
                return $table->$field;
            }
            return 0;
        }
        
        /**
         * Находим ИД категрии по ее пути
         * @param string $alias
         * @return int 
         */
        public function find_parent_id($path = FALSE, $zavod )
        {
            if(!$path OR !$zavod)
            {
                return 1;
            }
            $table = $this->getTable('Category');
            if($table->load(array('path'=>$path, 'zavod'=>$zavod)))
            {
                return $table->id;
            }
            return 1;
        }
        
        /**
         * Находим ИД родительского меню
         * @param string $alias
         * @return int 
         */
        public function menu_find_parent_id($path = FALSE)
        {
            if(!$path)
            {
                return 0;
            }
            $table = $this->getTable('Menu');
            if($table->load(array('path'=>$path)))
            {
                return $table->id;
            }
            return 0;
        }

        /**
         * Находим ИД родительского меню
         * @param string $alias
         * @return int 
         */
        private function _category_find_id($path = FALSE)
        {
            if(!$path)
            {
                return 0;
            }
            $table = $this->getTable('Category');
            if($table->load(array('path'=>$path)))
            {
                return $table->id;
            }
            return 0;
        }

        /**
         * Создаем или переписываем категорию
         * @param type $category
         * @return type 
         */
        public function create_category($category)
        {
            // ищем родителя пункта меню
            if($category['parent_path'])
            {
                $parent_menu_path = $category['parent_path'];
                $menu_parent_id = $this->menu_find_parent_id($parent_menu_path); 
            }
            else  // Если это категория завода
            {
                $parent_menu_path = 'com-uvelir';
                $menu_parent_id = 1; 
            }
            JFactory::getApplication()->setUserState('com_uvelir.menu_parent_id', $menu_parent_id);
            
            // Готовим данные категории
            $category_alias = JApplication::stringURLSafe($category['name']);
            $path = $category['parent_path']?$category['parent_path'].'/'.$category_alias:$category_alias;
            $id = $this->_category_find_id($path);
            
            // Проверяем наличие этого алиаса на других уровнях
            $othe_level = $this->_find_id($category_alias, 'level', $category['zavod']);
            if($othe_level AND $othe_level != $category['level'])
            {
                // если находим, то изменяем алиас
                $category_alias .= '_'.$category['level'];
            }
            $category = array(
                'name'=>  addslashes($category['name']),
                'alias'=>$category_alias,
                'path'=>$path,
                'parent_id'=>  $category['parent_id'],
                'level'=>$category['level'],
                'zavod'=>$category['zavod'],
            );
            
            if($id)
            {
                $category['id'] = $id;
            }
            
            // Сохраняем категорию
            $category['id'] = $this->_save($category);
            if(!$category['id'])
            {
                // Не смогли сохранить категорию
                return array(0,  JText::_('COM_UVELIR_CANOT_SAVE_CATEGORY'));
            }
            return array(1,  $category);
        }
}