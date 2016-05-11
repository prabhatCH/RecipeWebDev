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


class BeestoWorkflowModelClientTask extends JModelAdmin
{

	protected $text_prefix = 'COM_BEESTOWORKFLOW_TASKS';
	protected $assignees;

	public function getTable($type = 'Task', $prefix = 'BeestoWorkflowTable', $config = array()) {
		
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true) {	
		
		$form = $this->loadForm('com_beestoworkflow.clienttask', 'clienttask', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}
	

	protected function loadFormData() {
		
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_beestoworkflow.edit.clienttask.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}
		
		return $data;
	}
	
	protected function prepareTable( $table ) {
		
		if (!$table->id) {
			
			$data = JRequest::getVar('assigned_to',array(),'','array');
			
			if($data) {
				$table->assigned_to = json_encode($data);
			}

			$table->start = BeestoWorkflowHelper::getTime();
			$table->created_by = JFactory::getUser()->get('id');
			$table->ordering = 0;
	
		} 
	}
	
	
	public function getAssignees () {
		
		$data = $this->getItem();
		
		// get assignees
		if(isset($data->assigned_to)) {
			$assignees = json_decode($data->assigned_to);
			$to = array();
			foreach($assignees as $assign) {
				$to[] = JFactory::getUser($assign)->get('name');
			}
			
			$this->assignees = new stdClass();
			$this->assignees->names = $to;
			$this->assignees->id = $assignees;
		}
		
		return $this->assignees;
	}
	
	
	public function getWhoCompleted () {
		
		$data = $this->getItem();
		$assignees = array();
		
		// get assignees
		if(($data->completed)) {
			
			if($data->completed == 'completed') {
				$assignees = json_decode($data->assigned_to);
			} else {
				$assignees = json_decode($data->completed);
			}
			
		}	
		return $assignees;
		
	}
	
	
	public function getProject() {
		$data = $this->getItem();
		if($data->id) {
			if ($data->project_id) {
				$table = JTable::getInstance('Project','BeestoWorkflowTable');
				$table->load($data->project_id);
				if ($table->name) {
					return '<a href="'.JRoute::_('index.php?option=com_beestoworkflow&task=staffproject.edit&id=' . $data->project_id).'">' . $table->name . '</a>';
				} 
			} else {
				return JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_GENERAL') ;
			}
		}
	}
	
	
	public function getStatus () {
		$data = $this->getItem();
		if($data->id) {
			if ($data->completed == 'completed') {
				return JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_COMPLETED');
			} else {
				$stage = BeestoWorkflowHelper::getStage($data->due);
				if ($stage == 'active') {
					return JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_ACTIVE');
				} elseif ($stage == 'overdue') {
					return '<span class="level4">' . JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_OVERDUE') . '</span>';
				}
			}
		}
	}
	
	

	
	/*
	 * Method to mark the status of a task as completed
	 * 
	 * @param		
	 * @return	
	 */
	public function setCompleted () {
		
		// change project status
		$table 	= $this->getTable();
		$task 	= JRequest::getVar('task_id','','','int');
		$user	= JFactory::getUser()->get('id');
		$table->load($task);
		
		if ($table->completed != 'completed') {
			
			$assignees = json_decode($table->assigned_to);

			if(in_array($user,$assignees)) {
				$completed_by = ($table->completed) ? json_decode($table->completed) : array();
				if(!in_array($user,$completed_by)) {
					array_push($completed_by,$user);
				} 
				
				$completed = true;
				foreach($assignees as $assigned) {
					if (!in_array($assigned,$completed_by)) {
						$completed = false;
					}
				} 
				
				if(!$completed) {
					$table->completed = json_encode($completed_by);
					$table->store();
				// only if it's completed by all the task assignees
				} else {
					$table->completed = 'completed';
					$table->store();
					
					// send email for completed task
					BeestoWorkflowHelper::sendAlert( 'taskcompleted', array('task_id'=>$task, 'task_name'=>$table->name, 'created_by'=>$table->created_by, 'project_id'=>$table->project_id) );
					
					// check if the task is part of workflow
					if ($table->ordering != 0) {
						
						
						$query = ' SELECT a.type FROM #__beestowf_project_workflow AS a ' .
								' INNER JOIN #__beestowf_projects AS b ON b.project_template = a.id ' .
								' INNER JOIN #__beestowf_tasks AS c ON c.project_id = b.id ' .
								' WHERE b.id = ' . $table->project_id ;
						$this->_db->setQuery($query);
						$type = $this->_db->loadResult();
						
						// if dependant tasks
						if ($type == 0) {
							
							// get next tasks
							$query = ' SELECT a.* FROM #__beestowf_project_workflow_steps AS a ' .
								' INNER JOIN #__beestowf_projects AS b ON b.project_template = a.project_workflow ' .
								' INNER JOIN #__beestowf_tasks AS c ON c.project_id = b.id ' .
								' WHERE a.order > ' . $table->ordering .
								' AND b.id = ' . $table->project_id .
								' ORDER BY a.order ASC ';
							$this->_db->setQuery($query);
							$nexts = $this->_db->loadObjectList();
							if ($nexts) {
								
								// we need the next task, so we'll take only the first one
								$next = $nexts[0];
								
								$time = strtotime(BeestoWorkflowHelper::getTime());
								$days = $next->due;
								$due = date('Y-m-d H:i:s',strtotime("+$days days",$time));
						
								// add new task to database
								$query = ' INSERT INTO #__beestowf_tasks(id,project_id,name,description,start,due,assigned_to,created_by,priority,completed,ordering,checked_out) VALUES ' .
									' ( NULL, '.$table->project_id.' , '.$this->_db->Quote($next->title).', '.$this->_db->Quote($next->description).
									','.$this->_db->Quote(BeestoWorkflowHelper::getTime()).','.$this->_db->Quote($due). ','.$this->_db->Quote($next->assigned_to).
									','.$table->created_by.' , '.$next->priority.','.$this->_db->Quote('').','.$next->order.',0)';

								$this->_db->setQuery($query);
								$this->_db->query();
						
								// send alert for new task created
								$extra = array('id'=>$this->_db->insertid(), 'assigned_to'=>$next->assigned_to, 'name'=>$next->title);
								BeestoWorkflowHelper::sendAlert ('newtask',$extra);
								
							}						
						}
					}
				}
			}
		}
	}

	
	public function getAttachments() {
		
		$data = $this->getItem();
		$query = ' SELECT a.id, a.name,a.owner,a.filetype,a.added FROM #__beestowf_files AS a WHERE a.type = 1 AND a.parent = ' . (int) $data->id;
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
		
	}
	
