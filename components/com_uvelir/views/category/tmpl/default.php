<?php
/**
 * @version     1.0.0
 * @package     com_jugraauto
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */

// no direct access
defined('_JEXEC') or die;

//var_dump($this->children);
    $empty_img_src = JURI::base().'components/com_uvelir/assets/img/empty_64.png';
    if(isset($this->item->desc))
    {
        $desc = json_decode($this->item->desc);
        $img_src = isset($desc->img_small)?$desc->img_small:$empty_img_src;
    }
    else
    {
        $img_src = $empty_img_src;
    }
?>
<?php if( $this->item ) : ?>
<!--Детали категории-->
    <h1>
        <img src="<?=$img_src?>" atl="<?=$this->item->name?>"/>
        <?=$this->item->name?>
    </h1>

<!--Список зависимых категорий-->
    <?php if($this->children):?>
        <div class="uvelir_subcategiries">
            <ul>
            <?php foreach ($this->children as $child):?>
                <?php 
                    if(isset($child->desc))
                    {
                        $desc = json_decode($child->desc);
                        $img_src = isset($desc->img_small)?$desc->img_small:$empty_img_src;
                    }
                    else
                    {
                        $img_src = $empty_img_src;
                    }
                ?>
                <li>
                    <?php $href = JRoute::_('index.php?option=com_uvelir&alias='.$child->alias)?>
                    <a href="<?=$href?>">
                        <img src="<?=$img_src?>" atl="<?=$this->item->name?>"/>
                        <?=$child->name?>
                    </a>
                </li>
            <?php endforeach;?>
            </ul>
        </div>
    <?php endif;?>

<!--Список товаров категории-->
<?php if($this->items):?>
<?php echo $this->loadTemplate('products');?>
<?php endif;?>

<?php endif ?>

