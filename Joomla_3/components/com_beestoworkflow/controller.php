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
		
		$view 		= JRequest::getCmd('view','beestoworkflow');
		$authorised = BeestoWorkflowHelper::authorise ();

		if  ($view =='beestoworkflow' && !isset($authorised->type)) {
			$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow&view=login',false),JText::_('COM_BEESTOWORKFLOW_LOGIN_YOU_ARE_NOT_AUTHORISED'),'warning');
			return;
		} 
		
		if ( $view != 'login' && $view != 'beestoworkflow' )  {
			
			// if he is logged in
			if(isset($authorised->type)) { 
				// he is a client
				if($authorised->type == 1) {
					if(stripos($view,'client') === false) { 
						$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow',false),JText::_('COM_BEESTOWORKFLOW_LOGIN_YOU_ARE_NOT_AUTHORISED'),'warning');
						return;
					}
				// he is staff
				} elseif ($authorised->type == 0) {
					if(stripos($view,'staff') === false) {
						$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow',false),JText::_('COM_BEESTOWORKFLOW_LOGIN_YOU_ARE_NOT_AUTHORISED'),'warning');
						return;
					}
				}
				
			// if he isn't logged in redirect directly
			} else {
				$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow&view=login',false),JText::_('COM_BEESTOWORKFLOW_LOGIN_YOU_ARE_NOT_AUTHORISED'),'warning');
				return;
			}
			
		}
		
		parent::display();
	}
	
}
?>
