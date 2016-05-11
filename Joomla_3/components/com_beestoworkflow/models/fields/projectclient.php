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

JFormHelper::loadFieldClass('list');

class JFormFieldProjectClient extends JFormFieldList {

	public function getOptions() {
		
		$db = JFactory::getDbo();
		$query = ' SELECT a.id as value, b.name as text FROM #__beestowf_users AS a INNER JOIN #__users AS b ON a.id = b.id  WHERE a.type = 1 ';
		$db->setQuery($query);
		$clients = $db->loadObjectList();
		array_unshift($clients, JHtml::_('select.option', 0, JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_INTERNAL_CLIENT')));
		return $clients;

	}
	
}
?>
