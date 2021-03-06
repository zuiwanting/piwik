<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 * @category Piwik_Plugins
 * @package Piwik_ExampleUI
 */

/**
 * @package Piwik_ExampleUI
 */
class Piwik_ExampleUI_Controller extends Piwik_Controller
{
    public function dataTables()
    {
        $view = Piwik_ViewDataTable::factory('table', 'ExampleUI.getTemperatures', $controllerAction = 'ExampleUI.dataTables');
        $view->translations['value'] = "Temperature in °C";
        $view->translations['label'] = "Hour of day";
        $view->filter_sort_column = 'label';
        $view->filter_sort_order = 'asc';
        $view->graph_limit = 24;
        $view->filter_limit = 24;
        $view->show_exclude_low_population = false;
        $view->show_table_all_columns = false;
        $view->disable_row_evolution = true;
        $view->y_axis_unit = '°C'; // useful if the user requests the bar graph
        echo $view->render();
    }

    public function evolutionGraph()
    {
        echo "<h2>Evolution of server temperatures over the last few days</h2>";
        $this->echoEvolutionGraph();
    }

    public function echoEvolutionGraph()
    {
        $view = Piwik_ViewDataTable::factory(
            'graphEvolution', 'ExampleUI.getTemperaturesEvolution', $controllerAction = 'ExampleUI.echoEvolutionGraph');
        $view->translations['server1'] = "Temperature server piwik.org";
        $view->translations['server2'] = "Temperature server dev.piwik.org";
        $view->y_axis_unit = '°C'; // useful if the user requests the bar graph
        echo $view->render();
    }

    public function barGraph()
    {
        $view = Piwik_ViewDataTable::factory(
            'graphVerticalBar', 'ExampleUI.getTemperatures', $controllerAction = 'ExampleUI.barGraph');
        $view->translations['value'] = "Temperature";
        $view->y_axis_unit = '°C';
        $view->graph_limit = 24;
        $view->show_footer = false;
        echo $view->render();
    }

    public function pieGraph()
    {
        $view = Piwik_ViewDataTable::factory(
            'graphPie', 'ExampleUI.getPlanetRatios', $controllerAction = 'ExampleUI.pieGraph');
        $view->columns_to_display = array('value');
        $view->translations['value'] = "times the diameter of Earth";
        $view->graph_limit = 10;
        $view->show_footer_icons = false;
        echo $view->render();
    }

    public function tagClouds()
    {
        echo "<h2>Simple tag cloud</h2>";
        $this->echoSimpleTagClouds();

        echo "<br /><br /><h2>Advanced tag cloud: with logos and links</h2>
		<ul style='list-style-type:disc;margin-left:50px'>
			<li>The logo size is proportional to the value returned by the API</li>
			<li>The logo is linked to a specific URL</li>
		</ul><br /><br />";
        $this->echoAdvancedTagClouds();
    }

    public function echoSimpleTagClouds()
    {
        $view = Piwik_ViewDataTable::factory(
            'cloud', 'ExampleUI.getPlanetRatios', $controllerAction = 'ExampleUI.echoSimpleTagClouds');
        $view->columns_to_display = array('label', 'value');
        $view->translations['value'] = "times the diameter of Earth";
        $view->show_footer = false;
        echo $view->render();
    }

    public function echoAdvancedTagClouds()
    {
        $view = Piwik_ViewDataTable::factory(
            'cloud', 'ExampleUI.getPlanetRatiosWithLogos', $controllerAction = 'ExampleUI.echoAdvancedTagClouds');
        $view->display_logo_instead_of_label = true;
        $view->columns_to_display = array('label', 'value');
        $view->translations['value'] = "times the diameter of Earth";
        echo $view->render();
    }

    public function sparklines()
    {
        $view = new Piwik_View('@ExampleUI/sparklines');
        $view->urlSparkline1 = $this->getUrlSparkline('generateSparkline', array('server' => 'server1', 'rand' => mt_rand()));
        $view->urlSparkline2 = $this->getUrlSparkline('generateSparkline', array('server' => 'server2', 'rand' => mt_rand()));
        echo $view->render();
    }

    public function generateSparkline()
    {
        $view = Piwik_ViewDataTable::factory(
            'sparkline', 'ExampleUI.getTemperaturesEvolution', $controllerAction = 'ExampleUI.generateSparkline');

        $serverRequested = Piwik_Common::getRequestVar('server', false);
        if ($serverRequested !== false) {
            $view->columns_to_display = array($serverRequested);
        }
        
        echo $view->render();
    }

    // Example use
    private function echoDataTableSearchEnginesFiltered()
    {
        $view = $this->getLastUnitGraph($this->pluginName, __FUNCTION__, 'Referers.getSearchEngines');
        $view->setColumnsToDisplay('nb_visits');
        $view->setSearchPattern('^(Google|Yahoo!)$', 'label');
        return $this->renderView($view);
    }
}
