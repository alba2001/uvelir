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
 * Ювелиры Урала
 */
class UvelirParseZavod_1
{

    private $_file_data;
    private $_file_category;
    
    public function __construct() {
        $this->_file_data = JPATH_ROOT.DS.'tmp'.DS.'parse_1_data.txt';
        $this->_file_category = JPATH_ROOT.DS.'tmp'.DS.'parse_1_category.txt';
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
        // Исходные данные
        $link = $base_link = $data['base_link']; // Ссылка на главную страницу
        $name = array_shift($data['products_categories']);
        // Если категории закончились - возвращаем флаг выхода из парсинна
        if(!$name)
        {
            return array(1,JText::_('COM_UVELIR_PARSE_SUCCES'));
        }
        else 
        {
            $data['product_name'] = $name;
        }
        
        /**
         *  Создаем категорию
         */
        $category_data = $this->_get_category_data();
        $category_model = new UvelirModelCategory;
        $category_save_data = array(
            'name'=>  $name,
            'parent_path'=>$category_data['path'][0],
            'parent_id'=>$category_data['parent_id'][0],
            'level'=>'2',
            'zavod'=>'1',
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
        $category_data['level'] = $category_created['level'];
        $this->_set_category_data($category_save_data);
        
        // Подготавливаем данные о категории для таблицы товаров
        $data['category'] = array(
            'category_id' => $category_created['id'],
            'path' => $category_created['path'],
        );
        // END -Создаем категорию
        
        // Открываем главную страницу и перебираем все ссылки
        $html = file_get_html($link);
        foreach($html->find('a') as $home_page_link) 
        {
            // Ищем ссылку на страницу списка товаров
            if(preg_match("/$name/", $home_page_link->innertext))
            {
                // Вычисляем ссылку на страницу списка товаров
                $sub_link = str_replace('&amp;', '&', $base_link.$home_page_link->href);
                array_unshift($data['link'], $sub_link);
                array_unshift($data['func'], "get_page");
                break;
            }
        }
        $this->_set_data($data);
        
        
        
//var_dump($data);echo 'main_page <hr>';


        // Возвращаем результат
        $link = isset($data['link'][0])?$data['link'][0]:'';
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
    }
    

    /**
    * Парсинг страницы списка товаров с пагинацией
    * @param type $page_link 
    */
    public function get_page()
    {
        
        $data = $this->_get_data();
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }
        
        // Исходные данные
        $base_link = $data['base_link']; // Ссылка на главную страницу
        $page_link = array_shift($data['link']); // Ссылка на главную страницу

        // Переходим на страницу списка товаров
        $html = file_get_html($page_link);
        if(!$html)
        {
            // Если не находим страницу, то продолжаем поиск уже со следующей категории
            array_shift($data['func']);
            $this->_set_data($data);
            
            $link = isset($data['link'][0])?$data['link'][0]:'';
            return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
        }
        
        // Корневой див для тизера товара
        $items = $html->find('div[class=good_wrap]'); 
        if(!$items)
        {
            // Если не находим каталог элементов, то продолжаем поиск уже со следующей категории
            array_shift($data['func']);
            $link = isset($data['link'][0])?$data['link'][0]:'';
            return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
        }
        
        // Перебираем все тизеры товаров на странице
        $data_items = array();
        for ($i=0; $i<count($items);$i++)
        {
            // Данные краткого вывода товара
            $data_items[$i]['desc'] = array(
                'item_link' => '',
                'img_small' => '',
            );
            // Ссылка на полную карточку товара
            $item_links = $items[$i]->find('a');
            if($item_links)
            {
                $data_items[$i]['desc']['item_link'] = $base_link.str_replace('&amp;', '&', $item_links[0]->href);
            }
            // Ссылка на тизер товара
            $img_srcs = $items[$i]->find('img');
            if($img_srcs)
            {
                $data_items[$i]['desc']['img_small'] = $img_srcs[0]->src;
            }
        }
        $data['items'] = $data_items;    
        // Вычисляем следующую страницу
        $data['last_page'] = TRUE; // Для вычисления последней страницы
        foreach($html->find('a') as $sub_link) 
        {
            if(preg_match("/^&rarr;$/", $sub_link->innertext))
            {
                $data['last_page'] = FALSE;
                // Ссылка на следующую страницу
                $next_link = str_replace('&amp;', '&', $base_link.$sub_link->href);
                array_unshift($data['link'],$next_link);
                break;
            }
        }
        array_unshift($data['link'],$data_items[0]['desc']['item_link']);
        array_unshift($data['func'],'parse_item');
        // Продолжаем парсить уже карточку товара
        $this->_set_data($data);
        
//var_dump($data);echo 'get_page 1 <hr>';
        
        $link = isset($data['link'][0])?$data['link'][0]:'';
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
    }

