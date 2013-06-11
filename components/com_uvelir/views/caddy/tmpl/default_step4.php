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
</style>
<div class="div_user_detail expand-source">
	<p>Личные данные</p>
	<form action="<?php echo JURI::base().'/lichnye-dannye'; ?>" method="post" name="user_detail_show" id="user_detail_show">
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
	    <input class="button" type="submit" value="<?=JTEXT::_('COM_UVELIR_EDIT_USERDATA')?>" />
	</form>
</div>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="caddy_show" id="caddy_show">
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
	        <?php foreach($this->items as $item):?>
	            <?php $id = $item['zavod_id'].'_'.$item['id']?>
	            <tr>
	                <td>
	                	<div class="image">
                                        <img src="<?=$item['src']?>" alt="<?=$item['artikul']?>">
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
<!--Кнопки-->       
        	<tr>
        		<th colspan="3" class="left">
                            <a href="<?php echo JUri::base().'sposob-oplaty'; ?>" class="button" />Вернуться к способу оплаты</a>
        		</th>
        		<th colspan="2" class="right">
        			<input id="to_step_end" class="button" type="submit" value="Подтвердить" />
        		</th>
        	</tr>
        </tfoot>

    </table>
    <input type="hidden" name="option" value="com_uvelir" />
    <input type="hidden" name="view" value="caddy" />
    <input id="caddy_task" type="hidden" name="task" value="caddy.order_add" />
    <?php echo JHtml::_('form.token'); ?>

</form>
<?php else: ?>
    <?=JTEXT::_('COM_UVELIR_CADDY_IS_EMPTY')?>
<?php endif ?>

