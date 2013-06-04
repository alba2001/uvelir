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

//$params = array('size'=>array('x'=>100, 'y'=>100));
//
//JHTML::_('behavior.modal', 'a.mymodal', $params);


//var_dump($this->item);
//echo '<hr/>';
$desc = json_decode($this->item->desc);
//echo '<hr/>';
//exit;
    // Обработка корзины
    if(isset($this->caddy[$this->item->id]))
    {
        $btn_del_style = $count_li_style = '';
        $caddy_count = $this->caddy[$this->item->id]['count'];
    }
    else
    {
        $caddy_count = 0;
        $btn_del_style = $count_li_style = 'style="display:none"';
    }

?>
<?php if( $this->item ) : ?>
    <div class="item_fields">

		<h2>
	    	<?php if(isset($this->item->name) AND $this->item->name):?>
	    		<? if ($this->item->name != $this->item->artikul){ ?>
	    			<?=ucfirst( mb_convert_case($this->item->name, MB_CASE_TITLE, 'UTF-8') );?>
	    		<?}else{?>
	    		<?}?>
	    	<?php endif;?>
		</h2>

    	<div class="leftside">
	        <a class="fancybox"
	        href="<?=$desc->img_large?>"
	        rel="{handler: 'iframe'}">
	        <img src="<?=isset($desc->img_medium)?$desc->img_medium:$desc->img_large?>" alt="<?=$this->item->name?>"/>
	        </a>
	        <!-- social block-->
        </div>

	    <table class="fields_list">
			<tbody>
	      		 <?php if(isset($this->item->artikul) AND $this->item->artikul):?>
		        	<tr>
		        		<td>
		        			<span class="left">
		        				<?= JText::_('COM_UVELIR_ARTIKUL').': ' ?>
		        			</span>
		        		</td>
		        		<td>
		        			<span class="right">
		        				<?=$this->item->artikul?>
		        			</span>
		        		</td>
		        	</tr>
		    	<?php endif;?>

		        <?php if(isset($this->item->material) AND $this->item->material):?>
		        	<tr>
		        		<td>
		        			<span class="left">
		        				<?= JText::_('COM_UVELIR_MATERIAL').': ' ?></span>
		        		</td>
		        		<td>
		        			<span class="right">
			        			<?=$this->item->material?>
			        		</span>
		        		</td>
		        	</tr>
		        <?php endif;?>

		        <?php if(isset($this-> item->proba) AND $this->item->proba):?>
		        	<tr>
		        		<td>
		        			<span class="left">
		        				<?= JText::_('COM_UVELIR_PROBA').': ' ?></span>
		        		</td>
		        		<td>
		        			<span class="right">
			        			<?=$this->item->proba?>
			        		</span>
		        		</td>
		        	</tr>
	        	<?php endif;?>

	        	<?php if(isset($this->item->average_weight) AND $this->item->average_weight):?>
		        	<tr>
		        		<td>
		        			<span class="left">
		        				<?= JText::_('COM_UVELIR_AVERAGE_WEIGHT').': ' ?></span>
		        		</td>
		        		<td>
		        			<span class="right">
			        			<?=$this->item->average_weight?>
			        		</span>
		        		</td>
		        	</tr>
	        	<?php endif;?>

	        	<?php if(isset($this->item->vstavki) AND $this->item->vstavki):?>
		        	<tr>
		        		<td>
		        			<span class="left">
		        				<?= JText::_('COM_UVELIR_VSTAVKI').': ' ?></span>
		        		</td>
		        		<td>
		        			<span class="right">
			        			<?=$this->item->vstavki?>
			        		</span>
		        		</td>
		        	</tr>
	        	<?php endif;?>

	        	<?php if(isset($this->item->opisanije) AND $this->item->opisanije):?>
		        	<tr class="last">
		        		<td>
		        			<span class="left">
		        				<?= JText::_('COM_UVELIR_OPISANIJE').': ' ?></span>
		        		</td>
		        		<td>
		        			<span class="right">
			        			<?=$this->item->opisanije?>
			        		</span>
		        		</td>
		        	</tr>
	        	<?php endif;?>
	        </tbody>
	        <tfoot  class="bottom">

				<tr class="separatorBig">
					<td></td>
					<td></td>
				</tr>
				<tr class="size">
					<td>
						<span>РАЗМЕР:</span>
					</td>
					<td>
						<select name="size" id="id">
							<?php if(isset($this->item->razmer) AND $this->item->razmer):?>
								<?$sizes = explode(';', $this->item->razmer)?>
								<?php foreach ($sizes as $key => $value): ?>
									<option value="<?=$value?>"><?=$value?></option>
								<?php endforeach ?>
		        			<?php else: ?>
									<option value="0">-</option>
		        			<?php endif;?>
						</select>
					</td>
				</tr>
				<tr class="separatorSmall">
					<td></td>
					<td></td>
				</tr>
	        	<tr>
	        		<td>
	        			<div class="buttons">
		        			<!--Кнопки покупки-->
		        			<input class="addButton" id="add_<?php echo $this->item->id?>" type="button" value="<?php echo JText::_('COM_UVELIR_ADD_TO_CART')?>"
		        			       onclick="uvelir_caddy_add({
		        			            action:'<?php echo JRoute::_('index.php'); ?>',
		        			            data:{
		        			                option:     'com_uvelir',
		        			                task:       'caddy.add',
		        			                item_id:    '<?php echo $this->item->id?>',
		        			                '<?php echo JUtility::getToken()?>':'1'
		        			            }
		        			       })"
		        			/>

		        			<input class="removeButton" id="del_<?php echo $this->item->id?>" type="button" <?=$btn_del_style?> value="<?php echo JText::_('COM_UVELIR_DEL_FROM_CART')?>"
		        			       onclick="uvelir_caddy_del({
		        			            action:'<?php echo JRoute::_('index.php'); ?>',
		        			            data:{
		        			                option:     'com_uvelir',
		        			                task:       'caddy.del',
		        			                item_id:    '<?php echo $this->item->id?>',
		        			                '<?php echo JUtility::getToken()?>':'1'
		        			            }
		        			       })"
		        			/>

		        			<!--Показ кол-ва товаров в корзине-->
		        			<div class="count" id="count_li_<?php echo $this->item->id; ?>" <?php echo $count_li_style?> >
		        			    <?= JText::_('COM_UVELIR_CADDY_COUNT').': ' ?>
		        			    <span id="count_span_<?php echo $this->item->id; ?>"><?php echo $caddy_count; ?></span>
		        			    <?= JText::_('COM_UVELIR_CADDY_ITEMS') ?>
		        			</div>

	        			</div>
	        		</td>
	        		<td>

	        			<?php if(isset($this->item->cena_tut) AND $this->item->cena_tut):?>
							<span class="black">
		        				<?= JText::_('COM_UVELIR_CENA').': ' ?>
		        			</span>
							<br>
							<span 	class="black big">
	        					<?=number_format($this->item->cena_tut, 0, '.', ' ') . ' '?>
		        				<span class="ruble"><?=JTEXT::_('COM_UVELIR_RUB')?></span>
							</span>
	        			<?php endif;?>

	        			<?php if(isset($this->item->cena_mag) AND $this->item->cena_mag):?>
							<br>
							<span class="gold">
	        					<?= JText::_('COM_UVELIR_CENA_MAG').': ' ?>
	        				</span>
							<br>
							<span class="gold small">
								<?=number_format($this->item->cena_mag, 0, '.', ' ') . ' '?>
	        					<span class="ruble"><?=JTEXT::_('COM_UVELIR_RUB')?></span>
							</span>
	        			<?php endif;?>



	        		</td>
	        	</tr>
	        </tfoot>
		</table>

    </div>
<?php endif ?>
<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
</script>