<?php
/*
Plugin Name: QZ Chartbuilder
Plugin URI: http://aramzs.me
Description: This plugin is an adaptation of Quartz's Chartbuilder to bring it inside the WordPress dashboard. 
Version: 0.1
Author: Aram Zucker-Scharff
Author URI: http://aramzs.me
License: GPL2
*/

define( 'QZCB_SLUG', 'qzcb' );
define( 'QZCB_TITLE', 'PressForward' );
define( 'QZCB_MENU_SLUG', QZCB_SLUG . '-menu' );
define( 'QZCB_ROOT', dirname(__FILE__) );
define( 'QZCB_FILE_PATH', QZCB_ROOT . '/' . basename(__FILE__) );
define( 'QZCB_URL', plugins_url('/', __FILE__) );

class qz_chartbuilder {
	
	function __construct() {
		add_action('admin_menu', array($this, 'admin_build_main_page'));
		add_action("admin_print_scripts-media_page_qz-chartbuilder", array($this, 'add_admin_head_stuff'));
	}
	
	function admin_build_main_page(){
		add_media_page('Quartz Chartbuilder', 'Chartbuilder', 'edit_posts', 'qz-chartbuilder', array($this, 'admin_output_main'));
		
	}
	
	function admin_output_main(){
		?>
		        <!-- Add your site or application content here -->
        <div id="interactiveContent">
			<div id="leftColumn" class="cbColumn">
				<div id="staticStuff">
					<div id="chartContainer">

		        	</div>
		<canvas id="canvas" width="1200px" height="676px"></canvas> 
		<canvas id="favicanvas" width="16px" height="16px"></canvas> 
					<div id="datainput">
						<form accept-charset="utf-8" id="dataInputForm">
							<textarea id="csvInput" name="csvInput" rows="8" cols="40"></textarea>
						</form>
					</div>
					<div id="dataTable">
						<table border=0>
							<tr><th>Header</th></tr>
							<tr><td>Data</td></tr>
						</table>
					</div>
				</div>
				
			</div>
			<div id="rightColumn" class="cbColumn">
				<div id="chartOptions"  class="interfaceCol">
					<h2>Chart Options</h2>
					<form accept-charset="utf-8">
						<label for="chart_title">Title</label><input type="text" name="chart_title" value="" id="chart_title">
						<label for="right_axis_prefix">Right Axis Prefix</label><input type="text" name="right_axis_prefix" value="" id="right_axis_prefix">
						<label for="right_axis_suffix">Right Axis Suffix</label><input type="text/submit/hidden/button" name="right_axis_suffix" value="" id="right_axis_suffix">
						<label for="right_axis_tick_num">Number of Y Axis Ticks</label><select name="right_axis_tick_num" id="right_axis_tick_num" size="1">
							<option>2</option>
							<option>3</option>
							<option selected >4</option>
							<option>5</option>
							<option>6</option>
						</select>
						<label for="right_axis_max">Right Axis Max</label><input type="text" name="right_axis_max" value="" id="right_axis_max">
						<label for="right_axis_min">Right Axis Min</label><input type="text" name="right_axis_min" value="" id="right_axis_min">
						<label for="right_axis_tick_override">Right Axis Tick Override, comma separated</label><input type="text" name="right_axis_tick_override" value="" id="right_axis_tick_override">

						<div id="leftAxisControls">
							<label for="left_axis_prefix">Left Axis Prefix</label><input type="text" name="left_axis_prefix" value="" id="left_axis_prefix">
							<label for="left_axis_suffix">Left Axis Suffix</label><input type="text/submit/hidden/button" name="left_axis_suffix" value="" id="left_axis_suffix">
							<label for="left_axis_max">Left Axis Max</label><input type="text" name="left_axis_max" value="" id="left_axis_max">
							<label for="left_axis_min">Left Axis Min</label><input type="text" name="left_axis_min" value="" id="left_axis_min">
							<label for="left_axis_tick_override">Left Axis Tick Override, comma separated</label><input type="text" name="left_axis_tick_override" value="" id="left_axis_tick_override" >
						</div>

						<label for="credit">Credit</label><input id="creditLine" type="text" name="credit" value="Made with Chartbuilder">
						<label for="source">Source</label><input id="sourceLine" type="text" name="source" value="Data: ">
						<cite>"Something like Data: Bureau of Labor Statistics" or "Data compiled by Factset"</cite>
						<label for="x_axis_tick_num">Number of X Axis Ticks</label><select name="x_axis_tick_num" id="x_axis_tick_num" size="1">
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option selected>5</option>
							<option>6</option>
							<option>7</option>
							<option>8</option>
							<option>9</option>
							<option>10</option>
							<option>11</option>
							<option>12</option>
							<option>13</option>
							<option>14</option>
							<option>15</option>
						</select>
						<label for="x_axis_date_format">X Axis Date Format</label><select name="x_axis_date_format" id="x_axis_date_format">
							<option value="mmddyyyy">2/25/2012</option>
							<option value="mmdd">2/25</option>
							<option value="Mdd">Feb. 25</option>
							<option value="mmyy">2/13</option>
							<option value="yy">’13</option>
							<option value="yyyy">2013</option>
							<option value="MM">February</option>
							<option selected value="M">Feb.</option>
							<option value="hmm">3:27</option>

						</select>
						
					</form>
					<h2>Series Options</h2>
					<div id="seriesItems">
						
					</div>
					<label for="previous_charts">Previously Created Charts</label>
					<select name="previous_charts" id="previous_charts">
					</select>
					<div class="updateButton" id="createImageButton">
						
						<p>Create Image of Chart</p>
					</div>
					<a download="new_chart.png" id="downloadImageLink" class="hide downloadLink" href="#" target="_blank">Download Image of Chart</a>
					<a download="new_chart.svg" id="downloadSVGLink" class="hide downloadLink" href="#" target="_blank">Download SVG of Chart</a>
				</div>
				<div id="currentSeries" class="interfaceCol">

				</div>
			</div>
        	
			
        </div>
		<?php
	}
	
