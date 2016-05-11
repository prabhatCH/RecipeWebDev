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

jimport('joomla.application.component.modeladmin');


class BeestoWorkflowModelStaffProfileUser extends JModelAdmin
{

	protected $text_prefix = 'COM_BEESTOWORKFLOW_STAFFPROFILE';

	public function getTable($type = 'BWUser', $prefix = 'BeestoWorkflowTable', $config = array()) {
		
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true) {	

	$form = $this->loadForm('com_beestoworkflow.staffprofileuser', 'staffprofileuser', array('control' => 'jform', 'load_data' => $loadData));
			
	if (empty($form)) {
		return false;
	}
	
		return $form;
	}


	protected function loadFormData() {
		
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_beestoworkflow.edit.staffprofileuser.data', array());
		
		if (empty($data)) {
			$data = $this->getItem();
		}
		
		return $data;
	}
	
	

	
}
?>
