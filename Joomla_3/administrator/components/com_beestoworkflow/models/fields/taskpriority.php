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

class JFormFieldTaskPriority extends JFormFieldList {

	public function getInput() {

		$priority 	= BeestoWorkflowHelper::getPriority();
		$id	 		= $this->form->getValue('priority');
		$class 		= array(0=>'verylow',1=>'low',2=>'medium',3=>'high',4=>'veryhigh');

		
		$html = '<span class="'.$class[$id].'">' . $priority[$id] . '</span>';

		return $html;
	}
	
}
?>
