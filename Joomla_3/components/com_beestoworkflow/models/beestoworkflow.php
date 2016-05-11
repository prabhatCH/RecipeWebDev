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

jimport( 'joomla.application.component.modellist' );


class BeestoWorkflowModelBeestoWorkflow extends JModelList
{
	protected $days;
	
	
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	
	public function getUpcoming () {
		
		// instead of time(), use time set by user through joomla offset
		$time 		= strtotime(BeestoWorkflowHelper::getTime());
		$interval 	= ($intv = JComponentHelper::getParams('com_beestoworkflow')->get('upcomingItems')) ? $intv : 7;
		
		$user 		= (int) JFactory::getUser()->get('id');
		$current 	= BeestoWorkflowHelper::getTime();
		$week 		= strtotime("+$interval days",$time);
		$week		= date('Y-m-d H:i:s',$week);
		$query = 	' SELECT a.id, a.due, a.start, a.name, a.priority, a.completed, b.name AS project FROM #__beestowf_tasks AS a LEFT JOIN #__beestowf_projects AS b ON a.project_id = b.id'.
					' WHERE a.assigned_to REGEXP "'.$user.'" AND (( a.start >= '.$this->_db->Quote($current) . ' AND a.start <= '.$this->_db->Quote($week).')'.
					' OR ( a.due >= '.$this->_db->Quote($current) . ' AND a.due <= '.$this->_db->Quote($week).'))';
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
	
		if ($result) {
			
			$returned 	= array();
			$tocheck 	= array();
			
			// get days to check
			$tocheck[] = date('Y M d',$time);
			$tocheck[] = date('Y M d',strtotime("+1 day",$time));
			for ($i=2;$i<=$interval;$i++) {
				$tocheck[] = date('Y M d',strtotime("+$i days",$time));
			}
			
			// set as property
			$this->days = $tocheck;
			
			foreach ($this->days as $date) {
				
				foreach ($result as $upcoming) {
					
					$start 	= date('Y M d',strtotime($upcoming->start));
					$due 	= date('Y M d',strtotime($upcoming->due));
					if ($start == $date) {
						$returned[$date]['start'][] =  $upcoming;
					}
					if ($due == $date) {
						$returned[$date]['due'][] 	=  $upcoming;		
					}
				}
			}
			//echo '<pre>';print_r ($returned); echo '</pre>';
			return $returned;
		} 
		
		return false;
	}

	
	public function getDays () {
		
		if (isset($this->days)) {
			return $this->days;
		} 
		
		return false;
	}
	
	
	public function getProjectRequests() {
		$query = ' SELECT COUNT(a.id) FROM #__beestowf_projects AS a WHERE a.manager = 0 AND a.status = 0 AND a.client != 0';
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult($query);
		return $result;
	}
	
	
	public function getInterval () {
		
		$time 		= strtotime(BeestoWorkflowHelper::getTime());
		$params		= JComponentHelper::getParams('com_beestoworkflow');
		$interval	= new stdClass();

		$interval->start = date('M d, Y',$time);
		$days = $params->get('upcomingItems');
		
		if (!empty($days)) {
			$interval->end = date('M d, Y',strtotime("+$days days",$time));
		} else {
			$interval->end = date('M d, Y',strtotime("+7 days",$time));
		}
		
		return $interval;
		
	}
	
	
}
?>
