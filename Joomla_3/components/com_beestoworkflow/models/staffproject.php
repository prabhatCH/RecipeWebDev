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
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class BeestoWorkflowModelStaffProject extends JModelAdmin
{

	protected $text_prefix = 'COM_BEESTOWORKFLOW_PROJECTS';

	public function getTable($type = 'Project', $prefix = 'BeestoWorkflowTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true)
	{	
		// get project id to see if it's new project or existing project
		$id 		= JRequest::getVar('id','','','int');
		$layout 	= JRequest::getVar('isowner','','','int');
		$table		= $this->getTable();
		
		// check to see if it's the manager of the project, it he is we must show the edit button for project details
		$isManager = BeestoWorkflowHelper::isManager($id);
		
		// if it's not a new project OR it's a client request (manager id = 0)
		if ($id) {
			
			$table->load($id);
			// if it's not a client request
			if ($table->manager) {
				if ($layout == 1 && $isManager) {
					$form = $this->loadForm('com_beestoworkflow.staffproject', 'staffproject0', array('control' => 'jform', 'load_data' => $loadData));
				} else {
					$form = $this->loadForm('com_beestoworkflow.staffproject', 'staffproject1', array('control' => 'jform', 'load_data' => $loadData));
				}	
			} else {
				if ($layout == 1 && BeestoWorkflowHelper::canDo('manageProjectRequests')) {
					$form = $this->loadForm('com_beestoworkflow.staffproject', 'staffproject0', array('control' => 'jform', 'load_data' => $loadData));
				} else {
					$form = $this->loadForm('com_beestoworkflow.staffproject', 'staffproject1', array('control' => 'jform', 'load_data' => $loadData));
				}
			}	
		// if it's a new project and also not a client request
		} else {
			
			$form = $this->loadForm('com_beestoworkflow.staffproject', 'staffproject0', array('control' => 'jform', 'load_data' => $loadData));
		}
		

		if (empty($form)) {
			return false;
		}
		
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_beestoworkflow.edit.staffproject.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}
		
		return $data;
	}
	
	protected function prepareTable( $table ) {
		
		if (!$table->id) {
			// set the manager of the project
			$user = JFactory::getUser()->get('id');
			$table->manager = $user;
			$table->start = BeestoWorkflowHelper::getTime();
			
			// send alerts for new project
			BeestoWorkflowHelper::sendAlert( 'newproject' );
			
		} 
	}
	
	
	
	/*
	 * Method to overwrite the save function in order to start possible workflow when the project is saved 
	 * 
	 * @param
	 * @return
	 */
	public function save($data) {

		parent::save($data);
		$this->startWorkflows ();
		return true;
	}
	
	
	
	/*
	 * Method to mark the status of a project as completed
	 * 
	 * @param		$project, project ID
	 * @return	
	 */
	public function setCompleted ( $project  ) {
		
		// change project status
		$table = $this->getTable();
		$table->load($project);
		$table->status = 2;
		$table->completed = BeestoWorkflowHelper::getTime();
		
		if($table->store()) {
			
			// chage status for all tasks from that project
			$query = 'UPDATE #__beestowf_tasks SET completed = "completed" WHERE project_id = ' . $project;
			$this->_db->setQuery($query);
			$this->_db->query();
			
			// send alert for completed project
			BeestoWorkflowHelper::sendAlert('projectcomplete');
		}
	}
	

	/*
	 * Method to start a pending project (client requests)
	 * 
	 * @param	$project - ID of the pending project
	 * @return	save the data into database
	 */
	public function start ($project) {
		
		$data = JRequest::getVar('jform',array(),'','array');
		$data['id'] 		= $project;	
		$data['manager'] 	= JFactory::getUser()->get('id');
		$data['status'] 	= 1;
		parent::save($data);
		$this->startWorkflows ();
	}
	
	
	
	/*
	 * Method to check if the user can edit project details 
	 * 
	 * @param
	 * @return	boolean, true if success, false on failure
	 */
	public function getAuthorisation() {

		// get project id to see if it's new project or existing project
		$id 		= JRequest::getVar('id','','','int');
		$layout 	= JRequest::getVar('isowner','','','int');
		$form		= $this->getForm();
		$status		= $form->getValue('status');
		$user		= JFactory::getUser()->get('id');
		
		// check to see if it's the manager of the project, it he is we must show the edit button for project details
		$isManager = BeestoWorkflowHelper::isManager($id);

		if ($layout != 1) {
			
			// if the project is completed (archive)
			if ($status == 2) {
				if (!BeestoWorkflowHelper::canDo('viewArchiveProjects')) {
					$this->setError(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_NOT_ALLOWED_VIEW_ARCHIVE'));
					return false;
				}
			}
			
			// get all tasks and check if the person it's between the assignees/creator
			$query = ' SELECT COUNT(a.id) AS allowed FROM #__beestowf_tasks AS a ' .
					 ' INNER JOIN #__beestowf_projects AS b ON b.id = a.project_id ' .
					 ' WHERE (a.assigned_to REGEXP \'\"' . $user . '\"\' OR a.created_by = '. $user . ')' . 
					 ' AND a.project_id = ' . $id;
			$this->_db->setQuery($query);
			$allowed = $this->_db->loadResult();
			if (!$allowed && !$isManager && !BeestoWorkflowHelper::canDo('viewActiveProjects') && $status != 0) {
				$this->setError(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_NOT_ALLOWED'));
				return false;
			}
			
		// if we edit details	
		} elseif ($layout == 1) {
			
			// if the project is completed (archive)
			if ($status == 2) {
				if (!BeestoWorkflowHelper::canDo('editArchiveProjects')) {
					$this->setError(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_NOT_ALLOWED'));
					return false;
				}
			}
			
			// if it's not a new project and he isn't the manager
			if (!$isManager && $status != 0) {
				$this->setError(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_NOT_ALLOWED_EDIT_ARCHIVE'));
				return false;
			// if it's a new project but he doesn't have the right to manage project requests
			} elseif ($status == 0 && !BeestoWorkflowHelper::canDo('manageProjectRequests')) {
				$this->setError(JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_NOT_ALLOWED'));
				return false;
			}
		}

		
		return true;
	}
	
	
	/*
	 * Method to check if any project is using workflow and to start the workflow
	 * it is fired when a new project is saved or when a project request(pending project) is started
	 * 
	 * @param
	 * @return
	 */
	public function startWorkflows () {
		
		$manager = JFactory::getUser()->get('id');
		
		// get active projects using workflows without any task started
		$query = ' SELECT a.id, a.project_template FROM #__beestowf_projects AS a WHERE a.id NOT IN ( SELECT DISTINCT(b.project_id) FROM #__beestowf_tasks  AS b) ' . 
				 ' AND a.status = 1 AND a.project_template != 0 AND a.manager = ' . $manager;

		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();

		// if exist non started projects using workflows, get the first step from workflow and add him to task
		if($result) {
			
			$table = $this->getTable('Workflow','BeestoWorkflowTable');
			
			foreach ($result as $project) {
				
				$table->load($project->project_template);
				$workflow = $table->type;
				
				$query = ' SELECT a.* FROM #__beestowf_project_workflow_steps AS a WHERE a.project_workflow = ' . $project->project_template . ' ORDER BY a.order ASC';
				$this->_db->setQuery($query);
				$steps = $this->_db->loadObjectList();
				if ($steps) {
					// if the tasks are dependant
					if($workflow==0) {
					
						$time = strtotime(BeestoWorkflowHelper::getTime());
						$days = $steps[0]->due;
						$due = date('Y-m-d H:i:s',strtotime("+$days days",$time));
						
						$query = ' INSERT INTO #__beestowf_tasks(id,project_id,name,description,start,due,assigned_to,created_by,priority,completed,ordering,checked_out) VALUES ' .
								 ' ( NULL, '.$project->id.' , '.$this->_db->Quote($steps[0]->title).', '.$this->_db->Quote($steps[0]->description).
								 ','.$this->_db->Quote(BeestoWorkflowHelper::getTime()).','.$this->_db->Quote($due). ','.$this->_db->Quote($steps[0]->assigned_to).
								 ','.$manager.' , '.$steps[0]->priority.','.$this->_db->Quote('').','.$steps[0]->order.',0)';

						$this->_db->setQuery($query);
						$this->_db->query();
						
						// send alert for new task created
						$extra = array('id'=>$this->_db->insertid(), 'assigned_to'=>$steps[0]->assigned_to, 'name'=>$steps[0]->title);
						BeestoWorkflowHelper::sendAlert ('newtask',$extra);
						
						
					// if tasks are independant	
					} elseif ($workflow==1) {
						foreach ($steps as $step) {
							$time = strtotime(BeestoWorkflowHelper::getTime());
							$days = $step->due;
							$due = date('Y-m-d H:i:s',strtotime("+$days days",$time));
						
							$query = ' INSERT INTO #__beestowf_tasks(id,project_id,name,description,start,due,assigned_to,created_by,priority,completed,ordering,checked_out) VALUES ' .
								 ' ( NULL, '.$project->id.' , '.$this->_db->Quote($step->title).', '.$this->_db->Quote($step->description).
								 ' , '.$this->_db->Quote(BeestoWorkflowHelper::getTime()).','.$this->_db->Quote($due).
								 ','.$this->_db->Quote($step->assigned_to).','.$manager.' , '.$step->priority.','.$this->_db->Quote('').',0,0)';
			
							$this->_db->setQuery($query);
							$this->_db->query();
							
							$extra = array('id'=>$this->_db->insertid(), 'assigned_to'=>$step->assigned_to,'name'=>$step->title);
							BeestoWorkflowHelper::sendAlert ('newtask',$extra);
						}
					}
				}
			}
		}	
	}
	
	
	public function getTasks() {
		
		$form = $this->getForm();
		$query = ' SELECT a.id, a.name,a.start, a.due, a.priority, a.completed FROM #__beestowf_tasks AS a WHERE a.project_id = ' .  $form->getValue('id') . ' ORDER BY a.id DESC';
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
		
	}
	
	public function getAttachments() {
		
		$form = $this->getForm();
		$query = ' SELECT a.id, a.name,a.owner,a.filetype,a.added FROM #__beestowf_files AS a WHERE a.type = 0 AND a.parent = ' .  $form->getValue('id');
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
		
	}
	
	public function getComments () {
		
		$form = $this->getForm();
		$query = ' SELECT a.id,a.comment,a.added,a.owner FROM #__beestowf_comments AS a WHERE a.type = 0 AND a.parent = ' .  $form->getValue('id') . ' ORDER BY a.id DESC ';
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
	}

	public function deleteProjects($ids) {

		$table = $this->getTable();
		$user  = JFactory::getUser()->get('id');
				
		if ($ids) {
			foreach ($ids as $project) {
				
				$table->load($project);
				
				// he can delete only if he is the project manager 
				if ($table->manager == $user || $table->status == 0) {
				
					// get dependent tasks
					$query = ' SELECT a.id, a.name,a.start, a.due, a.priority from #__beestowf_tasks AS a WHERE a.project_id = ' .  $project;
					$this->_db->setQuery($query);
					$tasks = $this->_db->loadObjectList();
					
					if ($tasks) {
						foreach ($tasks as $task) {
							$this->deleteTask( $task );
						}
					}
				
					// delete attachment from server
					$path = JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . '00'.$project;
					if (is_dir($path)) {
						JFolder::delete($path);
					}

					// delete attachment from database
					$query = ' DELETE FROM #__beestowf_files WHERE type = 0 AND parent = ' . $project;
					$this->_db->setQuery($query);
					$this->_db->Query();
							
					// delete any comment related with project
					$query = ' DELETE FROM #__beestowf_comments WHERE type = 0 AND parent = ' . $project;
					$this->_db->setQuery($query);
					$this->_db->Query();
							
					// delete project
					$query = ' DELETE FROM #__beestowf_projects WHERE id = ' . $project;
					$this->_db->setQuery($query);
					$this->_db->Query();
				} else {
					$this->setError('');
				}
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

	
	
	public function addComment ($project,$comment) {
		
		if (!$project || !$comment) {
			return false;
		}
		
		$filter = JFilterInput::getInstance();
		
		$project = $filter->clean($project,'int');
		$comment = $filter->clean($comment,'string');
		$comment = str_replace(array("\r\n"),'<br/>',$comment);
		$added 	 = BeestoWorkflowHelper::getTime();
		$owner   = JFactory::getUser()->get('id');
		
		$query = ' INSERT INTO #__beestowf_comments (id,type,comment,parent,added,owner) VALUES (' .
				' NULL, 0, '.$this->_db->Quote($this->_db->escape($comment)).','.$project.','.$this->_db->Quote($added).','.$owner.')';
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
				if(!JFolder::create($dirpath,0755)) {
					$this->setError(JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_ERROR_CREATE_FOLDER'));
					return false;
				}
				
				// add index.html file inside the directory
				$content = '<html></html>';
				JFile::write( $dirpath . DIRECTORY_SEPARATOR . 'index.html', $content );
			}
			
			// check if file exist, if exist we rename him by adding a letter in front
			$filepath = JPath::clean($dirpath . DIRECTORY_SEPARATOR . $file['name']);
			if(JFile::exists($filepath)) {
				$i = 0;
				while(JFile::exists($filepath)) {
					$filepath = JPath::clean($dirpath . DIRECTORY_SEPARATOR . $i . $file['name']);
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
					 ' NULL,0,'.$project.','.$this->_db->Quote($file['name']).','.$this->_db->Quote($extension).','.$this->_db->Quote($location).
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
