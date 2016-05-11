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

Jimport( 'joomla.application.component.view');

class BeestoWorkflowViewLogin extends JViewLegacy {
	
	protected $authorised;
	
	public function display($tpl = null) {
		$this->authorised = BeestoWorkflowHelper::authorise ();
		$this->addToolbar();
		parent::display($tpl);
	}
	

	public function addToolbar() {	
		JHtml::stylesheet( 'components/com_beestoworkflow/assets/css/default.css' );	
		if (isset($this->authorised->type)) {
			$this->menu = BeestoWorkflowMenu::getMenu($this->authorised->type);
		} 
	}
	
}
?>
