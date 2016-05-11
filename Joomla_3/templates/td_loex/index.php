<?php
/*---------------------------------------------------------------
# Package - Joomla Template based on Stools Framework   
# ---------------------------------------------------------------
# Author - joomlatd http://www.joomlatd.com
# Copyright (C) 2008 - 2014 joomlatd.com. All Rights Reserved.
# Websites: http://www.joomlatd.com
-----------------------------------------------------------------*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');
require_once(dirname(__FILE__).'/lib/frame.php');
$jversion = new JVersion;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language;?>" >
<head>
<?php
$stools->loadHead();
$stools->addCSS('template.css,joomla.css,menu.css,override.css,modules.css');
if ($stools->isRTL()) $stools->addCSS('template_rtl.css');
?>

</head>
<?php $stools->addFeatures('ie6warn'); ?>
<body class="bg <?php echo $stools->direction . ' ' . $stools->style ?> clearfix">
<?php $stools->addFeatures('cpanel'); ?>

<div id="menuout" class="clearfix">
<div id="header" class="clearfix">
<div class="ju-base clearfix">
<?php 
$stools->addFeatures('logo');//Logo
?>
<?php 
$stools->addModules("mainmenu"); //position mainmenu
?>
</div>
</div>	
</div>
<?php if ($stools->showSlideItem()): ?>
<div id="slides">
<?php include 'slider/slider.php'; ?> 	
<?php
$stools->addModules("header"); //position header
?>
</div>
<?php endif; ?>
<div id="ju-top-header" class="clearfix">
<div class="ju-base clearfix">
<?php 
$stools->addModules('bookmarks');
$stools->addModules('search'); // search
$stools->addModules('top-menu'); // module top-menu
?>
</div>	
</div>
<div id="ju-basetop">
<div class="ju-base clearfix">		
<?php
$stools->addModules('top1, top2, top3, top4, top5, top6', 'ju_block', 'ju-userpos'); //positions top1-top6 
?>
</div>
</div>
<div class="main-bg clearfix">	
<div class="ju-base clearfix">	
<?php
$stools->addModules("breadcrumbs"); //breadcrumbs
?>
<div class="clearfix">
<?php $stools->loadLayout(); //mainbody ?>
</div>
</div>
</div>
<?php
$stools->addModules('bottom1, bottom2, bottom3, bottom4, bottom5, bottom6', 'ju_block', 'ju-bottom', '', false, true); //positions bottom1-bottom6 
?>
<!--Start Footer-->
<div id="ju-footer" class="clearfix">
<div class="ju-base">
<div class="cp">
<?php $stools->addFeatures('copyright,designed')  ?>					
</div>
<?php 
$stools->addFeatures('gotop');			
$stools->addModules("footer-nav"); 
?>
<div class="clearfix">
</div>
</div>
</div>
<!--End Footer-->

<?php 
$stools->addFeatures('analytics,jquery,ieonly'); /*--- analytics, jquery features ---*/
?>
<jdoc:include type="modules" name="debug" />
</body>
</html>