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

include_once('simple_html_dom.php');
require_once JPATH_ADMINISTRATOR.'/components/com_uvelir/models/category.php'; 
require_once JPATH_ADMINISTRATOR.'/components/com_uvelir/models/product.php'; 
jimport('joomla.filesystem.file');

/**
 * zavod parser class.
 * Красцветмет, Красноярск.
 */
class UvelirParseZavod_3
{

    private $_file_data;
    private $_file_category;
    
    public function __construct() {
        $this->_file_data = JPATH_ROOT.DS.'tmp'.DS.'parse_3_data.txt';
        $this->_file_category = JPATH_ROOT.DS.'tmp'.DS.'parse_3_category.txt';
    }
    /**
     * Парсинг главной страницы
     */
    public function main_page()
    {
        $data = $this->_get_data();
        
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }
        
        $base_link = $data['base_link']; // Ссылка на главную страницу

        // Ищем все малериалы на главной странице
        list($result, $msg, $materials) = $this->_get_materials($base_link);
        if(!$result)
        {
            return array(0, $msg);
        }
        
        // Сохраняем данные
        $data['materials'] = $materials;
        $data['parameters'] = array();
        $data['func'][0] = "get_types";
        $this->_set_data($data);
        
         // Переходим на страницу типов изделий
        $link = $base_link.'?selmat=1&material=1&colortype=0';
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
    }
    /**
     * Вычисляем список материалов
     * @param string $page_link
     * @return array 
     */
    private function _get_materials($page_link)
    {
        $materials = array();
        // Открываем главную страницу
        $html = file_get_html($page_link);
        if(!$html)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_OPEN_MAIN_PAGE'), $materials); // Не нашли селект с материалами
        }
        
        // Ищем селект с материаллами на главной сттранице
        $selects = $html->find('select[id=material]');
        if(!$selects)
        {
            return array(0,  JText::_('COM_UVELIR_SELECT_MATERIALS_NOT_FIND'), $materials); // Не нашли селект с материалами
        }
        // Находим все опции селекта
        $options = $selects[0]->find('option');
        
        
        foreach($options as $option)
        {
            $materials[$option->value]['key'] = $option->value;
            $materials[$option->value]['value'] = iconv("windows-1251", "utf-8", $option->innertext);
        }

        return array(1,  JText::_('COM_UVELIR_SELECT_MATERIALS_SUCCES'), $materials); // Не нашли селект с материалами
    }

    /**
     * Выборка типов изделий
     * @return array
     */
    public function get_types()
    {
        $data = $this->_get_data();
        $base_link = $data['base_link']; // Ссылка на главную страницу
        
        $material = array_shift($data['materials']);
        $page_link = $base_link.'?selmat='.$material['key'].'&material='.$material['key'].'&colortype=0';
        // Открываем страницу
        $html = file_get_html($page_link);
        if(!$html)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_OPEN_PAGE').': '.$page_link);
        }
        // Ищем селект с типами изделий
        $selects = $html->find('select[id=prodtype]');
        if($selects)
        {
            // Находим все опции селекта
            $options = $selects[0]->find('option');
            foreach($options as $option)
            {
                $types[$option->value]['key'] = $option->value;
                $types[$option->value]['value'] = iconv("windows-1251", "utf-8", $option->innertext);
            }
            
            $data['parameters'][] = array(
                'types'=>$types,
                'material'=>$material
            );
        }
        
        
        // Вычисляем следующую ссылку
        if(isset($data['materials'][0]))
        {
            $key = $data['materials'][0]['key'];
            $link = $base_link.'?selmat='.$key.'&material='.$key.'&colortype=0';
        }
        else
        {
            // Если закончились материаллы, переходим на страницы с изделиями
            $data['func'][0] = "make_category_array";
            $link = 'Получаем массив категорий';
        }
        
        $this->_set_data($data);
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
        
    }
    
    /**
     * Преобразовываем массив в разрезе 
     * материаллов в массив в разрезе типов изделий
     * @return type
     */
    public function make_category_array()
    {
        $data = $this->_get_data();
        $categories = array();
        foreach($data['parameters'] as $row)
        {
            foreach($row['types'] as $type)
            {
                if(!isset($categories[$type['key']]))
                {
                    $categories[$type['key']]['name'] =  $type['value'];
                    // Создаем новую категорию
                    $category_id = $this->_create_category($type['value']);
                    if(!$category_id)
                    {
                        // Не смогли сохранить категорию
                        return array(0,  JText::_('COM_UVELIR_CANOT_SAVE_CATEGORY'));
                    }
                    $categories[$type['key']]['id'] = $category_id;
                }
                $categories[$type['key']]['material'][$row['material']['key']] =  $row['material']['value'];                
            }
        }
        unset($data['parameters']);
        $data['categories'] = $categories;
        $data['func'][0] = "get_page";
        $this->_set_data($data);
        
        
        $category = reset($categories);
        $material = reset($category['material']);
        $link = $category['name'].', '.$material;
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
        
    }
    
   /**
    *  Создаем категорию
    * @param string тайтл категории
    */
    private function _create_category($name)
    {
        $category_data = $this->_get_category_data();
        $category_model = new UvelirModelCategory;
        $category_save_data = array(
            'name'=>  $name,
            'parent_path'=>$category_data['path'][0],
            'parent_id'=>$category_data['parent_id'][0],
            'level'=>'2',
            'zavod'=>'3',
        );
        
        // Сохраняем категорию
        list($result, $category_created) = $category_model->create_category($category_save_data);
        if(!$result)
        {
            // Не смогли сохранить категорию
            return 0;
        }
        
        return  $category_created['id'];
    }

    /**
    * Парсинг страницы списка товаров 
    * @param type $page_link 
    */
    public function get_page()
    {
        $data = $this->_get_data();
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }
        $base_link = $data['base_link']; // Ссылка на главную страницу
        //
        // Получаем данные о категории
