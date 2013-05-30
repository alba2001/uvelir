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
//var_dump($this->item);
?>
<ul class="tabs">
	<li class="first">
		<a class="<?=$this->products_group=='1'?'active':''?>" href="<?php echo JRoute::_('novinki')?>" class="trigger"><?=  JText::_('COM_UVELIR_PRODUCT_NEW')?></a>
	</li>
	<li class="separator"></li>
	<li>
		<a class="<?=$this->products_group=='2'?'active':''?>" href="<?php echo JRoute::_('spetsialnye-predlozheniya')?>" class="trigger"><?=  JText::_('COM_UVELIR_PRODUCT_SPETS')?></a>
	</li>
	<li class="separator"></li>
	<li class="last">
		<a class="<?=$this->products_group?'':'active'?>" href="<?php echo JRoute::_('katalog-izdelij')?>" class="trigger"><?=  JText::_('COM_UVELIR_PRODUCT_ALL')?></a>
	</li>
</ul>
<?php echo $this->loadTemplate('products');?>