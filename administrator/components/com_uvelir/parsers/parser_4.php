<?php
/**
 * @version     1.0.0
 * @package     com_uvelir
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */

/**
 *$opts=array('http'=>array('method'=>"GET",'header'=>"Accept-language: en\r\n"."Cookie: cookie_name=cookie_value\r\n",'user_agent'=>'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10.4; en-US; rv:1.9.2.28) Gecko/20120306 Firefox/3.6.28'));
$context=stream_context_create($opts);
$data=file_get_html('http://site.ru/',false,$context); 
 */
// No direct access
defined('_JEXEC') or die;

include_once('simple_html_dom.php');
require_once JPATH_ADMINISTRATOR.'/components/com_uvelir/models/category.php'; 
require_once JPATH_ADMINISTRATOR.'/components/com_uvelir/models/product.php'; 
jimport('joomla.filesystem.file');

/**
 * zavod parser class.
 * Красная Пресня
 */
class UvelirParseZavod_4
{

    private $_file_data;
    private $_file_category;
    private $_base_category_link;
    private $_context;
    
    public function __construct() {
        $this->_file_data = JPATH_ROOT.DS.'tmp'.DS.'parse_4_data.txt';
        $this->_file_category = JPATH_ROOT.DS.'tmp'.DS.'parse_4_category.txt';
        $this->_base_link = 'http://kr-presnya.ru/';
        $this->_base_category_link = 'http://kr-presnya.ru/cat.php?id=';
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Accept-language: en\r\n"
                ."Cookie: sortcookie=sortart_down; perpagecookie=12; path=/; domain=kr-presnya.ru\r\n",
                'user_agent'=>'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10.4; en-US; rv:1.9.2.28) Gecko/20120306 Firefox/3.6.28')
            );
        $this->_context = stream_context_create($opts);
    }
