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
/**
 * zavod parser class.
 * Ювелиры Урала
 */
class UvelirParseZavod_2
{
    private $_table_name = 'product';

    /**
     * Парсинг главной страницы
     */
    public function main_page()
    {
        $data = JFactory::getApplication()->getUserState('com_uvelir.parse', array());
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }
        // Исходные данные
        $base_link = $data['base_link']; // Ссылка на главную страницу
        $category = array_shift($data['products_categories']);
        $product_category_data = explode('^', $category);
        $name = $product_category_data[0]; 
        // Если категории закончились - возвращаем флаг выхода из парсинна
        if(!$name)
        {
            return array(1,JText::_('COM_UVELIR_PARSE_SUCCES'));
        }
        else 
        {
            $data['product_name'] = $name;
        }
        // Открываем первую страницу категории и перебираем все ссылки
        $link = $product_category_data[1]; 
        $html = file_get_html($link);
        $divs = $html->find('div'); 
        
        foreach ($divs as $div)
        {
            if($div->id == 'content')
            {
                break;
            }
        }
        $table = $div->find('table'); 
        $links = $table[0]->find('a');
        $datas = array();
        for($i=0; $i<count($links); $i++) 
        {
            $imgs = $links[$i]->find('img');
            $datas[$i]['name'] = $imgs[0]->alt;
            $datas[$i]['src'] = $base_link.$imgs[0]->src;
            $datas[$i]['href'] = $base_link.$links[$i]->href;
        }
        $data['subcategories'] = $datas;
        $sub_link = $datas[0]['href'];
        
        array_unshift($data['link'], $sub_link);
        array_unshift($data['func'], "get_subcategory");
        JFactory::getApplication()->setUserState('com_uvelir.parse', $data);
        
        // Создаем категорию
        $category_data = JFactory::getApplication()->getUserState('com_uvelir.category_data', array());
        $category_model = new UvelirModelCategory;
        $category_save_data = array(
            'name'=>  $product_category_data[0],
            'parent_path'=>$category_data['path'][0],
            'level'=>'2',
            'zavod'=>'2',
        );
        
        // Сохраняем категорию
        list($result, $category_created) = $category_model->create_category($category_save_data);
        if(!$result)
        {
            // Не смогли сохранить категорию
            return array(0,  $category_created);
        }
        $category_data['name'] = $category_created['name'];
        $category_data['alias'] = $category_created['alias'];
        array_unshift($category_data['path'], $category_created['path']);
        $category_data['level'] = $category_created['level'];
        JFactory::getApplication()->setUserState('com_uvelir.category_data', $category_data);

