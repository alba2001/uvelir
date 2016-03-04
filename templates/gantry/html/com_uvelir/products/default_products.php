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
jimport('incase.init');
//var_dump($this->item);
?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm" class="items-wrapper">
	<div class="items">
		<?php foreach ($this->items as $item) : ?>
			<?php
			$href = JRoute::_('index.php?option=com_uvelir&alias='.$item->id);
			$desc = json_decode($item->desc);
			$src = $desc->img_small;
			$novinka = ($item->novinka_dt > date('d.m.Y'))?'new':'';
			$spets_predl = $item->spets_predl?'spets_predl':'';

            // Вычисляем кол-во товаров в корзине без учета размеров
			$caddy_count = 0;
			$btn_del_style = $count_li_style = '';
			foreach($this->caddy as $key=>$value)
			{
				/* @var $key string */
				if(preg_match('/^'.$item->id.'_\d+$/', $key))
				{
					$caddy_count += $value['count'];
				}
			}
			if(!$caddy_count)
			{
				$caddy_count = 0;
				$btn_del_style = $count_li_style = 'style="display:none"';
			}
			?>
			<div class="com_uvelir_item <?=$novinka.' '.$spets_predl?>">

				<div class="image">
					<a href="<?=$href?>">
						<img data-src="<?=incase::thumb($src, 150, 100, true);?>" src="/images/load.gif" alt="<?=$item->name?>"/>
					</a>
				</div>

				<a href="<?=$href;?>" class="article">
					<?php echo JText::_('COM_UVELIR_ARTIKUL').': '.$item->artikul; ?>
				</a>

				<div class="price-block">
					<?php $prises = ComponentHelper::getPrices($item->id); ?>

					<div class="block1">
						<?php // if((int)$prises['cena_mag']):?>
						<?php if(FALSE):?>
							<?= JText::_('COM_UVELIR_CENA_MAG').': <br>' ?>
							<span class="line-through"><?=$prises['cena_mag']?></span>
							<span class="ruble"><?=' '.JTEXT::_('COM_UVELIR_RUB')?></span>
						<?php endif?>
					</div>

					<div class="block2">
						<?php if((int)$prises['cena_tut']):?>
							<?= JText::_('COM_UVELIR_CENA_TUT').': <br>' ?>
							<span><?=$prises['cena_tut']?></span>
							<span class="ruble"><?=' '.JTEXT::_('COM_UVELIR_RUB')?></span>
						<?php else:?>
							<span class="manager">
								<?=' '.JTEXT::_('COM_UVELIR_MANAGER_CENA')?>
							</span>
						<?php endif?>
					</div>
				</div>

				<div class="show">

					<?php if(isset($item->opisanije)):?>
						<div class="desc">
							<?//= JText::_('COM_UVELIR_OPISANIJE').': ' ?>
							<?=$item->opisanije?>
						</div>
					<?php endif;?>

					<!--Показ кол-ва товаров в корзине-->
					<div class="item_count" id="count_li_<?php echo $item->id; ?>" <?php echo $count_li_style?> >
						<?= JText::_('COM_UVELIR_CADDY_COUNT').': ' ?>
						<span id="count_span_<?php echo $item->id; ?>"><?php echo $caddy_count; ?></span>
						<?= JText::_('COM_UVELIR_CADDY_ITEMS') ?>
					</div>

					<!--Кнопки покупки-->

					<input class="addButton"  id="add_<?php echo $item->id?>" type="button" value="+ Добавить"
					onclick="uvelir_caddy_add({
					action:'<?php echo JRoute::_('index.php'); ?>',
					data:{
					option:     'com_uvelir',
					task:       'caddy.add',
					item_id:    '<?php echo $item->id?>',
					'<?php echo JUtility::getToken()?>':'1'
				}
			})"
			/>
			<input  class="removeButton" id="del_<?php echo $item->id?>" type="button" <?=$btn_del_style?> value="- Удалить"
			onclick="uvelir_caddy_del({
			action:'<?php echo JRoute::_('index.php'); ?>',
			data:{
			option:     'com_uvelir',
			task:       'caddy.del',
			item_id:    '<?php echo $item->id?>',
			'<?php echo JUtility::getToken()?>':'1'
		}
	})"
	/>


</div><?php //<!-- show -->?>
</div><?php //<!-- com_uvelir_item -->?>
<?php endforeach; ?>
</div><!-- items -->
<div class="pagination">
	<?php echo $this->pagination->getListFooter(); ?>
</div>
<input type="hidden" name="option" value="com_uvelir" />
<input type="hidden" name="view" value="products" />
<input type="hidden" name="item_id" value="" />
<input type="hidden" name="products_group" value="<?=$this->products_group?>" />
<?php if(!isset($this->show_menu_groups) OR !$this->show_menu_groups):?>
	<input type="hidden" name="show_menu_groups" value="0" />
<?php endif;?>

<?php echo JHtml::_('form.token'); ?>
</form>
