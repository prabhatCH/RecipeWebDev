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


class BeestoWorkflowModelStaffStep extends JModelAdmin
{

	protected $text_prefix = 'COM_BEESTOWORKFLOW_STAFFSTEP';
	protected $assignees;

	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.delete', 'com_beestoworkflow.staffstep');
		}
		else {
			return parent::canDelete($record);
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_beestoworkflow.staffstep');
		}
		else {
			return parent::canEditState($record);
		}
	}


	public function getTable($type = 'Step', $prefix = 'BeestoWorkflowTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_beestoworkflow.staffstep', 'staffstep', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_beestoworkflow.edit.staffstep.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}	
	
	protected function prepareTable( $table ) {
		
		if(empty($table->id)) {
			
			$app 		= JFactory::getApplication();
			$table->project_workflow	= $app->getUserState('com_beestoworkflow.session.workflow');
			
			$query 	= 'SELECT MAX(a.order) FROM #__beestowf_project_workflow_steps AS a WHERE a.project_workflow = ' . $table->project_workflow;
			$this->_db->setQuery ($query);
			$max = $this->_db->loadResult();
			$table->order = $max+1;
			
			$table->assigned_to = $this->assignees;
			
		}
		
		// get users assigned
		$assigned = JRequest::getVar('assigned',array(),'','array');
		$table->assigned_to = $this->assignees;	
	}
	
	
	public function save ($data) {
	
		if (empty($data['id'])) {
			
			$assigned = JRequest::getVar('assigned_to',array(),'post','array');
		
			if (!$assigned) { 
				$this->setError (JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_STEPS_ERROR_NO_ASSIGNEE'));
				return false;
			}
			$this->assignees = json_encode($assigned);
			
		}
		
		parent::save($data);
		return true;
	}
	
	
	public function deleteStep ($ids) {
	
		$id = $ids[0];
		
		$table = JTable::getInstance('Step','BeestoWorkflowTable');
		if (!$table->delete($id)) {
			$this->setError( JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_STEPS_ERROR_DELETE_STEP') );
		}
	
	}
	
	public function getOwner () {
		
		$app 		= JFactory::getApplication();
		$project_workflow	= $app->getUserState('com_beestoworkflow.session.workflow');
		
		$query = ' SELECT a.owner FROM #__beestowf_project_workflow AS a WHERE a.id = ' . $project_workflow . ' LIMIT 1';
		$this->_db->setQuery($query);
		$owner = $this->_db->loadResult();
		
		return $owner;
	}
	

	

}
?>