	function add_admin_head_stuff(){
		#global $pagenow;
		wp_register_style(QZCB_SLUG . '-boilerplate', QZCB_URL . 'css/boilerplate.css');
		wp_register_style(QZCB_SLUG . '-chartbuilder', QZCB_URL . 'css/chartbuilder.css');
		wp_register_style(QZCB_SLUG . '-gneisschart', QZCB_URL . 'css/gneisschart.css', null, null);
		wp_register_style(QZCB_SLUG . '-colorPicker', QZCB_URL . 'css/colorPicker.css');
		wp_register_script('modernizr', QZCB_URL . "js/vendor/modernizr-2.6.1.min.js");
		wp_enqueue_script('jquery', null, null, '1.8.1');
		wp_register_script(QZCB_SLUG . '-plugins', QZCB_URL . "js/vendor/plugins.js", array('jquery'));
		wp_register_script(QZCB_SLUG . '-d3', QZCB_URL . "js/vendor/d3.v2.min.js", array('jquery'));
		wp_register_script(QZCB_SLUG . '-sugar', QZCB_URL . "js/vendor/sugar-1.3.9-custom.min.js", array('jquery'));
		wp_register_script(QZCB_SLUG . '-gneisschart-js', QZCB_URL . "js/gneisschart.js", array('jquery'));
		wp_register_script(QZCB_SLUG . '-chartbuilder-js', QZCB_URL . "js/chartbuilder.js", array('jquery'));
		wp_register_script(QZCB_SLUG . '-configuration', QZCB_URL . "config/default_config.js", array('jquery'));
		wp_register_script(QZCB_SLUG . '-canvig', QZCB_URL . "js/vendor/canvg.js", array('jquery'));
		wp_register_script(QZCB_SLUG . '-rgbcolor', QZCB_URL . "js/vendor/rgbcolor.js", array('jquery'));
		
		
		wp_enqueue_style(QZCB_SLUG . '-boilerplate');
		wp_enqueue_style(QZCB_SLUG . '-chartbuilder');
		wp_enqueue_style(QZCB_SLUG . '-gneisschart');
		wp_enqueue_style(QZCB_SLUG . '-colorPicker');
		wp_enqueue_script('modernizr');
		wp_enqueue_script(QZCB_SLUG . '-plugins');
		wp_enqueue_script(QZCB_SLUG . '-d3');
		wp_enqueue_script(QZCB_SLUG . '-sugar');
		wp_enqueue_script(QZCB_SLUG . '-gneisschart-js');
		wp_enqueue_script(QZCB_SLUG . '-chartbuilder-js');
		wp_enqueue_script(QZCB_SLUG . '-configuration');
		wp_enqueue_script(QZCB_SLUG . '-canvig');
		wp_enqueue_script(QZCB_SLUG . '-rgbcolor');
		
	}
}

		$qzcb = new qz_chartbuilder();