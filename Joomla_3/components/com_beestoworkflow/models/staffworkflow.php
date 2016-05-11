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


class BeestoWorkflowModelStaffWorkflow extends JModelAdmin
{

	protected $text_prefix = 'COM_BEESTOWORKFLOW_STAFFWORKFLOWS';

	public function getTable($type = 'Workflow', $prefix = 'BeestoWorkflowTable', $config = array()) {
		
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true) {	

		$form = $this->loadForm('com_beestoworkflow.staffworkflow', 'staffworkflow', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}
	

	protected function loadFormData() {
		
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_beestoworkflow.edit.staffworkflow.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}
		
		return $data;
	}
	
	protected function prepareTable( $table ) {
		
		if (!$table->id) {
			
			$table->owner = JFactory::getUser()->get('id');
			$table->published = 1;
			$table->type = 0;
	
		} 
	}
	
	
	public function getSteps () {
	
		$data = $this->getItem();
		$query = ' SELECT a.id, a.title, a.order,a.priority FROM #__beestowf_project_workflow_steps AS a ' .
				 ' WHERE a.project_workflow = ' . $data->id . ' ORDER BY a.order ASC ';
		$this->_db->setQuery($query);
		$steps = $this->_db->loadObjectList();
		
		return $steps;	
	}
	
	
	public function getOrdering() {
		
		$data = $this->getItem();
		
		$query = ' SELECT a.order FROM #__beestowf_project_workflow_steps AS a WHERE a.project_workflow = ' .$data->id. ' ORDER BY a.order ASC ';
		$this->_db->setQuery($query);
		$ordering = new stdClass();
		
		$ordering->values = $this->_db->loadColumn();
		$ordering->min = 0;
		$ordering->max = count($ordering->values) - 1;
		
		return $ordering;
		
	}
	
	
	public function orderdown () {
		
		$workflow 	= JRequest::getVar('workflow_id','','','int');
		$ids		= JRequest::getVar('cid',array(),'post','array');
		$id			= $ids[0];
		
		$query	= ' SELECT COUNT(a.id) FROM #__beestowf_project_workflow_steps AS a ' .	
				  ' WHERE a.project_workflow = ' . $workflow;
		$this->_db->setQuery($query);
		$max = $this->_db->loadResult();

		if ($max <= 1) {
			return true;
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
		
		$workflow 	= JRequest::getVar('workflow_id','','','int');
		$ids		= JRequest::getVar('cid',array(),'post','array');
		$id			= $ids[0];
		
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
	
	
	public function delete (&$ids) {
		
		$user = JFactory::getUser()->get('id');
		$table = $this->getTable();
		
		if ($ids) {
			foreach ($ids as $id) {
				$table->load($id);
				if ($table->owner != $user || $table->owner == 0) {
					$this->setError(JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_WORKFLOWS_SOME_DELETED'));
				} else {
					$table->delete($id);
					$query = ' DELETE FROM #__beestowf_project_workflow_steps WHERE project_workflow = ' . $id ;
					$this->_db->setQuery($query);
					$this->_db->Query();
				}
			}
		}
	
	}
	
	
	
}
?>
