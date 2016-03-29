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

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
        
		if (task == 'product.cancel' || document.formvalidator.isValid(document.id('product-form'))) {
			Joomla.submitform(task, document.getElementById('product-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
<style type="text/css">
    fieldset.adminform dt{
        min-width: 30%;
        max-width: 50%;
        width: 50%;
        padding: 0 5px 0 0;
        float: left;
        clear: left;
        display: block;
        margin: 5px 0;        
    }
    fieldset.adminform dd{
        float: left;
        width: auto;
        margin: 5px 5px 5px 0;
        font-weight: bold;
    }
</style>
<form action="<?php echo JRoute::_('index.php?option=com_uvelir&layout=edit&id='.(int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="product-form" class="form-validate">
<?php if($this->prises):?>
	<div class="width-40 fltlft left">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_UVELIR_CENA_PRICE'); ?></legend>
                <dl>
                    <dt><?php echo JText::_('COM_UVELIR_PRODUCT_TYPE_NUME'); ?></dt>
                    <dd><?=$this->prises['producttype_name']?></dd>

                    <dt><?php echo JText::_('COM_UVELIR_PRODUCT_PRICE_MAG'); ?></dt>
                    <dd><?=$this->prises['price_mag']?></dd>

                    <dt><?php echo JText::_('COM_UVELIR_PRODUCT_PRICE_TUT'); ?></dt>
                    <dd><?=$this->prises['price_tut']?></dd>

                    <dt><?php echo JText::_('COM_UVELIR_PRODUCT_CENA_MAG'); ?></dt>
                    <dd><?=$this->prises['cena_mag']?></dd>

                    <dt><?php echo JText::_('COM_UVELIR_PRODUCT_CENA_TUT'); ?></dt>
                    <dd><?=$this->prises['cena_tut']?></dd>
                </dl>
            </fieldset>
        </div>
<?php endif;?>
	<div class="width-60 fltlft left">
	<?php $fields = $this->form->getFieldset('product');?>
	<?php if (count($fields)):?>
		<fieldset class="adminform">
                    <legend><?php echo JText::_('COM_UVELIR_LEGEND_PRODUCT'); ?></legend>
                    <ul class="adminformlist">
		<?php foreach($fields as $field):// Iterate through the fields in the set and display them.?>
			<?php if ($field->hidden):// If the field is hidden, just display the input.?>
				<?php echo $field->input;?>
			<?php else:?>
				<li>
					<?php echo $field->label; ?>
                                        <?php echo ($field->type!='Spacer') ? $field->input : "&#160;"; ?>
				</li>
			<?php endif;?>
		<?php endforeach;?>
                                    
                    </ul>
		</fieldset>
	<?php endif;?>
                
		</fieldset>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>