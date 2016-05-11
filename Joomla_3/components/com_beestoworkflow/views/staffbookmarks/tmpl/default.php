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

$return 	= base64_encode(JRoute::_('index.php?option=com_beestoworkflow&view=staffbookmarks',false));
?>

<div id="beestoworfklow">
	
	<?php echo $this->menu;?>
	
	<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffbookmarks', false); ?>" method="post" name="adminForm" id="adminForm">
			
		<div class="form-actions">
			<a class="btn btn-default" href="javascript:Joomla.submitbutton('staffbookmarks.delete');">
				<i class="icon icon-delete"></i>	<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DELETE_SELECTED');?>
			</a>
		</div>
		
		<div class="row-fluid">
			<div class="span12 page-header">
				<h1><i class="icon icon-bookmark"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_MENU_FAVORITES');?></h1>
			</div>
		</div>
			
		<div class="row-fluid">
			<div class="span12">
				<input type="text" class="input-xlarge pull-left" name="url" id="urls" value="http://"  />
				<button class="btn btn-default" onclick="Joomla.submitbutton('staffbookmarks.add');">
					<i class="icon icon-plus"></i>
					<?php echo JText::_('COM_BEESTOWORKFLOW_BOOKMARKS_ADD_NEW'); ?>
				</button>
			</div>
		</div>	
		
			
		<table class="table table-hover">
			<thead>
				<tr>
					<th>
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_BOOKMARKS_URL');?>
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
						<a href="<?php echo $this->escape($item->location);?>" target="_blank"><?php echo $this->escape($item->location);?></a>
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
