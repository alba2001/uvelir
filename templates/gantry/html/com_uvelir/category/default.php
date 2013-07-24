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
jimport('incase.init');

//var_dump($this->children);
    $empty_img_src = JURI::base().'components/com_uvelir/assets/img/empty_64.png';


    if($this->item->img)
    {
        $img_src = JURI::base().$this->item->img;
    }
    else
    {
        $img_src = $empty_img_src;
    }
?>

<?php if( $this->item ) : ?>
	<div class="category items-wrapper">

		<!--Детали категории-->
	    <h2>
	        <?/*<img class="thumb" src="<?=$img_src?>" atl="<?=$this->item->name?>"/>*/?>
	        <?=ucfirst( mb_convert_case($this->item->name, MB_CASE_TITLE, 'UTF-8') );?>
	    </h2>
	    <div class="description">
		    <?php if($this->item->note):?>
		        <h6><?=$this->item->note?></h6>
		    <?php endif;?>
		    <?php if($this->item->description):?>
		        <?=$this->item->description?>
		    <?php endif;?>
	    </div>

		<!--Список зависимых категорий-->
	    <?php if($this->children):?>
	        <div class="uvelir_subcategiries">
	            <ul class="items">
	            <?php foreach ($this->children as $child):?>
	                <?php
	                    if(!empty($child->img))
	                    // if(isset($child->desc))
	                    {
	                        // $desc = json_decode($child->desc);
	                        // $img_src = isset($desc->img_small)?$desc->img_small:$empty_img_src;
	                        $img_src = $child->img;
	                    }
	                    else
	                    {
	                        $img_src = $empty_img_src;
	                    }
	                ?>
	                <li class="com_uvelir_item">
	                    <?php $href = JRoute::_('index.php?option=com_uvelir&alias='.$child->alias)?>
	                    <div class="image">
		                    <a href="<?=$href?>">
		                    		<img data-src="<?=incase::thumb($img_src, 150, 100, true);?>" src="/images/load.gif" alt="<?=$this->item->name?>"/>
		                    </a>
	                    </div>
	                    <a href="<?=$href?>">
		                    <?=$child->name?>
	                    </a>
	                </li>
	            <?php endforeach;?>
	            </ul>
	        </div>
	    <?php endif;?>

		<!--Список товаров категории-->
		<?php if($this->items):?>
			<?php echo $this->loadTemplate('products');?>
		<?php endif;?>
	</div><!-- category -->
<?php endif ?>