        // Возвращаем результат
        $msg = $datas[0]['name'];
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$sub_link.'<hr/>'.$msg); // Продолжаем парсинг
    }

    /**
     * Парсинг субкатегорий
     */
    public function get_subcategory()
    {
        $data = JFactory::getApplication()->getUserState('com_uvelir.parse', array());
//        var_dump($data);
//                echo 'get_subcategory<hr/>';
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }
        // Если кончились подкатегории, переходим к следующей категории
        if(!$data['subcategories'])
        {
            array_shift($data['link']);
            array_shift($data['func']);
            JFactory::getApplication()->setUserState('com_uvelir.parse', $data);
            return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$data['link'][0]); // Продолжаем парсинг
        }
        // Исходные данные
        $sub_category = array_shift($data['subcategories']); // Исходные данные подкатегории
        $sub_category_link = $sub_category['href'];
        $data['product_name'] = $sub_category['name'];
        
        // Переходим к каталогу товаров этой подкатегории
        array_shift($data['link']); // Убираем ссылку на старую подкатегорию
        array_unshift($data['link'], $sub_category_link); // Выставляем ссылку на новую подкатегорию
        array_unshift($data['link'], $sub_category_link); // Выставляем ссылку на первую страницу новой подкатегории
        array_unshift($data['func'], "get_page");
        JFactory::getApplication()->setUserState('com_uvelir.parse', $data);

        // Создаем категорию
        $category_data = JFactory::getApplication()->getUserState('com_uvelir.category_data', array());
        $category_model = new UvelirModelCategory;
        $category_save_data = array(
            'name'=>  $sub_category['name'],
            'parent_path'=>$category_data['path'][0],
            'level'=>'3',
            'zavod'=>'2',
        );
        
        // Сохраняем категорию
        list($result, $category_created) = $category_model->create_category($category_save_data);
        if(!$result)
        {
            // Не смогли сохранить категорию
            return array(0,  $category_created);
        }
        $category_data['name'] = $category_created['name'];
        $category_data['alias'] = $category_created['alias'];
        array_unshift($category_data['path'], $category_created['path']);
        $category_data['level'] = $category_created['level'];
        JFactory::getApplication()->setUserState('com_uvelir.category_data', $category_data);
        
        $msg = $data['product_name'];
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$sub_category_link.'<hr/>'.$msg);
    }

    /**
    * Парсинг страницы списка товаров с пагинацией
    */
    public function get_page()
    {
        $data = JFactory::getApplication()->getUserState('com_uvelir.parse', array());
//        var_dump($data);
//                echo 'get_page<hr/>';
        
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }
        
        // Исходные данные
        $base_link = $data['base_link']; // Ссылка на главную страницу
        $page_link = array_shift($data['link']); // Ссылка на текущую страницу подкатегории
        // Переходим на страницу списка товаров
        $html = file_get_html($page_link);
        $divs = $html->find('div');
        $data_items = array();
        $i=0;
        
        // находим див content
        foreach ($divs as $div)
        {
            if($div->id == 'content')
            {
                break;
            }
        }
        $tables = $div->find('table'); 
        foreach($tables as $table)
        {
            $table_divs = $table->find('div');
            foreach ($table_divs as $table_div)
            {
                $links = $table_div->find('a');
                $data_items[$i]['desc']['item_link'] = $base_link.$links[0]->href;
                $small_img = $links[0]->find('img');
                $data_items[$i]['desc']['img_small'] = $base_link.$small_img[0]->src;
                $i++;
            }
            
        }
        
        $next_page_link = '';
        $div_pagination = $div->find('div[class=pagination]');
        if($div_pagination)
        {
            $hrefs = $div_pagination[0]->find('a');
            foreach($hrefs as $href)
            {
                $imgs = $href->find('img');
                if($imgs)
                {
                    if (preg_match("/page_next/", $imgs[0]->src))
                    {
                        $next_page_link = $href->href;
                        break;
                    }
                }
            }
        }
        $data['items'] = $data_items;
        // Если найдена ссылка на следующую страницу, то выставляем ее
        if($next_page_link)
        {
            array_unshift($data['link'], $data['link'][0].$next_page_link);
        }
        else
        {
            // Если не найдена следующая страница, то 
            // закладываем переход к следующей подкатегории
            array_shift($data['link']);
            array_shift($data['func']);
            // Убираем путь к текущей подкатегории
            $category_data = JFactory::getApplication()->getUserState('com_uvelir.category_data', array());
            array_shift($category_data['path']);
            JFactory::getApplication()->setUserState('com_uvelir.category_data', $category_data);
        }
        array_unshift($data['link'],$data_items[0]['desc']['item_link']);
        array_unshift($data['func'],'parse_item');

        JFactory::getApplication()->setUserState('com_uvelir.parse', $data);
        $msg = 'переходим к следующей странице';
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$data['link'][0].'<hr/>'.$msg); // Продолжаем парсинг
    }

    /**
     * Парсинг карточки товара 
     */
    function parse_item()
    {
       
        $data = JFactory::getApplication()->getUserState('com_uvelir.parse', array());
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }

        // Исходные данные
        $data_item = array_shift($data['items']); // Выбираем данные элемента
        $base_link = $data['base_link']; // Ссылка на главную страницу
        
        
        if(!$data_item)
        {
            // Если не находим 'страницу'элемент в массиве $data['items'], то продолжаем поиск уже со следующей страницы категории
            array_shift($data['func']);
            JFactory::getApplication()->setUserState('com_uvelir.parse', $data);
            $link = isset($data['link'][0])?$data['link'][0]:'';
            return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link);
        }
        // Переходим на карточку товара
        $item_link = $data_item['desc']['item_link'];
        // Очищаем текущую ссылу
        array_shift($data['link']);
        // Если присутствует ссылка на сдедующий товар в данных товара
        // то устанавливаем ее в качестве текущей ссылки
        if(isset($data['items'][0]['desc']['item_link']))
        {
            array_unshift($data['link'],$data['items'][0]['desc']['item_link']);
        }

        $html = file_get_html($item_link);

        // Див-контейнер с карточкой товара
        $items = $html->find('div[class=description]');
        $item = $items[0];

        // Артикул товара
        $h3s = $item->find('h3');
        $h3 = $h3s[0];
        $data_item['artikul'] = iconv("windows-1251", "utf-8", $h3->innertext);
        
        // Ссылка на рисунок товара
        $imgs = $item->find('img');
        $img = $imgs[0];
        $data_item['desc']['img_large'] = $base_link.$img->src;
        
        // Описание товара
        if (preg_match("/Описание:\<\/h4\>(.+)\<br\>/", $item->innertext, $matches))
        {
            $data_item['opisanije'] = $matches[1];
        }
        
        // Детали товара
        $details = $item->find('div');
        foreach ($details as $detail)
        {
            if (preg_match("/Средний вес: (\d+\.\d+)/", $detail->innertext, $matches))
            {
                $data_item['average_weight'] = $matches[1];
            }
            else 
            {
                $data_item['average_weight'] = '';
            }
            if (preg_match("/Вставка: \<\/b\>([^Z]+)/", $detail->innertext, $matches))
            {
                $vstavki = iconv("windows-1251", "utf-8", $matches[1]);
                $data_item['vstavki'] = $vstavki;
            }
            else
            {
                $data_item['vstavki'] = '';
            }
            if (preg_match("/Материал: \<\/b\>([^Z]+)/", $detail->innertext, $matches))
            {
                $material = iconv("windows-1251", "utf-8", $matches[1]);
                $data_item['material'] = $material;
            }
            else
            {
                $data_item['material'] = '';
            }
        }
        $img_small = $data_item['desc']['img_small'];
        $data_item['desc'] = json_encode($data_item['desc']);
        $data_item['name'] = $data_item['artikul'];
        $data_item['alias'] = JApplication::stringURLSafe($data_item['name']);
        // URI
        $category_data = JFactory::getApplication()->getUserState('com_uvelir.category_data', array());
        $data_item['path'] = $category_data['path'][0].'/'.$data_item['alias'];
        // Ищем родительскую категорию
        $category_model = new UvelirModelCategory;
        $data_item['category_id'] = $category_model->find_parent_id($category_data['path'][0]);
        // ищем родителя пункта меню
        $menu_parent_id = $category_model->menu_find_parent_id($category_data['path'][0]); 
        JFactory::getApplication()->setUserState('com_uvelir.menu_parent_id', $menu_parent_id);
        // Уровень пункта меню
        $data_item['level'] = '4';
        
        // Вставляем запись
        $table = $this->getTable('product_2');
        if($table->load(array('artikul'=>$data_item['artikul'])))
        {
            $data_item['id'] = $table->id;
        }
        $save_item = $table->save($data_item);
        
