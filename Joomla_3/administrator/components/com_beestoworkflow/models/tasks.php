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


class BeestoWorkflowModelTasks extends JModelList
{
	
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'a.id',
				'a.name',
				'a.project_id',
				'a.start',
				'a.due',
				'a.priority',
				'a.completed',
			);
		}
		parent::__construct($config);
	}
	
	
	
	protected function populateState($ordering = null, $direction = null) { 
		
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		
		// Load the filter state.
		$state = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
		$this->setState('filter.search', $state);
		
		$state = $app->getUserStateFromRequest($this->context.'.filter.project', 'filter_project', '', 'string');
		$this->setState('filter.project', $state);
		
		$state = $app->getUserStateFromRequest($this->context.'.filter.priority', 'filter_priority', '', 'string');
		if(!isset($state) || $state == '') {
			$state = 'none';
		}
		$this->setState('filter.priority', $state);		
		
		$state = $app->getUserStateFromRequest($this->context.'.filter.status', 'filter_status', '', 'string');
		$this->setState('filter.status', $state);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_beestoworkflow');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'desc');
	}
	
	protected function getStoreId($id = '') {
		
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.project');
		$id	.= ':'.$this->getState('filter.priority');
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
				'list.select','a.id,a.name,a.project_id,a.start,a.due,a.priority,a.completed'
			)
		);
		$query->from(' #__beestowf_tasks AS a ');
		
		// Filter by priority
		$priority = $this->getState('filter.priority');
		if ($priority != 'none') {
			$query->where('a.priority = '.(int) $priority);
		} 
		
		// Filter by project
		$project = $this->getState('filter.project');

		if ( $project ) {
			if ($project == 'none') { $project = 0;}
			$query->where('a.project_id = '.(int) $project);
		} 
		
		// Filter by status
		$status = $this->getState('filter.status');
		if ($status) {
			$query->where('a.completed = "completed"');
		} 	
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%'.$db->escape(strtolower($search), true).'%');
				$query->where( '( LOWER(a.name) LIKE '.$search.' OR LOWER (a.description) LIKE '.$search.')' );
			}
		}
		
		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'a.id');
		$query->order($db->escape($orderCol).' '.$db->escape($this->getState('list.direction', 'DESC')));
	
		return $query;
	}
	

	public function getProjects () {
		
		$query = ' SELECT a.id AS value, a.name AS text FROM #__beestowf_projects AS a ';
		$this->_db->setQuery($query);
		$projects = $this->_db->loadObjectList();
		
		array_unshift($projects, JHtml::_('select.option', 'none', JText::_('COM_BEESTOWORKFLOW_TASKS_NO_PROJECT')));
		
		return $projects;
	}
	

	public function getBaseName() {
		
		if (!isset($this->basename)) {
			
			jimport('joomla.utilities.date');
				
			$app		= JFactory::getApplication();
		
			$tz			= new DateTimeZone($app->getCfg('offset'));
			$jdate 		= new JDate();
			$jdate->setTimezone($tz);
			$date 		=& $jdate->toMySQL(true);	
			
			$basename	= $this->getState('basename');
			$basename	= str_replace('__SITE__', $app->getCfg('sitename'), $basename);
			$basename	= str_replace('__CATNAME__', JText::_('COM_BEESTOWORKFLOW_PROJECTS'), $basename);
			$basename	= str_replace('__DATE__', $date, $basename);

			$this->basename = $basename;
		}

		return $this->basename;
	}


	public function getFileType() {
		return$this->getState('exported') == 'xls' ? 'xls' : 'pdf';
	}


	public function getMimeType() {
		return 'application/octet-stream';
	}
	
	
	public function getPDF () {
	
		// Get pdf library
		$pdfPATH = JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'assets' . DS . 'tcpdf';
		require_once ($pdfPATH . DIRECTORY_SEPARATOR . 'tcpdf.php');
		require_once ($pdfPATH . DIRECTORY_SEPARATOR . 'config/lang/eng.php');
	
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetMargins(3, 15, 3);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		$pdf->setLanguageArray($l);
		$pdf->setFontSubsetting(true);

		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont('dejavusans', '', 7, '', true);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		$html = '<table border="1" cellpadding="1">';
		$html .= '<tr>';
		$html .= '<td style="width:40%">' . JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_NAME') . '</td>';
		$html .= '<td style="width:22%">' . JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_PROJECT') . '</td>';
		$html .= '<td style="width:10%">' . JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_START') . '</td>';
		$html .= '<td style="width:10%">' . JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_DUE') . '</td>';
		$html .= '<td style="width:7%">' . JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_PRIORITY') . '</td>';
		$html .= '<td style="width:8%">' . JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_COMPLETED') . '</td>';
		$html .= '<td style="width:3%">' . JText::_('COM_BEESTOWORKFLOW_ID') . '</td>';
		$html .= '</tr>';
		
		$items 		= $this->getItems();
		$priority	= BeestoWorkflowHelper::getPriority();
		
		foreach($items as $item) {
			$completed = $item->completed == 'completed' ? JText::_('JYES') : JText::_('JNO');
			$html .= '<tr>';
			$html .= '<td>' . $item->name . '</td>';
			$html .= '<td>' . BeestoWorkflowHelper::getProject($item->project_id,'name') . '</td>';
			$html .= '<td>' . $item->start . '</td>';
			$html .= '<td>' . $item->due . '</td>';
			$html .= '<td>' . $priority[$item->priority] . '</td>';
			$html .= '<td>' . $completed . '</td>';
			$html .= '<td>' . $item->id . '</td>';
			$html .= '</tr>';
		}
				
		$html .= '</table>';
$html = <<<EOD
$html
EOD;

		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

		$pdf->Output($this->getBasename() . '.pdf', 'I');
	}



	public function getXLS() {
			
			$this->content = $this->xlsBOF();
			$this->content.= $this->xlsWriteLabel(0,0, JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_NAME'));
			$this->content.= $this->xlsWriteLabel(0,1, JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_PROJECT'));
			$this->content.= $this->xlsWriteLabel(0,2, JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_START'));
			$this->content.= $this->xlsWriteLabel(0,3, JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_DUE'));
			$this->content.= $this->xlsWriteLabel(0,4, JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_PRIORITY'));
			$this->content.= $this->xlsWriteLabel(0,5, JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_COMPLETED'));
			$this->content.= $this->xlsWriteLabel(0,6, JText::_('COM_BEESTOWORKFLOW_ID'));
								
			$items 	= $this->getItems();
			$priority	= BeestoWorkflowHelper::getPriority();
			
			$i = 1;
			foreach($items as $item) {
				
				$completed = $item->completed == 'completed' ? JText::_('JYES') : JText::_('JNO');
				
				$this->content.= $this->xlsWriteLabel($i,0, $item->name);
				$this->content.= $this->xlsWriteLabel($i,1, BeestoWorkflowHelper::getProject($item->project_id,'name'));
				$this->content.= $this->xlsWriteLabel($i,2, $item->start);
				$this->content.= $this->xlsWriteLabel($i,3, $item->due);
				$this->content.= $this->xlsWriteLabel($i,4, $priority[$item->priority]);
				$this->content.= $this->xlsWriteLabel($i,5, $completed);
				$this->content.= $this->xlsWriteNumber($i,6, $item->id);
				$i++;
			}

			$this->content .= $this->xlsEOF();
			
		return $this->content;
	}
	
	
	protected function xlsBOF() {
		return pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
	}

	protected function xlsEOF() {
		return pack("ss", 0x0A, 0x00);
	}
	
	protected function xlsWriteNumber($Row, $Col, $Value) {
		
		$encoding = mb_detect_encoding($Value, 'UTF-8, ISO-8859-1');

		if ($encoding == 'UTF-8') {
			$Value = iconv("UTF-8", "cp1252//TRANSLIT//IGNORE", $Value); 
		}
		
		$return = pack("sssss", 0x203, 14, $Row, $Col, 0x0);
		$return .=  pack("d", $Value);
		return $return;
	}

	protected function xlsWriteLabel($Row, $Col, $Value ) {
		
		$encoding = mb_detect_encoding($Value, 'UTF-8, ISO-8859-1');

		if ($encoding == 'UTF-8') {
			$Value = iconv("UTF-8", "cp1252//TRANSLIT//IGNORE", $Value); 
		}
		
		$L = strlen($Value);
		$t = pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
		$t .=  $Value;
		return $t;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>
