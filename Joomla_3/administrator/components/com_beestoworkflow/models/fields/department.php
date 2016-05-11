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

class JFormFieldDepartment extends JFormFieldList {

	public function getOptions() {
		
		$db = JFactory::getDbo();

		$type = $this->form->getValue('department');

		$html =  JText::_('COM_BEESTOWORKFLOW_USERS_NO_DEPARTMENT');
	
		// if == 0 (staff)
		if (!$type){
			
			$query = ' SELECT a.id as value,a.title as text FROM #__beestowf_departments AS a';
			$db->setQuery($query);
			$db->Query();
		
			$result = $db->loadObjectList();
			
			if ($result) { 
				array_unshift($result, JHtml::_('select.option', '', ' - ' . JText::_('COM_BEESTOWORKFLOW_USERS_CONTACT_DEPARTMENT') . ' - ')); 
				return $result ; 
			} 
		}
		
		return $html;

	}
	
}
?>
