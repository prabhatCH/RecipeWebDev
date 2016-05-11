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

class BeestoWorkflowControllerStaffBookmarks extends JControllerAdmin {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_BOOKMARKS';
	
	public function getModel($name = 'StaffBookmarks', $prefix = 'BeestoWorkflowModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	
	public function add () {
		
		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$return = JRoute::_('index.php?option=com_beestoworkflow&view=staffbookmarks',false);

		$url = JRequest::getVar('url','','','string');
		
		if (empty($url) || (stripos($url,'http://') === false && stripos($url,'https://') === false)) {
			$this->setRedirect( $return, JText::_('COM_BEESTOWORKFLOW_BOOKMARKS_URL_INCORECT'), 'warning' );
			return false;
		} 
		
		$model = $this->getModel();
		$model->add($url);
		$this->setRedirect( $return );
	
	}
	
	
	public function delete () {
		
		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$return = JRoute::_('index.php?option=com_beestoworkflow&view=staffbookmarks',false);

		$urls = JRequest::getVar('cid',array(),'','array');
		
		if (!$urls) {
			$this->setRedirect( $return, JText::_('COM_BEESTOWORKFLOW_BOOKMARKS_URL_NOT_SELECTED'), 'warning' );
			return false;
		} 
		
		$model = $this->getModel();
		$model->delete($urls);
		$this->setRedirect( $return );
	
	}
	
	
}

?>
