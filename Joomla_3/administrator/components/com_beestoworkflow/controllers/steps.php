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

jimport('joomla.application.component.controlleradmin');

class BeestoWorkflowControllerSteps extends JControllerAdmin {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_STEPS';
	
	public function getModel($name = 'Step', $prefix = 'BeestoWorkflowModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function go () {
		
		$id = JRequest::getVar('id','','','int');
		
		if (empty($id)) {
			$url = 'index.php?option=com_beestoworkflow&view=workflows';
			$this->setRedirect( $url, JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_STEPS_NO_SELECTED'), 'warning' );
		} else {
			JFactory::getApplication()->setUserState('workflow_id',$id);
			$url = 'index.php?option=com_beestoworkflow&view=steps';
			$this->setRedirect( $url );
		}
		
	}
	
	
	public function up() {

		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel();
		$ids =  JRequest::getVar('cid',	null, 'post', 'array');
		$fid =  JRequest::getVar('fid',	0, '', 'int');
		$model->orderup();
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));

	}
	
	public function down() {
		
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel();
		$ids =  JRequest::getVar('cid',	null, 'post', 'array');
		$fid =  JRequest::getVar('fid',	0, '', 'int');
		$model->orderdown();
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
	}


	
}

?>
