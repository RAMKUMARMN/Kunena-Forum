<?php
/**
 * Kunena Component
 * @package Kunena.Template.Default
 * @subpackage Topic
 *
 * @copyright (C) 2008 - 2011 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();
$row = 0;
?>
<div class="kblock kpollbox">
	<div class="kheader">
		<span class="ktoggler"><a class="ktoggler close" title="<?php echo JText::_('COM_KUNENA_TOGGLER_COLLAPSE') ?>" rel="kpolls_tbody"></a></span>
		<h2><span><?php echo JText::_('COM_KUNENA_POLL_NAME'); ?> <?php echo KunenaHtmlParser::parseText ($this->poll->title); ?></span></h2>
	</div>
	<div class="kcontainer" id="kpolls_tbody">
		<div class="kbody">
			<div class="kpolldesc" style="border: 1px solid #BCBCBC;">
				<?php foreach ( $this->poll->getOptions() as $option ) : ?>
					<ul style="border-bottom: 1px solid #BCBCBC;">
						<li style="padding: 0 5px; border-right: 1px solid #BCBCBC;display: inline;">
						<?php echo KunenaHtmlParser::parseText ($option->text); ?></li>
						<li style="padding: 0 5px; border-right: 1px solid #BCBCBC;display: inline;"><img class="jr-forum-stat-bar" src="<?php echo $this->template->getImagePath('bar.png') ?>" height="10" width="<?php echo intval(($option->votes*300)/max($this->poll->getTotal(),1))+3; ?>" /></li>
						<li style="padding: 0 5px; border-right: 1px solid #BCBCBC;display: inline;"><?php if(isset($option->votes) && ($option->votes > 0)) { echo $option->votes; } else { echo JText::_('COM_KUNENA_POLL_NO_VOTE'); } ?></li>
						<li style="display: inline;"> <?php echo round(($option->votes*100)/max($this->poll->getTotal(),1),1)."%"; ?></li>
					</ul>

				<?php endforeach; ?>
				<ul style="border-bottom: 1px solid #BCBCBC;">
					<li>
					<?php
					echo JText::_('COM_KUNENA_POLL_VOTERS_TOTAL')." <strong>".$this->usercount."</strong> ";
					echo " ( ".implode(', ', $this->users_voted_list)." ) "; ?>
					<?php if ( $this->usercount > '5' ) : ?><a href="#" id="kpoll-moreusers"><?php echo JText::_('COM_KUNENA_POLLUSERS_MORE')?></a>
					<div style="display: none;" id="kpoll-moreusers-div"><?php echo implode(', ', $this->users_voted_morelist); ?></div>
					<?php endif; ?>
					</li>
				</ul>
				<ul>
					<li><?php if (!$this->me->exists()) : ?>
						<?php echo JText::_('COM_KUNENA_POLL_NOT_LOGGED'); ?>
						<?php elseif ($this->voted && !$this->config->pollallowvoteone) : ?>
						<a href="<?php echo CKunenaLink::GetPollURL('vote', $this->topic->id, $this->category->id);?>">
						<?php echo JText::_('COM_KUNENA_POLL_BUTTON_VOTE'); ?>
						</a>
						<?php else : ?>
						<a href="<?php echo CKunenaLink::GetPollURL('changevote', $this->topic->id, $this->category->id); ?>">
						<?php echo JText::_('COM_KUNENA_POLL_BUTTON_CHANGEVOTE'); ?>
						</a>
						<?php endif; ?>
					</li>
					<li>
						<?php if( $this->me->isModerator() ) : ?>
						<a href="<?php echo KunenaRoute::_("index.php?option=com_kunena&view=topic&id={$this->topic->id}&catid={$this->category->id}&pollid={$this->poll->id}&task=resetvotes&".JUtility::getToken() .'=1') ?>">Reset votes</a>
						<?php endif; ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>