    /**
     * Парсинг карточки товара 
     */
    function parse_item()
    {
       
        $data = $this->_get_data();
//        var_dump($data);
//        exit;
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }

        // Исходные данные
        $data_item = array_shift($data['items']); // Ссылка на главную страницу
        
        if(!$data_item)
//        if(TRUE)
        {
            // Если не находим 'страницу'элемент в массиве $data['items'], то продолжаем поиск уже со следующей страницы категории
            array_shift($data['func']);
            array_shift($data['link']);
            // Если это последняя страница, то переходим на главную страницу
            if($data['last_page'])
            {
                $data['last_page'] = FALSE;
                array_shift($data['func']);
            }
            $this->_set_data($data);

//var_dump($data);echo 'parse_item 2 <hr>';
            
            $link = isset($data['link'][0])?$data['link'][0]:'';
            return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link);
        }

        // Переходим на карточку товара
        $item_link = $data_item['desc']['item_link'];
        // Если ссылка на товар в данных товара и текущая ссылка совпадают
        // то очищаем текущую ссылку
//        if($item_link == $data['link'][0])
//        {
            array_shift($data['link']);
//        }
        // Если присутствует ссылка на сдедующий товар в данных товара
        // то устанавливаем ее в качестве текущей ссылки
        if(isset($data['items'][0]['item_link']))
        {
            array_unshift($data['link'],$data['items'][0]['item_link']);
        }
        else
        {
            array_unshift($data['link'],'End Items');
        }
        $html = file_get_html($item_link);

        // Див-контейнер с карточкой товара
        $items = $html->find('div[id=catalogue]');
        $item = $items[0];

        // Ссылка на рисунок товара
