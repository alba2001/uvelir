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
<ul class="step-wrapper">
	<li class="step <?php echo $this->action=='step1'?'active':''?>" ><span>1</span><div>Список покупок</div></li>
	<li class="step <?php echo $this->action=='step2'?'active':''?>"><span>2</span><div>Способ доставки</div></li>
	<li class="step <?php echo $this->action=='step3'?'active':''?>"><span>3</span><div>Способ оплаты</div></li>
	<li class="step <?php echo $this->action=='step4'?'active':''?>"><span>4</span><div>Завершение заказа</div></li>
</ul>

<!--Загружаем нужный шаг--> 
<?php  echo $this->loadTemplate($this->action);?>

<?php else: ?>
    <?=JTEXT::_('COM_UVELIR_CADDY_IS_EMPTY')?>
<?php endif ?>
<script type="text/javascript">
</script>
<div id="uvelir_debud"></div>