/**
 * =============================================================================
 * Этап создания категорий 
 * ============================================================================= 
 */

    /**
     * Список категорий
     * заполняется вручную
     * @return type 
     */
    
    private function _get_categories()
    {
        $_categories =  array(
            '4365' => array('name'=>'Золото', 'items' => array(
                '4400'=> array( 'name'=>'Броши', 'items'=>array()),
                '4368'=> array( 'name'=>'Кольца', 'items'=>array()),
                '4369'=> array( 'name'=>'Серьги', 'items'=>array()),
                '4402'=> array( 'name'=>'Колье', 'items'=>array()),
                '4370'=> array( 'name'=>'Подвески', 'items'=>array()),
                '6827'=> array( 'name'=>'Браслеты', 'items'=>array()),
                '6828'=> array( 'name'=>'Зажимы', 'items'=>array()),
                '6829'=> array( 'name'=>'Запонки', 'items'=>array()),
                '6830'=> array( 'name'=>'Цепи машинной вязки', 'items'=>array()),
                '6831'=> array( 'name'=>'Цепи ручной вязки', 'items'=>array()),
                '11822'=> array( 'name'=>'Сувенирная группа', 'items'=>array())
            )),


            '4366' => array('name'=>'Серебро', 'items' => array(
                '4397'=> array( 'name'=>'Броши', 'items'=>array()),
                '4398'=> array( 'name'=>'Кольца', 'items'=>array()),
                '4399'=> array( 'name'=>'Серьги', 'items'=>array()),
                '4403'=> array( 'name'=>'Колье', 'items'=>array()),
                '4401'=> array( 'name'=>'Подвески', 'items'=>array()),
                '11420'=> array( 'name'=>'Шнурки', 'items'=>array()),
                '7109'=> array( 'name'=>'Браслеты', 'items'=>array()),
                '7110'=> array( 'name'=>'Зажимы', 'items'=>array()),
                '7111'=> array( 'name'=>'Запонки', 'items'=>array()),
                '9717'=> array( 'name'=>'Столовое серебро', 'items'=>array(
                    '9719'=> array( 'name'=>'Набор -Ампир'),
                    '12435'=> array( 'name'=>'Набор -Ампир- золоченый'),
                    '9720'=> array( 'name'=>'Набор -Вензель'),
                    '12441'=> array( 'name'=>'Набор -Вензель- золоченый'),
                    '9721'=> array( 'name'=>'Набор -Весна'),
                    '11585'=> array( 'name'=>'Набор -Весна- золоченый'),
                    '9722'=> array( 'name'=>'Набор -Классик'),
                    '9723'=> array( 'name'=>'Набор -Лада'),
                    '11512'=> array( 'name'=>'Набор -Отечество'),
                    '11584'=> array( 'name'=>'Набор -Отечество- золоченый'),
                    '12975'=> array( 'name'=>'Набор -Элегант'),
                    '13033'=> array( 'name'=>'Набор -Элегант- позолоченный'),
                )),
                '11713'=> array( 'name'=>'Ложки', 'items'=>array()),
                '11714'=> array( 'name'=>'Винный ассортимент', 'items'=>array()),
                '11715'=> array( 'name'=>'Посуда для воды', 'items'=>array()),
                '11716'=> array( 'name'=>'Подносы', 'items'=>array()),
                '11717'=> array( 'name'=>'Чайный, кофейный ассортимент', 'items'=>array()),
                '11718'=> array( 'name'=>'Ионизаторы', 'items'=>array()),
                '11719'=> array( 'name'=>'Солонка', 'items'=>array()),
                '11720'=> array( 'name'=>'Миски', 'items'=>array()),
                '11721'=> array( 'name'=>'Вазы', 'items'=>array()),
                '12378'=> array( 'name'=>'Икорница', 'items'=>array()),
                '7112'=> array( 'name'=>'Цепи машинной вязки', 'items'=>array(
                    '9859'=> array( 'name'=>'Кордовая тройная'),
                    '9860'=> array( 'name'=>'Овал двойной'),
                    '9861'=> array( 'name'=>'Панцирная граненая'),
                    '9862'=> array( 'name'=>'Ромб двойной граненый'),
                    '9863'=> array( 'name'=>'Ромб двойной сингапур'),
                    '13513'=> array( 'name'=>'Питон'),
                )),
                '7113'=> array( 'name'=>'Цепи ручной вязки', 'items'=>array()),
                '7344'=> array( 'name'=>'Брелоки', 'items'=>array()),
                '7491'=> array( 'name'=>'С полудрагоценными вставками', 'items'=>array()),
                '12446'=> array( 'name'=>'Изделия с покрытием родием', 'items'=>array()),
                '9841'=> array( 'name'=>'Сувенирная группа', 'items'=>array())
            )),

            '4367'=> array('name'=>'Бижутерия', 'items' => array(
                '4404'=> array( 'name'=>'Броши', 'items'=>array()),
                '4405'=> array( 'name'=>'Кольца', 'items'=>array()),
                '4406'=> array( 'name'=>'Серьги', 'items'=>array()),
                '4407'=> array( 'name'=>'Колье', 'items'=>array()),
                '4408'=> array( 'name'=>'Подвески', 'items'=>array()),
                '4474'=> array( 'name'=>'Шнурки', 'items'=>array()),
                '4506'=> array( 'name'=>'Браслеты', 'items'=>array()),
                '4470'=> array( 'name'=>'Зажимы', 'items'=>array()),
                '10581'=> array( 'name'=>'Запонки', 'items'=>array()),
                '4469'=> array( 'name'=>'Брелоки', 'items'=>array()),
                '4471'=> array( 'name'=>'Сувенирная группа', 'items'=>array()),
                '4472'=> array( 'name'=>'Цепи машинной вязки', 'items'=>array(
                    '10655'=> array( 'name'=>'Кордовая тройная'),
                    '10656'=> array( 'name'=>'Ромб двойной граненый'),
                    '10657'=> array( 'name'=>'Панцирная граненая'),
                )),
                '4473'=> array( 'name'=>'Цепи ручной вязки', 'items'=>array()),
            )),
            '11729'=> array('name'=>'Сувениры на 2013 год', 'items' => array())
        );
        
        // Преобразовываем массив к удобному для обработки виду
        // и, не теряя времени, записываем категории в БД
        $categories = array();
        
        foreach($_categories as $key=>$value)
        {
            if($key == '4366') // Сереб
            {
//                var_dump($value);
//                exit;
            }
            // Создаем категорию
            list($category_id, $category_path) = $this->_create_category($value['name']);
            if(!$category_id)
            {
                // Не смогли сохранить категорию
                return array(0,  JText::_('COM_UVELIR_CANOT_SAVE_CATEGORY'));
            }
            $categories[] = array(
                'key' =>  $key,
                'name' =>  $value['name'],
                'category_id' => $category_id,
                'category_path' => $category_path,
                'category_path' => $category_path,
                'items' => $this->_get_category_items($value['items'], $category_id, $category_path)
            );
        }
        return $categories;
    }
    
    /**
     * Получаем подкатегории
     * @param type $_items
     * @return type 
     */
    private function _get_category_items($_items, $id, $path)
    {
        $items = array();
        foreach ($_items as $key=>$value)
        {
            // Создаем категорию
            list($category_id, $category_path) = $this->_create_category($value['name'], $id, $path, '3');
//            var_dump('category_id: '.$category_id, 'category_path: '.$category_path);
//            echo '<hr>';
            if(!$category_id)
            {
                // Не смогли сохранить категорию
                return array(0,  JText::_('COM_UVELIR_CANOT_SAVE_CATEGORY'));
            }
            
            $items[] = array(
                'key'=>$key,
                'value'=>$value,
                'category_id' => $category_id,
                'category_path' => $category_path,
                'items' => $this->_get_subcategory_items($value['items'], $category_id, $category_path)
            );
        }
//            exit;
        return $items;
    }

    /**
     * Получаем подкатегории подкатегорий
     * @param type $_items
     * @return type 
     */
    private function _get_subcategory_items($_items, $id, $path)
    {
        $items = array();
        foreach ($_items as $key=>$value)
        {
            // Создаем категорию
            list($category_id, $category_path) = $this->_create_category($value['name'], $id, $path, '4');
            if(!$category_id)
            {
                // Не смогли сохранить категорию
                return array(0,  JText::_('COM_UVELIR_CANOT_SAVE_CATEGORY'));
            }
            
            $items[] = array(
                'key'=>$key,
                'value'=>$value,
                'category_id' => $category_id,
                'category_path' => $category_path,
            );
        }
        return $items;
    }

    /**
    *  Создаем категорию
    * @param string тайтл категории
    * @param int ID родительской категории
    * @param string as int уровень вложености категории
    */
    private function _create_category($name, $parent_id = 0, $parent_path = '', $level = 2)
    {
        $category_data = $this->_get_category_data();
        if(!$parent_id)
        {
            $parent_id = $category_data['parent_id'][0];
        }
        if(!$parent_path)
        {
            $parent_path = $category_data['path'][0];
        }
        $category_model = new UvelirModelCategory;
        $category_save_data = array(
            'name'=>  $name,
            'parent_path'=>$parent_path,
            'parent_id'=>$parent_id,
            'level'=>$level,
            'zavod'=>'4',
        );
        // Сохраняем категорию
        list($result, $category_created) = $category_model->create_category($category_save_data);
        if(!$result)
        {
            // Не смогли сохранить категорию
            return array(0,0);
        }
        $category_path = $parent_path.'/'.JApplication::stringURLSafe($name);
        return  array($category_created['id'], $category_path);
    }
    
    /**
     * Создание категорий
     */
    public function main_page()
    {
        $data = $this->_get_data();
        
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }
        
        // Получаем список категорий
        $data['categories'] = $this->_get_categories();
        
        // Сохраняем данные
        $data['func'][0] = "get_category_page";
        $this->_set_data($data);
        
         // Переходим на страницу типов изделий
        $link = $this->_base_link.'?selmat=1&material=1&colortype=0';
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
    }
    
