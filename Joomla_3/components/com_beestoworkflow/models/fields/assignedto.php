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

class JFormFieldAssignedto extends JFormFieldList {

	public function getInput() {
		
		$isNew = $this->form->getValue('id') ? false : true;

		$db 	= JFactory::getDbo();
		$html 	= array();
		$user 	= JFactory::getUser()->get('id');
		
		if($isNew) {
		
			$query = ' SELECT a.id, b.name FROM #__beestowf_users AS a INNER JOIN #__users AS b ON a.id = b.id WHERE a.type = 0';
			$db->setQuery ($query);
			$db->Query();
			$staff = $db->loadObjectList();
			
			$query = ' SELECT a.id, b.name FROM #__beestowf_users AS a INNER JOIN #__users AS b ON a.id = b.id WHERE a.type = 1';
			$db->setQuery ($query);
			$db->Query();
			$clients = $db->loadObjectList();
				
			$html[] = '<select name="assigned_to[]" multiple="multiple" style="height:150px;width:350px;">';
			
			// staff list
			$html[] = '<optgroup label="'.JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_STAFF').'">';
			if ($staff) {
				foreach ($staff as $staf) {
					if( $staf->id != $user ) {
						$html[] = '<option value="'.$staf->id.'">'.$staf->name.'</option>';
					}
				}
			}
			$html[] = '</optgroup>';
			
			// client list
			$html[] = '<optgroup label="'.JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_CLIENT').'">';
			if ($clients) {
				foreach ($clients as $client) {
					$html[] = '<option value="'.$client->id.'">'.$client->name.'</option>';
				}
			}
			$html[] = '</optgroup>';
			
			$html[] = '</select>';
				 
			return implode($html);
		} else {
			
			$assigned_to = $this->form->getValue('assigned_to');
			if ($assigned_to) {
				$assigned = json_decode($assigned_to);
			} else {
				$assigned = array();
			}
			foreach ($assigned as $assigned) {
				$html[] = JFactory::getUser($assigned)->get('name');
			}
			return implode(',',$html);
		}

	}
	
}
?>
