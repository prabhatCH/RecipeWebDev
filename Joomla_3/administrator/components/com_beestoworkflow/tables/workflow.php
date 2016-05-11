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


class BeestoWorkflowTableWorkflow extends JTable {
	
	public function __construct(&$db) {
		parent::__construct('#__beestowf_project_workflow', 'id', $db);
	}


}
?>
