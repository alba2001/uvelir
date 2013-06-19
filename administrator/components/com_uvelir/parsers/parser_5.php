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
class UvelirParseZavod_5
{

    private $_file_data;
    private $_file_category;
    private $_base_link;
    private $_zavod_id;
    
    public function __construct() {
        $this->_zavod_id = '5';
        $this->_file_data = JPATH_ROOT.DS.'tmp'.DS.'parse_'.$this->_zavod_id.'_data.txt';
        $this->_file_category = JPATH_ROOT.DS.'tmp'.DS.'parse_'.$this->_zavod_id.'_category.txt';
        $this->_base_link = 'http://leprekongold.ru/';
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
	array(246=>"Золотой пирсинг", array(
		249=>"В пупок",
		247=>"В нос",
		275=>"В губу, бровь",
	)),
	array(265=>"Золотые цепочки", array(
		405=>"золотые цепочки женские (легкие до 1,5 мм)",
		266=>"средние (от 1,6мм до 3 мм)",
		406=>"тяжелые (более 3,1 мм)",
		273=>"цепи-колье",
		274=>"мужские цепочки",
	)),
	array(234=>"Золотые браслеты", array(
		532=>"с жемчугом",
		235=>"без камней",
		271=>"с камнями",
		407=>"браслеты-цепочки женские",
		421=>"ролексы",
		420=>"на ногу",
		419=>"мужские",
	)),
	array(259=>"Золотые серьги и сережки", array(
		260=>"без камней",
		276=>"с фианитами",
		497=>"с жемчугом",
		264=>"с полудрагоценными камнями",
		294=>"с бриллиантами",
		579=>"Сваровски (Swarovski)",
		582=>"белое золото",
		409=>"кольцами (конго)",
		270=>"длинные, висячие, протяжки и цепочки",
		263=>"гвоздики и пусеты",
		261=>"детские",
	)),
	array(240=>"Золотые кольца", array(
		581=>"белое золото",
		241=>"без вставок(без камней)",
		277=>"с фианитами",
		417=>"с жемчугом",
		245=>"с полудрагоценными камнями",
		244=>"с бриллиантами",
		578=>"Сваровски (Swarovski)",
		422=>"печатки",
		242=>"обручальные кольца",
		577=>"помолвочные",
		517=>"&quot;Спаси и Сохрани&quot;",
	)),
	array(250=>"Золотые подвески и кулоны", array(
		251=>"без камней",
		278=>"с фианитами",
		426=>"с жемчугом",
		258=>"с полудрагоценными камнями",
		257=>"с бриллиантами",
		580=>"Сваровски (Swarovski)",
		252=>"буквы",
		408=>"нательные иконы",
		415=>"кресты",
		416=>"мусульманские",
	)),
	array(301=>"Золотые подвески и кулоны Знаки зодиака", array(
		304=>"Козерог",
		303=>"Водолей",
		313=>"Рыбы",
		317=>"Овен",
		309=>"Телец",
		305=>"Близнецы",
		312=>"Рак",
		310=>"Лев",
		307=>"Дева",
		306=>"Весы",
		311=>"Скорпион",
		308=>"Стрелец",
	)),
	array(238=>"Золотое колье", array(
		239=>"без камней",
		272=>"с камнями",
		559=>"с бриллиантами",
		404=>"ролексы",
	)),
	array(297=>"Бусы из жемчуга", array()),
	array(267=>"Золотые часы", array(
		268=>"женские",
		269=>"мужские",
	)),
	array(410=>"Золотые броши", array(
		411=>"без камней",
		412=>"с камнями",
	)),
	array(425=>"Золотые булавки", array()),
	array(237=>"Золотые зажимы и заколки для галстука", array()),
	array(289=>"Золотые запонки", array(
		515=>"с камнями",
		514=>"без камней",
	)),
	array(382=>"Золотая Коллекция Бест Ювелир", array(
		385=>"Браслеты",
		388=>"Колье",
		384=>"Кольца",
		383=>"Подвески",
		386=>"Серьги",
	)),
	array(565=>"Коллекция  &quot;Beavers&quot;", array(
		567=>"кольца",
		566=>"подвески",
		568=>"серьги",
	)),
	array(369=>"Серебряная Коллекция Бест Ювелир", array(
		371=>"Кольца",
		373=>"Серьги",
		372=>"Подвески(кулоны)",
		370=>"Колье",
		379=>"Браслеты",
	)),
	array(428=>"Серебряные кольца", array(
		511=>"&quot;Спаси и Сохрани&quot;",
		429=>"без камней",
		438=>"с фианитами",
		490=>"с жемчугом",
		437=>"с полудрагоценными камнями",
		489=>"печатки",
		513=>"обручальные кольца",
	)),
	array(458=>"Серебряные серьги", array(
		459=>"без камней",
		462=>"с фианитами",
		516=>"с жемчугом",
		461=>"с полудрагоценными камнями",
		519=>"протяжки",
		531=>"конго",
	)),
	array(443=>"Серебряные подвески ", array(
		444=>"без камней",
		456=>"с фианитами",
		491=>"с жемчугом",
		449=>"с полудрагоценными камнями",
		457=>"фэн-шуй",
		448=>"нательные иконы",
		446=>"кресты",
		447=>"мусульманские",
		555=>"звезда Давида",
	)),
	array(472=>"Знаки зодиака серебро", array(
		477=>"Козерог",
		475=>"Водолей",
		481=>"Рыбы",
		479=>"Овен",
		484=>"Телец",
		473=>"Близнецы",
		480=>"Рак",
		478=>"Лев",
		476=>"Дева",
		474=>"Весы",
		482=>"Скорпион",
		483=>"Стрелец",
	)),
	array(466=>"Серебряные цепочки", array(
		467=>"легкие",
		469=>"средние",
		470=>"цепи-колье",
		468=>"мужские цепи",
	)),
	array(486=>" Серебряное колье", array(
		487=>"без камней",
		488=>"с камнями",
	)),
	array(430=>" Серебряные браслеты", array(
		431=>"без камней",
		450=>"с камнями",
		432=>"браслеты-цепочки",
		436=>"ролексы",
		433=>"жесткие (обручи)",
		435=>"на ногу",
		434=>"мужские",
	)),
	array(454=>"Серебряные броши и булавки", array(
		455=>"броши",
		463=>"булавки",
	)),
	array(451=>"Серебряные брелки", array(
		452=>"для ключей автомобиля",
	)),
	array(464=>"Серебряные зажимы", array()),
	array(465=>"Серебряные запонки", array()),
	array(485=>"Значки серебро", array()),
	array(439=>"Серебряный пирсинг", array(
		440=>"В пупок",
	)),
	array(361=>"Иконы в окладе", array()),
	array(542=>"Серебряные часы", array(
		543=>"женские",
		544=>"мужские",
	)),
	array(391=>"Сувениры из серебра", array(
		537=>"элитные расчёски",
		536=>"статуэтки",
		557=>"ручки серебро",
		553=>"погремушки",
		554=>"рамки для фотографий",
		556=>"монеты",
	)),
	array(321=>"Столовое серебро", array(
		617=>"Рюмки, Бокалы",
		398=>"Серебряные ложечки детские",
		322=>"Ложки",
		552=>"Столовые приборы",
		326=>"Ионизаторы",
	)),
	array(358=>"Бижутерия", array(
		498=>"Браслеты",
		499=>"Бусы",
		500=>"Кольца",
		501=>"Подвески",
		502=>"Серьги",
		503=>"Шнурки",
	)),
	array(365=>"Футляры", array())
);
        
