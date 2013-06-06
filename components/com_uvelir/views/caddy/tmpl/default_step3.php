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
?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="step1_form" id="step3_form">
<table>    
        	<tr>
        		<th colspan="3" class="left">
                            <button type="sub,it" class="button"> Вернуться к способу доставки</button>
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
        $('#to_step4').click(function(){
           $('#caddy_step_action').val('step4'); 
           $('#caddy_task').val(''); 
           $('#step3_form').submit();
        });
    });
</script>    

