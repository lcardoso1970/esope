<?php
/**
 * leaflet plugin
 *
 */

elgg_register_event_handler('init', 'system', 'leaflet_init'); // Init


/**
 * Init adf_leaflet plugin.
 */
function leaflet_init() {
	
	elgg_extend_view('css', 'leaflet/css');
	/*
	elgg_register_simplecache_view('leaflet/js');
	$leaflet_js = elgg_get_simplecache_url('js', 'leaflet');
	elgg_register_js('leaflet', $leaflet_js, 'head');
	*/
	
	// Register PHP libraries
	elgg_register_library('leaflet', elgg_get_plugins_path() . 'leaflet/lib/leaflet/leaflet.php');
	
	// Register JS scripts and CSS
	// @TODO Note : switching JS libs handling to RequireJS, so this now handles only CSS
	leaflet_register_libraries();
	
	// Register leaflet page handler
	elgg_register_page_handler('leaflet','leaflet_page_handler');
	
	// Register geocoder
	elgg_register_plugin_hook_handler('geocode', 'location', 'leaflet_geocode');
	
	// Cron handler (batch geoconding...)
	elgg_register_plugin_hook_handler('cron', 'daily', 'leaflet_cron_geocode_all_members');
	
	// Define JS scripts
	// @TODO Note : switching JS libs handling to RequireJS
	$vendors_url = '/mod/leaflet/vendors/';
	// Leaflet core
	elgg_define_js('leaflet', array(
		'src' => $vendors_url . 'Leaflet/leaflet.js',
		//'exports' => 'L',
	));
	// Leaflet plugins
	// Awesome markers
	elgg_define_js('leaflet.awesomemarkers', array(
		'src' => $vendors_url . 'Leaflet.awesome-markers/leaflet.awesome-markers.js',
		'deps' => array('leaflet'),
		//'exports' => 'jQuery.fn.ajaxForm',
	));
	// Providers
	elgg_define_js('leaflet.providers', array(
		'src' => $vendors_url . 'leaflet-providers/leaflet-providers.js',
		'deps' => array('leaflet'),
		//'exports' => 'L',
	));
	// GeoSearch
	elgg_define_js('leaflet.geosearch', array(
		'src' => $vendors_url . 'L.GeoSearch/src/js/l.control.geosearch.js',
		'deps' => array('leaflet'),
		//'exports' => 'L',
	));
	elgg_define_js('leaflet.geosearch.openstreetmap', array(
		'src' => $vendors_url . 'L.GeoSearch/src/js/l.geosearch.provider.openstreetmap.js',
		'deps' => array('leaflet', 'leaflet.geosearch'),
		//'exports' => 'L',
	));
	// Routing Machine
	elgg_define_js('leaflet.routing', array(
		'src' => $vendors_url . 'leaflet-routing-machine/leaflet-routing-machine.min.js',
		'deps' => array('leaflet'),
		//'exports' => 'L',
	));
	// MarkerCluster
	elgg_define_js('leaflet.markercluster', array(
		'src' => $vendors_url . 'Leaflet.markercluster/leaflet.markercluster.js',
		'deps' => array('leaflet'),
		//'exports' => 'L',
	));
	
	
}


/* Get libraries and extensions configuration
 * lib_name => array(
 *     array('type' => 'css|js', 'url' => 'lib_file_path'),
 *   )
 */
function leaflet_get_libraries_config() {
	$vendors_url = elgg_get_site_url() . 'mod/leaflet/vendors/';
	
	$libraries_config = array(
			'leaflet' => array(
					array('type' => 'css', 'url' => $vendors_url . 'Leaflet/leaflet.css'),
				),
			'leaflet.awesomemarkers' => array(
					array('type' => 'css', 'url' => $vendors_url . 'Leaflet.awesome-markers/leaflet.awesome-markers.css'),
				),
			'leaflet.providers' => array(
					array('type' => 'css', 'url' => $vendors_url . 'leaflet-providers/css/gh-fork-ribbon.css'),
				),
			'leaflet.geosearch' => array(
					array('type' => 'css', 'url' => $vendors_url . 'L.GeoSearch/src/css/l.geosearch.css'),
				),
			'leaflet.routing' => array(
					array('type' => 'css', 'url' => $vendors_url . 'leaflet-routing-machine/leaflet-routing-machine.css'),
				),
			'leaflet.markercluster' => array(
					array('type' => 'css', 'url' => $vendors_url . 'Leaflet.markercluster/MarkerCluster.css'),
					array('type' => 'css', 'url' => $vendors_url . 'Leaflet.markercluster/MarkerCluster.Default.css', 'name' => 'leaflet.markercluster.default'),
				),
		);
	return $libraries_config;
}


