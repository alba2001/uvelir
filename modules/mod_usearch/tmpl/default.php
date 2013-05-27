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
<style type="text/css">
    #mod_usearch_uvelir{
        width: 100%;
        height: 100px;
        overflow: hidden;
        border: 1px solid activeborder;
        border-radius: 10px;
    }
    #mod_usearch_uvelir h1{
        font-size: 120%;
    }
    #mod_usearch_header{
        float: left;
        width: 15%;
    }
    #mod_usearch_body{
        float: left;
        width: 65%;
    }
    #mod_usearch_body table tbody tr td select{
        width: 100px
    }
    #mod_usearch_body table input{
        width: 20px
    }
    #mod_usearch_footer{
        width: 20%;
        float: left;
        overflow: hidden;
    }
</style>
<div id="mod_usearch_uvelir">
    <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="usearchForm" id="usearchForm">
    <div id="mod_usearch_header">
        <h1><?php echo JText::_('MOD_USEARCH_PODBOR_UZDELI')?></h1>
    </div>
    
    <div id="mod_usearch_body">
    <table>
        <tr>
            <td><?php echo JText::_('MOD_USEARCH_IZDELIE')?></td>
<!--            <td><input size="2" type="text" id="mod_usearch_izdelie" name="usearch_data[izdelie]" /></td>-->
            <td><?=$izdelie?></td>
            
            <td><?php echo JText::_('MOD_USEARCH_METAL')?></td>
            <td><?=$metal?></td>
<!--            <td><input type="text" id="mod_usearch_metal" name="usearch_data[metal]" /></td>-->
            
            <td><?php echo JText::_('MOD_USEARCH_VSTAVKI')?></td>
            <td><?=$vstavki?></td>
<!--            <td colspan="2"><input type="text" id="mod_usearch_vstavki" name="usearch_data[vstavki]" /></td>-->
        </tr>
        <tr>
            <td><?php echo JText::_('MOD_USEARCH_RAZMER')?></td>
            <td><?=$razmer?></td>
<!--            <td><input type="text" id="mod_usearch_razmer" name="usearch_data[razmer]" /></td>-->
            
            <td><?php echo JText::_('MOD_USEARCH_PROBA')?></td>
            <td><?=$proba?></td>
<!--            <td><input type="text" id="mod_usearch_proba" name="usearch_data[proba]" /></td>-->
            
            <td><?php echo JText::_('MOD_USEARCH_COST')?></td>
            <td><input size="2" type="text" id="mod_usearch_cost_1" name="usearch_data[cost_1]" value="<?=$usearch_data['cost_1']?>"/></td>
            <td><input size="2" type="text" id="mod_usearch_cost_2" name="usearch_data[cost_2]" value="<?=$usearch_data['cost_2']?>" /></td>
        </tr>
    </table>
    
    </div>
    
    <div id="mod_usearch_footer">
        <input type="submit" value="<?=JText::_('MOD_USEARCH_SUBMIT')?>">
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
    