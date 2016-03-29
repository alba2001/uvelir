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
class UvelirParseZavod_6
{

    private $_file_data;
    private $_file_category;
    private $_base_link;
    private $_base_category_link;
    private $_zavod_id;
    
    public function __construct() {
        $this->_zavod_id = '6';
        $this->_file_data = JPATH_ROOT.DS.'tmp'.DS.'parse_'.$this->_zavod_id.'_data.txt';
        $this->_file_category = JPATH_ROOT.DS.'tmp'.DS.'parse_'.$this->_zavod_id.'_category.txt';
        $this->_base_link = 'http://adamas.ru/';
        $this->_base_category_link = 'http://catalogue.adamas.ru';
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
        $_categories = array(
            array(
                'link'=>'http://catalogue.adamas.ru/ru/catalogue/209/',
                'name'=>'Сборные украшения'
            ),
            array(
                'link'=>'http://catalogue.adamas.ru/ru/catalogue/220/',
                'name'=>'Цепи и браслеты'
            )
        );
        
        // Преобразовываем массив к удобному для обработки виду
        // и, не теряя времени, записываем категории в БД
        $categories = array();
        $k = 0;
        foreach($_categories as $category)
        {
            // Создаем категорию
            $name = $category['name'];
            
            list($category_id, $category_path) = $this->_create_category($name);
            
            if(!$category_id)
            {
                // Не смогли сохранить категорию
                return array(0,  JText::_('COM_UVELIR_CANOT_SAVE_CATEGORY'));
            }
            $categories[] = array(
                'name' =>  $name,
                'link' =>  $category['link'],
                'category_id' => $category_id,
                'category_path' => $category_path,
                'items' => $this->_get_category_items($category['link'], $category_id, $category_path, $k)
            );
            $k++;
        }
        
        return $categories;
    }
    
