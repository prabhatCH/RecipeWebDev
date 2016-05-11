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

//jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.client.helper');

class BeestoWorkflowControllerStaffFile extends JControllerLegacy {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_FILES';
	

	public function download () {
		
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
				
		JClientHelper::setCredentialsFromRequest('ftp');
		$id 	= JRequest::getVar('fid',array(),'','array');
		$return = JRequest::getVar('return-url','','','base64');

		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow',false);
		}
		
		if (!$id) {	
			$this->setRedirect($return,JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_NO_SELECTED_FILE'), 'warning');
			return;
		}
		
		$db = JFactory::getDbo();
		$query = ' SELECT a.location FROM #__beestowf_files AS a WHERE a.id = ' . (int) $id[0];
		$db->setQuery($query);
		$location = $db->loadResult();
		
		if (!$location) {
			$this->setRedirect($return, JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_LOCATION_DATABASE'), 'warning');
			return false;
		}
		
		$path = JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $location;
		
		if(is_file($path)) {
		
			$content 	= JFile::read($path);
			$filename 	= JFile::getName ($path);
			$filesize 	= @filesize($path);
			
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Length: " .(string)($filesize) );
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header("Content-Transfer-Encoding: binary\n");
			
			ob_clean();
			flush();
					
			echo $content;
			
			ob_flush();
			flush();  
			exit(0);
					
		} else {
			$this->setRedirect($return,JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_LOCATION_SERVER'),'warning');
		}
	}
	
	
	
	public function delete () {
	
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
			
		JClientHelper::setCredentialsFromRequest('ftp');

		$db 	= JFactory::getDbo();
		$id 	= JRequest::getVar('fid',array(),'','array');
		$return = JRequest::getVar('return-url','','','base64');

		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow',false);
		}
		
		if (!$id) {	
			$this->setRedirect($return,JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_NO_SELECTED_FILE'), 'warning');
			return;
		}
			
		$query = ' SELECT a.location FROM #__beestowf_files AS a WHERE a.id = ' . (int) $id[0];
		$db->setQuery($query);
		$location = $db->loadResult();
			
		if ($location) {
			$path = JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $location;
			
			if(is_file($path)) {
				if (JFile::delete($path)) {
					$query = ' DELETE FROM #__beestowf_files WHERE id = ' . (int) $id[0];
					$db->setQuery($query);
					$db->Query();
					$this->setRedirect($return);
					return true;
				} else {
					$this->setRedirect($return,JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_DELETE_FILE_SERVER'),'warning');
					return false;
				}
			} 
			
			$query = ' DELETE FROM #__beestowf_files WHERE id = ' . (int) $id[0];
			$db->setQuery($query);
			$db->Query();
			$this->setRedirect($return, JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_LOCATION_SERVER'), 'warning');
			return false;

		} else {
			$this->setRedirect($return, JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_ERROR_LOCATION_DATABASE'), 'warning');
			return false;
		}
	}
	
	
}
?>
