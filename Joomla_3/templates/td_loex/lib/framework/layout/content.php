<?php
/*---------------------------------------------------------------
# Package - Stools Framework 
# ---------------------------------------------------------------
# Author - joomlatd http://www.joomlatd.com
# Copyright (C) 2008 - 2014 joomlatd.com. All Rights Reserved.
# license - PHP files are licensed under  GNU/GPL V2 
# Websites: http://www.joomlatd.com
-----------------------------------------------------------------*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');
?>
<div id="ju-maincol" class="clearfix">
<?php
$this->addModules('login'); //login module
?>
<?php
$this->addModules('header1', 'ju_xhtml'); //header1
$this->addModules('top', 'ju_xhtml'); //top 
$this->addModules('maintop1, maintop2, maintop3, maintop4', 'ju_xhtml', 'ju-maintop-grid-top'); //maintop1-maintop4
?>
<div class="clr"></div>
<?php $this->addModules('sideleft', 'ju_xhtml'); //sideleft ?>
<div id="inner_content" class="clearfix"> <!--Component Area-->
<?php 
$this->addModules('advert', 'ju_xhtml'); //advert	
$this->addModules('advert1,advert2,advert3,advert4', 'ju_xhtml', 'ju-advert-grid-top'); //advert1-advert4
$this->addModules('advert-mid', 'ju_xhtml'); //advert-mid
$this->addModules('advert5,advert6,advert7,advert8', 'ju_xhtml', 'ju-advert-grid-bottom'); //advert5-advert8	
$this->addModules('advert-bottom', 'ju_xhtml'); //advert-bottom
?>		
<?php if (!$this->hideItem()): ?>
<div class="ju-component-area clearfix">
<div class="ju-inner clearfix">
<?php $this->addModules('pathway'); //pathway position ?>
<jdoc:include type="message" />
<div class="ju-component-area-inner clearfix">
<jdoc:include type="component" />
</div>	
</div>
</div>
<?php endif; ?>
<?php
$this->addModules('info-top', 'ju_xhtml'); //info-top 
$this->addModules('info1,info2,info3,info4', 'ju_xhtml', 'ju-info'); //info1-info4
$this->addModules('info-bottom', 'ju_xhtml'); //info-bottom 
?>
</div>
<?php 
$this->addModules('sideright', 'ju_xhtml'); //sideright
?>
<div class="clr"></div>
<?php 
$this->addModules('mainbottom1,mainbottom6,mainbottom7,mainbottom8', 'ju_xhtml', 'ju-mainbottom-grid-bottom'); //mainbottom1-mainbottom8	
?>
</div>