	public function getComments () {
		
		$data = $this->getItem();
		$query = ' SELECT a.id,a.comment,a.added,a.owner FROM #__beestowf_comments AS a WHERE a.type = 1 AND a.parent = ' . (int)  $data->id . ' ORDER BY a.id DESC ';
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
	}


	public function addComment ($task_id,$comment) {
		
		if (!$task_id || !$comment) {
			return false;
		}
		
		$filter = JFilterInput::getInstance();
		
		$task_id = $filter->clean($task_id,'int');
		$comment = $filter->clean($comment,'string');
		$comment = str_replace(array("\r\n"),'<br/>',$comment);
		$added 	 = BeestoWorkflowHelper::getTime();
		$owner   = JFactory::getUser()->get('id');
		
		$query = ' INSERT INTO #__beestowf_comments (id,type,comment,parent,added,owner) VALUES (' .
				' NULL, 1, '.$this->_db->Quote($this->_db->escape($comment)).','.$task_id.','.$this->_db->Quote($added).','.$owner.')';
		$this->_db->setQuery($query);
		$this->_db->Query();
		
		return true;
	}
	
	
	
	public function deleteComment ( $comments ) {
		if ($comments) {
			foreach ($comments as $comment) {
				$query = ' DELETE FROM #__beestowf_comments WHERE id = ' . $comment;
				$this->_db->setQuery($query);
				$this->_db->Query();
			}
			return true;
		}
	}
	
	
	public function addfile () {
	
		JClientHelper::setCredentialsFromRequest('ftp');
		$file		= JRequest::getVar('fileadd', array(), 'files', 'array');
		$task_id	= JRequest::getVar('task_id','','','int');
		$project	= JRequest::getVar('project','','','int');
		$folder 	= '00'.$project;
		$params		= JComponentHelper::getParams('com_beestoworkflow');
		
		if ($file['name']) {
			
			// check if extension is allowed or ignored
			if ($params->get('allow_attachments') != 1) {
				$this->setError(JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_ERROR_NO_ATTACHMENT_ALLOW'));
				return false;
			}
			
			$extension 	= JFile::getExt($file['name']);
			
			$allowed = $params->get('upload_extensions');
			if (!empty($allowed)) {
				$ext_allowed = explode(',',$allowed);
				if (!in_array($extension,$ext_allowed)) {
					$this->setError(JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_ERROR_ATTACHMENT_NOT_ALLOW'));
					return false;
				}
			}

			$denied = $params->get('ignored_extensions');
			if (!empty($allowed)) {
				$ignored = explode(',',$denied);
				if (in_array($extension,$ignored)) {
					$this->setError(JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_ERROR_ATTACHMENT_NOT_ALLOW'));
					return false;
				}
			}
			
			$file['name']	= JFile::makeSafe($file['name']);
			
			// check if the directory exists, if no we will create him	
			$dirpath = JPath::clean(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $folder );
			if(!is_dir($dirpath)) {
				if(!JFolder::create($dirpath,0777)) {
					$this->setError(JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_ERROR_CREATE_FOLDER'));
					return false;
				}
			}
			
			// check if file exist, if exist we rename him by adding a letter in front
			$filepath = JPath::clean($dirpath . DIRECTORY_SEPARATOR . $file['name']);
			if(JFile::exists($filepath)) {
				$i = 0;
				while(JFile::exists($filepath)) {
					$filepath = JPath::clean($dirpath . DIRECTORY_SEPARATOR  . $i . $file['name']);
					$i++;
				}
			}
			
			// if we can't upload set the error
			if (!JFile::upload($file['tmp_name'], $filepath)) {
				// Error in upload
				$this->setError(JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_ERROR_UPLOAD_FILE'));
				return false;
			}
			
	
			// get some details about the file to use when inserting into database
			$location  	= $folder . DIRECTORY_SEPARATOR . JFile::makeSafe($file['name']);
			$date 		= BeestoWorkflowHelper::getTime();
			$owner		= JFactory::getUser()->get('id');
			
			// add the file into database
			$query = ' INSERT INTO #__beestowf_files (id,type,parent,name,filetype,location,added,owner) VALUES (' .
					 ' NULL,1,'.$task_id.','.$this->_db->Quote($file['name']).','.$this->_db->Quote($extension).','.$this->_db->Quote($location).
					 ' , ' .$this->_db->Quote($date).','.$owner.')';
			$this->_db->setQuery($query);
			$this->_db->Query();
			
			return true;
		}
	}
	
	
	public function getParams () {
		$params		= JComponentHelper::getParams('com_beestoworkflow');
		return $params;
	}
	
}
?>
