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


class BeestoWorkflowModelStaffProjects extends JModelList
{
	protected $userID;
	
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'a.id',
				'a.name',
				'a.start',
				'a.due',
				'b.name',
				'a.status',
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
		
		// set to load active projects
		$status = $app->getUserStateFromRequest($this->context.'.filter.status', 'filter_status', '', 'int');
		$this->setState('filter.status', $status);
		
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

		return parent::getStoreId($id);
	}
	
	

	protected function getListQuery() {
		
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		
		
		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select','a.id,a.name,a.start,a.due,b.name AS manager, a.status'
			)
		);
		$query->from(' #__beestowf_projects AS a ');
		$query->join('LEFT','#__users AS b ON a.manager = b.id ');
		
		// Filter by access
		$access = BeestoWorkflowHelper::getOtherProjects ();
		if ($access != 'all') {
			if ($access) {
				$query->where( 'a.id IN ('. implode(',', $access) .')' );
			} else {
				$query->where( 'a.id IN (0)' );
			}
		}
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $db->Quote('%'.$db->escape(strtolower($search), true).'%');
			$query->where( 'LOWER(a.name) LIKE '.$search );
		}
		
		// Filter by status
		$status = $this->getState('filter.status');
		if ((!empty($status) || $status === 0) && $status != 3){
			$query->where('a.status = '. $status);
		}
	
		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'a.id');
		$query->order($db->escape($orderCol).' '.$db->escape($this->getState('list.direction', 'DESC')));

		return $query;
	}

	
	public function setProjectStatus ( $status = 1) {
		JFactory::getApplication('administrator')->setUserState($this->context.'.filter.status', $status);
	}
	

	public function getBaseName() {

		$this->basename = JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_REPORTS');
		
		return $this->basename;
	}


	public function getFileType() {
		return $this->getState('type') == 'csv' ? 'csv' : 'pdf';
	}


	public function getMimeType() {
		return $this->getState('type') == 'csv' ? 'text/csv' : 'application/pdf';
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
		$html .= '<td style="width:30%">' . JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_NAME') . '</td>';
		$html .= '<td style="width:15%">' . JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DATE_START') . '</td>';
		$html .= '<td style="width:15%">' . JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DATE_DUE') . '</td>';
		$html .= '<td style="width:15%">' . JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_STATUS') . '</td>';
		$html .= '<td style="width:20%">' . JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_MANAGER') . '</td>';
		$html .= '<td style="width:5%">' . JText::_('COM_BEESTOWORKFLOW_ID') . '</td>';
		$html .= '</tr>';
		
		$items = $this->getItems();
		$state	= BeestoWorkflowHelper::getProjectStatuses();
		
		foreach($items as $item) {
			$html .= '<tr>';
			$html .= '<td>' . $item->name . '</td>';
			$html .= '<td>' . $item->start . '</td>';
			$html .= '<td>' . $item->due . '</td>';
			$html .= '<td>' . $state[$item->status] . '</td>';
			$html .= '<td>' . $item->manager . '</td>';
			$html .= '<td>' . $item->id . '</td>';
			$html .= '</tr>';
		}
				
		$html .= '</table>';

		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

		$pdf->Output($this->getBasename() . '.pdf', 'I');
	}



	public function getXLS() {
			
		$state	= BeestoWorkflowHelper::getProjectStatuses();
					
		if (!isset($this->content)) {

			$this->content = '';
			$this->content.=
			'"'.str_replace('"', '""', JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_NAME')).'","'.
				str_replace('"', '""', JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DATE_START')).'","'.
				str_replace('"', '""', JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DATE_DUE')).'","'.
				str_replace('"', '""', JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_STATUS')).'","'.
				str_replace('"', '""', JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_MANAGER')).'","'.
				str_replace('"', '""', JText::_('COM_BEESTOWORKFLOW_ID')).'"' . "\n";

			foreach($this->getItems() as $item) {
				
				$this->content.=
				'"'.str_replace('"', '""', $item->name).'","'.
					str_replace('"', '""', $item->start).'","'.
					str_replace('"', '""', $item->due).'","'.
					str_replace('"', '""', $item->manager).'","'.
					str_replace('"', '""', $state[$item->status]).'","'.
					str_replace('"', '""', $item->id).'"' . "\n";
			}
		}
		return $this->content;
	}
	

	
}
?>
