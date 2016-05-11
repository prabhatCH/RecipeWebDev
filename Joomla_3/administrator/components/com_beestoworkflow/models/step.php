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


class BeestoWorkflowModelStep extends JModelAdmin
{

	protected $text_prefix = 'COM_BEESTOWORKFLOW_STEPS';


	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.delete', 'com_beestoworkflow.step');
		}
		else {
			return parent::canDelete($record);
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_beestoworkflow.step');
		}
		else {
			return parent::canEditState($record);
		}
	}


	public function getTable($type = 'Steps', $prefix = 'BeestoWorkflowTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_beestoworkflow.step', 'step', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_beestoworkflow.edit.step.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}	
	
	protected function prepareTable( $table ) {
		
		if(!$table->order) {
			$id	 	= JFactory::getApplication('administrator')->getUserStateFromRequest('workflow_id','',0);
			$query 	= 'SELECT MAX(a.order) FROM #__beestowf_project_workflow_steps AS a WHERE a.project_workflow = ' . $id;
			$this->_db->setQuery ($query);
			$max = $this->_db->loadResult();
			$table->order = $max+1;
		}
		
		// get users assigned
		$assigned = JRequest::getVar('assigned',array(),'','array');
		$table->assigned_to = json_encode($assigned);	
	}
	
	
	public function orderdown () {
		
		$workflow 	= JFactory::getApplication('administrator')->getUserStateFromRequest('workflow_id','',0);

		$ids	= JRequest::getVar('cid',array(),'post','array');
		$id		= $ids[0];
		
		$query	= ' SELECT COUNT(a.id) FROM #__beestowf_project_workflow_steps AS a ' .	
				  ' WHERE a.project_workflow = ' . $workflow;
		$this->_db->setQuery($query);
		$max = $this->_db->loadResult();

		if ($max <= 1) {
			return;
		} else {
			//get item order 
			$query = ' SELECT a.order FROM #__beestowf_project_workflow_steps AS a WHERE a.id = ' . $id . ' LIMIT 1';
			$this->_db->setQuery($query);
			$orderCurrent = $this->_db->loadResult();
			
			// get current order list	
			$query = ' SELECT a.order FROM #__beestowf_project_workflow_steps AS a WHERE a.project_workflow = ' .$workflow. ' ORDER BY a.order ASC ';
			$this->_db->setQuery($query);
			$orders = $this->_db->loadColumn();
			
			// find the position of the item into the order list
			$position 	= array_search ($orderCurrent,$orders);

			if ($position < ($max-1)) {
				
				$query = ' UPDATE #__beestowf_project_workflow_steps AS a SET a.order = '. $orders[$position] . ' WHERE a.order = ' . ($orders[($position+1)]) . ' AND a.project_workflow = ' . $workflow;
				$this->_db->setQuery($query);
				$this->_db->Query();
		
				$query = ' UPDATE #__beestowf_project_workflow_steps AS a SET a.order = '. ($orders[($position+1)]) . ' WHERE a.id = ' . $id;
				$this->_db->setQuery($query);
				$this->_db->Query();

			}
		}
		
	}
	
	
	public function orderup () {
		
		$workflow 	= JFactory::getApplication('administrator')->getUserStateFromRequest('workflow_id','',0);

		$ids	= JRequest::getVar('cid',array(),'post','array');
		$id		= $ids[0];
		
		$query = ' SELECT a.order FROM #__beestowf_project_workflow_steps AS a WHERE a.id = ' . $id . ' LIMIT 1';
		$this->_db->setQuery($query);
		$orderCurrent = $this->_db->loadResult();
		
		if ($orderCurrent == 1) {
			
			return;
			
		} else {
			// get current order list	
			$query = ' SELECT a.order FROM #__beestowf_project_workflow_steps AS a WHERE a.project_workflow = ' .$workflow. ' ORDER BY a.order ASC ';
			$this->_db->setQuery($query);
			$orders = $this->_db->loadColumn();
			
			// find the position of the item into the order list
			$position 	= array_search ($orderCurrent,$orders);
			
			// set the order of the upper item ($position-1) as $position
			$query = ' UPDATE #__beestowf_project_workflow_steps AS a SET a.order = '. $orders[$position] . ' WHERE a.order = ' . ($orders[($position-1)]) . ' AND a.project_workflow = ' . $workflow;
			$this->_db->setQuery($query);
			$this->_db->Query();
			
			// set the order for the selected item as 1 position up ($position-1)
			$query = ' UPDATE #__beestowf_project_workflow_steps AS a SET a.order = '. ($orders[($position-1)]) . ' WHERE a.id = ' . $id;
			$this->_db->setQuery($query);
			$this->_db->Query();

		}
	}
	

}
?>
