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

//jimport( 'joomla.application.component.controller' );

class BeestoWorkflowController extends JControllerLegacy {
	
	public function display($cachable = false, $urlparams = false) {
		$view = JRequest::getVar('view', null );
		if ( !$view ) {
			$this->setRedirect( 'index.php?option=com_beestoworkflow&view=users' ); return;
		}
		parent::display();
	}
	
	function cpanel () {
		$urlredirect = 'index.php?option=com_beestoworkflow&view=users';
		$this->setRedirect( $urlredirect );
	}
}
?>
