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
?>
<style type="text/css">
    div.com_uvelir-delete{
        height: 32px; 
        overflow: hidden;
        cursor: pointer;
    }
    span.com_uvelir-arow{
        font-weight: bold;
        font-size: 120%;
        padding: 0 5px;
        cursor: pointer;
    }
    input.caddy_item_count{width: 10px;}
    
</style>

<form action="<?=JURI::base()?>index.php?<?=JSession::getFormToken()?>=1" method="post" name="step1_form" id="step1_form">
    <table>
        <thead>
            <tr>
                <th colspan="2" class="left">Выбранные товары</span></th>
                <th>
                	<?=JTEXT::_('COM_UVELIR_COUNT')?>
                	<span class="separator"></span>
                </th>
                <th>
                	<?=JTEXT::_('COM_UVELIR_PRICE')?>
                	<span class="separator"></span>
                </th>
                <th>
            		<?=JTEXT::_('COM_UVELIR_SUM')?>
                	<span class="separator">
                </th>
                <th>
                </th>
            </tr>
        </thead>
        <tbody>
        	<tr class="separator">
        		<td colpan="5"></td>
        	</tr>
	        <?php foreach($this->items as $item):?>
	            <?php $id = $item['id']?>
	            <tr id="item_row_<?=$id?>">
	                <td>
	                	<div class="image">
                                    <a href="<?= $item['path'].'/'.$item['id'];?>">
		                		<img src="<?=$item['src']?>" alt="<?=$item['artikul']?>">
		                	</a>
	                	</div>
	                </td>
	                <td class="info">
	                	<?php if(isset($this->item->name) AND $this->item->name):?>
	        			<?php endif;?>
	                		<div class="item_title">
	                			<a href="<?= $item['path'].'/'.$item['id'];?>">
		                			<?=$item['name']?>
		                		</a>
	                		</div>

                		<div class="manufacturer">
                			Завод: <?=$item['zavod_name']?>
                		</div>

                		<?php if(isset($this->item->razmer) AND $this->item->razmer):?>
	                		<div class="size">
	                			Размер: <?=$item['razmer']?>
	                		</div>
	        			<?php endif;?>

                		<div class="article">
                			Артикул: <?=$item['artikul']?>
                		</div>
	                </td>
	                <td>
                            <span class="com_uvelir-arow arow_left" id="arow_left_<?=$id?>"> < </span>
	                    <input id="caddy_item_count_<?=$id?>" name="count[<?=$id?>]" size="1" class="caddy_item_count" type="text" rel="<?=$id?>" value="<?=$item['count']?>"/>
                            <span class="com_uvelir-arow  arow_right" id="arow_right_<?=$id?>"> > </span>
	                </td>
	                <td class="price">
	                	<span id="caddy_item_price_<?=$id?>"><?=$item['price']?></span>
	                	<span class="ruble"><?=JTEXT::_('COM_UVELIR_RUB')?></span>
	                </td>
	                <td class="caddy_item_sum">
	                	<span id="caddy_item_sum_<?=$id?>"><?=(int)$item['sum']?></span>
	                	<span class="ruble"><?=JTEXT::_('COM_UVELIR_RUB')?></span>
	                </td>
	                <td>
                                <div class="com_uvelir-delete" id="delete_<?=$id?>">
                                    <img src="<?=  JURI::base().'components/com_uvelir/assets/img/icon-32-delete.png'?>">
                                </div>
	                </td>
	            </tr>
	        <?php endforeach;?>
        </tbody>
        <tfoot>
        	<tr>
        		<td colspan="5" class="right">
        			<span class="gray-sum">
        				<?=JTEXT::_('COM_UVELIR_ITOGO')?>:
        			</span>
	    			<span class="black-sum" id="caddy_total_sum">
	    				<?=$this->caddy_data['sum']?>
	    			</span>
	    			<span class="ruble">
	    				<?=JTEXT::_('COM_UVELIR_RUB')?>
	    			</span>
        		</td>
        	</tr>
        	<tr>
        		<th colspan="3" class="left">
                            <a href="<?php echo JUri::base().'katalog-izdelij'?>" class="button">Вернуться в каталог</a>
        		</th>
        		<th colspan="2" class="right">
                                <a href="<?php echo JUri::base().'sposob-dostavki'?>" class="button" />Далее</a>
        		</th>
        	</tr>
        </tfoot>
    </table>
</form>