/**
 * =============================================================================
 * Этап парсинга 
 * ============================================================================= 
 */

    /**
     * Страница главной категории
     * @return type 
     */
    public function get_category_page()
    {
        
        $data = $this->_get_data();
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }
        // Если закончились категории, выходим из парсинга
        if(!$data['categories'])
        {
            return array(1,JText::_('COM_UVELIR_PARSE_SUCCES'));
        }
        
        $data['category'] = array_shift($data['categories']);
        
        // Определяем материал
        switch ($data['category']['key'])
        {
            case '4365':
                $data['material'] = 'Золото';
                break;
            case '4366':
                $data['material'] = 'Серебро';
                break;
            default :
                $data['material'] = '';
        }
        
        // Если нет подкатегорий, напр.: Сувениры на 2013 год
        if(!$data['category']['items'])
        {
            // Парсим страницу со списком изделий
            $link = $this->_base_category_link.$data['category']['key'];

//            $first_page_link = $this->_get_first_page_link($link);
//            array_unshift($data['func'], 'parse_page');
//            $data['link'] = $first_page_link;
            $data['page_links'] = $this->_get_page_links($link);
            array_unshift($data['func'], 'get_page_link');

            $data['link'] = $data['page_links'][0];
            
            $data['category_id'] = $data['category']['category_id'];
            $this->_set_data($data);

            $link = $data['link'];
            return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
            
        }
        
        // Сохраняем данные
        array_unshift($data['func'], 'get_category_items_page');
        $this->_set_data($data);

        $link = $this->_base_category_link.$data['category']['key'];
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
    }

    /**
     * Страница подкатегории
     * @return type 
     */
    public function get_category_items_page()
    {
        $data = $this->_get_data();
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }
        // Если закончились подкатегории, переходим на уровень выше
        if(!$data['category']['items'])
        {
            unset($data['category']);
            array_shift($data['func']);
            $this->_set_data($data);
            
            $link = 'To main page';
            return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
        }
        
        $data['sub_category'] = array_shift($data['category']['items']);
        if(!$data['sub_category']['items'])
        {
            
            // Парсим страницу со спискрм изделий
            $link = $this->_base_category_link.$data['sub_category']['key'];

//            $first_page_link = $this->_get_first_page_link($link);
//            array_unshift($data['func'], 'parse_page');
//            $data['link'] = $first_page_link;
//            
            
            $data['page_links'] = $this->_get_page_links($link);
            array_unshift($data['func'], 'get_page_link');

            $data['link'] = $data['page_links'][0];
            
            $data['category_id'] = $data['sub_category']['category_id'];
            $this->_set_data($data);

            $link = $data['link'];
            return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
        }
        
        // Сохраняем данные
        array_unshift($data['func'], 'get_subcategory_items_page');
        $this->_set_data($data);

        $link = $this->_base_category_link.$data['sub_category']['key'];
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
        
    }

    /**
     * Страница подкатегории подкатегории (если есть такая)
     * @return type 
     */
    public function get_subcategory_items_page()
    {
        $data = $this->_get_data();
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }
        
        // Если закончились подкатегории в подкатегориях, переходим на уровень выше
        if(!$data['sub_category_items']['items'])
        {
            unset($data['sub_category_items']);
            array_shift($data['func']);
            $this->_set_data($data);
            
            $link = 'To subcategory page';
            return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
        }
        
        
        $data['sub_category_items'] = array_shift($data['sub_category']['items']);
        
        // Парсим страницу со спискрм изделий
        $link = $this->_base_category_link.$data['sub_category_items']['key'];

