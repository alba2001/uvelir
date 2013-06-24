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
require_once JPATH_COMPONENT.'/helpers/khtml.php';

JHtml::_('behavior.tooltip');
JHTML::_('script', 'system/multiselect.js', false, true);
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_uvelir/assets/css/uvelir.css');

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$href = 'index.php?option=com_uvelir&view=product';
//exit;
?>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" />
            <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
        </div>

        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search_artikul"><?php echo JText::_('COM_UVELIR_ARTIKUL_SEARCH'); ?></label>
            <input type="text" name="filter_search_artikul" id="filter_search_artikul" value="<?php echo $this->escape($this->state->get('filter.search_artikul')); ?>" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" />
            <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button type="button" onclick="document.id('filter_search_artikul').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
        </div>


        <div class='filter-select fltrt'>
            <select name="filter_zavod" class="inputbox" onchange="this.form.submit()">
                <?php echo JHtml::_('select.options', KhtmlHelper::zavods(), "value", "text", $this->state->get('filter.zavod'), true); ?>
            </select>
            <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true); ?>
            </select>
        </div>


    </fieldset>
    <div class="clr"> </div>

    <table class="adminlist">
        <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
                </th>
                <th width="5%">
                    <?php echo JText::_('COM_UVELIR_PRODUCTS_AVIALABLE'); ?>
                </th>

                <th class='left'>
                    <?php echo JText::_('COM_UVELIR_PRODUCTS_NAME'); ?>
                </th>
                <th class='left'>
                    <?php echo JHtml::_('grid.sort',  'COM_UVELIR_ARTIKUL', 'a.artikul', $listDirn, $listOrder); ?>
                </th>
                <th class='left'>
                    <?php echo JText::_('COM_UVELIR_PRODUCTS_CREATED_BY'); ?>
                </th>
                <th class='left'>
                    <?php echo JText::_('COM_UVELIR_PRODUCTS_CATEGORY_PATH'); ?>
                </th>
                <th width="5%">
                    <?php echo JText::_('JPUBLISHED'); ?>
                </th>
                    <?php if (isset($this->items[0]->state)) { ?>
                    <?php } ?>
                <?php if (isset($this->items[0]->id)) { ?>
                    <th width="1%" class="nowrap">
                    <?php echo JText::_('JGRID_HEADING_ID'); ?>
                    </th>
                    <?php } ?>
            </tr>
        </thead>
        <tfoot>
<?php
if (isset($this->items[0])) {
    $colspan = count(get_object_vars($this->items[0]));
} else {
    $colspan = 10;
}
?>
            <tr>
                <td colspan="<?php echo $colspan ?>">
            <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
<?php
foreach ($this->items as $i => $item) :
    ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    
                    <td class="center">
                        <?php
                            $available_text_set = $item->available?JText::_('COM_UVELIR_PRODUCT_SET_NOT_AVAILABLE'):JText::_('COM_UVELIR_PRODUCT_SET_AVAILABLE');
                            $available_text = $item->available?JText::_('COM_UVELIR_PRODUCT_AVAILABLE'):JText::_('COM_UVELIR_PRODUCT_NOT_AVAILABLE');
                            $available_task = $item->available?'products.unset_available':'products.set_available';
                            $available_class = $item->available?'publish':'unpublish';
                        ?>
                        <a class="jgrid" href="javascript:void(0);" rel="<?=$i?>" task="<?=$available_task?>" title="<?=$available_text_set?>">
                                <span class="state <?=$available_class?>">
                                        <span class="text"><?=$available_text?></span>
                                </span>
                        </a>
                    </td>

                    <td>
                        <?php if (isset($item->checked_out) && $item->checked_out) : ?>
                            <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'products.', 1); ?>
                         <?php endif; ?>
                        <a href="<?php echo JRoute::_($href.'&id=' . (int) $item->id); ?>">
                            <?php $img = json_decode($item->desc)->img_small; ?>
                            <img src="<?=$img?>" height="50" width="50" />
                            <?php echo $this->escape($item->name); ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $item->artikul; ?>
                    </td>
                    <td>
                        <?php echo $item->created_by; ?>
                    </td>
                    <td>
                        <?php echo $this->get_category_path($item->category_id); ?>
                    </td>

                        <?php if (isset($this->items[0]->state)) { ?>
                    <td class="center">
                        <?php echo JHtml::_('jgrid.published', $item->state, $i, 'products.', 1, 'cb'); ?>
                    </td>
                        <?php } ?>
                        <?php if (isset($this->items[0]->id)) { ?>
                    <td class="center">
                        <?php echo (int) $item->id; ?>
                    </td>
                        <?php } ?>
                </tr>
                <?php endforeach; ?>
        </tbody>
    </table>

    <div>
        <input type="hidden" name="option" value="com_uvelir" />
        <input type="hidden" name="view" value="products" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo JHtml::_('form.token'); ?>
    </div>
</form>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('.jgrid').click(function(){
            var i = $(this).attr('rel');
            var task = $(this).attr('task');
            var span = $(this).children().first();
            var _this = $(this);
            $('input[type=checkbox]').attr('checked',false)
            $('#cb'+i).attr('checked',true)
            $("form [name=task]").val(task);
            var form = $('#adminForm');
            $.ajax({
                type: 'POST',
                url: $(form).attr('action'),
                data: $(form).serialize(),
                success: function(data){
                    if(data)
                    {
                        $('#cb'+i).attr('checked',false)
                        if($(span).hasClass('publish'))
                        {
                            $(span).removeClass('publish');
                            $(span).addClass('unpublish');
                            _this.attr('task','products.set_available');
                        }
                        else
                        {
                            $(span).removeClass('unpublish');
                            $(span).addClass('publish');
                            _this.attr('task','products.unset_available');
                        }
                    }

                }
            });
        });
    });

</script>    
    