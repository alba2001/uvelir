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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
 JHtml::_('behavior.modal');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_uvelir/assets/css/uvelir.css');
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
        
		if (task == 'metal.cancel' || document.formvalidator.isValid(document.id('metal-form'))) {
			Joomla.submitform(task, document.getElementById('metal-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_uvelir&layout=edit&id='.(int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="metal-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_UVELIR_LEGEND_METAL'); ?></legend>
			<ul class="adminformlist">
                
				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
				<li><?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?></li>
				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>
				<li><?php echo $this->form->getLabel('created_by'); ?>
				<?php echo $this->form->getInput('created_by'); ?></li>


            </ul>
		</fieldset>
	</div>
    
    <div class="clr"></div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>

    <style type="text/css">
        /* Temporary fix for drifting editor fields */
        .adminformlist li {
            clear: both;
        }
    </style>
<div id="com_uvelir_show_metal_items" style="width:auto; height:auto; display:none">
    
</div>    
</form>