//        $first_page_link = $this->_get_first_page_link($link);
        $data['page_links'] = $this->_get_page_links($link);

        // Сохраняем данные
//        array_unshift($data['func'], 'parse_page');
        array_unshift($data['func'], 'get_page_link');
        $data['link'] = $data['page_links'][0];
        $data['category_id'] = $data['sub_category_items']['category_id'];
        $this->_set_data($data);

        $link = $data['link'];
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
    }

    /**
     * Парсинг первой странички со списком товаров
     * находим ссылки на остальные страницы категории
     */
    private function _get_page_links($_link)
    {
        $page_links = array($_link);
        $html = file_get_html($_link,false,$this->_context);
        $links = $html->find('a[class=pges]');
        foreach($links as $link)
        {
            $page_links[] = $this->_base_link.$link->href;
        }
//        if(count($links)>1)
//        {
//            var_dump($_link, count($links), $page_links);
//            exit;
//        }
        return $page_links;
    }
    
    /**
     * Парсинг страницы со списком товаров
     * определение линков на карточки товаров на этой странице
     */
    
    public function get_page_link()
    {
        $data = $this->_get_data();
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }
        
        /**
         *  Если закончились страницы списка товаров
         *  переходим на верхний уровень
         */
        if(!$data['page_links'])
        {
            array_shift($data['func']);
            $this->_set_data($data);
            
            $link = 'End item pages';
            return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
        }
        
        $_link = array_shift($data['page_links']);
        $html = file_get_html($_link,false,$this->_context);
        $links = $html->find('a[class=pict]');
        $page_items = array();
        foreach($links as $link)
        {
            $page_items[] = $this->_base_link.$link->href;
        }
        $data['page_items'] = $page_items;
        
        // Сохраняем данные
        array_unshift($data['func'], 'parse_page');
        $data['link'] = $data['page_items'][0];
        $this->_set_data($data);

        $link = $data['link'];
        return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг

    }