// Check enabled libraries and register the corresponding scripts and CSS
function leaflet_register_libraries() {
	// List available libraries
	$libraries_config = leaflet_get_libraries_config();
	
	global $leaflet_registered_libs;
	$leaflet_registered_libs = array('js' => array(), 'css' => array());
	
	// Register JS scripts and CSS files
	foreach ($libraries_config as $name => $lib_config) {
		// @TODO enable only wanted plugins
		// if (!in_array($name, $enabled_plugins)) { continue; }
		foreach ($lib_config as $config) {
			switch($config['type']) {
				case 'js':
					$libname = $name;
					if (isset($config['name'])) $libname = $config['name'];
					$location = 'head';
					if (isset($config['location'])) $libname = $config['location'];
					//elgg_register_js($libname, $config['url'], $location);
					elgg_define_js($libname, [
							'src' => $config['url'],
						]);
					/* Setting the URL of a module
					// You may have a script outside your views you wish to make available as a module.
					// In your PHP init, system event handler, you can use elgg_define_js() to do this:
					elgg_define_js('underscore', [
						'src' => '/mod/myplugin/vendors/underscore/underscore-min.js',
					]);
					*/
					/* Using traditional JS libraries as modules
					// JavaScript libraries that define global resources can also be defined as AMD modules 
					// if you shim them by setting exports and optionally deps:
					// set the path, define its dependencies, and what value it returns
					elgg_define_js('jquery.form', [
						'src' => '/mod/myplugin/vendors/jquery.form.js',
						'deps' => array('jquery'),
						'exports' => 'jQuery.fn.ajaxForm',
					]);
					*/
					
					$leaflet_registered_libs['js'][] = $libname;
					break;
				case 'css':
					$libname = $name;
					if (isset($config['name'])) $libname = $config['name'];
					elgg_register_css($libname, $config['url']);
					$leaflet_registered_libs['css'][] = $libname;
					break;
			}
		}
	}
	
}


// Load registered PHP, JS and CSS libraries
function leaflet_load_libraries() {
	
	elgg_load_library('leaflet');
	
	// Load JS defined libs
	elgg_require_js("leaflet");
	elgg_require_js("leaflet.awesomemarkers");
	elgg_require_js("leaflet.providers");
	elgg_require_js("leaflet.geosearch");
	elgg_require_js("leaflet.routing");
	elgg_require_js("leaflet.markercluster");
	
	// Load registered libs
	global $leaflet_registered_libs;
	foreach ($leaflet_registered_libs as $type => $libraries) {
		switch($type) {
			case 'js':
				foreach ($libraries as $name) {
					if ($name == 'leaflet') {
						elgg_require_js("leaflet");
					} else {
						elgg_require_js("leaflet/$name");
					}
				}
				break;
			case 'css':
				foreach ($libraries as $name) { elgg_load_css($name); }
				break;
		}
	}
}


/** leaflet page handler
 * home | index | null => home page
 * geocode/ => geocoding tool
 * search/ => search page
 * world/ => global admin page
 */
function leaflet_page_handler($page) {
	// Load registered libraries
	elgg_load_library('leaflet');
	leaflet_load_libraries();
	
	$leaflet_root = dirname(__FILE__) . '/pages/leaflet/';
	
	if (empty($page[0])) $page[0] = 'index';
	switch($page[0]) {
		
		// All these have same name than script
		case "geocode":
		case "search":
		case 'world': // Admin global view
			require($leaflet_root . $page[0] . ".php");
			break;
		
		// Map home page
		case "index":
		default:
			require($leaflet_root . "index.php");
	}
	
	return true;
}


