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

class JFormFieldNoteCategory extends JFormFieldList {

	public function getInput() {

		$db 	= JFactory::getDbo();
		$user	= JFactory::getUser()->get('id');
	
		$query = ' SELECT DISTINCT(a.category) FROM #__beestowf_notes AS a WHERE a.owner = ' . $user ;
		$db->setQuery($query);
		$categories = $db->loadColumn();
		
		if ($categories) {
			foreach ($categories as $category) {
				$result[$category] = $category;
			}
		} else {
			$result = array();
		}

		$html = array();
		$html[] = '<select name="jform[category]" class="inputbox">';
		$html[] = '<option value="">- '. JText::_('COM_BEESTOWORKFLOW_STAFFNOTES_SELECT_CATEGORY'). '-</option>';
		$html[] = JHtml::_('select.options', $result );
		$html[] = '</select>';
		$html[] = JText::_('COM_BEESTOWORKFLOW_STAFFNOTES_OR');
		$html[] = '<input type="text" name="jform[new_category]" value="" />';
		

		return implode($html);
	}
	
	
}
?>
