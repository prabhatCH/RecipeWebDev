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

class BeestoWorkflowViewBeestoworkflow extends JViewLegacy {
	
	protected $authorised;
	protected $menu;
	protected $upcoming;
	protected $days;
	protected $requests;
	protected $interval;
	
	public function display($tpl = null) {
		$this->authorised = BeestoWorkflowHelper::authorise ();
		$this->upcoming = $this->get('Upcoming');
		$this->days	= $this->get('Days');
		$this->requests = $this->get('ProjectRequests');
		$this->interval	= $this->get('Interval');
		$this->addToolbar();
		parent::display($tpl);
	}
	

	public function addToolbar() {	
		
		JHtml::stylesheet( 'components/com_beestoworkflow/assets/css/default.css' );	
		//JHtml::_('bootstrap.loadCss', true);
		
		if (isset($this->authorised->type)) {
			$this->menu = BeestoWorkflowMenu::getMenu($this->authorised->type);
		} 
	}
	
}
?>
