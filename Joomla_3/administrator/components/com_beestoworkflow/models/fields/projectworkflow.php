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

JFormHelper::loadFieldClass('list');

class JFormFieldProjectworkflow extends JFormFieldList {

	public function getInput() {
		
		$table 	= JTable::getInstance('Workflow', 'BeestoWorkflowTable', array());
		$id	 	= JFactory::getApplication('administrator')->getUserStateFromRequest('workflow_id','',0);
		
		$table->load($id);
		$workflow = new stdClass();
		$workflow->id = $id;
		$workflow->title = $table->title;
		
		$html = '<input type="text" size="30" class="readonly" value="'.$workflow->title.'" id="jform_x" name="x" readonly="readonly" />';
		$html .= '<input type="hidden" name="jform[project_workflow]" value="'.$workflow->id.'" />';
		return $html ;

	}
	
}
?>
