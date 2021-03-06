<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 * @category Piwik_Plugins
 * @package Piwik_Live
 */

/**
 *
 * @package Piwik_Live
 */
class Piwik_Live extends Piwik_Plugin
{
    /**
     * @see Piwik_Plugin::getListHooksRegistered
     */
    public function getListHooksRegistered()
    {
        return array(
            'AssetManager.getJsFiles'  => 'getJsFiles',
            'AssetManager.getCssFiles' => 'getCssFiles',
            'WidgetsList.add'          => 'addWidget',
            'Menu.add'                 => 'addMenu',
            'ViewDataTable.getReportDisplayProperties' => 'getReportDisplayProperties',
        );
    }

    public function getCssFiles(&$cssFiles)
    {
        $cssFiles[] = "plugins/Live/stylesheets/live.less";
    }

    public function getJsFiles(&$jsFiles)
    {
        $jsFiles[] = "plugins/Live/javascripts/live.js";
    }

    function addMenu()
    {
        Piwik_AddMenu('General_Visitors', 'Live_VisitorLog', array('module' => 'Live', 'action' => 'indexVisitorLog'), true, $order = 5);
    }

    public function addWidget()
    {
        Piwik_AddWidget('Live!', 'Live_VisitorsInRealTime', 'Live', 'widget');
        Piwik_AddWidget('Live!', 'Live_VisitorLog', 'Live', 'getVisitorLog');
        Piwik_AddWidget('Live!', 'Live_RealTimeVisitorCount', 'Live', 'getSimpleLastVisitCount');
    }

    public function getReportDisplayProperties(&$properties)
    {
        $properties['Live.getLastVisitsDetails'] = $this->getDisplayPropertiesForGetLastVisitsDetails();
    }

    private function getDisplayPropertiesForGetLastVisitsDetails()
    {
        return array(
            'datatable_template' => "@Live/getVisitorLog.twig",
            'disable_generic_filters' => true,
            'enable_sort' => false,
            'filter_sort_column' => 'idVisit',
            'filter_sort_order' => 'asc',
            'show_search' => false,
            'filter_limit' => 20,
            'show_offset_information' => false,
            'show_exclude_low_population' => false,
            'show_all_views_icons' => false,
            'show_table_all_columns' => false,
            'show_export_as_rss_feed' => false,
            'disable_row_actions' => true,
            'documentation' => Piwik_Translate('Live_VisitorLogDocumentation', array('<br />', '<br />')),
            'custom_parameters' => array(
                // set a very high row count so that the next link in the footer of the data table is always shown
                'totalRows' => 10000000,

                'filterEcommerce' => Piwik_Common::getRequestVar('filterEcommerce', 0, 'int'),
                'pageUrlNotDefined' => Piwik_Translate('General_NotDefined', Piwik_Translate('Actions_ColumnPageURL'))
            ),
        );
    }
}