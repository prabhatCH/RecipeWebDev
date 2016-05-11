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

class BeestoWorkflowControllerStaffProject extends JControllerForm {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_STAFFPROJECTS';
	
	public function getModel($name = 'StaffProject', $prefix = 'BeestoWorkflowModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	
	public function complete () {
		
		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$project 	= JRequest::getVar('project','','','int');
		$isManager 	= BeestoWorkflowHelper::isManager($project);
		
		$return = JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects',false);
		
		if (!$isManager) {
			$this->setRedirect($return,JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_NOT_ALLOWED_CHANGE_STATUS'), 'warning');
		}
		
		if (!$project) {
			$this->setRedirect($return,JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_NO_PROJECT'), 'warning');
		} else {
			$model = $this->getModel();
			$model->setCompleted($project);
			$this->setRedirect($return,JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_INFO_MARKED_COMPLETED'), 'message');
		}
	
	}
	
	
	public function start () {
		
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$return 	= JRequest::getVar('return-url','','','base64');
		$project 	= JRequest::getVar('project','','','int');

		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects',false);
		}
		
		if (BeestoWorkflowHelper::canDo('manageProjectRequests')) {
			$model = $this->getModel();
			$model->start($project);
			$this->setRedirect($return,JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_INFO_NOW_ACTIVE'), 'message');
		}
	}
	
	
	public function addcomment () {
		
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$return 	= JRequest::getVar('return-url','','','base64');
		$project 	= JRequest::getVar('project','','','int');
		$comment 	= JRequest::getVar('beestoworkflow-comment','','','string');
		$sendEmail 	= JRequest::getVar('messagealert',0,'','int');

		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects',false);
		}
		
		if(!$comment || !$project) {
			$this->setRedirect( $return );
			return false;
		}
		
		$model = $this->getModel();
		if ($model->addComment($project,$comment)) {
			// send email alert if it's selected
			if ($sendEmail) {
				BeestoWorkflowHelper::sendAlert('newprojmessage',array('id'=>$project,'comment'=>$comment));
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
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects',false);
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
		$project 	= JRequest::getVar('project','','','int');
		$sendEmail 	= JRequest::getVar('filealert',0,'','int');

		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects',false);
		}
		
		if(!$file['name']) {
			$this->setRedirect( $return );
			return false;
		}
		
		$model = $this->getModel();
		
		if (!$model->addfile()) {
			$error = $model->getErrors();
			$this->setRedirect( $return,$error,'warning' );
		} else {
			if ($sendEmail) {
				BeestoWorkflowHelper::sendAlert('newprojfile',array('name'=>$file['name'],'project'=>$project));
			}
		}
		
		$this->setRedirect( $return );
	}
	
	

	public function cancel ( $key = NULL ) {
		
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$return = JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects',false);
		
		$this->setRedirect( $return );
	}
	
	
}

?>
