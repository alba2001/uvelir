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
UvelirHelper::isKoltsa($this->item->id);
jimport('incase.init');

//$params = array('size'=>array('x'=>100, 'y'=>100));
//
//JHTML::_('behavior.modal', 'a.mymodal', $params);


//var_dump($this->item->razmer);
//var_dump($this->caddy);
//echo '<hr/>';
$desc = json_decode($this->item->desc);
//echo '<hr/>';
//exit;
    // Обработка корзины
    if(isset($this->caddy[$this->item->id.'_0']))
    {
        $btn_del_style = $count_li_style = 'style="display:inline-block;"';
        $caddy_count = $this->caddy[$this->item->id.'_0']['count'];
    }
    else
    {
        $caddy_count = 0;
        $btn_del_style = $count_li_style = 'style="display:none"';
    }

?>
<?php if( $this->item ) : ?>
   <div class="item_fields com_uvelir_item">

		<h2>
	    	<?php if(isset($this->item->name) AND $this->item->name):?>
	    		<?php if ($this->item->name != $this->item->artikul){ ?>
	    			<?//=ucfirst( mb_convert_case($this->item->name, MB_CASE_TITLE, 'UTF-8') );?>
	    			<?=$this->item->name;?>
	    		<?php }else{?>
	    		<?php }?>
	    	<?php endif;?>
		</h2>

    	<div class="leftside">
	    	<div class="image">
	    		<?php $src = $desc->img_large?>
			 	<a class="fancybox" href="<?=incase::thumb($src, 800, 600, true)?>" rel="{handler: 'iframe'}">
			 		<img data-src="<?=incase::thumb($src, 400, 400, true)?>" src="/images/load.gif" alt="<?=$item->name?>"/>
			   </a>
                </div>
                <?php if($this->item->show_logo):?>
                <div class="logo_on_product">
                    <?=$this->item->logo_on_product;?>
                </div>
                <?php endif;?>
	      <div class="social-block">
	     		<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
				<div id="ya_share" class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="button" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir"></div>
	      </div>
		</div>

		<div class="rightside">
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
			        			<span class="right" id="item_average_weight">
	                                                    <?php $average_weights = explode(',', $this->item->average_weight);?>
				        			<?=$average_weights[0]?>
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

			        	<tr>
			        		<td>
			        			<span class="left">
			        				<?= JText::_('COM_UVELIR_PRODUCTS_AVIALABLE').': ' ?></span>
			        		</td>
			        		<td>
			        			<span class="right">
				        			<?=$this->item->available?JText::_('COM_UVELIR_PRODUCTS_AVIALABLED'):JText::_('COM_UVELIR_PRODUCTS_NOT_AVIALABLED')?>
				        		</span>
			        		</td>
			        	</tr>

		        	<?php if(isset($this->item->opisanije) AND $this->item->opisanije):?>
			        	<tr>
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

	  	        	<?php if( UvelirHelper::isKoltsa($this->item->id) ):?>
	  		        	<tr>
	  		        		<td>
	  		        			<span class="left">
	  		        			</span>
	  		        		</td>
	  		        		<td>
	  		        			<span class="right size-block">
	  			        				<?
	        	      		        	jimport('joomla.application.module.helper');
	        	      		        	// this is where you want to load your module position
	        	      		        	$modules = JModuleHelper::getModules('product-special');
	        	      		        	foreach($modules as $module){
	        	      			        	echo JModuleHelper::renderModule($module);
	        	      		        	}
	        	      	        	?>
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
							<select name="size" id="item_razmer">
								<?php if(isset($this->item->razmer) AND $this->item->razmer):?>
									<?$sizes = explode(',', $this->item->razmer)?>
									<?php foreach ($sizes as $key => $value): ?>
										<option value="<?=$key?>"><?=$value?></option>
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
			        			                razmer_key:    $('#item_razmer').val(),
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
	                                                                razmer_key:    $('#item_razmer').val(),
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
	                   <?php $prises = ComponentHelper::getPrices($this->item->id); ?>
	                   <span class="black">
	                       <?= JText::_('COM_UVELIR_CENA').': ' ?>
	                   </span>
	                   <br>
	                   <span 	class="black big">
	                       <?php if((int)$prises['cena_tut']):?>
	                       <span id="item_cena_tut">
	                           <?=number_format($prises['cena_tut'], 0, '.', ' ') . ' '?>
	                       </span>
	                       <span class="ruble"><?=JTEXT::_('COM_UVELIR_RUB')?></span>
	                       <?php else:?>
	                       <?=' '.JTEXT::_('COM_UVELIR_MANAGER_CENA')?>
	                       <?php endif?>
	                   </span>

	                   <br>
                           <?php if(FALSE):?>
	                   <span class="gold">
	                       <?= JText::_('COM_UVELIR_CENA_MAG').': ' ?>
	                   </span>
	                   <br>
	                   <span class="gold small">
	                           <span id="item_cena_mag">
	                               <?=number_format($prises['cena_mag'], 0, '.', ' ') . ' '?>
	                           </span>
	                           <span class="ruble"><?=JTEXT::_('COM_UVELIR_RUB')?></span>
	                   </span>
                           <?php endif;?>
		        		</td>
		        	</tr>
		        </tfoot>
			</table>
			<?
	        	jimport('joomla.application.module.helper');
	        	// this is where you want to load your module position
	        	$modules = JModuleHelper::getModules('product-note');
	        	foreach($modules as $module){
		        	echo JModuleHelper::renderModule($module);
	        	}
        	?>
		</div>
   </div>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".fancybox").fancybox();

			// Изменение размера изделия
			$('#item_razmer').change(function(){
			    var url = '<?=JURI::base()?>index.php';
			    var razmer_key = $(this).val();
			    var cid = '<?=$this->item->id?>'
			    $.ajax({
			        type: 'POST',
			        url: url,
			        data: {
			            cid: cid,
			            razmer_key: razmer_key,
			            option: 'com_uvelir',
			            task: 'product.change_size',
			            '<?php echo JUtility::getToken()?>':'1'
			        },
			        success: function(html){
			            console.log(html);
			            var data = $.parseJSON(html);
			            $('#item_average_weight').text(data.average_weight);
			            $('#item_cena_mag').number(data.cena_mag, 0, ',', ' ');
			            $('#item_cena_tut').number(data.cena_tut, 0, ',', ' ');
			            if(data.count)
			            {
			                $('#count_li_<?php echo $this->item->id; ?>').show();
			                $('.removeButton').show('slow');
			                $('#count_span_<?php echo $this->item->id; ?>').text(data.count);
			            }
			            else
			            {
			                $('#count_li_<?php echo $this->item->id; ?>').hide();
			                $('.removeButton').hide('slow');
			                $('#count_span_<?php echo $this->item->id; ?>').text(data.count);
			            }
			        }

			    });
			});
		});
	</script>
<?php endif ?>
