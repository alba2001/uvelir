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
JHTML::_('script', 'system/multiselect.js', false, true);
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_uvelir/assets/css/uvelir.css');

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
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


        <div class='filter-select fltrt'>
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

                <th class='left'>
                    <?php echo JText::_('COM_UVELIR_CATEGORIES_NAME'); ?>
                </th>
                <th class='left'>
                    <?php echo JText::_('COM_UVELIR_PRODUCTTYPE'); ?>
                </th>
                <th class='left'>
                    <?php echo JText::_('COM_UVELIR_CATEGORIES_CREATED_BY'); ?>
                </th>
                <th class='left'>
                    <?php echo JText::_('COM_UVELIR_CATEGORIES_ZAVOD'); ?>
                </th>
                    <?php if (isset($this->items[0]->state)) { ?>
                <th width="5%">
                    <?php echo JText::_('JPUBLISHED'); ?>
                </th>
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
    if($item->level>0):
    ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>

                    <td>
                        <?php if (isset($item->checked_out) && $item->checked_out) : ?>
                            <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'categories.', 1); ?>
                         <?php endif; ?>
                        <a href="<?php echo JRoute::_('index.php?option=com_uvelir&task=category.edit&id=' . (int) $item->id); ?>">
                        <?php echo str_repeat('|-----', ($item->level-1)).$this->escape($item->name); ?></a>
                    </td>
                    <td>
                        <?php echo $this->model->get_producttype_id($item->producttype_id); ?>
                    </td>
                    <td>
                        <?php echo $item->created_by; ?>
                    </td>
                    <td>
                        <?php echo $this->get_zavod_name($item->zavod); ?>
                    </td>


                        <?php if (isset($this->items[0]->state)) { ?>
                    <td class="center">
                        <?php echo JHtml::_('jgrid.published', $item->state, $i, 'categories.', 1, 'cb'); ?>
                    </td>
                        <?php } ?>
                        <?php if (isset($this->items[0]->id)) { ?>
                    <td class="center">
                        <?php echo (int) $item->id; ?>
                    </td>
                        <?php } ?>
                </tr>
    <?php endif; ?>
<?php endforeach; ?>
        </tbody>
    </table>

    <div>
        <input type="hidden" name="option" value="com_uvelir" />
        <input type="hidden" name="view" value="categories" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo JHtml::_('form.token'); ?>
    </div>
</form>