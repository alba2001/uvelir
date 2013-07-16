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

    $dostavka = isset($this->zakaz['dostavka'])?$this->zakaz['dostavka']:'1';

    if($dostavka == '2')
    {
        $oplata = '2';
    }
    else
    {
        $oplata = isset($this->zakaz['oplata'])?$this->zakaz['oplata']:'1';
    }

?>
<form action="<?php echo JUri::base().'zavershenie-zakaza'?>" method="post" name="step1_form" id="step3_form">
<table>

<?php if($dostavka == '1'):?>
    <tr>
        <td colspan="6" class="left">
            <input id="com_uvelir_cash" type="radio" name="oplata" value="1" <?=$oplata=='1'?'checked="checked"':''?>/>
            <label for="com_uvelir_cash" type="radio" ><?=  JText::_('COM_UVELIR_CASH')?></label>
        </td>
    </tr>
<?php else:?>
    <tr>
        <td colspan="6" class="left">
            <input id="com_uvelir_cash_express" type="radio" name="oplata" value="3" <?=$oplata=='3'?'checked="checked"':''?>/>
            <label for="com_uvelir_cash_express" type="radio" ><?=  JText::_('COM_UVELIR_CASH_EXPRESS')?></label>
        </td>
    </tr>
<?php endif;?>
    <tr>
        <td colspan="6" class="left">
            <input id="com_uvelir_robokassa" type="radio" name="oplata" value="2"  <?=$oplata=='2'?'checked="checked"':''?>/>
            <label for="com_uvelir_robokassa" type="radio" ><?=  JText::_('COM_UVELIR_ROBOKASSA')?></label>
        </td>
    </tr>
	<tr>
		<th colspan="4" class="left">
                    <a href="<?php echo JUri::base().'sposob-dostavki'?>" class="button" />Вернуться к способу доставки</a>
		</th>
		<th colspan="2" class="right">
			<input id="to_step4" class="button" type="submit" value="Далее" />
		</th>
	</tr>
</table>

</form>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#to_step4').click(function(e){
            e.preventDefault();
           var oplata = $('input[name=oplata]:checked', '#step3_form').val();
            $.ajax({
                type: 'GET',
                data:{
                    option: 'com_uvelir',
                    task: 'caddy.oplata_submit',
                    oplata: oplata
                },
                url: '<?=JURI::base()?>index.php?<?=JSession::getFormToken()?>=1',
                success: function(html){
                   console.log(html);
                   $('#step3_form').submit();
                }
            });

        });
    });
</script>

