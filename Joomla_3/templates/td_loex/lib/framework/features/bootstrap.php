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
if ($this->getParam('bootstrap',0)){
if (JVERSION>=3) {
// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');		
// Load optional rtl Bootstrap css and Bootstrap bugfixes
JHtmlBootstrap::loadCss();
} else {
$this->addCSS('bootstrap.min.css,bootstrap-extended.css');
$this->addJQuery();
$this->addJS('bootstrap.min.js');
}
}