/* Geocoding service */
function leaflet_geocode($hook, $entity_type, $returnvalue, $params) { 
	if (isset($params['location'])) {
		/* GOOGLE API */
		/* 
		$google_api = get_plugin_setting('google_api', 'googlegeocoder');
		// Desired address
		$address = "http://maps.google.com/maps/geo?q=".urlencode($params['location'])."&output=json&key=" . $google_api;
		// Retrieve the URL contents
		$result = file_get_contents($address);
		$obj = json_decode($result);
		$obj = $obj->Placemark[0]->Point->coordinates;
		*/
		
		/* Mapquest API */
		$api_key = elgg_get_plugin_setting('osm_api_key', 'leaflet');
		if (empty($api_key)) $api_key = elgg_get_plugin_setting('api_key', 'osm_maps');
		if (empty($api_key)) {
			error_log(elgg_echo('leaflet:error:missingapikey'));
			return false;
		}
		//$callback = get_input('callback', 'renderOptions');
		$inFormat = get_input('inFormat', 'kvp');
		$outFormat = get_input('outFormat', 'json');
		$maxResults = get_input('maxResults', '1');
		$location = urlencode($params['location']);

		// Desired address
		// Documentation : http://open.mapquestapi.com/geocoding/
		//$address = "http://open.mapquestapi.com/geocoding/v1/address?key=$api_key&callback=$callback&inFormat=$inFormat&outFormat=$outFormat&location=$location&maxResults=$maxResults";
		$address = "http://open.mapquestapi.com/geocoding/v1/address?key=$api_key&inFormat=$inFormat&outFormat=$outFormat&location=$location&maxResults=$maxResults";
		// Retrieve the URL contents
		$result = file_get_contents($address);
		$obj = json_decode($result); // true returns array[][] instead of object->props[0]->prop
		$latlong = $obj->results[0]->locations[0]->latLng;
		$lat = $latlong->lat;
		$long = $latlong->lng;
		// @TODO : extract and save zoom level ?
		//error_log("GEOCODING start : {$params['location']} => $lat,$long  using URL $address");
		
		// Return geocoded address if it is valid
		if (!empty($lat) && !empty($long)) {
			return array('lat' => $lat, 'long' => $long);
		}
	}
	
	// Don't save geocoded address if wrong result or no location
	return false;
}


/* Renvoie un tableau avec les coordonnées des personnes positionnées sur la carte
 * $filePath = chemin du fichier
 * $tslimit = intervale de temps valide en secondes
 * Line data structure : "$name|$lat|$lng|$radius|$timestamp|$timeout"
 */
function leaflet_read_positions_from_file($filePath = '', $tslimit = false) {
	if (file_exists($filePath)) {
		$currentts = time();
		$lines = file($filePath);
		foreach ($lines as $line_num => $line) {
			$line = trim($line);
			if (empty($line) || ($line == "\n")) continue;
			$position_data = explode('|', $line);
			$unique_key = $position_data[0]; // name
			// Exclude old data : 
			//  - user per-line timeout if $tslimit is only enabled
			// - or use custom value if $tslimit is set to a custom timeframe
			if ($tslimit === true) {
				if (($currentts - $position_data[4]) > $position_data[5]) { continue; }
			} else if ($tslimit) {
				if (($currentts - $position_data[4]) > $tslimit) { continue; }
			}
			// If all tests have passed, keep the data
			if (!empty($unique_key) && !empty($line)) $positions[$unique_key] = $line;
		}
	}
	return $positions;
}



/* Cron (daily) tasks
 * Geocode all registered members locations
 */
