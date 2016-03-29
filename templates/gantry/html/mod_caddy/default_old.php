<?php
/**
 * @package		Uvelir.Site
 * @subpackage	mod_caddy
 * @copyright	Copyright (C) 2010 - 2013 Konstantin Ovcharenko.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div id="mod_caddy_uvelir">
	<div class="inner">
		<div class="cart">
			<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">
			    <span class="icon"></span>
			    <input class="submit" type="submit" value="КОРЗИНА">
			    <input type="hidden" name="option" value="com_uvelir" />
			    <input type="hidden" name="view" value="caddy" />
			    <?//php echo JHtml::_('form.token'); ?>
			</form>
		</div>
	    <div class="sum">
			<span class="separator"></span>
	        <span id="mod_caddy_product_sum">
	        	<?php echo $caddy_data['sum']?>
	        </span>
	        <span class="ruble">p</span>
	    </div>
	    <div class="count">
	        (<span id="mod_caddy_product_count"><?php echo $caddy_data['count']?></span>)
	    </div>
    </div>
</div>