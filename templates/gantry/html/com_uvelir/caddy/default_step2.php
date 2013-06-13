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
?>
<form action="<?php echo JUri::base().'sposob-oplaty'; ?>" method="post" name="step1_form" id="step2_form">
<table>
    <tr>
        <td colspan="6" class="left">
            <input id="com_uvelir_courjer" type="radio" name="dostavka" value="1" <?=$dostavka=='1'?'checked="checked"':''?>/>
            <label for="com_uvelir_courjer" type="radio" ><?=  JText::_('COM_UVELIR_COURJER')?></label>
        </td>
    </tr>
    <tr>
        <td colspan="6" class="left">
            <input id="com_uvelir_spsr" type="radio" name="dostavka" value="2"  <?=$dostavka=='2'?'checked="checked"':''?>/>
            <label for="com_uvelir_spsr" type="radio" ><?=  JText::_('COM_UVELIR_SPSR')?></label>
        </td>
    </tr>

<!--Кнопки-->
    <tr>
        <th colspan="4" class="left">
                <a href="<?php echo JUri::base().'spisok-pokupok'?>" class="button" />Вернуться к списку покупок</a>
        </th>
        <th colspan="2" class="right">
                <input id="to_step3" class="button" type="submit" value="Далее" />
        </th>
    </tr>
</table>
</form>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#to_step3').click(function(e){
           e.preventDefault();
           var dostavka = $('input[name=dostavka]:checked', '#step2_form').val();
            $.ajax({
                type: 'GET',
                data:{
                    option: 'com_uvelir',
                    task: 'caddy.dostavka_submit',
                    dostavka: dostavka
                },
                url: '<?=JURI::base()?>index.php?<?=JSession::getFormToken()?>=1',
                success: function(html){
                    console.log(html);
                    $('#step2_form').submit();
                }
            });
        });
    });
</script>

