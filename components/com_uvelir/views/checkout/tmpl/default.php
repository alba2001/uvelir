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
<style type="text/css">
    dl.dl_user_detail dt{
        float: left;
        font-weight: bold;
        width: 200px;
    }
    div.div_user_detail{
        padding: 5px;
        margin: 5px;
    }
</style>
<div class="div_user_detail">
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="user_detail_show" id="user_detail_show">
    <dl class="dl_user_detail">
        <dt><?=JTEXT::_('COM_UVELIR_FIO').': '?></dt>
        <dd><?=$this->user->fam.' '.$this->user->im.' '.$this->user->ot?></dd>
        <dt><?=JTEXT::_('COM_UVELIR_ADDRESS').': '?></dt>
        <dd><?=$this->user->address?></dd>
        <dt><?=JTEXT::_('COM_UVELIR_PHONE').': '?></dt>
        <dd><?=$this->user->phone?></dd>
        <dt><?=JTEXT::_('COM_UVELIR_EMAIL').': '?></dt>
        <dd><?=$this->user->email?></dd>
    </dl>
    <input type="hidden" name="option" value="com_uvelir" />
    <input type="hidden" name="task" value="caddy.order_add" />
    <?php echo JHtml::_('form.token'); ?>
    <input type="submit" value="<?=JTEXT::_('COM_UVELIR_EDIT_USERDATA')?>" />

</form>
</div>
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
                <td><?=$item['price']?></td>
                <td><?=$item['count']?></td>
                <td><?=(int)$item['sum']?></td>
            </tr>
        <?php endforeach;?>
        <tfoot>
            <th colspan="4"><?=JTEXT::_('COM_UVELIR_ITOGO')?></th>
            <th id="caddy_total_sum"><?=$this->caddy_data['sum']?></th>
        </tfoot>
    </table>
    
    <input type="hidden" name="option" value="com_uvelir" />
    <input type="hidden" name="task" value="caddy.order_add" />
    <?php echo JHtml::_('form.token'); ?>
    <input type="submit" value="<?=JTEXT::_('COM_UVELIR_CHECKOUT')?>" />
    
</form>    
<?php else: ?>
    <?=JTEXT::_('COM_UVELIR_CADDY_IS_EMPTY')?>
<?php endif ?>
<div id="uvelir_debud"></div>