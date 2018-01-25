<?php
/**
 * @package    DD_
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
**/

defined('_JEXEC') or die;

/**
 * Class DD_GMaps_LocationsModelLocation
 *
 * @since  Version 1.0.0.0
 */
class DD_GMaps_Locations_CMTY_EditModelLocations extends JModelItem
{
	/**
	 * getItems
	 *
	 * @return  string
	 *
	 * @since   Version 1.0.0.0
	 */
	public function getItems()
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();

		$authorised = $user->authorise('core.create', 'com_dd_gmaps_locations')
			|| count($user->getAuthorisedCategories('com_dd_gmaps_locations', 'core.create'));

		if ($authorised !== true)
		{
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->setHeader('status', 403, true);

			return false;
		}

		jimport('joomla.application.component.model');

		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_dd_gmaps_locations/models');
		$model = JModelLegacy::getInstance('Locations', 'DD_GMaps_LocationsModel', array('ignore_request' => true));

		if (!$user->authorise('core.admin'))
		{
			$model->setState('filter.author_id', $user->id);
		}

		$model->setState('filter.state', 'all');

		return $model->getItems();
	}
}
