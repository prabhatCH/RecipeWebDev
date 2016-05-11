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


class BeestoWorkflowModelStaffNote extends JModelAdmin
{

	protected $text_prefix = 'COM_BEESTOWORKFLOW_STAFFNOTES';

	public function getTable($type = 'Note', $prefix = 'BeestoWorkflowTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true)
	{	

	$form = $this->loadForm('com_beestoworkflow.staffnote', 'staffnote', array('control' => 'jform', 'load_data' => $loadData));
			
	if (empty($form)) {
		return false;
	}
	
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_beestoworkflow.edit.staffnote.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}
		
		return $data;
	}
	
	protected function prepareTable( $table ) {
		
		if (!$table->id) {
			// set the manager of the project
			$user = JFactory::getUser()->get('id');
			$table->owner  = $user;
			$table->added = BeestoWorkflowHelper::getTime();
			
			$jform  = JRequest::getVar('jform',array(),'','array');
			if (!empty($jform['new_category'])) {
				$table->category = $jform['new_category'];
			}		
		} 
	}
	
}
?>
