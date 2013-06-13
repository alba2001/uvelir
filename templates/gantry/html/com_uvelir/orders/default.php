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
$href = JUri::base().'index.php?option=com_uvelir&view=order&id=';
$src = JURI::base().'components/com_uvelir/assets/img/info_16.png';

?>
<div id="orders">
	<table>
	    <thead>
	        <tr>
	            <th><?=JText::_('COM_UVELIR_ORDER_ID')?></th>
	            <th><?=JText::_('COM_UVELIR_ORDER_DT')?></th>
	            <th><?=JText::_('COM_UVELIR_ORDER_SUM')?></th>
	            <th><?=JText::_('COM_UVELIR_ORDER_STATUS')?></th>
	            <th><?=JText::_('COM_UVELIR_ORDER_INFO')?></th>
	        </tr>
	    </thead>
	    <tbody>
	    	<tr class="separator"><td colpan="5"></td></tr>
	        <?php foreach ($this->items as $item):?>
	        <tr>
	            <td><?= $item->id?></td>
	            <td><?= $this->model->get_order_dt($item->order_dt)?></td>
	            <td><?= $item->sum?></td>
	            <td><?= $this->model->get_order_status($item->order_status_id)?></td>
	            <td><a href="<?=$href.$item->id?>" title="<?=JText::_('COM_UVELIR_ORDER_INFO')?>">
	                    <img src="<?=$src?>" alt="<?=JText::_('COM_UVELIR_ORDER_INFO')?>"/>
	                </a>
	            </td>
	        </tr>
	        <?php endforeach;?>
	    </tbody>
	</table>
</div>