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
	            <?php $id = $item['id']?>
	            <tr>
	                <td>
	                	<div class="image">
	                		<a href="<?=$href;?>">
		                		<img src="<?=$item['src']?>" alt="<?=$item['artikul']?>">
		                	</a>
	                	</div>
	                </td>
	                <td class="info">
	                	<?php if(isset($this->item->name) AND $this->item->name):?>
	        			<?php endif;?>
	                		<div class="item_title">
	                			<a href="<?=$href;?>">
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
	                    <input name="count[<?=$id?>]" size="1" class="caddy_item_count" type="text" rel="<?=$id?>" value="<?=$item['count']?>"/>
	                </td>
	                <td class="price" id="caddy_item_price_<?=$id?>">
	                	<?=$item['price']?>
	                	<span class="ruble"><?=JTEXT::_('COM_UVELIR_RUB')?></span>
	                </td>
	                <td class="caddy_item_sum" id="caddy_item_sum_<?=$id?>">
	                	<?=(int)$item['sum']?>
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
        	<tr>
        		<th colspan="3" class="left">
        			<button class="back">
        				Вернуться в каталог
        			</button>
        		</th>
        		<th colspan="2" class="right">
        			<input class="next" type="submit" value="Далее" onclick="document.caddy_show.task.value = '';" />
        		</th>
        	</tr>
        </tfoot>
    </table>

    <input type="hidden" name="option" value="com_uvelir" />
    <input type="hidden" name="view" value="checkout" />
    <input type="hidden" name="task" value="caddy.correction" />
    <?php echo JHtml::_('form.token'); ?>


</form>
<?php else: ?>
    <?=JTEXT::_('COM_UVELIR_CADDY_IS_EMPTY')?>
<?php endif ?>
<script type="text/javascript">
</script>
<div id="uvelir_debud"></div>