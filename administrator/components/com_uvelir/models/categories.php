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
class UvelirModelCategories extends UvelirModelKModelList
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

            );
        }

        parent::__construct($config);
    }
    
    protected function populateState($ordering = null, $direction = null) {
        
        $app = JFactory::getApplication();
        
        // Устанавливаем наименование контекста
        $app->setUserState('com_uvelir.this_context', $this->context);
        
        // Load the filter state.
        $zavod = $app->getUserStateFromRequest($this->context.'.filter.zavod', 'filter_zavod', '2', 'string');
        $this->setState('filter.zavod', $zavod);        

        
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
            
            $query->from('`#__uvelir_categories` AS a');
            
            // Фильтр по заводу
            $zavod = $this->getState('filter.zavod', '2');
            $query->where('zavod = '.$zavod);
            
            // Filter by search in title
            $search = $this->getState('filter.search');
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
         * Возвращаем наименование типа продукта
         * @param int $producttype_id 
         * @return string 
         */
        public function get_producttype_id($producttype_id) 
        {
            $name = '';
            $table = $this->getTable('Producttype', 'UvelirTable');
            if($table->load($producttype_id))
            {
                $name = $table->name;
            }
            return $name;
        }
        
        /**
         * Парсинг товаров тлько для одной категории
         */
        public function parse_one_catrgory($id=NULL)
        {
            // Определяем ИД категории
            $cids = JRequest::getVar('cid',array(),'','array');
            if(!$cids)
            {
                return array(0,  JText::_('COM_UVELIR_CIDS_NOT_SELECTED'));
            }
            $category_id = $cids[0];

            $category = $this->getTable('category', 'UvelirTable');
            if (!$category->load($category_id))
            {
                return array(0,  JText::_('COM_UVELIR_CATEGORY_CAN_NOT_LOAD'),': '.$category);
            }

            // Если не указан парсер для категории
            if(!$category->parser_id)
            {
                return array(0,  JText::_('COM_UVELIR_CATEGORY_PARSER_ID_NOT_FIND'));
            }
            $parser_id = $category->parser_id;
            
            // Проверяем на начало парсинга (загрузка ли стартовой страницы)
            $start = JRequest::getInt('start',0);
            if($start)
            {

                jimport('joomla.filesystem.file');
                $file_data = JPATH_ROOT.DS.'tmp'.DS.'parse_'.$parser_id.'_data.txt';
                if(JFile::exists($file_data))
                {
                    JFile::delete($file_data);
                }

                // Если не указан адрес страницы ресурса для категории
                //http://www.ju-ur.ru/?category=35&class=shop_items&catalogue_id=2
                if(!$category->category_sourse_path)
                {
                    return array(0,  JText::_('COM_UVELIR_CATEGORY_SOURSE_PATH_ID_NOT_FIND'));
                }
                $category_sourse_path = $category->category_sourse_path;


                // Если не найден завод по $parser_id
                $zavod = $this->getTable('zavod', 'UvelirTable');
                if(!$zavod->load($parser_id))
                {
                    return array(0,  JText::_('COM_UVELIR_CATEGORY_PARSER_ID_NOT_FIND_ZAVOD'));
                }

                // Если не указан адрес страницы завода
                if(!$zavod->base_url)
                {
                    return array(0,  JText::_('COM_UVELIR_CATEGORY_ZAVOD_BASE_URL_NOT_FIND'));
                }

                
                // Если не совпадают  $zavod->base_url и домен $category->category_sourse_path
                if(!preg_match('/'.str_replace('http://', '', $zavod->base_url).'/',  $category_sourse_path))
                {
                    return array(0,  JText::_('COM_UVELIR_CATEGORY_ZAVOD_URL_NOT_MATCH'));
                }
                $base_link = $zavod->base_url;

                $data = array(
                    'zavod_id'=>$category->zavod,
                    'func'=>array('parse_one_catrgory'),
                    'base_link'=> $base_link,
                    'link'=>array(),
                    'page_link'=>array($category_sourse_path),
                    'category'=>array('category_id'=>$category_id)
                );

                $msg = 'Начинаем парсить категорию';
                JFactory::getApplication()->setUserState('com_uvelir.parse', $data);
                $result = array(2,  JText::_('COM_UVELIR_OPEN_MAIN_PAGE').': '.$category_sourse_path.'<hr/>'.$msg);
            }
            else 
            {
                $cid = $category->parser_id;
                // Вычисляем контроллер парсера
                jimport('joomla.filesystem.file');
                $file_path = JPATH_COMPONENT.DS.'parsers'.DS.'parser_'.$parser_id.'.php';
                
                if (!JFile::exists($file_path))
                {
                    return array(0,  JText::_('COM_UVELIR_PARSER_DO_NOT_FIND'));
                }
                
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
        * Берем сохраненные данные
        * @param array $data 
        */
        private function _get_data($parser_id = NULL)
        {
            $data = JFactory::getApplication()->getUserState('com_uvelir.parse', array());
            if(isset($parser_id))
            {
                $file_data = JPATH_ROOT.DS.'tmp'.DS.'parse_'.$parser_id.'_data.txt';
                if(JFile::exists($file_data))
                {
                    $data = json_decode(JFile::read($file_data),TRUE);
                }
            }
            return $data;
        }
        
}
