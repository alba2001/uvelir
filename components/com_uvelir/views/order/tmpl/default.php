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

<style type="text/css">
    dl.dl_user_detail dt{
        float: left;
        font-weight: bold;
        width: 200px;
    }
</style>
<h1><?=JTEXT::_('COM_UVELIR_ORDER')?> № <?=$this->item->id?></h1>

	    <dl class="dl_user_detail">
	        <dt><?=JTEXT::_('COM_UVELIR_FIO').': '?></dt>
	        <dd><?=$this->user->fam.' '.$this->user->im.' '.$this->user->ot?></dd>
	        <dt><?=JTEXT::_('COM_UVELIR_ADDRESS').': '?></dt>
	        <dd><?=$this->user->address?></dd>
	        <dt><?=JTEXT::_('COM_UVELIR_PHONE').': '?></dt>
	        <dd><?=$this->user->phone?></dd>
	        <dt><?=JTEXT::_('COM_UVELIR_EMAIL').': '?></dt>
	        <dd><?=$this->user->email?></dd>
	        <dt><?=JTEXT::_('COM_UVELIR_ORDER_OPLATA').': '?></dt>
	        <dd><?=$this->sposob_oplaty?></dd>
	        <dt><?=JTEXT::_('COM_UVELIR_ORDER_DOSTAVKA').': '?></dt>
	        <dd><?=$this->sposob_dostavki?></dd>
	        <dt><?=JTEXT::_('COM_UVELIR_ITOGO').': '?></dt>
	        <dd>
                    <?=$this->total_sum?>
                    <span class="ruble"><?=JTEXT::_('COM_UVELIR_RUB')?></span>
                </dd>
	    </dl>

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
    	    </tr>
    	</thead>
    	<tbody>
    		<tr class="separator">
    			<td colpan="5"></td>
    		</tr>
	        <?php foreach($this->products as $item):?>
	            <?php $id = $item['zavod_id'].'_'.$item['id']?>
	            
	            <tr>
	                <td>
	                	<div class="image">
                                    <img src="<?=$item['img_src']?>" alt="<?=$item['artikul']?>">
	                	</div>
	                </td>
	                <td class="info">
	                	<?php if(isset($this->item->name) AND $this->item->name):?>
	        			<?php endif;?>
	                		<div class="item_title">
                                                <?=$item['name']?>
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
	                <td class="price">
	                    <?=$item['count']?>
	                </td>
	                <td class="price">
	                	<span id="caddy_item_price_<?=$id?>"><?=$item['price']?></span>
	                	<span class="ruble"><?=JTEXT::_('COM_UVELIR_RUB')?></span>
	                </td>
	                <td class="caddy_item_sum">
	                	<span id="caddy_item_sum_<?=$id?>"><?=(int)$item['sum']?></span>
	                	<span class="ruble"><?=JTEXT::_('COM_UVELIR_RUB')?></span>
	                </td>
	            </tr>
	        <?php endforeach;?>
        </tbody>
        <tfoot>
        	<tr>
        		<th colspan="5" class="left">
                            <a href="<?php echo JUri::base().'spisok-zakazov'?>" class="button"><?=JTEXT::_('COM_UVELIR_ORDERS_LIST')?></a>
        		</th>
        	</tr>
        </tfoot>
        
    </table>