//        $img_links = $item->find('a');
//        foreach ($img_links as $img_link)
//        {
//            if (preg_match("/src=/", $img_link->innertext))
//            {
//                $data_item['desc']['img_large'] = $img_link->href;
//            }
//        }

        // Ссылка на тизер рисунка в карточке товара
        $teaser_links = $item->find('img');
        foreach ($teaser_links as $teaser_link)
        {
            if (preg_match("/medium/", $teaser_link->src))
            {
                $data_item['desc']['img_medium'] = $teaser_link->src;
                $data_item['desc']['img_large'] = str_replace('medium', 'large', $teaser_link->src);
            }
        }

        // Детали товара
        $details = $item->find('p');
        $data_item['material'] = '';
        $data_item['proba'] = '';
        $data_item['average_weight'] = '';
        $data_item['vstavki'] = '';
        
        foreach ($details as $detail)
        {
            if (preg_match("/Шифр/", $detail->innertext))
            {
                $spans = $detail->find('span');
                $span = $spans[0];
                $data_item['artikul'] = $span->innertext;
            }
            if (preg_match("/Материал/", $detail->innertext))
            {
                $spans = $detail->find('span');
                $span = $spans[0];
                $data_item['material'] = $span->innertext;
            }
            if (preg_match("/Проба/", $detail->innertext))
            {
                $spans = $detail->find('span');
                $span = $spans[0];
                $data_item['proba'] = $span->innertext;
            }
            if (preg_match("/Ср\. вес/", $detail->innertext))
            {
                $spans = $detail->find('span');
                $span = $spans[0];
                $data_item['average_weight'] = $span->innertext;
            }
            if (preg_match("/Вставки/", $detail->innertext))
            {
                $span = $detail->next_sibling('p');
                $data_item['vstavki'] = $span->innertext;
            }
        }
        
        
        // Вставляем запись
        
        $img_small = $data_item['desc']['img_small'];
        $data_item['desc'] = json_encode($data_item['desc']);
        
        $data_item['name'] = $data_item['artikul'];
        $data_item['alias'] = JApplication::stringURLSafe($data_item['name']);
        $data_item['category_id'] = $data['category']['category_id'];
        $data_item['zavod_id'] = isset($data['zavod_id'])?$data['zavod_id']:'1';
        
        $product_model = new UvelirModelProduct;
        $save_item = $product_model->save_product($data_item);
        
        $msg = '';
        if(isset($data['items'][0]['desc']['img_small']))
        {
            $msg .= '<img src="'.$img_small.'" style="float:left;">';
        }
        $color = $save_item?'green':'red';
        $msg .= '
            <table style="color:'.$color.'">
                <tr>
                    <th>Артикул</th>
                    <td>'.$data_item['artikul'].'</td>
                </tr>
                <tr>
                    <th>Материал</th>
                    <td>'.$data_item['material'].'</td>
                </tr>
                <tr>
                    <th>Проба</th>
                    <td>'.$data_item['proba'].'</td>
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
        $this->_set_data($data);
        
        $link = isset($data['link'][0])?$data['link'][0]:'';
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link.'<hr/>'.$msg);
    }
  
    /**
     * Сохраняем данные перед выходом
     * @param array $data 
     */
    private function _set_data($data)
    {
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
    
    
    /**
     * Парсинг одной категории
     * напр.: Обручальные кольца 
     * http://www.ju-ur.ru/?category=35&class=shop_items&catalogue_id=2 
     */
    public function parse_one_catrgory($data=NULL)
    {
        if(!isset($data))
        {
          $data = $this->_get_data();   
        }

        if(!isset($data['base_link']))
        {
            return array(1,  JText::_('COM_UVELIR_WRONG_PARSE_DATA')); 
        }
        // Исходные данные
        $base_link = $data['base_link']; // Ссылка на главную страницу
        $page_link = $data['page_link'][0]; // Ссылка на страницу с товарами

        // Переходим на страницу списка товаров
        $html = file_get_html($page_link);
        if(!$html)
        {
            // Выход
            return array(1,  JText::_('COM_UVELIR_END_PARSE_CATEGORY')); 
        }
        
        // Корневой див для тизера товара
        $items = $html->find('div[class=good_wrap]'); 
        if(!$items)
        {
            // Выход
            return array(1,  JText::_('COM_UVELIR_END_PARSE_CATEGORY')); 
        }
        
        // Перебираем все тизеры товаров на странице
        $data_items = array();
        for ($i=0; $i<count($items);$i++)
        {
            // Данные краткого вывода товара
            $data_items[$i]['desc'] = array(
                'item_link' => '',
                'img_small' => '',
            );
            // Ссылка на полную карточку товара
            $item_links = $items[$i]->find('a');
            if($item_links)
            {
                $data_items[$i]['desc']['item_link'] = $base_link.str_replace('&amp;', '&', $item_links[0]->href);
            }
            // Ссылка на тизер товара
            $img_srcs = $items[$i]->find('img');
            if($img_srcs)
            {
                $data_items[$i]['desc']['img_small'] = $img_srcs[0]->src;
            }
        }
        $data['items'] = $data_items;    
        // Вычисляем следующую страницу
        $data['last_page'] = TRUE; // Для вычисления последней страницы
        foreach($html->find('a') as $sub_link) 
        {
            if(preg_match("/^&rarr;$/", $sub_link->innertext))
            {
                $data['last_page'] = FALSE;
                // Ссылка на следующую страницу
                $next_link = str_replace('&amp;', '&', $base_link.$sub_link->href);
                array_unshift($data['link'],$next_link);
                break;
            }
        }
        array_unshift($data['link'],$data_items[0]['desc']['item_link']);
        array_unshift($data['func'],'parse_item');
        // Продолжаем парсить уже карточку товара
        $this->_set_data($data);
        
//var_dump($data);echo 'get_page 1 <hr>';
        
        $link = isset($data['link'][0])?$data['link'][0]:'';
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
        
    }
}


