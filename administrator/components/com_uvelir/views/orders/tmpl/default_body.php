<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
                        <?php echo $this->get_user_fio($item->userid);?>
		</td>
		<td>
			<?php echo $this->get_order_status($item->order_status_id);?>
		</td>
		<td>
			<?php echo $this->get_order_dt($item->order_dt);?>
		</td>
		<td>
			<?php echo $this->get_sum($item->sum);?>
		</td>
	</tr>
<?php endforeach; ?>