        // Преобразовываем массив к удобному для обработки виду
        // и, не теряя времени, записываем категории в БД
        $categories = array();
        
        foreach($_categories as $category)
        {
            // Создаем категорию
            $key = key($category);
            $name = reset($category);
            
            list($category_id, $category_path) = $this->_create_category($name);
            
            if(!$category_id)
            {
                // Не смогли сохранить категорию
                return array(0,  JText::_('COM_UVELIR_CANOT_SAVE_CATEGORY'));
            }
            $categories[] = array(
                'key' =>  $key,
                'name' =>  $name,
                'category_id' => $category_id,
                'category_path' => $category_path,
                'items' => $this->_get_category_items($category[$key+1], $category_id, $category_path)
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
            list($category_id, $category_path) = $this->_create_category($value, $id, $path, '3');
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
            );
        }
//        var_dump($items);
//            exit;
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
        
//        var_dump($data['categories']);
//        exit;
        // Сохраняем данные
        $data['func'][0] = "get_category_page";
        $this->_set_data($data);
        
         // Переходим на первую страницу категории
        $link = $this->_base_link.'zolotokat'.$data['categories'][0]['key'];
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
        
        
        // Если нет подкатегорий, напр.: Футляры
        if(!$data['category']['items'])
        {
            // Парсим страницу со списком изделий
            $link = $this->_base_link.'zolotokat740'.$data['category']['key'];

            // Ищем страницы 
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

        $link = $this->_base_link.'zolotokat'.$data['category']['key'];
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
        $link = $this->_base_link.'zolotokat740'.$data['category']['key'].'/zolotokat740'.$data['sub_category']['key'];

//            $first_page_link = $this->_get_first_page_link($link);
//            array_unshift($data['func'], 'parse_page');
//            $data['link'] = $first_page_link;
//            
        // Ищем страницы 
        $data['page_links'] = $this->_get_page_links($link);
        array_unshift($data['func'], 'get_page_link');

        $data['link'] = $data['page_links'][0];

        $data['category_id'] = $data['sub_category']['category_id'];
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
        $divs = $html->find('div[class=links]');
        if($divs)
        {
            $links = $divs[0]->find('a');
            foreach($links as $link)
            {
                if(!in_array($link->href, $page_links))
                {
                    $page_links[] = $link->href;
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
        $divs = $html->find('div[id=content]');
        $links = $divs[0]->find('a');
        $page_items = array();
        foreach($links as $link)
        {
            if (preg_match("/zolotoizdel/", $link->href) AND !preg_match("/src=/", $link->innertext))
            {
                $page_items[] = $link->href;
            }
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
        
        $html = file_get_html($link);
        
        // Находим  и записываем необходимые данные для карточки изделия
        $data_item = $this->_get_data_item($html, $data, $link);
        if($data_item)
        {
            $this->_add_items($data_item);
            $msg = $this->_get_msg($data_item);
        }
        else
        {
            $msg = 'Изделие не Красносельского завода';
        }
        
        $this->_set_data($data);
        
        $link = isset($data['page_items'][0])?$data['page_items'][0]:'Переходим к следующей категории';
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
            'material'=>'',
            'pokrytije'=>'',
            'proba'=>'',
            'average_weight'=>'',
            'vstavki'=>'',
            'razmer'=>'',
            'opisanije'=>'',
            'category_id'=>$data['category_id'],
            'zavod_id'=>$this->_zavod_id
        );

        $divs = $html->find('div[id=content]');
        // Тайтл
        $h1s = $html->find('h1');
        $data_item['title'] = $h1s[0]->innertext;
        
        $trs = $divs[0]->find('tr');
        
        $tds = $trs[0]->find('td');
        
        // Рисунки
        $a_imgs = $tds[0]->find('a');
        $data_item['desc']['img_medium'] = $a_imgs[0]->href;
        $data_item['desc']['img_large'] = $a_imgs[0]->href;
        $data_item['desc']['img_small'] = $a_imgs[0]->href;
        
        
        $trs2 = $tds[1]->find('tr');
        foreach ($trs2 as $tr)
        {
            $tds = $tr->find('td');
            if(preg_match("/Код:/",$tds[0]))
            {
                preg_match("/\[(.+)\]/",$tds[1]->innertext,$regs);
                $data_item['artikul']  = $regs[1];
            }
            
            if(preg_match("/Производитель:/",$tds[0]))
            {
                if(!preg_match("/Красносельский/",$tds[1]->innertext,$regs))
//                if(!preg_match("/Тверь/",$tds[1]->innertext,$regs))
                {
                    // Если это не Красносельский юв. з-д Диамант
                    // не записываем данные
                    return FALSE;
                }
            }
            // Вставки
            if(preg_match("/Камень:/",$tds[0]))
            {
                $data_item['vstavki']  = $this->_options_deals($tds[0]);
            }
            
            // Материал
            if(preg_match("/Металл:/",$tds[0]))
            {
                $data_item['material']  = $this->_options_deals($tds[0]);
            }
            
            // Описание
            if(preg_match("/Цвет:/",$tds[0]))
            {
                $data_item['opisanije']  = 'Цвет: '.$this->_options_deals($tds[0]);
            }
            
            // Проба
            if(preg_match("/Проба:/",$tds[0]))
            {
                $data_item['proba']  = $this->_options_deals($tds[0]);
            }
            
            // Вес
            if(preg_match("/Вес:/",$tds[0]))
            {
                $data_item['average_weight']  = $this->_options_deals($tds[0]);
            }
            
        }
        
        $data_item['name'] = $data_item['title'];
        $data_item['alias'] = JApplication::stringURLSafe($data_item['name']);
        $data_item['desc'] = json_encode($data_item['desc']);
        return $data_item;
    }

    private function _options_deals($tds)
    {
        $option_values = array();
        $options = $tds->find('option');
        foreach ($options as $option)
        {
            $option_values[] = preg_replace('/\s+/', ' ', trim($option->innertext));
        }
        return implode(';', $option_values);
    }

        /**
     * Вывод сообщения с параметрами изделия
     * @param type $data_item
     * @return string
     */
    private function _get_msg($data_item)
    {
        $desc = json_decode($data_item['desc'],TRUE);
        $msg = '<img src="'.$desc['img_small'].'" height="100" style="float:left;">';
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

