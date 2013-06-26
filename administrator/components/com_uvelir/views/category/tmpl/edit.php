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
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_uvelir/assets/css/uvelir.css');

?>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="category-form" class="form-validate">
	<div class="width-60 fltlft left">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_UVELIR_LEGEND_CATEGORY'); ?></legend>
			<ul class="adminformlist">
                
				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
                                
				<li><?php echo $this->form->getLabel('parent_id'); ?>
				<?php echo $this->form->getInput('parent_id'); ?></li>
                                
				<li><?php echo $this->form->getLabel('producttype_id'); ?>
				<?php echo $this->form->getInput('producttype_id'); ?></li>
                                
				<li><?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?></li>
                                
				<li><?php echo $this->form->getLabel('alias'); ?>
				<?php echo $this->form->getInput('alias'); ?></li>
                                
				<li><?php echo $this->form->getLabel('path'); ?>
				<?php echo $this->form->getInput('path'); ?></li>
                                
				<li><?php echo $this->form->getLabel('source_url'); ?>
				<?php echo $this->form->getInput('source_url'); ?></li>
                                
				<li><?php echo $this->form->getLabel('img'); ?>
				<?php echo $this->form->getInput('img'); ?></li>
                                
				<li><?php echo $this->form->getLabel('note'); ?>
				<?php echo $this->form->getInput('note'); ?></li>
                                
				<li><?php echo $this->form->getLabel('description'); ?>
				<?php echo $this->form->getInput('description'); ?></li>
            </ul>
		</fieldset>
	</div>
	<input type="hidden" name="option" value="com_uvelir" />
	<input type="hidden" name="layout" value="edit" />
	<input type="hidden" name="id" value="<?=(int) $this->item->id?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>

</form>