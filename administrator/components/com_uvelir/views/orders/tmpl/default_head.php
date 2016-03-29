<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
	<th width="5">
		<?php echo JText::_('COM_UVELIR_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JText::_('COM_UVELIR_USER_FIO'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_UVELIR_ORDER_STATUS'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_UVELIR_ORDER_DATE'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_UVELIR_ORDER_SUM'); ?>
	</th>
</tr>