//var_dump($table);
//echo '<hr/>';
//var_dump($item_link);
//var_dump($data_item);
//echo '<hr/>';
//var_dump($data);
//        JFactory::getApplication()->setUserState('com_uvelir.parse', $data);
//        $link = isset($data['link'][0])?$data['link'][0]:'';
//        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link.'<hr/>'.$msg);
        
        $msg = '';
        if(isset($data['items'][0]['desc']['img_small']))
        {
            $msg .= '<img src="'.$img_small.'" style="float:left;">';
        }
//        var_dump($img_small);
        $color = $save_item?'green':'red';
        $msg .= '
            <table style="color:'.$color.'">
                <tr>
                    <th>Наименование</th>
                    <td>'.$data_item['name'].'</td>
                </tr>
                <tr>
                    <th>Артикул</th>
                    <td>'.$data_item['artikul'].'</td>
                </tr>
                <tr>
                    <th>Материал</th>
                    <td>'.$data_item['material'].'</td>
                </tr>
                <tr>
                    <th>Ср. вес</th>
                    <td>'.$data_item['average_weight'].'</td>
                </tr>
                <tr>
                    <th>Вставки</th>
                    <td>'.$data_item['vstavki'].'</td>
                </tr>
            </table>
            ';
//                    <td>'.$data_item['desc'].'</td>
        
        JFactory::getApplication()->setUserState('com_uvelir.parse', $data);
        $link = isset($data['link'][0])?$data['link'][0]:'';
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link.'<hr/>'.$msg);
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
	public function getTable($type = NULL, $prefix = 'UvelirTable', $config = array()) 
	{
            if(!isset($type))
            {
                $type = $this->_table_name;
            }
            return JTable::getInstance($type, $prefix, $config);
	}
   
}

