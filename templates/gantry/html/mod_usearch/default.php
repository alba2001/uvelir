<?php
/**
 * @package		Uvelir.Site
 * @subpackage	mod_usearch
 * @copyright	Copyright (C) 2010 - 2013 Konstantin Ovcharenko.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div id="mod_usearch_uvelir">
    <form action="<?php echo JRoute::_('index.php'); ?>" method="get" name="usearchForm" id="usearchForm">
    <div id="mod_usearch_header">
        <h1>ПОДБОР<br><span>ИЗДЕЛИЙ</span></h1>
    </div>
	<div class="separator"></div>
    <div id="mod_usearch_body">
    <table>
        <tr>
            <td><label for="mod_usearch_izdelie"><?php echo JText::_('MOD_USEARCH_IZDELIE')?></label></td>
            <td><?=$izdelie?></td>
            <td><label for="mod_usearch_metal"><?php echo JText::_('MOD_USEARCH_METAL')?></label></td>
            <td><?=$metal?></td>
            <td><label for="mod_usearch_vstavki"><?php echo JText::_('MOD_USEARCH_VSTAVKI')?></label></td>
            <td><?=$vstavki?></td>
            <!--В наличии-->
            <td rowspan="2"><label for="mod_usearch_available"><?php echo JText::_('MOD_USEARCH_AVAILABLE')?></label></td>
            <td rowspan="2"><?=$available?></td>
        </tr>
        <tr>
            <td><label for="mod_usearch_razmer"><?php echo JText::_('MOD_USEARCH_RAZMER')?></label></td>
            <td><?=$razmer?></td>
            <td><label for="mod_usearch_proba"><?php echo JText::_('MOD_USEARCH_PROBA')?></label></td>
            <td><?=$proba?></td>
            <td><label for="mod_usearch_cost_1"><?php echo JText::_('MOD_USEARCH_COST')?></label></td>
            <td>
            	<input placeholder="От" size="2" type="text" id="mod_usearch_cost_1" name="usearch_data[cost_1]" value="<?=$usearch_data['cost_1']?>"/>
            	<input placeholder="До" size="2" type="text" id="mod_usearch_cost_2" name="usearch_data[cost_2]" value="<?=$usearch_data['cost_2']?>" />
            </td>
        </tr>
    </table>

    </div>

    <div id="mod_usearch_footer">
        <button onclick="document.submit();">ПОДОБРАТЬ <br> УКРАШЕНИЕ</button>
       <?/* <input type="submit" value="ПОДОБРАТЬ &#10; УКРАШЕНИЕ">
         */?>
    </div>

        <input type="hidden" name="option" value="com_uvelir" />
        <input type="hidden" name="view" value="products" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#mod_usearch_izdelie').change(function(){
            var product_vid = $(this).val();
            $.ajaxSetup({
                beforeSend: function (){$('#mod_usearch_razmer').attr('readonly','true')},
                complete: function (){$('#mod_usearch_razmer').removeAttr('readonly')}
            });
            $.ajax({
                type: 'POST',
                url: '<?php echo JUri::base().'index.php?option=com_uvelir&task=usearch.ch_size&'.JSession::getFormToken().'=1'; ?>',
                data: {
                    productvid:product_vid
                },
                success: function(html){
            console.log(html);
                    $('#mod_usearch_razmer').html(html);
                }
            });
        });
    });

</script>