//    /**
//     * Парсинг странички со списком товаров
//     * находим ссылку на первый товар 
//     */
//    private function _get_first_page_link($link)
//    {
//        $html = file_get_html($link,false,$this->_context);
//        $links = $html->find('a[class=pict]');
//        return $this->_base_link.$links[0]->href;
//    }
//
    /**
     * Парсинг страницы карточки изделия
     */
    public function parse_page()
    {
        $data = $this->_get_data();
        if(!$data)
        {
            return array(0,  JText::_('COM_UVELIR_CAN_NOT_PARSE_PAGE'));
        }
        /**
         *  Если закончились карточки товаров
         *  переходим на верхний уровень
         */
        if(!$data['page_items'])
        {
            array_shift($data['func']);
            $this->_set_data($data);
            
            $link = 'End parse item page';
            return array(2,  JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
            
        }
        
//        $link = $data['link'];
        $link = array_shift($data['page_items']);
        
        $html = file_get_html($link, false, $this->_context);
        $links = $html->find('a');
        
        $data['link'] = ''; // Обнуляем ссылку на карточку изделия
        // Находим ссылку на следующую карточку изделия
        foreach($links as $link)
        {
            if (preg_match("/Следующее/", $link->innertext))
            {
                $data['link'] = $this->_base_link.$link->href;
                break;
            }
            
        }
        
        // Находим  и записываем необходимые данные для карточки изделия
        $data_item = $this->_get_data_item($html, $data, $data['link']);
        $this->_add_items($data_item);

        // Сохраняем данные
        if(!$data['link'])
        {
            array_shift($data['func']); // переходим к функции, вызвавшей эту функцию
        }
        $this->_set_data($data);

        $msg = $this->_get_msg($data_item);
        $link = $data['link'];
        return array(2,  $msg.'<hr>'.JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
        
    }

    /**
     * Заполнение массива карточки товара
     */
    private function _get_data_item($html, $data, $link)
    {
        $data_item = array(
            'artikul'=>'',
            'title'=>'',
            'desc'=>array(
                'img_medium'=>'',
                'img_large'=>'',
                'img_small'=>'',
                'item_link'=>$link,
            ),
            'material'=>$data['material'],
            'pokrytije'=>'',
            'proba'=>'',
            'average_weight'=>'',
            'vstavki'=>'',
            'razmer'=>'',
            'opisanije'=>'',
            'category_id'=>$data['category_id'],
            'zavod_id'=>'4'
        );

        $tables = $html->find('table');
        $trs = $tables[7]->find('tr');
        foreach ($trs as $tr)
        {
            $tds = $tr->find('td');
            if(preg_match("/tymba\/b/",$tds[count($tds)-1]))
            {
                $imgs = $tds[count($tds)-1]->find('img');
                $data_item['desc']['img_large'] = $this->_base_link.$imgs[0]->src;
                $ar_src = explode('/',$data_item['desc']['img_large']);
                $ar_src[count($ar_src)-1] = str_replace('b', '', $ar_src[count($ar_src)-1]);
                $data_item['desc']['img_small'] = $data_item['desc']['img_medium'] = implode('/', $ar_src);
            }
            if(count($tds) == 2)
            {
                if(preg_match("/Артикул/",$tds[0]))
                {
                    preg_match("/>(.+)<\/b>/",$tds[1]->innertext,$regs);
                    $data_item['artikul'] = $data_item['title']  = $regs[1];
                }
                if(preg_match("/Вес/",$tds[0]))
                {
                    preg_match("/>(.+)<\/b>/",$tds[1]->innertext,$regs);
                    $data_item['average_weight'] = $regs[1];
                }
                if(preg_match("/Покрытие/",$tds[0]))
                {
                    preg_match("/>(.+)<\/b>/",$tds[1]->innertext,$regs);
                    $data_item['pokrytije'] = $regs[1];
                }
                if(preg_match("/Описание/",$tds[0]))
                {
                    preg_match("/>(.+)<\/b>/",$tds[1]->innertext,$regs);
                    $data_item['opisanije'] = $regs[1];
                }
                if(preg_match("/Размерный/",$tds[0]))
                {
                    preg_match("/>(.+)<\/b>/",$tds[1]->innertext,$regs);
                    $data_item['razmer'] = str_replace(',', '.', $regs[1]);
                    $data_item['razmer'] = str_replace(';', ',', $data_item['razmer']);
                }
                if(preg_match("/Вставка/",$tds[0]))
                {
                    preg_match("/>(.+)<\/b>/",$tds[1]->innertext,$regs);
                    $data_item['vstavki'] = $regs[1];
                }
            }
        }
        $data_item['name'] = $data_item['opisanije']?$data_item['opisanije']:$data_item['title'];
        $data_item['alias'] = JApplication::stringURLSafe($data_item['name']);
        $data_item['desc'] = json_encode($data_item['desc']);
        return $data_item;
    }


    /**
     * Вывод сообщения с параметрами изделия
     * @param type $data_item
     * @return string
     */
    private function _get_msg($data_item)
    {
        $desc = json_decode($data_item['desc'],TRUE);
        $msg = '<img src="'.$desc['img_small'].'" style="float:left;">';
        $color = 'green';
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
                    <th>Размер</th>
                    <td>'.$data_item['razmer'].'</td>
                </tr>
                <tr>
                    <th>Вставки</th>
                    <td>'.$data_item['vstavki'].'</td>
                </tr>
            </table>
            ';
        return $msg;
    }

    /**
     * Записываем изделие в базу
     * @param array данные об изделии
     * @return boolean
     */
    private function _add_items($data_item)
    {
        $result = TRUE;
        if($data_item['alias'])
        {
            $product_model = new UvelirModelProduct;
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


        JFactory::getApplication()->setUserState('com_uvelir.parse', $data);
        if (!JFile::write($this->_file_data, json_encode($data)))
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

