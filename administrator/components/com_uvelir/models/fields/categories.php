<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');

/**
 * Supports an custom SQL select list
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldCategories extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'Categories';

	/**
	 * Method to get the custom field options.
	 * Use the query attribute to supply a query to generate the list.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();
                // Список уатегорий с учетом завода
                $items = $this->_get_categories();

		// Build the field options.
		if (!empty($items))
		{
                    foreach ($items as $item)
                    {
                        $options[] = JHtml::_('select.option', $item['id'], $item['title']);
                    }
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
        

        /**
         * Выборка категорий
         * @return string 
         */
        private function _get_categories()
        {
            $zavod = JRequest::getInt('zavod', '2');
            $result = array();
            // Get the database object.
            $db = JFactory::getDBO();
            $query = 'SELECT `id`, `name`, `parent_id` FROM `#__uvelir_categories` WHERE `zavod`='.$zavod;
            // Set the query and get the result list.
            $db->setQuery($query);
            $items = $db->loadObjectlist();
            foreach ($items as $item)
            {
                $title = $item->parent_id?$this->_get_name($item->parent_id,$item->name):$item->name;
                $result[] = array(
                    'id'=>$item->id,
                    'title'=>$title,
                ); 
            }
            return $result;
        }
        
        private function _get_name($id=0, $name='')
        {
            $db = JFactory::getDBO();
            $query = 'SELECT `name`, `parent_id` FROM `#__uvelir_categories` WHERE `id`='.$id;
            // Set the query and get the result list.
            $db->setQuery($query);
            $item = $db->loadObject();
            if($item->parent_id)
            {
                $name = $this->_get_name($item->parent_id, $item->name).' | '.$name;
            }
            else 
            {
                $name = $item->name.' | '.$name;
            }
            return $name;
            
        }
}
