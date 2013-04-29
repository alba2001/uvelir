<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
$form_action = JRoute::_('index.php?option=com_uvelir');
// Загружаем тултипы.
JHtml::_('behavior.tooltip');

JHtml::_('behavior.formvalidation');

$client_fiz = $this->form->getFieldset('client_fiz');
$uid = $client_fiz['jform_uid']->value;
?>
<style type="text/css">
    div.uvelir-clear{clear: both}
    span.uvelir-button{width: 100px; cursor: pointer}
    span.com_uvelir-tooltipe{margin: 0 5px; font-weight: bold}
    .uvelir-hidden{display: none}
</style>
<script type="text/javascript">
    var Token = '<?=JUtility::getToken()?>';
</script>
<form action="<?php echo $form_action ?>" method="post" name="adminForm" id="member-registration" class="form-validate">
<?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one.?>
	<?php $fields = $this->form->getFieldset($fieldset->name);?>
	<?php if (count($fields)):?>
                <?php
                    $fieldset_id = isset($fieldset->id)?'id="'.$fieldset->id.'"':'';
                    $fieldset_style = isset($fieldset->style)?'style="'.$fieldset->style.'"':'';
                    $fieldset_class = isset($fieldset->class)?'class="'.$fieldset->class.'"':'';
                ?>
                <fieldset <?=$fieldset_id?> <?=$fieldset_style?> <?=$fieldset_class?>>
		<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.
		?>
			<legend><?php echo JText::_($fieldset->label);?></legend>
		<?php endif;?>
			<dl>
		<?php foreach($fields as $field):// Iterate through the fields in the set and display them.?>
			<?php if ($field->hidden):// If the field is hidden, just display the input.?>
				<?php echo $field->input;?>
			<?php else:?>
				<dt>
					<?php echo $field->label; ?>
				</dt>
				<dd><?php echo ($field->type!='Spacer') ? $field->input : "&#160;"; ?></dd>
			<?php endif;?>
		<?php endforeach;?>
			</dl>
		</fieldset>
	<?php endif;?>
<?php endforeach;?>
<style type="text/css">
    div.invalid{
        border: 1px red solid;
        border-radius: 5px;
        display: none;
    }
</style>
<div id="error_msg" class="invalid"></div>
    <?php $show_reg_checkbox = $uid?'style="display:none"':''?>
    <div id="com_uvelir_user_registration_div" <?=$show_reg_checkbox?>>
        <label for="com_uvelir_user_registration">
                <?=JTEXT::_('COM_UVELIR_USER_REGISTRATION')?>
                <?=JTEXT::_('COM_UVELIR_USER_REGISTRATION_START_TIPE')?>
                <span class="com_uvelir-tooltipe"><?=JTEXT::_('COM_UVELIR_USER_REGISTRATION_WHOT_IT_IS')?></span>
                <?=JTEXT::_('COM_UVELIR_END_TIPE')?>

        </label>
        <input id="com_uvelir_user_registration" type="checkbox" name="registration" value="1"/>

   </div>
        <input type="hidden" name="task" value="userform.submit" />
        <?php echo JHtml::_('form.token'); ?>
        <input type="submit" name="jform_submit" value="<?php echo JText::_('COM_UVELIR_FORM_SUBMIT');?>">
</form>
<div class="clear" style="clear: both"></div>