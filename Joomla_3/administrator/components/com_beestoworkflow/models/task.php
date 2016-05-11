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


class BeestoWorkflowModelTask extends JModelAdmin
{

	protected $text_prefix = 'COM_BEESTOWORKFLOW_TASKS';


	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.delete', 'com_beestoworkflow.task');
		}
		else {
			return parent::canDelete($record);
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_beestoworkflow.task');
		}
		else {
			return parent::canEditState($record);
		}
	}


	public function getTable($type = 'Task', $prefix = 'BeestoWorkflowTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_beestoworkflow.task', 'task', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_beestoworkflow.edit.task.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	

	public function getAttachments() {
		
		$form = $this->getForm();
		$query = ' SELECT a.id, a.name,a.owner,a.filetype FROM #__beestowf_files AS a WHERE a.type = 1 AND a.parent = ' .  $form->getValue('id');
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
		
	}
	
	
	public function getComments () {
		
		$form = $this->getForm();
		$query = ' SELECT a.id,a.comment,a.added,a.owner FROM #__beestowf_comments AS a WHERE a.type = 1 AND a.parent = ' .  $form->getValue('id');
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
	}
	

	public function deleteTasks ($ids) {

		JClientHelper::setCredentialsFromRequest('ftp');
		
		if ($ids) {
			foreach ($ids as $id) { 
				
				// find task attachments and delete
				$query = ' SELECT a.location,a.id FROM #__beestowf_files AS a WHERE a.type = 1 AND a.parent = ' . $id;
				$this->_db->setQuery($query);
				$location = $this->_db->loadObject();
				if ($location) {
							// delete attachment from server
					$path = JPATH_COMPONENT . DIRECTORY_SEPARATOR . $location->location;
					if (is_file($path)) {
							JFile::delete($path);
					}
				}
				
				// delete attachment from database
				$query = ' DELETE FROM #__beestowf_files WHERE id = ' . $location->id;
				$this->_db->setQuery($query);
				$this->_db->Query();
						
				// delete any note related with task
				$query = ' DELETE FROM #__beestowf_notes WHERE type = 1 AND parent = ' . $id;
				$this->_db->setQuery($query);
				$this->_db->Query();
						
				// delete any comment related with task
				$query = ' DELETE FROM #__beestowf_comments WHERE type = 1 AND parent = ' . $id;
				$this->_db->setQuery($query);
				$this->_db->Query();
						
				// delete task
				$query = ' DELETE FROM #__beestowf_tasks WHERE id = ' . $id;
				$this->_db->setQuery($query);
				$this->_db->Query();
			}
		}
	}
	
	
	public function deleteMessage ( $id ) {
	
			$query = ' DELETE FROM #__beestowf_comments WHERE id = ' . $id;
			$this->_db->setQuery($query);
			$this->_db->Query();

	}
	
	
	

}
?>
