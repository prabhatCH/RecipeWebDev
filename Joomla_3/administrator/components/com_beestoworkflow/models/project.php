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


class BeestoWorkflowModelProject extends JModelAdmin
{

	protected $text_prefix = 'COM_BEESTOWORKFLOW_PROJECTS';


	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.delete', 'com_beestoworkflow.project');
		}
		else {
			return parent::canDelete($record);
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_beestoworkflow.project');
		}
		else {
			return parent::canEditState($record);
		}
	}


	public function getTable($type = 'Project', $prefix = 'BeestoWorkflowTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_beestoworkflow.project', 'project', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_beestoworkflow.edit.project.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	
	public function getTasks() {
		
		$form = $this->getForm();
		$query = ' SELECT a.id, a.name,a.start, a.due, a.priority from #__beestowf_tasks AS a WHERE a.project_id = ' .  $form->getValue('id');
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
		
	}
	
	public function getAttachments() {
		
		$form = $this->getForm();
		$query = ' SELECT a.id, a.name,a.owner,a.filetype FROM #__beestowf_files AS a WHERE a.type = 0 AND a.parent = ' .  $form->getValue('id');
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
		
	}
	
	public function getComments () {
		
		$form = $this->getForm();
		$query = ' SELECT a.id,a.comment,a.added,a.owner FROM #__beestowf_comments AS a WHERE a.type = 0 AND a.parent = ' .  $form->getValue('id');
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
	}

	public function deleteProjects($ids) {

		if ($ids) {
			foreach ($ids as $project) {
				
				// get dependent tasks
				$query = ' SELECT a.id, a.name,a.start, a.due, a.priority from #__beestowf_tasks AS a WHERE a.project_id = ' .  $project;
				$this->_db->setQuery($query);
				$tasks = $this->_db->loadObjectList();
				
				if ($tasks) {
					foreach ($tasks as $task) {
						$this->deleteTask( $task );
					}
				}
				
				// find and delete project files
				$query = ' DELETE FROM #__beestowf_files WHERE type = 0 AND parent = ' . $project;
				$this->_db->setQuery($query);
				$this->_db->Query();
				
				// delete folder from server
				$path = JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . '00'.$project;
				if (is_dir($path)) {
					JFolder::delete($path);
				}

				// delete any comment related with project
				$query = ' DELETE FROM #__beestowf_comments WHERE type = 0 AND parent = ' . $project;
				$this->_db->setQuery($query);
				$this->_db->Query();
						
				// delete project
				$query = ' DELETE FROM #__beestowf_projects WHERE id = ' . $project;
				$this->_db->setQuery($query);
				$this->_db->Query();
			}
		}
	}
	
	
	protected function deleteTask( $task ) {

		// find task attachments and delete
		$query = ' SELECT a.location,a.id FROM #__beestowf_files AS a WHERE a.type = 1 AND a.parent = ' . $task->id;
		$this->_db->setQuery($query);
		$files = $this->_db->loadObjectList();

		if ( $files ) {

			foreach ( $files as $file ) {
				// delete attachment from server
				$path = JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $file->location;
				if (is_file($path)) {
					JFile::delete($path);
				}

				// delete attachment from database
				$query = ' DELETE FROM #__beestowf_files WHERE id = ' . $file->id;
				$this->_db->setQuery($query);
				$this->_db->Query();
			}
		}

		// delete any comment related with task
		$querys = ' DELETE FROM #__beestowf_comments WHERE type = 1 AND parent = ' . $task->id;
		$this->_db->setQuery($query);
		$this->_db->Query();
		
		// delete task
		$query = ' DELETE FROM #__beestowf_tasks WHERE id = ' . $task->id;
		$this->_db->setQuery($query);
		$this->_db->Query();
	}
	
	
	

}
?>
