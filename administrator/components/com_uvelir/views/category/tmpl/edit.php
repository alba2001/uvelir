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

<form action="<?php echo JRoute::_('index.php?option=com_uvelir&layout=edit&id='.(int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="category-form" class="form-validate">
	<div class="width-60 fltlft left">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_UVELIR_LEGEND_CATEGORY'); ?></legend>
			<ul class="adminformlist">
                
				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
				<li><?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?></li>
				<li><?php echo $this->form->getLabel('alias'); ?>
				<?php echo $this->form->getInput('alias'); ?></li>
				<li><?php echo $this->form->getLabel('path'); ?>
				<?php echo $this->form->getInput('path'); ?></li>
                                
				<li><?php echo $this->form->getLabel('city_id'); ?>
				<?php echo $this->form->getInput('city_id'); ?></li>
				<li><?php echo $this->form->getLabel('street_type'); ?>
				<?php echo $this->form->getInput('street_type'); ?></li>
				<li><?php echo $this->form->getLabel('street'); ?>
				<?php echo $this->form->getInput('street'); ?></li>
				<li><?php echo $this->form->getLabel('house'); ?>
				<?php echo $this->form->getInput('house'); ?></li>
				<li><?php echo $this->form->getLabel('address_else'); ?>
				<?php echo $this->form->getInput('address_else'); ?></li>
                                
				<li><?php echo $this->form->getLabel('email'); ?>
				<?php echo $this->form->getInput('email'); ?></li>
				<li><?php echo $this->form->getLabel('fio'); ?>
				<?php echo $this->form->getInput('fio'); ?></li>
				<li><?php echo $this->form->getLabel('phone'); ?>
				<?php echo $this->form->getInput('phone'); ?></li>
				<li><?php echo $this->form->getLabel('fax'); ?>
				<?php echo $this->form->getInput('fax'); ?></li>
                                
				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>
				<li><?php echo $this->form->getLabel('created_by'); ?>
				<?php echo $this->form->getInput('created_by'); ?></li>
                                
				<li><?php echo $this->form->getLabel('logo'); ?>
				<?php echo $this->form->getInput('logo'); ?></li>
				<li><?php echo $this->form->getLabel('width'); ?>
				<?php echo $this->form->getInput('width'); ?></li>
				<li><?php echo $this->form->getLabel('height'); ?>
				<?php echo $this->form->getInput('height'); ?></li>
				
                                <li><?php echo $this->form->getLabel('type'); ?>
				<?php echo $this->form->getInput('type'); ?></li>
                                <div id="com_uvelir_image">
                                    <li><?php echo $this->form->getLabel('image'); ?>
                                    <?php echo $this->form->getInput('image'); ?></li>
                                </div>
                                <div id="com_uvelir_points">
                                    <li><?php echo $this->form->getLabel('pointx'); ?>
                                    <?php echo $this->form->getInput('pointx'); ?></li>
                                    <li><?php echo $this->form->getLabel('pointy'); ?>
                                    <?php echo $this->form->getInput('pointy'); ?></li>
                                    <input type="button" id="com_uvelir_geo_code" 
                                           value="<?=JTEXT::_('COM_UVELIR_GEO_FIND')?>"
                                           style="cursor:pointer">
                                </div>
                                <li><?php echo $this->form->getLabel('category'); ?>
				<?php echo $this->form->getInput('category'); ?></li>
                                
                                <li><?php echo $this->form->getLabel('desc'); ?>
				<?php echo $this->form->getInput('desc'); ?></li>


            </ul>
		</fieldset>
	</div>
    <div class="left two-gigs" id="myMapId"></div>


	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>

</form>