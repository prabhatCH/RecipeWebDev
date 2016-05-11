<?php
/*---------------------------------------------------------------
# Package - Stools Framework  
# ---------------------------------------------------------------
# Author - joomlatd http://www.joomlatd.com
# Copyright (C) 2008 - 2013 joomlatd.com. All Rights Reserved. 
# Websites: http://www.joomlatd.com
-----------------------------------------------------------------*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

if ($this->countModules ( 'right or right1 or right2 or right-mid or right3 or right4 or right-bottom' )) {
define('_RIGHT', 1);

?>
<div id="ju-rightcol">
<?php 
$this->addModules('right','ju_xhtml');//position right
$this->addModules('right1,right2','ju_xhtml','ju-right-gridtop');//position right1 and right2
$this->addModules('right-mid','ju_xhtml');//position right-mid
$this->addModules('right3,right4','ju_xhtml','ju-right-gridbottom');//position right3 and right4	
$this->addModules('right-bottom','ju_xhtml');//position right-bottom 				
?>
</div>
<?php
}