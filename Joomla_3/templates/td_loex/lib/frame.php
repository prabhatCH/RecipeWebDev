<?php
/*---------------------------------------------------------------
# Package - Stools Framework  
# Stools Version 1.9.5
# ---------------------------------------------------------------
# Author - joomlatd http://www.joomlatd.com
# Copyright (C) 2008 - 2013 joomlatd.com. All Rights Reserved.
# license - PHP files are licensed under  GNU/GPL V2 
# Websites: http://www.joomlatd.com
-----------------------------------------------------------------*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');
if(!defined('DS')){
define( 'DS', DIRECTORY_SEPARATOR );
}
$docs = JFactory::getDocument();
$stools_path = (dirname(__file__) . DS . 'framework' . DS . 'main' . DS . 'helper.php');
require_once($stools_path);
$stools = new stoolsHelper($docs);
