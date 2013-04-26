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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_jugraauto/assets/css/jugraauto.css');
$address = $this->item->street_type.' '.$this->item->street.', '.$this->item->house;
//var_dump($this->item->pointy, $this->item->pointy);
//55.750734,37.624537
$this->item->pointx = $this->item->pointx?$this->item->pointx:'55.750734';
$this->item->pointy = $this->item->pointy?$this->item->pointy:'37.624537';
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
        
		if (task == 'company.cancel' || document.formvalidator.isValid(document.id('company-form'))) {
			Joomla.submitform(task, document.getElementById('company-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
        window.addEvent('domready', function() {
                document.id('jform_type0').addEvent('click', function(e){
                        document.id('com_jugraauto_image').setStyle('display', 'block');
                        document.id('com_jugraauto_points').setStyle('display', 'none');
                });
                document.id('jform_type1').addEvent('click', function(e){
                        document.id('com_jugraauto_image').setStyle('display', 'none');
                        document.id('com_jugraauto_points').setStyle('display', 'block');
                });
                document.id('jform_type2').addEvent('click', function(e){
                        document.id('com_jugraauto_image').setStyle('display', 'none');
                        document.id('com_jugraauto_points').setStyle('display', 'block');
                });
                if(document.id('jform_type0').checked==true) {
                        document.id('jform_type0').fireEvent('click');
                } else {
                        document.id('jform_type1').fireEvent('click');
                }
        });
        <?php if($this->item->type == 1): ?> // 2Gis
        // Создаем обработчик загрузки страницы:
            DG.autoload(function() {
                // Создаем объект карты, связанный с контейнером:
                var myMap = new DG.Map('myMapId');
                // Устанавливаем центр карты и коэффициент масштабирования:
//                myMap.setCenter(new DG.GeoPoint(60.596190,56.858066),15);
                myMap.setCenter(new DG.GeoPoint(<?=$this->item->pointy?>,<?=$this->item->pointx?>),15);
                // Добавляем элемент управления коэффициентом масштабирования:
                myMap.controls.add(new DG.Controls.Zoom());
                // Создаем балун:
                var myBalloon = new DG.Balloons.Common({
                    // Местоположение на которое указывает балун: 
//                    geoPoint: new DG.GeoPoint(60.596190,56.858066),
                    geoPoint: new DG.GeoPoint(<?=$this->item->pointy?>,<?=$this->item->pointx?>),
                    // Устанавливаем текст, который будет отображатся при открытии балуна:
                    contentHtml: '<b><?=$this->item->name?></b><br/><?=$address?>'
                });
                // Создаем маркер:
                var myMarker = new DG.Markers.Common({
                    // Местоположение на которое указывает маркер:
                    geoPoint: new DG.GeoPoint(<?=$this->item->pointy?>,<?=$this->item->pointx?>),
                    // Функция, вызываемая при клике по маркеру
                    clickCallback: function() {
                        if (! myMap.balloons.getDefaultGroup().contains(myBalloon)) {
                            // Если балун еще не был добавлен на карту, добавляем его:
                            myMap.balloons.add(myBalloon);
                        } else {
                            // Показываем уже ранее добавленный на карту балун
                            myBalloon.show();
                        }
                    }
                });
                // Добавить маркер на карту:
                myMap.markers.add(myMarker);        
            }); 
        <?php elseif($this->item->type == '2'):?> // Yandex maps
            ymaps.ready(init);
            var myMap;

            function init(){     
                myMap = new ymaps.Map ("myMapId", {
                    center: [<?=$this->item->pointx?>, <?=$this->item->pointy?>],
                    zoom: 15
            });
            // Создание экземпляра элемента управления
            myMap.controls.add(
                new ymaps.control.ZoomControl()
            );
            myPlacemark = new ymaps.Placemark([<?=$this->item->pointx?>, <?=$this->item->pointy?>], { 
                content: '<?=$this->item->name?>', 
                balloonContent: '<b><?=$this->item->name?></b><br/><?=$address?>' 
            });

            myMap.geoObjects.add(myPlacemark);            }
        <?php endif;?>
        jQuery(document).ready(function($){
            $('#com_jugraauto_geo_code').click(function(){
                var city = $('#jform_city_id').find(":selected").text();
                var street = $('#jform_street').val();
                var house = $('#jform_house').val();
                console.log(city);
                console.log(street);
                console.log(house);
                $.ajax({
                    type: 'GET',
                    url: 'http://geocode-maps.yandex.ru/1.x/?geocode='+city+',+'+street+'+'+house+'&format=json',
                    success: function(result){
                        var point = result.response.GeoObjectCollection.featureMember[0].GeoObject.Point.pos.split(" ");
                        $('#jform_pointx').val(point[1]);
                        $('#jform_pointy').val(point[0]);
                        console.log(result.response.GeoObjectCollection);
                    }
                });

            });
        });
    
</script>
<style type="text/css">
    div.left{float: left}
    div.two-gigs{
        width:470px; 
        height:400px;
/*        padding: 15px;*/
        margin: 20px 0;
                
    }
</style>

<form action="<?php echo JRoute::_('index.php?option=com_jugraauto&layout=edit&id='.(int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="company-form" class="form-validate">
	<div class="width-60 fltlft left">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JUGRAAUTO_LEGEND_COMPANY'); ?></legend>
			<ul class="adminformlist">
                
				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
				<li><?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?></li>
				<li><?php echo $this->form->getLabel('alias'); ?>
				<?php echo $this->form->getInput('alias'); ?></li>
                                
				<li><?php echo $this->form->getLabel('city_id'); ?>
				<?php echo $this->form->getInput('city_id'); ?></li>
				<li><?php echo $this->form->getLabel('street_type'); ?>
				<?php echo $this->form->getInput('street_type'); ?></li>
				<li><?php echo $this->form->getLabel('street'); ?>
				<?php echo $this->form->getInput('street'); ?></li>
				<li><?php echo $this->form->getLabel('house'); ?>
				<?php echo $this->form->getInput('house'); ?></li>
				<li><?php echo $this->form->getLabel('address_else'); ?>
				<?php echo $this->form->getInput('address_else'); ?></li>
                                
				<li><?php echo $this->form->getLabel('email'); ?>
				<?php echo $this->form->getInput('email'); ?></li>
				<li><?php echo $this->form->getLabel('fio'); ?>
				<?php echo $this->form->getInput('fio'); ?></li>
				<li><?php echo $this->form->getLabel('phone'); ?>
				<?php echo $this->form->getInput('phone'); ?></li>
				<li><?php echo $this->form->getLabel('fax'); ?>
				<?php echo $this->form->getInput('fax'); ?></li>
                                
				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>
				<li><?php echo $this->form->getLabel('created_by'); ?>
				<?php echo $this->form->getInput('created_by'); ?></li>
                                
				<li><?php echo $this->form->getLabel('logo'); ?>
				<?php echo $this->form->getInput('logo'); ?></li>
				<li><?php echo $this->form->getLabel('width'); ?>
				<?php echo $this->form->getInput('width'); ?></li>
				<li><?php echo $this->form->getLabel('height'); ?>
				<?php echo $this->form->getInput('height'); ?></li>
				
                                <li><?php echo $this->form->getLabel('type'); ?>
				<?php echo $this->form->getInput('type'); ?></li>
                                <div id="com_jugraauto_image">
                                    <li><?php echo $this->form->getLabel('image'); ?>
                                    <?php echo $this->form->getInput('image'); ?></li>
                                </div>
                                <div id="com_jugraauto_points">
                                    <li><?php echo $this->form->getLabel('pointx'); ?>
                                    <?php echo $this->form->getInput('pointx'); ?></li>
                                    <li><?php echo $this->form->getLabel('pointy'); ?>
                                    <?php echo $this->form->getInput('pointy'); ?></li>
                                    <input type="button" id="com_jugraauto_geo_code" 
                                           value="<?=JTEXT::_('COM_JUGRAAUTO_GEO_FIND')?>"
                                           style="cursor:pointer">
                                </div>
                                <li><?php echo $this->form->getLabel('category'); ?>
				<?php echo $this->form->getInput('category'); ?></li>
                                
                                <li><?php echo $this->form->getLabel('desc'); ?>
				<?php echo $this->form->getInput('desc'); ?></li>


            </ul>
		</fieldset>
	</div>
    <div class="left two-gigs" id="myMapId"></div>

<?php if (JFactory::getUser()->authorise('core.admin','jugraauto')): ?>
	<div class="width-100 fltlft">
		<?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
		<?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
		<fieldset class="panelform">
			<?php echo $this->form->getLabel('rules'); ?>
			<?php echo $this->form->getInput('rules'); ?>
		</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>
	</div>
<?php endif; ?>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>

    <style type="text/css">
        /* Temporary fix for drifting editor fields */
        .adminformlist li {
            clear: both;
        }
    </style>
</form>