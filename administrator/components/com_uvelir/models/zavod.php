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
class UvelirModelZavod extends JModelAdmin
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
	public function getTable($type = 'Zavod', $prefix = 'UvelirTable', $config = array())
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

            // Get the form.
            $form = $this->loadForm('com_uvelir.zavod', 'zavod', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_uvelir.edit.zavod.data', array());

		if (empty($data)) 
                {
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
				$db->setQuery('SELECT MAX(ordering) FROM zavod');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}
		}
	}
       

        /**
         * Парсинг
         * @return array
         */
        public function parse()
        {

            require_once dirname(__FILE__) . '/category.php'; 

//            $data_2 = JFactory::getApplication()->getUserState('com_uvelir.parse', array());
//                var_dump($data);
//                echo '<hr/>';
//                var_dump($data_2);exit;
            // Определяем ИД завода
            $cids = JRequest::getVar('cid',array(),'','array');
            if(!$cids)
            {
                return array(0,  JText::_('COM_UVELIR_CIDS_NOT_SELECTED'));
            }
            $cid = $cids[0];
            
            // Вычисляем контроллер парсера
            jimport('joomla.filesystem.file');
            $file_path = JPATH_COMPONENT.DS.'parsers'.DS.'parser_'.$cid.'.php';
            if (!JFile::exists($file_path))
            {
                return array(0,  JText::_('COM_UVELIR_PARSER_DO_NOT_FIND'));
            }
            
            // Проверяем на начало парсинга (загрузка ли стартовой страницы)
            $start = JRequest::getInt('start',0);
            if($start)
            {
                // Удаляем файлы дампа данных
                $file_data = JPATH_ROOT.DS.'tmp'.DS.'parse_'.$cid.'_data.txt';
                $file_category = JPATH_ROOT.DS.'tmp'.DS.'parse_'.$cid.'_category.txt';
                jimport('joomla.filesystem.file');
                if(JFile::exists($file_category))
                {
                    JFile::delete($file_category);
                }
                if(JFile::exists($file_data))
                {
                    JFile::delete($file_data);
                }
                
                $zavod =& $this->_get_zavod_url($cid);
                if(!$zavod->base_url)
                {
                    // В таблице заводов не адрес свйта завода
                    return array(0,  JText::_('COM_UVELIR_FACTORY_URL_NOT_FIND'));
                }
                if(!$zavod->products)
                {
                    // В таблице заводов не указан список категорий продуктов
                    return array(0,  JText::_('COM_UVELIR_FACTORY_PODUCTS_NOT_FIND'));
                }
                $products_categories = explode(';', $zavod->products);
                
                // Создаем категорию для завода
                $category = array(
                    'name'=>  $zavod->name,
                    'parent_path'=>'',
                    'level'=>1,
                    'zavod'=>$cid,
                    'parent_id'=>'1',
                );
                $category_model = new UvelirModelCategory;
                // Сохраняем категорию для завода
                list($result, $category_created) = $category_model->create_category($category);
                if(!$result)
                {
                    // Не смогли сохранить категорию завода
                    return array(0,  $category_created);
                }
                $category_data = array(
                    'name'=>  $category_created['name'],
                    'alias'=>$category_created['alias'],
                    'path'=>array($category_created['path']),
                    'level'=>$category_created['level'],
                    'parent_id'=>array($category_created['id']),
                );
                $data = array(
                    'base_link'=>$zavod->base_url,
                    'link'=>array($zavod->base_url), // При первом вызове парсера параметром указываем первую категорию товаров
                    'items'=>array(), // Краткие данные на товар
                    'func' => array('main_page'),
                    'products_categories' => $products_categories,
                    );
                JFactory::getApplication()->setUserState('com_uvelir.parse', $data);
                JFactory::getApplication()->setUserState('com_uvelir.category_data', $category_data);
                $category = explode('^',$products_categories[0]);
                $msg = $category[0].'<br/>';
                $result = array(2,  JText::_('COM_UVELIR_OPEN_MAIN_PAGE').': '.$zavod->base_url.'<hr/>'.$msg);
            }
            else 
            {
//                $data = JFactory::getApplication()->getUserState('com_uvelir.parse', array());
                $data = $this->_get_data($cid);
                if(!$data)
                {
                    return array(1,  JText::_('COM_UVELIR_PARSE_END'));
                }
                
                // Открываем контроллет парсера
                require_once $file_path;
                $controller_name = 'UvelirParseZavod_'.$cid; 
                $parser = new $controller_name;
                
                // Определяем функцию, параметры и запускаем их в парсере
                $func = $data['func'][0];
                $get_result = $parser->$func();
                $result = $get_result;
            }
            return $result;
        }
        
        /**
         * 
         * @param integer $cid
         * @return integer
         */
        private function _get_zavod_url($cid)
        {
            $zavod = $this->getTable();
            if(!$zavod->load($cid))
            {
                return 0;
            }
            return $zavod;
        }
        
    /**
     * Берем сохраненные данные
     * @param array $data 
     */
    private function _get_data($cid)
    {
        $data = JFactory::getApplication()->getUserState('com_uvelir.parse', array());
        $file_data = JPATH_ROOT.DS.'tmp'.DS.'parse_'.$cid.'_data.txt';
        if(JFile::exists($file_data))
        {
            $data = json_decode(JFile::read($file_data),TRUE);
        }
        return $data;
    }
    
        
}