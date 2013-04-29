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
                $company_id = JFactory::getApplication()->getUserState('com_jugraauto.company_id',FALSE);
		// Initialize some field attributes.
		$query = 'SELECT `category_id` FROM `#__jugraauto_companies_categories` WHERE `company_id`='.$company_id;

		// Get the database object.
		$db = JFactory::getDBO();
		// Выбираем массив категорий.
		$db->setQuery($query);
		$this->value = $db->loadResultArray();
                // Список уатегорий с учетом родителя
                $items = $this->_get_categories();
		// Check for an error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
			return $options;
		}

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
        
        private function _get_categories($parent_id = 1, $key = 0)
        {
            $result = array();
            // Get the database object.
            $db = JFactory::getDBO();
            $query = 'SELECT `id`, `title` FROM `#__categories` WHERE `extension`="com_jugraauto" AND `parent_id` = '.$parent_id;
            // Set the query and get the result list.
            $db->setQuery($query);
            $items = $db->loadObjectlist();
            $new_key = $key+1;
            foreach ($items as $item)
            {
                $result[] = array(
                    'id'=>$item->id,
//                    'title'=>str_repeat(' ', $key*10).$item->title,
                    'title'=>str_repeat('|---', $key).' '.$item->title,
                ); 
                $get_result = $this->_get_categories($item->id, $new_key);
                $result = array_merge($result, $get_result);
            }
            return $result;
        }
}
