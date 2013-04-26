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
            <tr>
                <td><img src="<?=$item['src']?>" alt="<?=$item['artikul']?>"> <?=$item['zavod_name']?></td>
                <td><?=$item['artikul']?></td>
                <td><?=$item['price']?></td>
                <td><?=$item['count']?></td>
                <td><?=$item['sum']?></td>
            </tr>
        <?php endforeach;?>
        <tfoot>
            <th colspan="4"><?=JTEXT::_('COM_UVELIR_ITOGO')?></th>
            <th><?=$this->caddy_data['sum']?></th>
        </tfoot>
    </table>
<?php else: ?>
    <?=JTEXT::_('COM_UVELIR_CADDY_IS_EMPTY')?>
<?php endif ?>
