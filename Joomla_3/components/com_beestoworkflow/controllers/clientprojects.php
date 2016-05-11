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

//jimport('joomla.application.component.controlleradmin');

class BeestoWorkflowControllerClientProjects extends JControllerLegacy {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_CLIENTPROJECTS';
	
	public function getModel($name = 'ClientProject', $prefix = 'BeestoWorkflowModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function delete () {
		
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$ids 	= JRequest::getVar('cid', array(), '', 'array');
		$return = JRequest::getVar('return-url','','','base64');
		
		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects',false);
		}
		
		if ($ids) {	
			$model = $this->getModel();
			$model->deleteProjects($ids);
			$error = $model->getErrors();
			if ($error) {
				$this->setRedirect($return, JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_DELETE_NOT_OWNER'),'message');
			} else {
				$this->setRedirect($return, JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_PROJECTS_DELETED'),'message');
			}	
		}	
	}
	
	
}

?>
