<?php
/**
* @version   $Id: index.php 7321 2013-02-07 05:15:16Z kevin $
 * @author RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2013 RocketTheme, LLC
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );

// load and inititialize gantry class
require_once(dirname(__FILE__) . '/lib/gantry/gantry.php');
// require_once ('lib/incase/index.php');
$gantry->init();

jimport('incase.init'); global $incase;

// get the current preset
$gpreset = str_replace(' ','',strtolower($gantry->get('name')));

?>
<!doctype html>
<html xml:lang="<?php echo $gantry->language; ?>" lang="<?php echo $gantry->language;?>" >
<head>
  <link rel="stylesheet" type="text/css" href="<?=$incase->noCache('/templates/gantry/css/style.css')?>" />
  <script type="text/javascript" src="https://lcab.sms-uslugi.ru/support/support.js?h=2e4fcf211d79d5bcaa3f3259538ae4c5" id="supportScript"></script>
  <?php if ($gantry->get('layout-mode') == '960fixed') : ?>
  <meta name="viewport" content="width=960px">
  <?php elseif ($gantry->get('layout-mode') == '1200fixed') : ?>
  <meta name="viewport" content="width=1200px">
  <?php else : ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php endif; ?>

    <?php
        $gantry->displayHead();

    // $gantry->addStyle('templates/' . $app->getTemplate() . '/lib/incase/compass/stylesheets/screen.css" type="text/css', 100);
    $gantry->addStyle('grid-responsive.css', 5);
    $gantry->addLess('bootstrap.less', 'bootstrap.css', 6);
      $gantry->addLess('global.less', 'master.css', 8, array('headerstyle'=>$gantry->get('headerstyle','dark')));
      $gantry->addScript('jquery.unveil.min.js');
      $gantry->addScript('init.js');

        if ($gantry->browser->name == 'ie'){
          if ($gantry->browser->shortversion == 9){
            $gantry->addInlineScript("if (typeof RokMediaQueries !== 'undefined') window.addEvent('domready', function(){ RokMediaQueries._fireEvent(RokMediaQueries.getQuery()); });");
          }
      if ($gantry->browser->shortversion == 8){
        $gantry->addScript('html5shim.js');
      }
    }
    if ($gantry->get('layout-mode', 'responsive') == 'responsive') $gantry->addScript('rokmediaqueries.js');
    if ($gantry->get('loadtransition')) {
    $gantry->addScript('load-transition.js');
    $hidden = ' class="rt-hidden"';}

    ?>
    <!--[if lt IE 9]>
      <script src="<?='templates/' . $app->getTemplate();?>/js/css3-mediaqueries.js"></script>
    <![endif]-->
    <script src="<?='templates/' . $app->getTemplate();?>/js/slideUpText.js"></script>
    <link rel="stylesheet" type="text/css" href="<?=$incase->noCache('/templates/gantry/css-compiled/screen.css')?>" />
<?php JHTML::_('behavior.modal', '#btn-zzv a'); ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body class="<?php echo (($incase->getMenu()->getActive() == $incase->getMenu()->getDefault()) ? ('front') : ('page')).' '.$incase->getActive()->alias.' '.$pageclass; ?>">
  <div class="strips"></div>
    <?php /** Begin Top Surround **/ if ($gantry->countModules('top') or $gantry->countModules('header')) : ?>
      <header id="rt-top-surround">
      <?php /** Begin Top **/ if ($gantry->countModules('top')) : ?>
      <div id="rt-top" <?php echo $gantry->displayClassesByTag('rt-top'); ?>>
        <div class="rt-container">
          <?php echo $gantry->displayModules('top','standard','standard'); ?>
          <div class="clear"></div>
        </div>
      </div>
      <?php /** End Top **/ endif; ?>
      <?php /** Begin Header **/ if ($gantry->countModules('header')) : ?>
      <div id="rt-header">
        <div class="rt-container">
          <?php echo $gantry->displayModules('header','standard','standard'); ?>
          <div class="clear"></div>
        </div>
      </div>
      <?php /** End Header **/ endif; ?>
    </header>
    <?php /** End Top Surround **/ endif; ?>
    <?php /** Begin Drawer **/ if ($gantry->countModules('drawer')) : ?>
      <div id="rt-drawer">
          <div class="rt-container">
              <?php echo $gantry->displayModules('drawer','standard','standard'); ?>
              <div class="clear"></div>
          </div>
      </div>
      <?php /** End Drawer **/ endif; ?>
    <?php /** Begin Showcase **/ if ($gantry->countModules('showcase')) : ?>
    <div id="rt-showcase">
      <div class="rt-showcase-pattern">
        <div class="rt-container">
          <?php echo $gantry->displayModules('showcase','standard','standard'); ?>
          <div class="clear"></div>
        </div>
      </div>
    </div>
    <?php /** End Showcase **/ endif; ?>
    <div id="rt-transition"<?php if ($gantry->get('loadtransition')) echo $hidden; ?>>
      <div id="rt-mainbody-surround">
        <?php /** Begin Feature **/ if ($gantry->countModules('feature')) : ?>
        <div id="rt-feature">
          <div class="rt-container">
            <?php echo $gantry->displayModules('feature','standard','standard'); ?>
            <div class="clear"></div>
          </div>
        </div>
        <?php /** End Feature **/ endif; ?>
        <?php /** Begin Utility **/ if ($gantry->countModules('utility')) : ?>
        <div id="rt-utility">
          <div class="rt-container">
            <?php echo $gantry->displayModules('utility','standard','standard'); ?>
            <div class="clear"></div>
          </div>
        </div>
        <?php /** End Utility **/ endif; ?>
        <?php /** Begin Breadcrumbs **/ if ($gantry->countModules('breadcrumb')) : ?>
        <div id="rt-breadcrumbs">
          <div class="rt-container">
            <?php echo $gantry->displayModules('breadcrumb','standard','standard'); ?>
            <div class="clear"></div>
          </div>
        </div>
        <?php /** End Breadcrumbs **/ endif; ?>
        <?php /** Begin Main Top **/ if ($gantry->countModules('maintop')) : ?>
        <div id="rt-maintop">
          <div class="rt-container">
            <?php echo $gantry->displayModules('maintop','standard','standard'); ?>
            <div class="clear"></div>
          </div>
        </div>
        <?php /** End Main Top **/ endif; ?>
        <?php /** Begin Full Width**/ if ($gantry->countModules('fullwidth')) : ?>
        <div id="rt-fullwidth">
          <?php echo $gantry->displayModules('fullwidth','basic','basic'); ?>
            <div class="clear"></div>
          </div>
        <?php /** End Full Width **/ endif; ?>
        <?php /** Begin Main Body **/ ?>
        <div class="rt-container">
              <?php echo $gantry->displayMainbody('mainbody','sidebar','standard','standard','standard','standard','standard'); ?>
            </div>
        <?php /** End Main Body **/ ?>
        <?php /** Begin Main Bottom **/ if ($gantry->countModules('mainbottom')) : ?>
        <div id="rt-mainbottom">
          <div class="rt-container">
            <?php echo $gantry->displayModules('mainbottom','standard','standard'); ?>
            <div class="clear"></div>
          </div>
        </div>
        <?php /** End Main Bottom **/ endif; ?>
        <?php /** Begin Extension **/ if ($gantry->countModules('extension')) : ?>
        <div id="rt-extension">
          <div class="rt-container">
            <?php echo $gantry->displayModules('extension','standard','standard'); ?>
            <div class="clear"></div>
          </div>
        </div>
        <?php /** End Extension **/ endif; ?>
      </div>
    </div>
    <?php /** Begin Bottom **/ if ($gantry->countModules('bottom')) : ?>
    <div id="rt-bottom">
      <div class="rt-container">
        <?php echo $gantry->displayModules('bottom','standard','standard'); ?>
        <div class="clear"></div>
      </div>
    </div>
    <?php /** End Bottom **/ endif; ?>
    <?php /** Begin Footer Section **/ if ($gantry->countModules('footer') or $gantry->countModules('copyright')) : ?>
    <footer id="rt-footer-surround">
      <?php /** Begin Footer **/ if ($gantry->countModules('footer')) : ?>
      <div id="rt-footer">
        <div class="rt-container">
          <?php echo $gantry->displayModules('footer','standard','standard'); ?>
          <div class="clear"></div>
        </div>
      </div>
      <?php /** End Footer **/ endif; ?>
      <?php /** Begin Copyright **/ if ($gantry->countModules('copyright')) : ?>
      <div id="rt-copyright">
        <div class="rt-container">
          <?php echo $gantry->displayModules('copyright','standard','standard'); ?>
          <div class="clear"></div>
          
          <p align="center">
          <!-- Рейтинг сайтов ювелирной сети , code for http://zoloto-online.net -->
          <script type="text/javascript">java="1.0";java1=""+"refer="+escape(document.referrer)+"&page="+escape(window.location.href); document.cookie="astratop=1; path=/"; java1+="&c="+(document.cookie?"yes":"now");</script>
          <script type="text/javascript1.1">java="1.1";java1+="&java="+(navigator.javaEnabled()?"yes":"now")</script>
          <script type="text/javascript1.2">java="1.2";java1+="&razresh="+screen.width+'x'+screen.height+"&cvet="+(((navigator.appName.substring(0,3)=="Mic"))? screen.colorDepth:screen.pixelDepth)</script>
          <script type="text/javascript1.3">java="1.3"</script>
          <script type="text/javascript">java1+="&jscript="+java+"&rand="+Math.random(); document.write("<a href='http://top.uvelir.info/?fromsite=450'><img "+" src='http://top.uvelir.info/img.php?id=450&"+java1+"&' border='0' alt='Рейтинг сайтов ювелирной сети' width='88' height='31'><\/a>");</script>
          <noscript><a href="http://top.uvelir.info/?fromsite=450" target="_blank"><img src="http://top.uvelir.info/img.php?id=450" border="0" alt="Рейтинг сайтов ювелирной сети" width="88" height="31"></a></noscript>
          <!-- /Рейтинг сайтов ювелирной сети -->
          </p>
        </div>
      </div>
      <?php /** End Copyright **/ endif; ?>
      <div class="metrika">
        <script type="text/javascript">(function(g,a,i){(a[i]=a[i]||[]).push(function(){try{a.yaCounter21491290=new Ya.Metrika({id:21491290,webvisor:true,clickmap:true,trackLinks:true,accurateTrackBounce:true})}catch(c){}});var h=g.getElementsByTagName("script")[0],b=g.createElement("script"),e=function(){h.parentNode.insertBefore(b,h)};b.type="text/javascript";b.async=true;b.src=(g.location.protocol=="https:"?"https:":"http:")+"//mc.yandex.ru/metrika/watch.js";if(a.opera=="[object Opera]"){g.addEventListener("DOMContentLoaded",e,false)}else{e()}})(document,window,"yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/21491290" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
      </div>
      <!-- BEGIN JIVOSITE CODE {literal} -->
      <script type='text/javascript'>
      (function(){ var widget_id = '122210';
      var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
      <!-- {/literal} END JIVOSITE CODE -->
    </footer>
    <?php /** End Footer Surround **/ endif; ?>
    <?php /** Begin Debug **/ if ($gantry->countModules('debug')) : ?>
    <div id="rt-debug">
      <div class="rt-container">
        <?php echo $gantry->displayModules('debug','standard','standard'); ?>
        <div class="clear"></div>
      </div>
    </div>
    <?php /** End Debug **/ endif; ?>
    <?php /** Begin Analytics **/ if ($gantry->countModules('analytics')) : ?>
    <?php echo $gantry->displayModules('analytics','basic','basic'); ?>
    <?php /** End Analytics **/ endif; ?>
  </body>
</html>
<?php
$gantry->finalize();
?>
