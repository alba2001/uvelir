<?php
/**
 * @version     1.0.0
 * @package     com_uvelir
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Konstantin Ovcharenko <alba2001@meta.ua> - http://vini-cloud.ru
 */
// no direct access
defined('_JEXEC') or die;
//var_dump($this->item);
?>
<style type="text/css">
    div.com_uvelir_item{
        float: left;
        width: 200px;
        height: 230px;
        border: 1px activecaption solid;
        border-radius: 5px;
        margin: 5px;
        padding: 5px;
    }
</style>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">
<div class="items">
        <?php foreach ($this->items as $item) : ?>
        <?php 
            $href = JRoute::_('index.php?option=com_uvelir&alias='.$item->id);
            $desc = json_decode($item->desc);
            $src = $desc->img_small;
            // Обработка корзины
            if(isset($this->caddy[$item->id]))
            {
                $btn_del_style = $count_li_style = '';
                $caddy_count = $this->caddy[$item->id]['count'];
            }
            else
            {
                $caddy_count = 0;
                $btn_del_style = $count_li_style = 'style="display:none"';
            }
        ?>
            <div class="com_uvelir_item">
                <a href="<?=$href;?>">
                    <img src="<?=$src?>" atl="<?=$item->name?>"/>
                    <?php echo JText::_('COM_UVELIR_ARTIKUL').': '.$item->artikul; ?>
                </a>
                <ul>
                    <?php if(isset($item->cena_mag) AND $item->cena_mag):?>
                    <li>
                        <?= JText::_('COM_UVELIR_CENA_MAG').': ' ?>
                        <?=$item->cena_mag.' '.JTEXT::_('COM_UVELIR_RUB')?>
                    </li>
                    <?php endif;?>

                    <?php if(isset($item->cena_tut) AND $item->cena_tut):?>
                    <li>
                        <?= JText::_('COM_UVELIR_CENA_TUT').': ' ?>
                        <?=$item->cena_tut.' '.JTEXT::_('COM_UVELIR_RUB')?>
                    </li>
                    <?php endif;?>
                    
                    <!--Показ кол-ва товаров в корзине-->
                    <li id="count_li_<?php echo $item->id; ?>" <?php echo $count_li_style?> >
                        <?= JText::_('COM_UVELIR_CADDY_COUNT').': ' ?>
                        <span id="count_span_<?php echo $item->id; ?>"><?php echo $caddy_count; ?></span>
                        <?= JText::_('COM_UVELIR_CADDY_ITEMS') ?>
                    </li>
                </ul>

                <!--Кнопки покупки-->
                <input id="del_<?php echo $item->id?>" type="button" <?=$btn_del_style?> value="<?php echo JText::_('COM_UVELIR_DEL_FROM_CART')?>"
                       onclick="uvelir_caddy_del({
                            action:'<?php echo JRoute::_('index.php'); ?>',
                            data:{
                                option:     'com_uvelir',
                                task:       'caddy.del',
                                item_id:    '<?php echo $item->id?>',
                                '<?php echo JUtility::getToken()?>':'1'
                            }
                       })"
                />
                <input id="add_<?php echo $item->id?>" type="button" value="<?php echo JText::_('COM_UVELIR_ADD_TO_CART')?>" 
                       onclick="uvelir_caddy_add({
                            action:'<?php echo JRoute::_('index.php'); ?>',
                            data:{
                                option:     'com_uvelir',
                                task:       'caddy.add',
                                item_id:    '<?php echo $item->id?>',
                                '<?php echo JUtility::getToken()?>':'1'
                            }
                       })"
                />
                
            </div>
        <?php endforeach; ?>
    
</div>
<div class="pagination">
    <?php echo $this->pagination->getListFooter(); ?>
</div>
        <input type="hidden" name="option" value="com_uvelir" />
        <input type="hidden" name="view" value="products" />
        <input type="hidden" name="item_id" value="" />
        <input type="hidden" name="products_group" value="<?=$this->products_group?>" />
        <?php echo JHtml::_('form.token'); ?>
</form>
<div id="uvelir_debud"></div>