function leaflet_cron_geocode_all_members($hook, $entity_type, $returnvalue, $params) {
	$cron_enabled = elgg_get_plugin_setting('cron_enable', 'leaflet');
	if ($cron_enable != 'yes') { return ''; }
	
	elgg_load_library('leaflet');
	
	error_log("LEAFLET : geocode cron batch triggered");
	// Block cron task if we do not have the required parameters
	$api_key = elgg_get_plugin_setting('osm_api_key', 'leaflet');
	if (empty($api_key)) $api_key = elgg_get_plugin_setting('api_key', 'osm_maps');
	if (empty($api_key)) {
		error_log(elgg_echo('leaflet:error:missingapikey'));
		return elgg_echo('leaflet:error:missingapikey');
	}
	
	// Ensure that we have this required parameter before going into a long task
	if (!empty($api_key)) {
		elgg_set_context('cron');
	
		// Avoid any time limit while processing
		set_time_limit(0);
		access_show_hidden_entities(true);
		$ia = elgg_set_ignore_access(true);
	
		// Geocoding batch
		error_log("LEAFLET GEOCODE BATCH : started at " . date('Ymd H:i:s'));
		$debug_0 = microtime(TRUE);
		$users_options = array('types' => 'user', 'limit' => 0);
		$batch = new ElggBatch('elgg_get_entities', $users_options, 'leaflet_batch_geocode_member', 10);
		$debug_1 = microtime(TRUE);
		error_log("LEAFLET GEOCODE BATCH : Finished at " . date('Ymd H:i:s') . " => ran in " . round($debug_1-$debug_0, 4) . " seconds");
		echo '<p>' . elgg_echo('leaflet:cron:geocode:allmembers:done') . '</p>';
	
		elgg_set_ignore_access($ia);
		
		// Now cache results for quicker map display
		$all_members_map = elgg_view('leaflet/data/all_members_map');
		leaflet_cache_data('all_members_map', $all_members_map);
	}
	return '<p>' . elgg_echo('leaflet:cron:done') . '</p>';
}


// Cache some data (usually large query results)
function leaflet_cache_data($key, $content = '') {
	// Data root path (used for caching results of large requests)
	$leaflet_dataroot = elgg_get_data_path() . 'leaflet_cache/';
	if (!is_dir($leaflet_dataroot)) {
		mkdir($leaflet_dataroot, 0777);
		chmod($leaflet_dataroot, 0777);
	}
	
	if (!$key) { return false; }
	
	$filePath = $leaflet_dataroot . $key;
	if (fwrite(fopen($filePath, 'w'), $content)) {
		error_log("LEAFLET : wrote new cache for $key");
		return true;
	}
	
	error_log("LEAFLET : could not write new cache for $key");
	return false;
}



// Get cached data
function leaflet_get_cached_data($key, $cache_validity = 3600) {
	// Data root path (used for caching results of large requests)
	$leaflet_dataroot = elgg_get_data_path() . 'leaflet_cache/';
	if (!is_dir($leaflet_dataroot)) {
		mkdir($leaflet_dataroot, 0777);
		chmod($leaflet_dataroot, 0777);
	}
	
	if (!$key) { return false; }
	
	$filePath = $leaflet_dataroot . $key;
	// Check that file exists
	if (file_exists($filePath)) {
		// Check that is is still valid (time diff < $cache_validity)
		$lastupdate = filemtime($filePath);
		if ((time() - $lastupdate) < $cache_validity) {
			error_log("LEAFLET : found recent cache for $key < $cache_validity");
			// Return latest cache
			$f = fopen($path . $filePath, 'r');
			$data = stream_get_contents($f, -1, 0);
			fclose($f);
			return $data;
		}
	}
	
	// Could not find recently updated cache
	error_log("LEAFLET : no recent cache found for $key < $cache_validity");
	return false;
}


/* Generate a unique id to ensure JS unique functions
 * Note that the id must contain valid id for both CSS and JS, ie. no '-'
 */
function leaflet_id($prefix = 'leaflet_') {
	if (function_exists('esope_unique_id')) {
		$id = esope_unique_id($prefix);
	} else {
		global $leaflet_unique_id;
		if (!isset($leaflet_unique_id)) {
			$leaflet_unique_id = 1;
		} else {
			$leaflet_unique_id++;
		}
		$id = $prefix . $leaflet_unique_id;
	}
	$id = elgg_get_friendly_title($id);
	// Ensure valid id for JS
	$id = str_replace('-', '_', $id);
	return $id;
}


