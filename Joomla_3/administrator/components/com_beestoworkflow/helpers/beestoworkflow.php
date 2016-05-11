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

class BeestoWorkflowHelper
{
	public static function getActions() {
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_beestoworkflow';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
	
	
	public static function getExtensionDetails ( $extension_name ) {
		$db = JFactory::getDBO();

		$extension = array();
		$extension['version'] 	= null;
		$extension['copyright'] = null;
		$extension['authorUrl'] = null;
		$extension['creationDate'] = null;
		
		$query = 	' SELECT a.manifest_cache ' .
					' FROM #__extensions AS a ' .
					' WHERE LOWER( a.name ) LIKE ' . $db->Quote('%'.$db->escape($extension_name, true).'%') . 
					' AND a.type = "component"' .
					' LIMIT 1 ';

		$db->setQuery($query);
		$result = $db->loadResult();
		
		
		if (!empty($result)) {
			$data = @json_decode( $result, true );
			
			if ($data) {
				foreach($data as $key => $value) {
					if ($key == 'type') {
						continue;
					}
					$extension[$key] = $value;
				}
			} 
		} 
		return $extension;	
	}
	
	

	public static function getUsers() {
		
		$db		= JFactory::getDbo();
		$users 	= array();
		
		$query  = 	' SELECT a.id AS value, a.name AS text FROM #__users AS a ' .
					' WHERE a.id NOT IN ( SELECT b.id FROM #__beestowf_users AS b ) ' .
					' ORDER BY a.id DESC ';
		
		$db->setQuery($query);
		$rez = $db->loadObjectList ();
		
		if (!empty( $rez )) {
			return $rez;
		}
		
		return $users;
		
	}
	
	
	/*
	 * Method to return a project property
	 * 
	 * @param		$id  project ID, $var the property we need
	 * @return		property
	 */
	public static function getProject ( $id, $var ) {
		
		$table 	= JTable::getInstance('Project', 'BeestoWorkflowTable', array());
		$table->load($id);
		
		return $table->$var;
	}
	
	
	public static function getPriority () {
		
		$priority	= array(0=>JText::_('COM_BEESTOWORKFLOW_USUAL_VERY_LOW'),1=>JText::_('COM_BEESTOWORKFLOW_USUAL_LOW'),2=>JText::_('COM_BEESTOWORKFLOW_USUAL_MEDIUM'),3=>JText::_('COM_BEESTOWORKFLOW_USUAL_HIGH'),4=>JText::_('COM_BEESTOWORKFLOW_USUAL_VERY_HIGH'));

		return $priority;
	}
	
}
