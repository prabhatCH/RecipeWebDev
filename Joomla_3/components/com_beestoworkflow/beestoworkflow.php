<?php
/*
 * @component BeestoWorkflow
 * @version 1.4 "Erdos"
 * @website : http://www.ionutlupu.me
 * @copyright Ionut Lupu. All rights reserved.
 * @license : http://www.gnu.org/copyleft/gpl.html GNU/GPL , see license.txt
 */
 
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.framework'); 

// add the submenu
require_once JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'submenu.php';
require_once JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'beestoworkflow.php';

// add field to tables
JTable::addIncludePath(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'tables');

jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('BeestoWorkflow');

//$controller->execute(JRequest::getCmd('task'));
$controller->execute( JFactory::getApplication()->input->get('task', 'display') );

$controller->redirect();

?>
