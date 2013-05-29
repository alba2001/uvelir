<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
define( 'FILEPATH', dirname(__FILE__) . DS . 'accordion_files' );
// Note. It is important to remove spaces between elements.
?>

<ul id="accordion" class="accordion menu<?php echo $class_sfx;?>"<?php
	$tag = '';
	if ($params->get('tag_id')!=NULL) {
		$tag = $params->get('tag_id').'';
		echo ' id="'.$tag.'"';
	}
?>>
<?php
foreach ($list as $i => &$item) :
	$class = 'item-'.$item->id;
	if ($item->id == $active_id) {
		$class .= ' current';
	}

	if (in_array($item->id, $path)) {
		$class .= ' active';
	}
	elseif ($item->type == 'alias') {
		$aliasToId = $item->params->get('aliasoptions');
		if (count($path) > 0 && $aliasToId == $path[count($path)-1]) {
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path)) {
			$class .= ' alias-parent-active';
		}
	}
	$deeper = false;
	if ($item->deeper) {
		$class .= ' deeper';
		$deeper = true;
	}

	if ($item->parent) {
		$class .= ' parent';
	}

	if (!empty($class)) {
		$class = ' class="'.trim($class) .'"';
	}

	echo '<li'.$class.'>';

	// Render the menu item.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
			require (FILEPATH . DS . 'items.php');
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
			break;
	endswitch;

	// The next item is deeper.
	if ($item->deeper) {
		echo '<div class="sub">';
		echo '<ul>';
	}
	// The next item is shallower.
	elseif ($item->shallower) {
		echo '</li>';
		echo str_repeat('</ul></div></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else {
		echo '</li>';
	}

	// insert separator.
	echo '<li class="separator"></li>';
	// echo count($list)-1 . ' != ' . $i;
	// if( count($list)-1 != $i ){
	// }
endforeach;
?></ul>

<script type="text/javascript" src="/templates/gantry/html/mod_menu/accordion_files/jquery.nestedAccordion.js"></script>
<script type="text/javascript">

	jQuery("html").addClass("js");
	jQuery.fn.accordion.defaults.container = false;
	jQuery(function() {
	  jQuery("#accordion").accordion({
	      el: ".h",
	      head: "h4",
	      next: "div",
	      showMethod: "slideFadeDown",
	      hideMethod: "slideFadeUp",
	      initShow : "div.shown"
	  });
	  jQuery("html").removeClass("js");
	});

</script>