<?php
/**
 * @package    DD_GMaps_Locations
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

$user = JFactory::getUser();
?>
<div class="dd_gmaps_locations locations well">
<div class="row-fluid">
<?php if (empty($this->items)): ?>
    <?php // todo:
    JFactory::getApplication()->enqueueMessage('No locations assigned to you found', 'notice'); ?>
<?php endif; ?>
    <div class="row-fluid">
        <div class="btn-toolbar">
            <div class="btn-group">
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_dd_gmaps_locations_cmty_edit&task=profile_edit.add'); ?>">
                    <span class="icon-plus"></span><?php echo JText::_('JNEW'); ?>
                </a>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span6">Location</div>
        <div class="span3">Category</div>
        <div class="span3">ID</div>
    </div>
<?php foreach ($this->items as $item): ?>
    <div class="row-fluid">
        <div class="span6">
	        <?php $canEdit    = $user->authorise('core.edit',       'com_dd_gmaps_locations.location.' . $item->id);  ?>
	        <?php $canEditOwn = $user->authorise('core.edit.own',   'com_dd_gmaps_locations.location.' . $item->id) && (int) $item->created_by === $user->id;  ?>
	        <?php if ($canEdit || $canEditOwn) : ?>
                <a href="<?php echo JRoute::_('index.php?option=com_dd_gmaps_locations_cmty_edit&task=profile_edit.edit&id=' . (int) $item->id);?> ">
			        <?php echo $this->escape($item->title);?>
                </a>
	        <?php else : ?>
		        <?php echo $this->escape($item->title); ?>
	        <?php endif; ?>
        </div>
        <div class="span3">
	        <?php echo $this->escape($item->category_title); ?>
        </div>
        <div class="span3">
	        <?php echo (int) $item->id; ?>
        </div>
    </div>
<?php endforeach; ?>
</div>