//        $category = array_shift($data['categories']);
        $_category = reset($data['categories']);
        $category['key'] = key($data['categories']);
        $category['name'] = $_category['name'];
        $category['id'] = $_category['id'];
        $category['material']['name'] = reset($_category['material']);
        // Вычисляем пробу
        $names = explode(' ', $category['material']['name']);
        if(isset($names[1]) AND (int)$names[1]==$names[1])
        {
            $category['material']['proba'] = $names[1];
        }
        else 
        {
            $category['material']['proba'] = '';
        }
        $category['material']['key'] = key($_category['material']);
        $category['link'] = $base_link.'?material='.$category['material']['key']
                .'&colortype=0&prodtype='.$category['key'].'&ifsrch=1&pcount=999&imexist=0';
        
        if(!$this->_add_items($category, $base_link))
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_ADD_ITEM')); // Выход из парсинга
        }
        // Переходим к следующему материалу
        unset($data['categories'][$category['key']]['material'][$category['material']['key']]);
        // Если закончились материалы, переходим к следующей категории
        if(!$data['categories'][$category['key']]['material'])
        {
            unset($data['categories'][$category['key']]);
        }
        // Если закончились категории, выходим из парсинга
        if(!$data['categories'])
        {
            return array(1,JText::_('COM_UVELIR_PARSE_SUCCES'));
        }
        
        // Продолжаем парсить уже карточку товара
        $this->_set_data($data);
        
        $_category = reset($data['categories']);
        $category = $_category['name'];
        $material = reset($_category['material']);
        
        $link = $category.' '.$material;
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
    }

    private function _add_items($category)
    {
        $img_link = 'http://www.knfmp.ru/pls/jewell/';
        $result = TRUE;
        $product_model = new UvelirModelProduct;
        $link = $category['link'];
        $html = file_get_html($link);
        $items = $html->find('div[class=catalogue-item]');
        foreach ($items as $item)
        {
            // Детали товара
            $data_item = array(
                'artikul'=>'',
                'title'=>'',
                'desc'=>array(
                    'img_medium'=>'',
                    'img_large'=>'',
                    'img_small'=>'',
                    'img_small_resize'=>'',
                    'img_medium_resize'=>'',
                ),
                'material'=>$category['material']['name'],
                'proba'=>$category['material']['proba'],
                'average_weight'=>'',
                'vstavki'=>'',
                'category_id'=>$category['id'],
                'zavod_id'=>'3'
            );
//            $data_item['artikul'] = '';
//            $data_item['title'] = '';
//            $data_item['desc']['img_medium'] = '';
//            $data_item['desc']['img_large'] = '';
//            $data_item['desc']['img_medium_resize'] = '1';
//            $data_item['desc']['img_small'] = '';
//            $data_item['desc']['img_small_resize'] = '1';
//            $data_item['material'] = $category['material']['name'];
//            $data_item['proba'] = $category['material']['proba'];
//            $data_item['average_weight'] = '';
//            $data_item['vstavki'] = '';
//            $data_item['category_id'] = $category['id'];
//            $data_item['zavod_id'] = '3';

            // Рисунок
            $imgs = $item->find('img');
            if(isset($imgs[0]))
            {
                $data_item['desc']['img_large'] = $img_link.$imgs[0]->src;
                $data_item['desc']['img_medium'] = $img_link.$imgs[0]->src;
                $data_item['desc']['img_small'] = $img_link.$imgs[0]->src;
            }
            
            // Артикул
            $artikul = $item->find('b');
            if(isset($artikul[0]))
            {
                $data_item['artikul'] = iconv("windows-1251", "utf-8", $artikul[0]->innertext);
            }
            
            // Наименование
            $title = $item->find('h4');
            if(isset($title[0]))
            {
                $data_item['name'] = iconv("windows-1251", "utf-8", $title[0]->innertext);
            }
            else
            {
                $data_item['name'] = $data_item['artikul'];
            }
            $data_item['alias'] = JApplication::stringURLSafe($data_item['name']);
            
            // Размер
            $dts = $item->find('dt');
            $dds = $item->find('dd');
            for( $i=0; $i<count($dts); $i++)
            {
                 $dt = $dts[$i];
                 $dd = $dds[$i];
//                    echo iconv("windows-1251", "utf-8", $dt->innertext).' '.iconv("windows-1251", "utf-8", $dd->innertext).'</br>';
                if (preg_match("/Размер/", iconv("windows-1251", "utf-8", $dt->innertext)))
                {
                    $data_item['razmer'] = iconv("windows-1251", "utf-8", $dd->innertext);
                }
                
            }
            $data_item['desc'] = json_encode($data_item['desc']);
             // Сохраняем продукт
            $product_id = $product_model->save_product($data_item);
             if(!$product_id)
             {
                 $result = FALSE;
             }
        }
        return $result;
    }
    
  
    /**
     * Сохраняем данные перед выходом
     * @param array $data 
     */
    private function _set_data($data)
    {

//        var_dump($data['categories']);
//        var_dump($data);

        JFactory::getApplication()->setUserState('com_uvelir.parse', $data);
        if (!JFile::write($this->_file_data, json_encode($data)))
        {
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Сохраняем данные категории перед выходом
     * @param array $data 
     */
    private function _set_category_data($category_data)
    {
        JFactory::getApplication()->setUserState('com_uvelir.category_data', $category_data);
        if (!JFile::write($this->_file_category, json_encode($category_data)))
        {
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Берем сохраненные данные
     * @param array $data 
     */
    private function _get_data()
    {
        $data = JFactory::getApplication()->getUserState('com_uvelir.parse', array());
        if(JFile::exists($this->_file_data))
        {
            $data = json_decode(JFile::read($this->_file_data),TRUE);
        }
        return $data;
    }
    
    /**
     * Берем сохраненные данные категории
     * @param array $data 
     */
    private function _get_category_data()
    {
        $category_data = JFactory::getApplication()->getUserState('com_uvelir.category_data', array());
        if(JFile::exists($this->_file_category))
        {
            $category_data = json_decode(JFile::read($this->_file_category),TRUE);
        }
        return $category_data;
    }
}

