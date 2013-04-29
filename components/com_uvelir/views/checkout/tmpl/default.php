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
//    var_dump($this->caddy);
?>
<?php if( $this->items ) : ?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="caddy_show" id="caddy_show">
    <table>
        <thead>
            <tr>
                <th><?=JTEXT::_('COM_UVELIR_ZAVOD')?></th>
                <th><?=JTEXT::_('COM_UVELIR_ARTIKUL')?></th>
                <th><?=JTEXT::_('COM_UVELIR_PRICE')?></th>
                <th><?=JTEXT::_('COM_UVELIR_COUNT')?></th>
                <th><?=JTEXT::_('COM_UVELIR_SUM')?></th>
            </tr>
        </thead>
        <?php foreach($this->items as $item):?>
            <?php $id = $item['zavod_id'].'_'.$item['id']?>
            <tr>
                <td><img src="<?=$item['src']?>" alt="<?=$item['artikul']?>"> <?=$item['zavod_name']?></td>
                <td><?=$item['artikul']?></td>
                <td id="caddy_item_price_<?=$id?>"><?=$item['price']?></td>
                <td>
                    <input name="count[<?=$id?>]" size="1" class="caddy_item_count" type="text" rel="<?=$id?>" value="<?=$item['count']?>"/>
                    
                </td>
                <td class="caddy_item_sum" id="caddy_item_sum_<?=$id?>"><?=(int)$item['sum']?></td>
            </tr>
        <?php endforeach;?>
        <tfoot>
            <th colspan="4"><?=JTEXT::_('COM_UVELIR_ITOGO')?></th>
            <th id="caddy_total_sum"><?=$this->caddy_data['sum']?></th>
        </tfoot>
    </table>
    
    <input type="hidden" name="option" value="com_uvelir" />
    <input type="hidden" name="view" value="checkout" />
    <input type="hidden" name="task" value="caddy.correction" />
    <?php echo JHtml::_('form.token'); ?>
    <input type="submit" value="<?=JTEXT::_('COM_UVELIR_CONFIRM')?>" onclick="document.caddy_show.task.value = '';" />
    
</form>    
<?php else: ?>
    <?=JTEXT::_('COM_UVELIR_CADDY_IS_EMPTY')?>
<?php endif ?>
<script type="text/javascript">
</script>
<div id="uvelir_debud"></div>