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
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.client.helper');

class BeestoWorkflowControllerFiles extends JControllerAdmin {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_FILES';
	
	public function getModel($name = 'File', $prefix = 'BeestoWorkflowModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function download () {
		
		$id 	= JRequest::getVar('cid',array(),'','array');

		if (!$id) {	
			$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow&view=files',false),JText::_('COM_BEESTOWORKFLOW_FILES_NO_SELECTED'),'warning');
			return;
		}
		
		$db = JFactory::getDbo();
		$query = ' SELECT a.location FROM #__beestowf_files AS a WHERE a.id = ' . (int) $id[0];
		$db->setQuery($query);
		$location = $db->loadResult();
		
		if (!$location) {
			$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow&view=files',false));
			return false;
		}
		
		$path = JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $location;
		
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
			header("Content-Disposition: attachment; filename=$filename");
			header("Content-Transfer-Encoding: binary\n");
			
			ob_clean();
			flush();
					
			echo $content;
			
			ob_flush();
			flush();  
			exit(0);
		}

		$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow&view=files',false));
	}
	
	
	public function delete () {
	
		$ids 	= JRequest::getVar('cid',array(),'','array');
		$return = JRequest::getVar('return-url','','','base64');
		$db 	= JFactory::getDbo();
		JClientHelper::setCredentialsFromRequest('ftp');

		if (!$ids) {	
			$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow&view=files',false),JText::_('COM_BEESTOWORKFLOW_FILES_NO_SELECTED'),'warning');
			return;
		}
		
		foreach ($ids as $id) {
			
			$query = ' SELECT a.location FROM #__beestowf_files AS a WHERE a.id = ' . (int) $id;
			$db->setQuery($query);
			$location = $db->loadResult();
			
			if ($location) {
				$path = JPATH_COMPONENT . DIRECTORY_SEPARATOR . $location;
				if(is_file($path)) {
					JFile::delete($path);
				}
			}
			
			$query = ' DELETE FROM #__beestowf_files WHERE id = ' . (int) $id;
			$db->setQuery($query);
			$db->Query();
		}
		
		
		// if he comes from projects or tasks set to return
		if ($return) {	
			$this->setRedirect(base64_decode($return));
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow&view=files',false));
		}
		$this->setMessage(JText::_('COM_BEESTOWORKFLOW_FILES_SUCCESS_DELETED'),'message');	
	}
	
	
}
?>