    /**
     * Получаем подкатегории
     * @param type $_items
     * @return type 
     */
    private function _get_category_items($_link, $id, $path, $k)
    {
        
        $items = array();
        // С помощью парсинга получаем линки на подкатегории
        $html = file_get_html($_link);
        $sidebars = $html->find('div[class=menu-sitemap-tree]');
        $open_lis = $sidebars[0]->find('li[class=open]');
        $item_lis = $open_lis[0]->find('li[class=noselected]');
//        foreach ($item_lis as $item_li)
        for ($i = 0; $i < count($item_lis); $i++)
        {
            $item_li = $item_lis[$i];
            $a_items = $item_li->find('a');
            $name = trim($a_items[0]->innertext);
            $link = $this->_base_category_link.$a_items[0]->href;
            $sub_items = array();
            
            // Создаем категорию
            list($category_id, $category_path) = $this->_create_category($name, $id, $path, '3');
            if(!$category_id)
            {
                // Не смогли сохранить категорию
                return array(0,  JText::_('COM_UVELIR_CANOT_SAVE_CATEGORY'));
            }

            // Если это двойная категория (Цепи и Браслеты) создаем подкатегории
            if($k)
            {
                for($j = 0; $j < 2; $j++)
                {
                    $sub_category_name = $j?'Цепи':'Браслеты';
                    list($sub_category_id, $sub_category_path) = $this->_create_category($sub_category_name, $category_id, $category_path, '4');
                    if(!$sub_category_id)
                    {
                        // Не смогли сохранить категорию
                        return array(0,  JText::_('COM_UVELIR_CANOT_SAVE_CATEGORY'));
                    }

                    $sub_items[$j] = array(
                        'name'=>$sub_category_name,
                        'link'=>$link,
                        'category_id' => $sub_category_id,
                        'category_path' => $sub_category_path,
                    );
                }
            }
            
            $items[$i] = array(
                'name'=>$name,
                'link'=>$link,
                'category_id' => $category_id,
                'category_path' => $category_path,
                'sub_items'=>$sub_items
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
            'zavod'=>$this->_zavod_id,
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
        
         // Переходим на первую страницу категории
        $link = $data['categories'][0]['link'];
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
        
        
        // Сохраняем данные
        array_unshift($data['func'], 'get_category_items_page');
        $this->_set_data($data);

        $link = $data['category']['link'];
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
            
        // Парсим страницу со спискрм изделий
        $link = $data['sub_category']['link'];

//            $first_page_link = $this->_get_first_page_link($link);
//            array_unshift($data['func'], 'parse_page');
//            $data['link'] = $first_page_link;
//            
        // Ищем страницы 
        $data['page_links'] = $this->_get_page_links($link);
        array_unshift($data['func'], 'get_page_link');

        $data['link'] = $data['page_links'][0];
        
        $data['category_id'] = array();
        if(!$data['sub_category']['sub_items'])
        {
            $data['category_id'][0] = $data['sub_category']['category_id'];
        }
        else
        {
            $data['category_id'] = array(
                $data['sub_category']['sub_items'][0]['category_id'],
                $data['sub_category']['sub_items'][1]['category_id'],
            );
        }
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
        $html = file_get_html($_link);
        $divs = $html->find('div[class=page-nav-top]');
//        $a_pages = $divs[0]->find('a[class=catalog-pages-num]');
        if($divs)
        {
            // Ссылка на последнюю страница
            $a_last_pages = $divs[0]->find('a[class=catalog-pages-lst]'); 
            if($a_last_pages)
            {
                $a_last_page_href = $a_last_pages[0]->href;
                $ar_href = explode('=', $a_last_page_href);
                for($i = 2; $i <= (int)$ar_href[1]; $i++)
                {
                    $page_links[] = $this->_base_category_link.$ar_href[0].'='.$i;
                }
            }
        }
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
        $html = file_get_html($_link);
        $divs = $html->find('div[class=article]');
        $page_items = array();
        foreach($divs as $div)
        {
            $a_hrefs = $div->find('a');
            $href = $this->_base_category_link.$a_hrefs[1]->href;
            $imgs = $a_hrefs[1]->find('img');
            $src = '';
            if(isset($imgs[0]))
            {
                $src = $this->_base_category_link.$imgs[0]->src;
                
            }
            else
            {
//                echo 'Нет рисунка';
//                echo $href;
//                exit;
            }
            $page_items[] = array(
                'link'=>$href,
                'src'=>$src,
            );
        }
        $data['page_items'] = $page_items;
        
        // Сохраняем данные
        array_unshift($data['func'], 'parse_page');
        $data['link'] = $data['page_items'][0]['link'];
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
        $page_item = array_shift($data['page_items']);
        $link = $page_item['link'];
        
        $html = file_get_html($link);
        
        // Находим  и записываем необходимые данные для карточки изделия
        $data_item = $this->_get_data_item($html, $data['category_id'], $page_item);

        foreach($data_item as $_data_item)
        {
            if($_data_item)
            {
                $this->_add_items($_data_item);
            }
        }
        $msg = $this->_get_msg($data_item[0]);
        
        $this->_set_data($data);
        
        $link = isset($data['page_items'][0]['link'])?$data['page_items'][0]['link']:'Переходим к следующей категории';
        return array(2,  $msg.'<hr>'.JText::_('COM_UVELIR_OPEN_PAGE').': '.$link); // Продолжаем парсинг
        
    }

    /**
     * Заполнение массива карточки товара
     */
    private function _get_data_item($html, $category_id, $page_item)
    {
        $data_item = array(
            'artikul'=>'',
            'title'=>'',
            'desc'=>array(
                'img_medium'=>'',
                'img_large'=>'',
                'img_small'=>$page_item['src'],
                'item_link'=>$page_item['link'],
            ),
            'material'=>'',
            'pokrytije'=>'',
            'proba'=>'',
            'average_weight'=>'',
            'vstavki'=>'',
            'razmer'=>'',
            'opisanije'=>'',
            'category_id'=>'',
            'zavod_id'=>$this->_zavod_id
        );
        $data_item2 = array();
        
        $divs = $html->find('div[class=catalog-element]');
        
        // Большой рисунок
        $img_divs = $divs[0]->find('div[class=element-img]');
        $imgs = $img_divs[0]->find('img');
        if(isset($imgs[0])) // Рисунка может и не быть
        {
            $data_item['desc']['img_medium'] = $data_item['desc']['img_large'] 
                                    = $this->_base_category_link.$imgs[0]->src;
        }
        
        // Див атрибутов
        $attr_divs = $divs[0]->find('div[class=element-attr]');
        $attr_tables = $attr_divs[0]->find('table');
        $weights_tables = $attr_divs[0]->find('table[class=weights]');
        
        // Тайтл
        $title_tds = $attr_tables[0]->find('td[class=elprop-value]');
        $title_hrefs = $title_tds[0]->find('a');
        $data_item['title'] = $title_hrefs[1]->innertext;
        
        // Артикул
        $data_item['artikul'] = trim($title_tds[1]->innertext);


        // Материал
        $material_tds = $attr_tables[count($attr_tables)-1]->find('td[class=elprop-value]');
        preg_match("/^(.+)<br/", trim($material_tds[0]->innertext), $regs);
        $material_code = mb_substr($regs[1], 1,2);
        $color_cod = mb_substr($regs[1], 3,2);
        list($data_item['material'], $data_item['proba']) = $this->_get_proba($material_code);
        
        // Описание
        $data_item['opisanije'] = 'Цвет: '.$this->_get_color(trim($color_cod), is_array($category_id));
        
        
        // Вычисляем размер и средний вес
        if(count($attr_tables) == 2) // Если нет отдельных таблиц с размерами определяем только средний вес
        {
            $data_item['average_weight'] = str_replace(',', '.', trim($title_tds[3]->innertext));
        }
        else
        {
            for($i = 0; $i < count($weights_tables); $i++)
            {
                list($razmer[$i], $average_weight[$i]) = $this->_get_razmer_and_weigth($weights_tables[$i]);
            }
            $data_item['razmer'] = $razmer[0];
            $data_item['average_weight'] = $average_weight[0];
        }
        
        $data_item['name'] = $data_item['artikul'];
        $data_item['alias'] = JApplication::stringURLSafe($data_item['name']);
        $data_item['desc'] = json_encode($data_item['desc']);
        $data_item['category_id'] = $category_id[0];
        
        // Если это двойная категория
        if(isset($category_id[1]) AND $i)
        {
            // Перебиваем артикулы
            $artikul = $data_item['artikul'];
            $data_item['artikul'] = 'Б'.$artikul; // Цепи
            $data_item['name'] = $data_item['artikul'];
            $data_item['alias'] = JApplication::stringURLSafe($data_item['name']);
            
            // Заполняем второй массив
            $data_item2 = $data_item;
            $data_item2['razmer'] = $razmer[1];
            $data_item2['average_weight'] = $average_weight[1];
            $data_item2['artikul'] = 'Ц'.$artikul; // Браслеты
            $data_item2['name'] = $data_item2['artikul'];
            $data_item2['alias'] = JApplication::stringURLSafe($data_item2['name']);
            $data_item2['category_id'] = $category_id[1];
        }
        
        return array($data_item, $data_item2);
    }

    /**
     * Вычисляем размер и средний вес
     * @param object $attr_tables
     * @return array
     */
    private function _get_razmer_and_weigth($weights_table)
    {
        $razmer = '';
        $average_weight = '';
        $trs = $weights_table->find('tr');
        foreach ($trs as $tr)
        {
            $td_headers = $tr->find('td[class=el-weignt-name]');
            if($td_headers)
            {
                // Размер
                if(preg_match("/Размер/",$td_headers[0]))
                {
                    $tds = $tr->find('td[class=el-weignt-value]');
                    $attr = array();
                    foreach ($tds as $td)
                    {
                        $attr[] = $td->innertext;
                    }
                    $razmer = implode(',', $attr);
                }

                // Средний вес
                if(preg_match("/Вес/",$td_headers[0]))
                {
                    $tds = $tr->find('td[class=el-weignt-value]');
                    $attr = array();
                    foreach ($tds as $td)
                    {
                        $attr[] = str_replace(',', '.', $td->innertext);
                    }
                    $average_weight = implode(',', $attr);
                }
            }
        }
        
        return array($razmer, $average_weight);
    }

    /**
     * Вывод сообщения с параметрами изделия
     * @param type $data_item
     * @return string
     */
    private function _get_msg($data_item)
    {
        $desc = json_decode($data_item['desc'],TRUE);
        $msg = '';
        if(isset($desc['img_small']) AND $desc['img_small'])
        {
            
            $msg .= '<img src="'.$desc['img_small'].'" height="100" style="float:left;">';
        }
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
                    <th>Описание</th>
                    <td>'.$data_item['opisanije'].'</td>
                </tr>
                <tr>
                    <th>Проба</th>
                    <td>'.$data_item['proba'].'</td>
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
    
    /**
     * Возвращаем материал и пробу
     * @param array $material_proba
     * @return array 
     */
    private function _get_proba($proba_kod)
    {
        $ar_material_proba = array('','');
        $cods = array(
            'А0'=>array('Золото',   '999'),
            'А9'=>array('Золото',   '958'),
            'А8'=>array('Золото',   '916'),
            'А7'=>array('Золото',   '750'),	 	
            'А5'=>array('Золото',   '585'),	 	
            'А4'=>array('Золото',   '583'),	 	
            'А3'=>array('Золото',   '500'),	 	 	 
            'А2'=>array('Золото',   '375'),	 	 	 
            'C0'=>array('Серебро',  '999'),
            'C9'=>array('Серебро',  '960'),
            'C8'=>array('Серебро',  '925'),
            'C7'=>array('Серебро',  '875'),
            'C6'=>array('Серебро',  '830'),
            'C5'=>array('Серебро',  '800')
        );
        if(isset($cods[$proba_kod]))
        {
            $ar_material_proba = array($cods[$proba_kod][0],$cods[$proba_kod][1]);
        }
         
        return $ar_material_proba;
    }
    
    private function _get_color($color_cod, $tsepi)
    {
        $color = '';
        if($tsepi)
        {
            switch ($color_cod)
            {
                case '1':
                    $color = 'Красный';
                    break;
                case '3':
                    $color = 'Жёлтый';
                    break;
                case '8':
                    $color = 'Белый';
                    break;
                default :
                    $color = '';
            }
        }
        else
        {
            switch ($color_cod)
            {
                case '0':
                    $color = 'Красный';
                    break;
                case '1':
                    $color = 'Белый';
                    break;
                case '5':
                    $color = 'Жёлтый';
                    break;
                case '01':
                    $color = 'Комбинирование сплавов: красный + белый';
                    break;
                case '02':
                    $color = 'полотно красного цвета с нанесением рисунка белого цвета';
                    break;
                case '04':
                    $color = 'комбинирование сплавов: красный + зелёный';
                    break;
                case '05':
                    $color = 'Комбинирование сплавов: красный + жёлтый';
                    break;
                case '06':
                    $color = 'Комбинирование сплавов: красный + белый + жёлтый';
                    break;
                case '19':
                    $color = 'комбинирование сплавов: белый + чёрный';
                    break;
                case '51':
                    $color = 'Комбинирование сплавов: белый + жёлтый';
                    break;
                default :
                    $color = '';
            }
            
        }
        return $color;
    }
}

