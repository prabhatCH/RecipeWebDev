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
 
<div class="cpanel-left">
	<div id="cpanel">
	
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_beestoworkflow&view=users">
					<img alt="" src="components/com_beestoworkflow/assets/images/icon-48-user.png">			
					<span><?php echo JText::_( 'COM_BEESTOWORKFLOW_USERS' ) ;?></span></a>
			</div>
		</div>
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_beestoworkflow&view=workflows">
					<img alt="" src="components/com_beestoworkflow/assets/images/icon-48-workflows.png">			
					<span><?php echo JText::_( 'COM_BEESTOWORKFLOW_WORFKFLOWS' ) ;?></span></a>
			</div>
		</div>
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_beestoworkflow&view=projects">
					<img alt="" src="components/com_beestoworkflow/assets/images/icon-48-projects.png">			
					<span><?php echo JText::_( 'COM_BEESTOWORKFLOW_PROJECTS' ) ;?></span></a>
			</div>
		</div>
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_beestoworkflow&view=tasks">
					<img alt="" src="components/com_beestoworkflow/assets/images/icon-48-tasks.png">			
					<span><?php echo JText::_( 'COM_BEESTOWORKFLOW_TASKS' ) ;?></span></a>
			</div>
		</div>
			
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_beestoworkflow&view=files">
					<img alt="" src="components/com_beestoworkflow/assets/images/icon-48-attachments.png">			
					<span><?php echo JText::_( 'COM_BEESTOWORKFLOW_ATTACHMENTS' ) ;?></span></a>
			</div>
		</div>	
		
		<div class="icon-wrapper">
			<div class="icon">
				<a href="index.php?option=com_beestoworkflow&view=about">
					<img alt="" src="components/com_beestoworkflow/assets/images/icon-48-about.png">			
					<span><?php echo JText::_( 'COM_BEESTOWORKFLOW_ABOUT' ) ;?></span></a>
			</div>
		</div>
		
		<div style="clear:both"></div>

	</div>
</div>


<div class="cpanel-right">
	<div style="border:1px solid #ccc;background:#fff;margin:15px;padding:15px">
		<h3><?php echo  JText::_('COM_BEESTOWORKFLOW_VERSION') ; ?></h3>
		<p><?php echo $extension['version'];?></p>

		<h3><?php echo  JText::_('COM_BEESTOWORKFLOW_COPYRIGHT') ; ?></h3>
		<p>Ionut Lupu</p>

		<h3><?php echo  JText::_('COM_BEESTOWORKFLOW_LICENSE') ; ?></h3>
		<p>GNU/GPL</p>
		
		<h3>Feedback</h3>
		<p>If you use BeestoWorkflow, please post a rating and a review at the <a href="http://extensions.joomla.org/extensions/owner/JoomlaSan">Joomla! Extensions Directory</a>.</p>
	</div>
</div>
