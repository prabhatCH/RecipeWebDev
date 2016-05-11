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

$return 	= base64_encode(JRoute::_('index.php?option=com_beestoworkflow&view=staffnotes',false));

?>

<div id="beestoworfklow">
	
	<?php echo $this->menu;?>

	<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffnotes', false); ?>" method="post" name="adminForm" id="adminForm">

		<div class="form-actions">
			<button class="btn btn-default" onclick="javascript:Joomla.submitbutton('staffnote.add')">
				<i class="icon icon-plus"></i>
				<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFNOTES_NEW');?>
			</button>
			<a class="btn btn-default" href="javascript:Joomla.submitbutton('staffnotes.delete');"><i class="icon icon-delete"></i> <?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DELETE_SELECTED');?></a>
		</div>
			
		<div class="row-fluid">
			<div class="span12 page-header">
				<h1><i class="icon icon-edit"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_MENU_NOTES');?></h1>
			</div>
		</div>
			
		<div class="row-fluid">
			<div class="span12">
				<div class="btn-group">
					<input type="text" class="input-medium pull-left" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" />
					<button type="submit" class="btn btn-default"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
					<button type="button" class="btn btn-default" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
				</div>
				
				<div class="btn-group">
					<select name="filter_category" class="input-medium" onchange="this.form.submit()">
						<option value="">- <?php echo JText::_('COM_BEESTOWORKFLOW_STAFFNOTES_SELECT_CATEGORY');?> -</option>
						<?php echo JHtml::_('select.options', $this->categories  , 'value', 'text', $this->state->get('filter.category'));?>
					</select>
				</div>
			</div>
		</div>


		<table class="table table-hover">
			<thead>
				<tr>
				
					<th>
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFNOTES_TITLE');?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFNOTES_CATEGORY'); ?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFNOTES_ADDED_ON'); ?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_ID');?>
					</th>
					
				</tr>
			</thead>

			<tbody>
			<?php foreach ($this->items as $i => $item) {
			?>
			<tr class="row<?php echo $i % 2; ?>">

				<td>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				
				<td>
					<a href="index.php?option=com_beestoworkflow&task=staffnote.edit&id=<?php echo $item->id;?>"><?php echo $this->escape($item->title);?></a>
				</td>
				
				<td>
					<?php echo $this->escape($item->category);?>
				</td>
				
				<td>
					<?php echo $item->added;?>
				</td>
				
				<td>
					<?php echo $item->id; ?>
				</td>
				
			</tr>

			<?php } ?>
			</tbody>
						
			<tfoot>
				<tr>
					<td colspan="7">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>


		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="return" value="<?php echo $return;?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
