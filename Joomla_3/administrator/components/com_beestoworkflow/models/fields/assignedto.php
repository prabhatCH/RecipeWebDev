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

		$db = JFactory::getDbo();
		$query = ' SELECT a.id, b.name FROM #__beestowf_users AS a INNER JOIN #__users AS b ON a.id = b.id WHERE a.type = 0';
		$db->setQuery ($query);
		$db->Query();
		$staff = $db->loadObjectList();
		
		$query = ' SELECT a.id, b.name FROM #__beestowf_users AS a INNER JOIN #__users AS b ON a.id = b.id WHERE a.type = 1';
		$db->setQuery ($query);
		$db->Query();
		$clients = $db->loadObjectList();
		
		$html = array();
		$assigned_to = $this->form->getValue('assigned_to');
		if ($assigned_to) {
			$assigned = json_decode($assigned_to);
		} else {
			$assigned = array();
		}
		
		// staff list
		$html[] = '<table class="table">';
		$html[] = '<thead>';
		$html[] = '<tr><th colspan="2"> ' . JText::_('COM_BEESTOWORKFLOW_USERS_STAFF').'</th></tr>';
		$html[] = '</thead>';
		$html[] = '<tbody>';
		if ($staff) {
			foreach ($staff as $staf) {
				if (in_array($staf->id,$assigned)) {
					$checked = ' checked="checked" ' ;
				} else {
					$checked = ' ' ;
				}
				$html[] = '<tr><td><input type="checkbox" class="inputbox" name="assigned[]" value="'.$staf->id.'" '.$checked.'/></td><td>'.$staf->name.'</td></tr>';
			}
		}
		$html[] = '</tbody>';
		$html[] = '</table>';
		
		// client list
		$html[] = '<table class="table">';
		$html[] = '<thead>';
		$html[] = '<tr><th colspan="2">' . JText::_('COM_BEESTOWORKFLOW_USERS_CLIENT') . '</th></tr>';
		$html[] = '</thead>';
		$html[] = '<tbody>';
		if ($clients) {
			foreach ($clients as $client) {
				if (in_array($client->id,$assigned)) {
					$checked = ' checked="checked" ' ;
				} else {
					$checked = ' ' ;
				}
				$html[] = '<tr><td><input type="checkbox" class="inputbox" name="assigned[]" value="'.$client->id.'" '.$checked.'/></td><td>'.$client->name.'</td></tr>';
			}
		}
		$html[] = '</tbody>';
		$html[] = '</table>';
             
        return implode($html);

	}
	
}
?>
