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
jimport('joomla.client.helper');

class BeestoWorkflowModelClientProject extends JModelAdmin
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
		
		// if it's not new, check if he wants to edit him
		if ($id) {
			if ( $layout == 1 ) {
				$form = $this->loadForm('com_beestoworkflow.clientproject', 'clientproject0', array('control' => 'jform', 'load_data' => $loadData));
			} else {
				$form = $this->loadForm('com_beestoworkflow.clientproject', 'clientproject1', array('control' => 'jform', 'load_data' => $loadData));
			}
			
		// if it's a new project and also not a client request
		} else {
			$form = $this->loadForm('com_beestoworkflow.clientproject', 'clientproject0', array('control' => 'jform', 'load_data' => $loadData));
		}
		

		if (empty($form)) {
			return false;
		}
		
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_beestoworkflow.edit.clientproject.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}
		
		return $data;
	}
	
	protected function prepareTable( $table ) {
		
		if (!$table->id) {
			// set the manager of the project
			$user = JFactory::getUser()->get('id');
			$table->client = $user;
			$table->client_view = 1;
			$table->start = BeestoWorkflowHelper::getTime();
			$table->status = 0;
			
			// send alerts for new project
			//BeestoWorkflowHelper::sendAlert( 'newproject' );
			
		} 
	}
	
	
	public function getParams () {
		$params	= JComponentHelper::getParams('com_beestoworkflow');
		return $params;
	}
	
	
	public function save($data) {
		parent::save($data);
		$this->addfile ();
		$this->sendCustomAlert();
		return true;
	}
	
	
	public function addfile () {
	
		JClientHelper::setCredentialsFromRequest('ftp');
		$params		= JComponentHelper::getParams('com_beestoworkflow');
		$file		= JRequest::getVar('fileadd', array(), 'files', 'array');
		$user 		= JFactory::getUser()->get('id');
		$query 	   	= ' SELECT MAX(a.id) FROM #__beestowf_projects AS a WHERE a.client = ' . $user;
		$this->_db->setQuery($query);
		$project 	= $this->_db->loadResult();
		
		$folder 	= '00'.$project;
		
	
		if (isset($file['name'])) {
			
			// check if extension is allowed or ignored
			if ($params->get('allow_attachments') != 1) {
				return;
			}
			
			$extension 	= JFile::getExt($file['name']);
			
			$allowed = $params->get('upload_extensions');
			if (!empty($allowed)) {
				$ext_allowed = explode(',',$allowed);
				if (!in_array($extension,$ext_allowed)) {
					$this->setError (JText::_('COM_BEESTOWORKFLOW_CLIENT_PROJECT_FILETYPE_NOT_ALLOWED'));
					return false;
				}
			}

			$denied = $params->get('ignored_extensions');
			if (!empty($allowed)) {
				$ignored = explode(',',$denied);
				if (in_array($extension,$ignored)) {
					return;
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
				
				// add the index.html file
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

	// send alert if it's available to the email set in Options
	protected function sendCustomAlert () {
		
		$params		= JComponentHelper::getParams('com_beestoworkflow');
		$config		= JFactory::getApplication('administrator');
		
		$email 		= $params->get('project_request_email_to');
		$subject	= $params->get('project_request_email_subj');
		$body		= $params->get('project_request_email_body');
		$from 		= $config->getCfg('mailfrom');
		$fromname	= $config->getCfg('fromname');
		
		if ($email && $subject && $body) {
			JUtility::sendMail( $from, $fromname, $email, $subject, $body, true );
		}
	
	}
	
	
	
	public function getTasks() {
		
		$form = $this->getForm();
		$query = ' SELECT a.id, a.name,a.start, a.due, a.priority, a.completed FROM #__beestowf_tasks AS a WHERE a.project_id = ' .  (int) $form->getValue('id') . ' ORDER BY a.id DESC';
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
		
	}
	
	public function getAttachments() {
		
		$form = $this->getForm();
		$query = ' SELECT a.id, a.name,a.owner,a.filetype,a.added FROM #__beestowf_files AS a WHERE a.type = 0 AND a.parent = ' . (int) $form->getValue('id');
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
		
	}
	
	public function getComments () {
		
		$form = $this->getForm();
		$query = ' SELECT a.id,a.comment,a.added,a.owner FROM #__beestowf_comments AS a WHERE a.type = 0 AND a.parent = ' . (int)  $form->getValue('id') . ' ORDER BY a.id DESC ';
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
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
	
	
	

}
?>
