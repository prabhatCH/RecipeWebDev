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

$extension = BeestoWorkflowHelper::getExtensionDetails ('beestoworkflow');

?>

<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=about'); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">

<div class="row-fluid">
	<div class="span6 offset3">
		
		<legend class="text-center">Info</legend>

			<h3><?php echo  JText::_('COM_BEESTOWORKFLOW_ABOUT') ; ?></h3>
			<p>Beestoworkflow is a fast, easy to use project management, workflow solution for Joomla!.</p>
			<p>Beestoworkflow enables your staff to organize, plan, and delegate jobs and tasks.</p>
			
			<dl class="dl-horizontal">
				<dt><?php echo  JText::_('COM_BEESTOWORKFLOW_VERSION') ; ?></dt>
				<dd><?php echo $extension['version'];?></dd>
				
				<dt><?php echo  JText::_('COM_BEESTOWORKFLOW_COPYRIGHT') ; ?></dt>
				<dd>Ionut Lupu</dd>

				<dt><?php echo  JText::_('COM_BEESTOWORKFLOW_LICENSE') ; ?></dt>
				<dd>GNU/GPL</dd>
			</dl>
			
			<p>Icons by <a href="http://glyphicons.com/" target="_blank">Glyphicons</a>.</p>
			<h3>Feedback</h3>
			<p>If you use Beestoworkflow, please post a rating and a review at the <a href="http://extensions.joomla.org/extensions/clients-a-communities/project-a-task-management/20605">Joomla! Extensions Directory</a>.</p>

	</div>
</div>


	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
