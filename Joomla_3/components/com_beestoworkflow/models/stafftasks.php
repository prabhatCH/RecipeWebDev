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


class BeestoWorkflowModelStaffTasks extends JModelList
{
	protected $userID;
	
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'a.id',
				'b.name',
				'a.start',
				'a.due',
				'a.completed',
				'a.priority',
			);
		}
		$this->userID = JFactory::getUser()->get('id');
		parent::__construct($config);
	}
	
	
	
	protected function populateState($ordering = null, $direction = null) { 
		
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		
		// Load the filter state.
		$state = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
		$this->setState('filter.search', $state);
		
		// Load the filter status (active/completed)
		$status = $app->getUserStateFromRequest($this->context.'.filter.status', 'filter_status', '', 'string');
		$this->setState('filter.status', $status);
		
		// set to load type (manager/assignee)
		$type = $app->getUserStateFromRequest($this->context.'.filter.type', 'filter_type', '', 'string');
		$this->setState('filter.type', $type);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_beestoworkflow');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'desc');
	}
	
	protected function getStoreId($id = '') {
		
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.status');
		$id	.= ':'.$this->getState('filter.type');

		return parent::getStoreId($id);
	}
	
	

	protected function getListQuery() {
		
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		
		
		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select','a.id,a.name, b.name as project,a.start,a.due,a.completed,a.priority,a.created_by'
			)
		);
		$query->from(' #__beestowf_tasks AS a ');
		$query->join('LEFT','#__beestowf_projects AS b ON a.project_id = b.id ');
		
		// Filter by access
		$access = $this->getOtherProjects ();

		if ($access != 'all') {
			if ($access) {
				$query->where( 'a.project_id IN (0,'. implode(',', $access) .')' );
			} else {
				$query->where( 'a.project_id IN (0)' );
			}
		}
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $db->Quote('%'.$db->escape(strtolower($search), true).'%');
			$query->where( 'LOWER(a.name) LIKE '.$search. ' OR  LOWER(a.description) LIKE ' . $search);
		}
		
		// Filter by status
		$status = $this->getState('filter.status');
		if (!empty($status)){
			if ($status == 'active') {
				$query->where('a.completed != "completed"');
			} elseif ($status == 'completed') {
				$query->where('a.completed = "completed"');
			}	
		}
		
		// Filter by owner
		$type = $this->getState('filter.type');
		if (!empty($type)){
			if ($type == 'manager') {
				$query->where( 'a.created_by = ' . $this->userID );
			} elseif ($type == 'assignee') {
				$query->where( 'a.assigned_to REGEXP ' . $db->Quote('"'.$this->userID.'"') );
			}	
		} else {
			// get both tasks created by him and tasks assigned to him
			$query->where( '( a.created_by = ' . $this->userID . ' OR  a.assigned_to REGEXP ' . $db->Quote('"'.$this->userID.'"') .')');
		}

	
		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'a.id');
		$query->order($db->escape($orderCol).' '.$db->escape($this->getState('list.direction', 'DESC')));

		return $query;
	}

	
	public function setTaskStatus ( $status = 'active') {
		JFactory::getApplication('administrator')->setUserState($this->context.'.filter.status', $status);
	}
	


	/*
	 * Method to get all the projects he is allowed to see
	 *
	 * @param
	 * @return		mixed, 'all' if he is allowed to see all projects / array with projects he is allowed
	 */
	protected function getOtherProjects () {
	
		//get user params
		$viewActiveProjects 	= BeestoWorkflowHelper::getUserProperty ('viewActiveProjects');
		$params					= JComponentHelper::getParams('com_beestoworkflow');

		// first we check if the user has his own settings, if no take the settings from component
		if ($viewActiveProjects) {
			switch($viewActiveProjects) {
					
					case('inherited'):
						if($params->get('viewActiveProjects') == 1) {
							return 'all';
						} else {
							$allowed = $this->getAllowed ();
							return $allowed;
						}
					break;
					
					case('allowed'):
						return 'all';
					break;
			
					case('denied'):
						$allowed = $this->getAllowed ();
						return $allowed;
					break;
			}
		} else {
			if($params->get('viewActiveProjects') == 1) {
				return 'all';
			} else {
				$allowed = $this->getAllowed ();
				return $allowed;
			}
		}
	}
	
	
	protected function getAllowed () {
		$query = ' SELECT DISTINCT(a.id) FROM #__beestowf_projects AS a  WHERE a.manager = ' .  $this->userID . ' UNION SELECT DISTINCT(b.project_id) FROM #__beestowf_tasks AS b WHERE b.assigned_to REGEXP \'\"' . $this->userID . '\"\'';
		$this->_db->setQuery($query);
		$access = $this->_db->loadColumn();
			
		return $access;
	}
	
	

	public function getBaseName() {

		$this->basename = JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASKS');
		return $this->basename;
	}


	public function getFileType() {
		return $this->getState('type') == 'csv' ? 'csv' : 'pdf';
	}


	public function getMimeType() {
		// chrome won't output with application/pdf
		// return $this->getState('type') == 'xls' ? 'application/octet-stream' : 'application/pdf';
		return 'application/octet-stream';
	}
	

	public function getPDF () {
	
		// Get pdf library
		require_once (JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php');
		require_once (JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . 'eng.php');
	
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);
		$pdf->setFontSubsetting(true);

		$pdf->SetFont('dejavusans', '', 8, '', true);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		$html = '<table border="1" cellpadding="1">';
		$html .= '<tr>';
		$html .= '<td style="width:25%">' . JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_TITLE') . '</td>';
		$html .= '<td style="width:15%">' . JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_DUE') . '</td>';
		$html .= '<td style="width:10%">' . JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_COMPLETED') . '</td>';
		$html .= '<td style="width:10%">' . JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_PRIORITY') . '</td>';
		$html .= '<td style="width:15%">' . JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_TYPE') . '</td>';
		$html .= '<td style="width:20%">' . JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_PROJECT') . '</td>';
		$html .= '<td style="width:5%">' . JText::_('COM_BEESTOWORKFLOW_ID') . '</td>';
		$html .= '</tr>';
		
		$items = $this->getItems();
		$priority	= BeestoWorkflowHelper::getPriority ();
		
		foreach($items as $item) {
			$completed = $item->completed ? JText::_('JYES') : JText::_('JNO');
			$owner = $item->created_by == JFactory::getUser()->get('id') ? JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_CREATOR') : JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_ASSIGNEE');
			$project = $item->project ? $item->project : JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_GENERAL');
			
			$html .= '<tr>';
			$html .= '<td>' . $item->name . '</td>';
			$html .= '<td>' . $item->due . '</td>';
			$html .= '<td>' . $completed . '</td>';
			$html .= '<td>' . $priority[$item->priority] . '</td>';
			$html .= '<td>' . $owner . '</td>';
			$html .= '<td>' . $project . '</td>';
			$html .= '<td>' . $item->id . '</td>';
			$html .= '</tr>';
		}
				
		$html .= '</table>';

		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

		$pdf->Output($this->getBasename() . '.pdf', 'I');
	}



	public function getXLS() {

		$priority	= BeestoWorkflowHelper::getPriority ();
			
		if (!isset($this->content)) {

			$this->content = '';
			$this->content.=
			'"'.str_replace('"', '""', JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_TITLE')).'","'.
				str_replace('"', '""', JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_DUE')).'","'.
				str_replace('"', '""', JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_COMPLETED')).'","'.
				str_replace('"', '""', JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_PRIORITY')).'","'.
				str_replace('"', '""', JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_TYPE')).'","'.
				str_replace('"', '""', JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_PROJECT')).'","'.
				str_replace('"', '""', JText::_('COM_BEESTOWORKFLOW_ID')).'"' . "\n";

			foreach($this->getItems() as $item) {
				
				$completed 	= $item->completed ? JText::_('JYES') : JText::_('JNO');
				$owner 		= $item->created_by == JFactory::getUser()->get('id') ? JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_CREATOR') : JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_ASSIGNEE');
				$project 	= $item->project ? $item->project : JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_GENERAL');
				
				$this->content.=
				'"'.str_replace('"', '""', $item->name).'","'.
					str_replace('"', '""', $item->due).'","'.
					str_replace('"', '""', $completed).'","'.
					str_replace('"', '""', $priority[$item->priority]).'","'.
					str_replace('"', '""', $owner).'","'.
					str_replace('"', '""', $project).'","'.
					str_replace('"', '""', $item->id).'"' . "\n";
			}
		}
		return $this->content;
	}
	
	
	
}
?>
