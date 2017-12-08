<?php
/**
 * @package    DD_GMaps_Locations
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2011 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

class DD_Gmaps_Locations_CMTY_EditViewLocations extends JViewLegacy
{
	protected $app;

	protected $items;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse
	 *
	 * @return boolean | mixed
	 *
	 * @since Version 1.1.0.0
	 *
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$this->app = JFactory::getApplication();

		$this->items = $this->get('Items');

		$user = JFactory::getUser();

		$authorised = $user->authorise('core.create', 'com_dd_gmaps_locations')
			|| count($user->getAuthorisedCategories('com_dd_gmaps_locations', 'core.create'));

		if ($authorised !== true)
		{
			$this->app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$this->app->setHeader('status', 403, true);

			return false;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		return parent::display($tpl);
	}
}
