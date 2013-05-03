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
		<?php echo JText::_('COM_UVELIR_HEADING_NAME'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_UVELIR_UVELIR_UID'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_UVELIR_UVELIR_PHONE'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_UVELIR_UVELIR_EMAIL'); ?>
	</th>
</tr>
