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

//jimport('joomla.application.component.controllerform');

class BeestoWorkflowControllerClientTask extends JControllerForm {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_CLIENTTASKS';
	
	public function getModel($name = 'ClientTask', $prefix = 'BeestoWorkflowModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	
	
	public function complete () {
		
		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$return = JRoute::_('index.php?option=com_beestoworkflow&view=clienttasks',false);

		$model = $this->getModel();
		$model->setCompleted();
		$this->setRedirect($return,JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_INFO_MARKED_COMPLETED'), 'message');
	
	}
	
	
		
	public function addcomment () {
		
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$return 	= JRequest::getVar('return-url','','','base64');
		$task_id 	= JRequest::getVar('task_id','','','int');
		$comment 	= JRequest::getVar('beestoworkflow-comment','','','string');
		$sendEmail 	= JRequest::getVar('messagealert',0,'','int');

		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=clienttasks',false);
		}
		
		if(!$comment || !$task_id) {
			$this->setRedirect( $return );
			return false;
		}
		
		$model = $this->getModel();
		if ($model->addComment($task_id,$comment)) {
			// send email alert if it's selected
			if ($sendEmail) {
				BeestoWorkflowHelper::sendAlert('newtaskmessage',array('id'=>$task_id,'comment'=>$comment));
			}
		}
		$this->setRedirect( $return );
	}
	
	
	public function deletecomment () {
		
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$return 	= JRequest::getVar('return-url','','','base64');
		$comments 	= JRequest::getVar('mid',array(),'','array');

		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=clienttasks',false);
		}
		
		if(!$comments) {
			$this->setRedirect( $return );
			return false;
		}

		$model = $this->getModel();
		$model->deleteComment($comments);
		$this->setRedirect( $return );
	}
	
	
	public function addfile () {
	
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$return 	= JRequest::getVar('return-url','','','base64');
		$file		= JRequest::getVar('fileadd', array(), 'files', 'array');
		$task_id 	= JRequest::getVar('task_id','','','int');
		$sendEmail 	= JRequest::getVar('filealert',0,'','int');

		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=clienttasks',false);
		}
		
		if(!$file['name']) {
			$this->setRedirect( $return );
			return false;
		}
		
		$model = $this->getModel();
		
		if (!$model->addfile()) {
			$error = $model->getError();
			$this->setRedirect( $return,$error,'warning' );
		} else {
			if ($sendEmail) {
				BeestoWorkflowHelper::sendAlert('newtaskfile',array('name'=>$file['name'],'task_id'=>$task_id));
			}
		}
		
		$this->setRedirect( $return );
	}
	
}

?>
