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
//var_dump($this->item);
//echo '<hr/>';
$desc = json_decode($this->item->desc);
//var_dump($desc);
//echo '<hr/>';
//exit;
    // Обработка корзины
    if(isset($this->caddy[$this->item->zavod.'_'.$this->item->id]))
    {
        $btn_del_style = $count_li_style = '';
        $caddy_count = $this->caddy[$this->item->zavod.'_'.$this->item->id]['count'];
    }
    else
    {
        $caddy_count = 0;
        $btn_del_style = $count_li_style = 'style="display:none"';
    }

?>
<?php if( $this->item ) : ?>
    <div class="item_fields">
        <img src="<?=$desc->img_large?>" alt="<?=$this->item->name?>"/>
        <ul class="fields_list">
            
            <?php if(isset($this->item->artikul) AND $this->item->artikul):?>
            <li>
                <?= JText::_('COM_UVELIR_ARTIKUL').': ' ?>
                <?=$this->item->artikul?>
            </li>
            <?php endif;?>
            
            <?php if(isset($this->item->material) AND $this->item->material):?>
            <li>
                <?= JText::_('COM_UVELIR_MATERIAL').': ' ?>
                <?=$this->item->material?>
            </li>
            <?php endif;?>
            
            <?php if(isset($this->item->proba) AND $this->item->proba):?>
            <li>
                <?= JText::_('COM_UVELIR_PROBA').': ' ?>
                <?=$this->item->proba?>
            </li>
            <?php endif;?>
            
            <?php if(isset($this->item->average_weight) AND $this->item->average_weight):?>
            <li>
                <?= JText::_('COM_UVELIR_AVERAGE_WEIGHT').': ' ?>
                <?=$this->item->average_weight?>
            </li>
            <?php endif;?>
            
            <?php if(isset($this->item->vstavki) AND $this->item->vstavki):?>
            <li>
                <?= JText::_('COM_UVELIR_VSTAVKI').': ' ?>
                <?=$this->item->vstavki?>
            </li>
            <?php endif;?>
            
            <?php if(isset($this->item->opisanije) AND $this->item->opisanije):?>
            <li>
                <?= JText::_('COM_UVELIR_OPISANIJE').': ' ?>
                <?=$this->item->opisanije?>
            </li>
            <?php endif;?>
            
            <?php if(isset($this->item->cena_mag) AND $this->item->cena_mag):?>
            <li>
                <?= JText::_('COM_UVELIR_CENA_MAG').': ' ?>
                <?=$this->item->cena_mag.' '.JTEXT::_('COM_UVELIR_RUB')?>
            </li>
            <?php endif;?>
            
            <?php if(isset($this->item->cena_tut) AND $this->item->cena_tut):?>
            <li>
                <?= JText::_('COM_UVELIR_CENA_TUT').': ' ?>
                <?=$this->item->cena_tut.' '.JTEXT::_('COM_UVELIR_RUB')?>
            </li>
            <?php endif;?>
            
            <!--Показ кол-ва товаров в корзине-->
            <li id="count_li_<?php echo $this->item->id; ?>" <?php echo $count_li_style?> >
                <?= JText::_('COM_UVELIR_CADDY_COUNT').': ' ?>
                <span id="count_span_<?php echo $this->item->id; ?>"><?php echo $caddy_count; ?></span>
                <?= JText::_('COM_UVELIR_CADDY_ITEMS') ?>
            </li>
        </ul>
                <!--Кнопки покупки-->
                <input id="del_<?php echo $this->item->id?>" type="button" <?=$btn_del_style?> value="<?php echo JText::_('COM_UVELIR_DEL_FROM_CART')?>"
                       onclick="uvelir_caddy_del({
                            action:'<?php echo JRoute::_('index.php'); ?>',
                            data:{
                                option:     'com_uvelir',
                                task:       'caddy.del',
                                item_id:    '<?php echo $this->item->id?>',
                                zavod:    '<?php echo $this->item->zavod?>',
                                '<?php echo JUtility::getToken()?>':'1'
                            }
                       })"
                />
                <input id="add_<?php echo $this->item->id?>" type="button" value="<?php echo JText::_('COM_UVELIR_ADD_TO_CART')?>" 
                       onclick="uvelir_caddy_add({
                            action:'<?php echo JRoute::_('index.php'); ?>',
                            data:{
                                option:     'com_uvelir',
                                task:       'caddy.add',
                                item_id:    '<?php echo $this->item->id?>',
                                zavod:    '<?php echo $this->item->zavod?>',
                                '<?php echo JUtility::getToken()?>':'1'
                            }
                       })"
                />
        
    </div>
<?php endif ?>
