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
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="step1_form" id="step3_form">
<table>    
    
        <?php if($dostavka == '1'):?>
            <tr>
                <th>
                    <label for="com_uvelir_cash" type="radio" ><?=  JText::_('COM_UVELIR_CASH')?></label>
                </th>
                <td>
                    <input id="com_uvelir_cash" type="radio" name="oplata" value="1" <?=$oplata=='1'?'checked="checked"':''?>/>
                </td>
            </tr>
        <?php endif;?>
    <tr>
        <th>
            <label for="com_uvelir_robokassa" type="radio" ><?=  JText::_('COM_UVELIR_ROBOKASSA')?></label>
        </th>
        <td>
            <input id="com_uvelir_robokassa" type="radio" name="oplata" value="2"  <?=$oplata=='2'?'checked="checked"':''?>/>
        </td>
    </tr>
    
    
        	<tr>
        		<th colspan="3" class="left">
                            <input id="to_step2" type="submit" class="button" value="Вернуться к способу доставки"> 
        		</th>
        		<th colspan="2" class="right">
        			<input id="to_step4" class="button" type="submit" value="Далее" />
        		</th>
        	</tr>
</table>    
    

    <input type="hidden" name="option" value="com_uvelir" />
    <input type="hidden" name="view" value="caddy" />
    <input type="hidden" name="action" value="step2" id="caddy_step_action" />
    <?php echo JHtml::_('form.token'); ?>
</form>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#to_step2').click(function(){
           $('#caddy_step_action').val('step2'); 
        });
        $('#to_step4').click(function(e){
            e.preventDefault();
           $('#caddy_step_action').val('step4'); 
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
                   $('#step3_form').submit();
                }
            });
           
        });
    });
</script>    

