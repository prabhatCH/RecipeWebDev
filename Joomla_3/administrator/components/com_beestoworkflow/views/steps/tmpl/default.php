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

JHtml::_('behavior.tooltip');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$ordering 	= ($listOrder == 'a.order');
$priority 	= BeestoWorkflowHelper::getPriority();
$class 		= array(0=>'verylow',1=>'low',2=>'medium',3=>'high',4=>'veryhigh');
?>

<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=steps'); ?>" method="post" name="adminForm" id="adminForm">
	
	<div id="filter-bar" class="btn-toolbar">
		
		<div class="filter-search btn-group">
			<input type="text" class="input-medium pull-left" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="" placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" />
			<button type="submit" class="btn btn-default"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" class="btn btn-default" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		
	</div>
	
	<div class="clr"> </div>
	
	<table class="table">
		<thead>
			<tr>
				<th>
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
				</th>
				
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_BEESTOWORKFLOW_WORKFLOWS_STEPS_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>

				<th>
					<?php echo JHtml::_('grid.sort', 'COM_BEESTOWORKFLOW_WORKFLOWS_STEPS_PRIORITY', 'a.priority', $listDirn, $listOrder); ?>
				</th>
				
				<th>
					<?php echo JHtml::_('grid.sort',  'COM_BEESTOWORKFLOW_WORKFLOWS_STEPS_ORDER', 'a.order', $listDirn, $listOrder); ?>
				</th>
				
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_BEESTOWORKFLOW_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				
			</tr>
		</thead>

		<tbody>
		<?php foreach ($this->items as $i => $item) {
			$orderkey	= array_search($item->order,$this->ordering);
		?>
		<tr class="row<?php echo $i % 2; ?>">

			<td>
				<?php echo JHtml::_('grid.id', $i, $item->id); ?>
			</td>
			
			<td>
				<a href="index.php?option=com_beestoworkflow&task=step.edit&id=<?php echo $item->id;?>"><?php echo $this->escape($item->title);?></a>
			</td>
			
			<td>
				<span class="<?php echo $class[$item->priority];?>"><?php echo $priority[$item->priority];?></span>
			</td>
			
			<td>
				<span><?php echo $this->pagination->orderUpIcon($i, true, 'steps.up', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
				<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'steps.down', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
				<input type="text" name="order[]" size="5" value="<?php echo ($orderkey+1);?>" class="text-area-order input-small" />
			</td>
			
			<td>
				<?php echo $item->id; ?>
			</td>
			
		</tr>

		<?php } ?>
		</tbody>
				
		<tfoot>
			<tr>
				<td colspan="5">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
</form>
