<?php
/**
 * @package    DD_GMaps_Locations
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

?>
<div class="dd_gmaps_locations locations well">
<div class="row-fluid">
<?php if (empty($this->items)): ?>
    <?php // todo:
    JFactory::getApplication()->enqueueMessage('No locations assigned to you found', 'notice'); ?>
<?php endif; ?>
<?php foreach ($this->items as $item): ?>
    <div class="row-fluid">
        <div class="span6"><a href="<?php echo JRoute::_('index.php?option=com_dd_gmaps_locations_cmty_edit&view=profile_edit&id=' . (int) $item->id);?> ">
		        <?php echo $this->escape($item->title);?>
            </a>
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
