<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\CurrentLocalTimeWidget\Widgets;

use DateTime;
use DateTimeZone;
use Piwik\Common;
use Piwik\Piwik;
use Piwik\Plugins\UsersManager\UserPreferences;
use Piwik\Site;
use Piwik\Widget\Widget;
use Piwik\Widget\WidgetConfig;

/**
 * This class allows you to add your own widget to the Piwik platform. In case you want to remove widgets from another
 * plugin please have a look at the "configureWidgetsList()" method.
 * To configure a widget simply call the corresponding methods as described in the API-Reference:
 * http://developer.piwik.org/api-reference/Piwik/Plugin\Widget
 */
class GetCurrentLocalTime extends Widget
{
    public static function configure(WidgetConfig $config)
    {
        /**
         * Set the category the widget belongs to. You can reuse any existing widget category or define
         * your own category.
         */
        $config->setCategoryId('General_Visitors');

        /**
         * Set the subcategory the widget belongs to. If a subcategory is set, the widget will be shown in the UI.
         */
        $config->setSubcategoryId('General_Overview'); // TODO - Figure out what subcategories this should apply to.

        /**
         * Set the name of the widget belongs to.
         */
        $config->setName('CurrentLocalTimeWidget_CurrentLocalTime');

        /**
         * Set the order of the widget. The lower the number, the earlier the widget will be listed within a category.
         */
        $config->setOrder(1);

        /**
         * Optionally set URL parameters that will be used when this widget is requested.
         * $config->setParameters(array('myparam' => 'myvalue'));
         */

        /**
         * Define whether a widget is enabled or not. For instance some widgets might not be available to every user or
         * might depend on a setting (such as Ecommerce) of a site. In such a case you can perform any checks and then
         * set `true` or `false`. If your widget is only available to users having super user access you can do the
         * following:
         *
         * $config->setIsEnabled(\Piwik\Piwik::hasUserSuperUserAccess());
         * or
         * if (!\Piwik\Piwik::hasUserSuperUserAccess())
         *     $config->disable();
         */
    }

    /**
     * This method renders the widget. It's on you how to generate the content of the widget.
     * As long as you return a string everything is fine. You can use for instance a "Piwik\View" to render a
     * twig template. In such a case don't forget to create a twig template (eg. myViewTemplate.twig) in the
     * "templates" directory of your plugin.
     *
     * @return string
     */
    public function render()
    {
		// This get's the time zone of the current site or the default site, if the idSite query paramerter isn't in the URL.
		$userPreferences = new UserPreferences();
		$idSite = Common::getRequestVar('idSite', $userPreferences->getDefaultWebsiteId(), 'int');
		$site = new Site($idSite);
		$siteTimeZone = $site->getTimezone();
		// Send both the site's time zone and the dateTime in the site's time zone so that we can check and make the conversion in JS.
		$siteDateTime = new DateTime('now', new DateTimeZone($siteTimeZone));
        return $this->renderTemplate('currentLocalTime', [
			'siteTimeZone' => $siteTimeZone,
			'siteDateTime' => $siteDateTime->format('Y-m-d H:i:s'),
			'localLabel' => Piwik::translate('CurrentLocalTimeWidget_LocalLabel'),
			'siteLabel' => Piwik::translate('CurrentLocalTimeWidget_SiteLabel')
		]);
    }

}