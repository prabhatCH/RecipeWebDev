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
if ($this->getParam('showcp')) {
echo JText::_('JU') . ' &copy; ' . $this->getParam('copyright'); 
}
echo '<span class="designed_by">designed by <a target="_blank" title="joomlatd" href="http://www.joomlatd.com">joomlatd</a><br /></span>';
