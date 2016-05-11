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

class BeestoWorkflowControllerStaffProjects extends JControllerAdmin {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_STAFFPROJECTS';
	
	public function getModel($name = 'StaffProject', $prefix = 'BeestoWorkflowModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}


	public function exportXLS () {
	
	// Get the document object.
		$document	= JFactory::getDocument();
		
		$vName		= 'staffprojects';
		$vFormat	= 'csv';

		// Get and render the view.
		if ($view = $this->getView($vName, $vFormat)) {
			// Get the model for the view.
			$model = $this->getModel($vName);
			$model->setState('type','csv');
			
			// Load the filter state.
			$app = JFactory::getApplication();

			// Push the model into the view (as default).
			$view->setModel($model, true);

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();
		}
	
	}
	
	
	public function exportPDF () {
	
	// Get the document object.
		$document	= JFactory::getDocument();
		
		$vName		= 'staffprojects';
		$vFormat	= 'pdf';

		// Get and render the view.
		if ($view = $this->getView($vName, $vFormat)) {
			// Get the model for the view.
			$model = $this->getModel($vName);
			$model->setState('type','pdf');
			
			// Load the filter state.
			$app = JFactory::getApplication();

			// Push the model into the view (as default).
			$view->setModel($model, true);

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();
		}
	}
	
	
	public function getpending () {
		$model = $this->getModel('StaffProjects','BeestoWorkflowModel');
		$model->setProjectStatus(0);
		$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects'));
	}
	
	
	public function getactive () {
		$model = $this->getModel('StaffProjects','BeestoWorkflowModel');
		$model->setProjectStatus(1);
		$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects'));	
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
