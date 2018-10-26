<?php
//set uour db up here
$DB_TYPE = "mysql"; 
$DB_HOST = "1.2.3.4";
$DB_USER = "rdmuser";
$DB_PSWD = "password";
$DB_NAME = "rdmdb";
$DB_PORT = 3306;


//optimization vars
$DELAY = 5;
$GYM_COUNT = 6;

$args = json_decode($_POST['data']);
if ($args->get_data == true) {
    
    
    $db = new mysqli($DB_HOST, $DB_USER, $DB_PSWD, $DB_NAME, $DB_PORT);
    if ($db->connect_error != '') {
        exit("Failed to connect to MySQL server!");
    }
    $db->set_charset('utf8');
    if ($args->show_unknownpois === true) {
        $show_unknown_mod = "name IS null AND ";
    }

    if ($args->show_gyms !== false) {
        
        $sql_gym = "SELECT id, lat, lon FROM gym WHERE " . $show_unknown_mod . "lat > ? AND lon > ? AND lat < ? AND lon < ?";
        if ($stmt = $db->prepare($sql_gym)) {
            $stmt->bind_param("dddd", $args->min_lat, $args->min_lon, $args->max_lat, $args->max_lon);
            
            $stmt->execute();
            $result = $stmt->get_result();
            while ($point = $result->fetch_array()) {
                $gyms[] = array(
                    'id' => $point['id'],
                    'lat' => $point['lat'],
                    'lon' => $point['lon']
                );
            }
        }
    }

    if ($args->show_pokestops !== false) {
        $sql_pokestop = "SELECT id, lat, lon FROM pokestop WHERE " . $show_unknown_mod . "lat > ? AND lon > ? AND lat < ? AND lon < ?";
        if ($stmt = $db->prepare($sql_pokestop)) {       
            $stmt->bind_param("dddd", $args->min_lat, $args->min_lon, $args->max_lat, $args->max_lon);
            
            $stmt->execute();
            
            $result = $stmt->get_result();
            while ($point = $result->fetch_array()) {
                $stops[] = array(
                    'id' => $point['id'],
                    'lat' => $point['lat'],
                    'lon' => $point['lon']
                );
            }
        }
    }
        
    if ($args->show_spawnpoints !== false) {
        $sql_spawnpoint = "SELECT id, lat, lon FROM spawnpoint WHERE lat > ? AND lon > ? AND lat < ? AND lon < ?";
        if ($stmt = $db->prepare($sql_spawnpoint)) {
            $stmt->bind_param("dddd", $args->min_lat, $args->min_lon, $args->max_lat, $args->max_lon);
            
            $stmt->execute();
            
            $result = $stmt->get_result();
            while ($point = $result->fetch_array()) {
                $spawns[] = array(
                    'id' => $point['id'],
                    'lat' => $point['lat'],
                    'lon' => $point['lon']
                );
            }
        }
    }
    
    echo json_encode(array('gyms' => $gyms, 'pokestops' => $stops, 'spawnpoints' => $spawns));
    die();
} else 
if ($args->get_optimization == true) {
    
    //adapted from https://github.com/123FLO321/RealDeviceRaidMap/blob/master/Control/createRoute.php
    $points = $args->points;
    $locationsBest = array();
    $tryCount = 1;
    foreach(range(1,$args->optimization_attempts) as $i) {
        shuffle($points);
        $workGyms = $points;
        $locations = array();
        while (sizeof($workGyms) != 0) {
            $gym = reset($workGyms);
            $index = key($workGyms);
            unset($workGyms[$index]);
            $clossestGyms = array();
            foreach ($workGyms as $index2 => $gym2) {
                $gym2->index = $index2;
                $dist = haversineGreatCircleDistance($gym->latitude, $gym->longitude, $gym2->latitude, $gym2->longitude);
                if ($dist <= $args->circle_size) {
                    if (sizeof($clossestGyms) < $GYM_COUNT - 1) {
                        while (isset($clossestGyms[$dist])) {
                            $dist += 1;
                        }
                        $clossestGyms[$dist] = $gym2;
                    } else {
                        krsort($clossestGyms);
                        $keys = array_keys($clossestGyms);
                        $last = end($keys);
                        if ($dist <= $last) {
                            while (isset($clossestGyms[$dist])) {
                                $dist += 1;
                            }
                            array_pop($clossestGyms);
                            $clossestGyms[$dist] = $gym2;
                        }
                    }
                }
            }
            foreach ($clossestGyms as $gym2) {
                unset($workGyms[$gym2->index]);
            }
            $locations[] = array(
                "latitude" => $gym->latitude,
                "longitude" => $gym->longitude,
            );
        }
        $tryCount++;
        if (sizeof($locationsBest) == 0 || sizeof($locationsBest) > sizeof($locations)) {
            $locationsBest = $locations;
        }
    };
    $return['circle_size'] = $args->circle_size;
    $return['optimization_attempts'] = $args->optimization_attempts;
    $return['old_points_count'] = count($points);
    $return['new_points_count'] = count($locationsBest);
    $return['efficiency'] = ($return['old_points_count']/$return['new_points_count']) / ($return['old_points_count']/($return['old_points_count']/$GYM_COUNT)) * 100;
    foreach ($locationsBest as $location) {
        $return['bestAttempt'][] = array("latitude"=>$location["latitude"], "longitude"=>$location["longitude"]);
    }
    echo json_encode($return);
}
    
function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
	// convert from degrees to radians
	$latFrom = deg2rad($latitudeFrom);
	$lonFrom = deg2rad($longitudeFrom);
	$latTo = deg2rad($latitudeTo);
	$lonTo = deg2rad($longitudeTo);
	$latDelta = $latTo - $latFrom;
	$lonDelta = $lonTo - $lonFrom;
	$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
			cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
	return $angle * $earthRadius;
}
if (empty($args->get_optimization) && empty($args->get_data)) { ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Circle Generator</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <style>
            html, body {
                height: 100%;
            }
            #map {
                height: 100%;
            }
        </style>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-easybutton@2.0.0/src/easy-button.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.EasyButton/2.3.0/easy-button.min.js"></script>      
        <script src="https://npmcdn.com/leaflet-geometryutil"></script>
        <script src="https://cdn.jsdelivr.net/npm/@turf/turf@5/turf.min.js"></script>
        <script type="text/javascript">
        // Force zIndex of Leaflet
        (function(global){
          var MarkerMixin = {
            _updateZIndex: function (offset) {
              this._icon.style.zIndex = this.options.forceZIndex ? (this.options.forceZIndex + (this.options.zIndexOffset || 0)) : (this._zIndex + offset);
            },
            setForceZIndex: function(forceZIndex) {
              this.options.forceZIndex = forceZIndex ? forceZIndex : null;
            }
          };
          if (global) global.include(MarkerMixin);
        })(L.Marker);
        </script>
    </head>
    <body>
        <div id="map"></div>
        </div>
        <script>
let map;
let drawControl;
let buttonSettingsModal;
let buttonTrash;
let buttonOutputModal

var gyms = [];
var pokestops = [];
var spawnpoints = [];

let showGyms;
let showPokestops;
let showSpawnpoints;
let showUnknownPOIs;
let optimizeGyms;
let optimizePokestops;
let optimizeSpawnpoints;
let circleSize;
let optimizationAttempts;
let mapMode;

let pokestopLayers;
let gymLayers;
let spawnpointLayers;
let editableLayers;
let circleLayers;
var workingLayers;

var allCircles = [];

$(function(){
    
    loadStorage();

    initMap();
    
    loadData();
    
    setMapMode();
    
    $('#route-generator').parent().on('click', function(event) {
        $('#circle-size').parent('.input-group').show();
        $('#optimization-attempts').parent('.input-group').hide();
        $('#show-unknownpois').parent().parent().parent().hide();
        $('#show-optimizegyms').parent().parent().parent().hide();
        $('#show-optimizepokestops').parent().parent().parent().hide();
        $('#show-optimizespawnpoints').parent().parent().parent().hide();
        $('#show-unknownpois').parent().removeClass('active');
        $('#hide-unknownpois').parent().addClass('active');
		showUnknownPOIs = false;
		store('show_unknownpois', false);
    });
    $('#route-optimizer').parent().on('click', function(event) {
        $('#circle-size').parent('.input-group').show();
        $('#optimization-attempts').parent('.input-group').show();
        $('#show-unknownpois').parent().parent().parent().hide();
        $('#show-optimizegyms').parent().parent().parent().show();
        $('#show-optimizepokestops').parent().parent().parent().show();
        $('#show-optimizespawnpoints').parent().parent().parent().show();
        $('#show-unknownpois').parent().removeClass('active');
        $('#hide-unknownpois').parent().addClass('active');
		showUnknownPOIs = false;
		store('show_unknownpois', false);
    });
    $('#poi-viewer').parent().on('click', function(event) {
        $('#circle-size').parent('.input-group').hide();
        $('#optimization-attempts').parent('.input-group').hide();
        $('#show-unknownpois').parent().parent().parent().show();
        $('#show-optimizegyms').parent().parent().parent().hide();
        $('#show-optimizepokestops').parent().parent().parent().hide();
        $('#show-optimizespawnpoints').parent().parent().parent().hide();
    });
    //importPolygonData
    
    $('#save-polygon').on('click', function(event) {
        const polygonData = csvtoarray($('#polygon-data').val());
        var polygonOptions = {
            clickable: false,
            color: "#3388ff",
            fill: true,
            fillColor: null,
            fillOpacity: 0.2,
            opacity: 0.5,
            stroke: true,
            weight: 4
        };
        var newPolygon = L.polygon(polygonData, polygonOptions).addTo(editableLayers);
        
        switch (mapMode) {
            case 'generator':
                runGenerator();
                break;
            case 'optimizer':
                runOptimizer();
                break;
            case 'poiviewer':
                break;
        }
        $('#modalImport').modal('hide');
        
       
    });
    $('#save-settings').on('click', function(event) {

        const newShowGyms = $('#show-gyms').parent().hasClass('active');
		const newShowPokestops = $('#show-pokestops').parent().hasClass('active');
        const newShowSpawnpoints = $('#show-spawnpoints').parent().hasClass('active');
		const newShowUnknownPOIs = $('#show-unknownpois').parent().hasClass('active');
		const newOptimizeGyms = $('#show-optimizegyms').parent().hasClass('active');
		const newOptimizePokestops = $('#show-optimizepokestops').parent().hasClass('active');
		const newOptimizeSpawnpoints = $('#show-optimizespawnpoints').parent().hasClass('active');
        const newCircleSize = $('#circle-size').val();
        const newOptimizationAttempts = $('#optimization-attempts').val();
                
        if ($('#route-generator').parent().hasClass('active') !== false) {
            var newMapMode = 'generator';
        } else if ($('#route-optimizer').parent().hasClass('active') !== false) {
            var newMapMode = 'optimizer';
        } else {
            var newMapMode = 'poiviewer';
        }
        if (newMapMode !== mapMode) {
            mapMode = newMapMode;
            store('map_mode', newMapMode);
            setMapMode();            
        }
        
        showGyms = newShowGyms;
		store('show_gyms', newShowGyms);
		showPokestops = newShowPokestops;
		store('show_pokestops', newShowPokestops);
        showSpawnpoints = newShowSpawnpoints;
        store('show_spawnpoints', newShowSpawnpoints);
		showUnknownPOIs = newShowUnknownPOIs;
		store('show_unknownpois', newShowUnknownPOIs);
		optimizeGyms = newOptimizeGyms;
		store('optimize_gyms', newOptimizeGyms);
		optimizePokestops = newOptimizePokestops;
		store('optimize_pokestops', newOptimizePokestops);
		optimizeSpawnpoints = newOptimizeSpawnpoints;
		store('optimize_spawnpoints', newOptimizeSpawnpoints);
		circleSize = newCircleSize;
		store('circlesize', newCircleSize);
		optimizationAttempts = newOptimizationAttempts;
		store('optimizationattempts', newOptimizationAttempts);
        
        loadData();
        $('#modalSettings').modal('hide');
    });
    
    $('#cancel-settings').on('click', function(event) {
        //reset settings to stored values
        loadStorage();
    });    
    
    $("#selectAllAndCopy").click(function () {
        $(this).parents("#output-body").children("#allCircles").select();
        document.execCommand('copy');
        $(this).text("Copied!");
    });
})
function csvtoarray(dataString) {
  var lines = dataString
    .split(/\n/)                     // Convert to one string per line
    .map(function(lineStr) {
        return lineStr.split(",");   // Convert each line to array (,)
    })
  
  return lines;
}

function initMap() {
    map = L.map('map').setView([42.548197, -83.14684], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
        maxZoom: 18
    }).addTo(map);

    circleLayers = new L.LayerGroup();
    map.addLayer(circleLayers);

    editableLayers = new L.FeatureGroup();
    map.addLayer(editableLayers);

    pokestopLayers = new L.LayerGroup();
    map.addLayer(pokestopLayers);

    gymLayers = new L.LayerGroup();
    map.addLayer(gymLayers);

    spawnpointLayers = new L.LayerGroup();
    map.addLayer(spawnpointLayers);
    
    workingLayers = new L.LayerGroup();
    map.addLayer(workingLayers);
    
    var options = {
        position: 'topleft',
        draw: {
            polyline: false,
            polygon:   {
                shapeOptions: {
                    clickable: false
                }
            },
            circle: false,
            rectangle: false,
            circlemarker: false,
            marker: false
        },
        edit: {
            featureGroup: editableLayers, 
            edit: false,
            remove: false,
            poly: false
        }
    };
    buttonSettingsModal = L.easyButton({
        states: [{
            stateName: 'openSettingsModal',
            icon: 'fas fa-cog', 
            title: 'Open settings',
            onClick: function (control){ 
            
				if (retrieve('show_gyms') == 'true') {
                    $('#show-gyms').parent().addClass('active');
                    $('#hide-gyms').parent().removeClass('active');
				} else {
                    $('#hide-gyms').parent().addClass('active');
                    $('#show-gyms').parent().removeClass('active');
				}
                
				if (retrieve('show_pokestops') == 'true') {
                    $('#show-pokestops').parent().addClass('active');
                    $('#hide-pokestops').parent().removeClass('active');
				} else {
                    $('#hide-pokestops').parent().addClass('active');
                    $('#show-pokestops').parent().removeClass('active');
				}
                
				if (retrieve('show_spawnpoints') == 'true') {
                    $('#show-spawnpoints').parent().addClass('active');
                    $('#hide-spawnpoints').parent().removeClass('active');
				} else {
                    $('#hide-spawnpoints').parent().addClass('active');
                    $('#show-spawnpoints').parent().removeClass('active');
				}
                
				if (retrieve('show_unknownpois') == 'true') {
                    $('#show-unknownpois').parent().addClass('active');
                    $('#hide-unknownpois').parent().removeClass('active');
				} else {
                    $('#hide-unknownpois').parent().addClass('active');
                    $('#show-unknownpois').parent().removeClass('active');
				}
                
				if (retrieve('optimize_gyms') == 'true') {
                    $('#show-optimizegyms').parent().addClass('active');
                    $('#hide-optimizegyms').parent().removeClass('active');
				} else {
                    $('#hide-optimizegyms').parent().addClass('active');
                    $('#show-optimizegyms').parent().removeClass('active');
				}
                
				if (retrieve('optimize_pokestops') == 'true') {
                    $('#show-optimizepokestops').parent().addClass('active');
                    $('#hide-optimizepokestops').parent().removeClass('active');
				} else {
                    $('#hide-optimizepokestops').parent().addClass('active');
                    $('#show-optimizepokestops').parent().removeClass('active');
				}
                
				if (retrieve('optimize_spawnpoints') == 'true') {
                    $('#show-optimizespawnpoints').parent().addClass('active');
                    $('#hide-optimizespawnpoints').parent().removeClass('active');
				} else {
                    $('#hide-optimizespawnpoints').parent().addClass('active');
                    $('#show-optimizespawnpoints').parent().removeClass('active');
				}
                
				if (retrieve('map_mode') == 'generator') {
                    $('#route-generator').parent().addClass('active');
                    $('#route-optimizer').parent().removeClass('active');
                    $('#poi-viewer').parent().removeClass('active');
				} else if (retrieve('map_mode') == 'optimizer') {
                    $('#route-generator').parent().removeClass('active');
                    $('#route-optimizer').parent().addClass('active');
                    $('#poi-viewer').parent().removeClass('active');
				} else {
                    $('#route-generator').parent().removeClass('active');
                    $('#route-optimizer').parent().removeClass('active');
                    $('#poi-viewer').parent().addClass('active');
                }
                
				if (retrieve('circlesize') != null) {
                    $('#circle-size').val(retrieve('circlesize'));
				} else {
                    $('#circle-size').val('500');
				}
                
				if (retrieve('optimizationattempts') != null) {
                    $('#optimization-attempts').val(retrieve('optimizationattempts'));
				} else {
                    $('#optimization-attempts').val('500');
				}
                
                $('#modalSettings').modal('show');
            }
        }]
    });
    buttonSettingsModal.addTo(map);
    
    drawControl = new L.Control.Draw(options);
    map.addControl(drawControl);
    
    buttonImportModal = L.easyButton({
        states: [{
            stateName: 'openImportModal',
            icon: 'fas fa-file-import', 
            title: 'Get output',
            onClick: function (control){
                $('#modalImport').modal('show');
            }
        }]
    });
    buttonImportModal.addTo(map);

    buttonTrash = L.easyButton({
        states: [{
            stateName: 'clearMap',
            icon: 'fas fa-trash', 
            title: 'Clear map',
            onClick: function (control){
                circleLayers.clearLayers();
                editableLayers.clearLayers();
                workingLayers.clearLayers();
            }
        }]
    });
    buttonTrash.addTo(map);
    
    buttonOutputModal = L.easyButton({
        states: [{
            stateName: 'openOutputModal',
            icon: 'fas fa-check', 
            title: 'Get output',
            onClick: function (control){
                allCircles = circleLayers.getLayers();
                for (i=0;i<allCircles.length;i++) {
                    var circleLatLng = allCircles[i].getLatLng();
                    $("#allCircles").append(circleLatLng.lat + "," + circleLatLng.lng);
                    if (i != allCircles.length-1) {
                        $("#allCircles").append("\n");
                    }
                }
                $('#modalOutput').modal('show');
            }
        }]
    });
    buttonOutputModal.addTo(map);    
    
    map.on('draw:deleted', function (e) {
        circleLayers.clearLayers();
    });

    map.on('draw:created', function (e) {
        var layer = e.layer;
        editableLayers.addLayer(layer);
        switch (mapMode) {
            case 'generator':
                runGenerator();
                break;
            case 'optimizer':
                runOptimizer();
                break;
            case 'poiviewer':
                break;
        }
    });

	map.on('zoomend', function() {
		loadData();
	});

	map.on('moveend', function() {
		loadData();
	});

	map.on('dragend', function() {
		loadData();
	});
}

function setMapMode(){
    switch (mapMode) {
        case 'generator':
            $('#circle-size').parent('.input-group').show();
            $('#optimization-attempts').parent('.input-group').hide();
            $('#show-unknownpois').parent().parent().parent().hide();
            $('#show-optimizegyms').parent().parent().parent().hide();
            $('#show-optimizepokestops').parent().parent().parent().hide();
            $('#show-optimizespawnpoints').parent().parent().parent().hide();
            $('#show-unknownpois').parent().removeClass('active');
            $('#hide-unknownpois').parent().addClass('active');
            showUnknownPOIs = false;
            store('show_unknownpois', false);
            workingLayers.clearLayers();
            runGenerator();
            break;
        case 'optimizer':
            $('#circle-size').parent('.input-group').show();
            $('#optimization-attempts').parent('.input-group').show();
            $('#show-unknownpois').parent().parent().parent().hide();
            $('#show-optimizegyms').parent().parent().parent().show();
            $('#show-optimizepokestops').parent().parent().parent().show();
            $('#show-optimizespawnpoints').parent().parent().parent().show();
            $('#show-unknownpois').parent().removeClass('active');
            $('#hide-unknownpois').parent().addClass('active');
            showUnknownPOIs = false;
            store('show_unknownpois', false);
            runOptimizer();
            break;
        case 'poiviewer':
            $('#circle-size').parent('.input-group').hide();
            $('#optimization-attempts').parent('.input-group').hide();
            $('#show-unknownpois').parent().parent().parent().show();
            $('#show-optimizegyms').parent().parent().parent().hide();
            $('#show-optimizepokestops').parent().parent().parent().hide();
            $('#show-optimizespawnpoints').parent().parent().parent().hide();
            editableLayers.clearLayers();
            circleLayers.clearLayers();
            workingLayers.clearLayers();
            buttonOutputModal.removeFrom(map);
            buttonTrash.removeFrom(map);
            map.removeControl(drawControl);
            break;
    }
}

function runOptimizer() {
    workingLayers.clearLayers();
    circleLayers.clearLayers();
    
    var newCircle,
        currentLatLng,
        point;
        
    var points = [];
        
    editableLayers.eachLayer(function (layer) {
        var poly = layer.toGeoJSON();
        var line = turf.polygonToLine(poly);
        
        if (optimizeGyms == true) {
            gymLayers.eachLayer(function (layer) {
                currentLatLng = [layer.getLatLng().lat, layer.getLatLng().lng];
                point = turf.point([currentLatLng[1], currentLatLng[0]]);
                if (turf.inside(point, poly)) {
                    newCircle = L.circle(currentLatLng, {
                        color: 'gold',
                        fillColor: '#FFA500',
                        fillOpacity: 0.5,
                        radius: 20
                    });
                    newCircle.addTo(workingLayers);
                    points.push({'latitude': point.geometry.coordinates[1], 'longitude': point.geometry.coordinates[0]});
                }
            });
        }
        if (optimizePokestops == true) {
            pokestopLayers.eachLayer(function (layer) {
                currentLatLng = [layer.getLatLng().lat, layer.getLatLng().lng];
                point = turf.point([currentLatLng[1], currentLatLng[0]]);
                if (turf.inside(point, poly)) {
                    newCircle = L.circle(currentLatLng, {
                        color: 'gold',
                        fillColor: '#FFA500',
                        fillOpacity: 0.5,
                        radius: 20
                    });
                    newCircle.addTo(workingLayers);
                    points.push({'latitude': point.geometry.coordinates[1], 'longitude': point.geometry.coordinates[0]});
                }
            });       
        }
        if (optimizeSpawnpoints == true) {
            spawnpointLayers.eachLayer(function (layer) {
                currentLatLng = [layer.getLatLng().lat, layer.getLatLng().lng];
                point = turf.point([currentLatLng[1], currentLatLng[0]]);
                if (turf.inside(point, poly)) {
                    newCircle = L.circle(currentLatLng, {
                        color: 'gold',
                        fillColor: '#FFA500',
                        fillOpacity: 0.5,
                        radius: 20
                    });
                    newCircle.addTo(workingLayers);
                    points.push({'latitude': point.geometry.coordinates[1], 'longitude': point.geometry.coordinates[0]});
                }
            });       
        }
    });
    const data = {
		'get_optimization': true,
        'circle_size': circleSize,
        'optimization_attempts': optimizationAttempts,
        'points': points
	};
    const json = JSON.stringify(data);
    
	$.ajax({
		url: this.href,
        type: 'POST',
        dataType: 'json',
		data: {'data': json},
		success: function (result) {
            result.bestAttempt.forEach(function(point) {
                 newCircle = L.circle([point.latitude, point.longitude], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: circleSize
                });
                newCircle.addTo(circleLayers);
            });
        }
    });
}

function runGenerator(layer) {
    circleLayers.clearLayers();
    editableLayers.eachLayer(function (layer) {
        var poly = layer.toGeoJSON();
        var line = turf.polygonToLine(poly);
        var newCircle;
        
        var xMod = Math.sqrt(0.75);
        var yMod = Math.sqrt(0.568);
        
        
        var currentLatLng = layer._bounds._northEast;
        var startLatLng = L.GeometryUtil.destination(layer._bounds._northEast, 45, circleSize*4);
        var endLatLng = L.GeometryUtil.destination(layer._bounds._southWest, 225, circleSize*4);

        var row = 0;
        var heading = 270;
        while(currentLatLng.lat > endLatLng.lat) {
            do {
                newCircle = L.circle(currentLatLng, {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: circleSize
                });
                var point = turf.point([currentLatLng.lng, currentLatLng.lat]);
                var distance = turf.pointToLineDistance(point, line, { units: 'meters' });
                if (distance <= circleSize || turf.inside(point, poly)) {
                    newCircle.addTo(circleLayers);
                }
                currentLatLng = L.GeometryUtil.destination(currentLatLng, heading, (xMod*circleSize*2));            
            }while((heading == 270 && currentLatLng.lng > endLatLng.lng) || (heading == 90 && currentLatLng.lng < startLatLng.lng));
            currentLatLng = L.GeometryUtil.destination(currentLatLng, 180, (yMod*circleSize*2));   
            
            rem = row%2;        
            if (rem == 1) {
                heading = 270;
            } else {
                heading = 90;
            }             
            currentLatLng = L.GeometryUtil.destination(currentLatLng, heading, (xMod*circleSize)*3);
            row++;
        }
    });
}

function loadData() {
    
	const bounds = map.getBounds();
    
    const data = {
        'get_data': true,
		'min_lat': bounds._southWest.lat,
		'max_lat': bounds._northEast.lat,
		'min_lon': bounds._southWest.lng,
		'max_lon': bounds._northEast.lng,
		'show_gyms': showGyms,
		'show_pokestops': showPokestops,
		'show_spawnpoints': showSpawnpoints,
        'show_unknownpois': showUnknownPOIs
	};
    const json = JSON.stringify(data);
    
	$.ajax({
		url: this.href,
        type: 'POST',
        dataType: 'json',
		data: {'data': json},
		success: function (result) {
            pokestopLayers.clearLayers();
            gymLayers.clearLayers();
            spawnpointLayers.clearLayers();
            
            if (result.gyms != null && showGyms == true) {
                for (i=0;i<result.gyms.length;i++) {
                    var circle = L.circle([result.gyms[i].lat, result.gyms[i].lon], {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5,
                        radius: 10
                    }).addTo(map);
                    circle.bindPopup("ID: " + result.gyms[i].id);
                    circle.addTo(gymLayers);
                }
            }
            
            if (result.pokestops != null && showPokestops == true) {
                for (i=0;i<result.pokestops.length;i++) {
                    var circle = L.circle([result.pokestops[i].lat, result.pokestops[i].lon], {
                        color: 'green',
                        fillColor: '#0f3',
                        fillOpacity: 0.5,
                        radius: 10
                    }).addTo(map);
                    circle.bindPopup("ID: " + result.pokestops[i].id);
                    circle.addTo(pokestopLayers);
                }
            }
            
            if (result.spawnpoints != null && showSpawnpoints == true) {
                for (i=0;i<result.spawnpoints.length;i++) {
                    var circle = L.circle([result.spawnpoints[i].lat, result.spawnpoints[i].lon], {
                        color: 'blue',
                        fillColor: '#30f',
                        fillOpacity: 0.5,
                        radius: 10
                    }).addTo(map);
                    circle.bindPopup("ID: " + result.spawnpoints[i].id);
                    circle.addTo(spawnpointLayers);
                }
            }
        }
    });
}

function loadStorage() {
    const showGymsValue = retrieve('show_gyms');
    if (showGymsValue === null) {
        store('show_gyms', true);
        showGyms = true;
    } else {
        showGyms = (showGymsValue === 'true');
    }

    const showPokestopsValue = retrieve('show_pokestops');
    if (showPokestopsValue === null) {
        store('show_pokestops', true);
        showPokestops = true;
    } else {
        showPokestops = (showPokestopsValue  === 'true');
    }

    const showSpawnpointsValue = retrieve('show_spawnpoints');
    if (showSpawnpointsValue === null) {
        store('show_spawnpoints', false);
        showSpawnpoints = false;
    } else {
        showSpawnpoints = (showSpawnpointsValue === 'true');
    }
    
    const showUnknownPOIsValue = retrieve('show_unknownpois');
    if (showUnknownPOIsValue === null) {
        store('show_unknownpois', false);
        showUnknownPOIs = false;
    } else {
        showUnknownPOIs = (showUnknownPOIsValue === 'true');
    }
    
    const optimizeGymsValue = retrieve('optimize_gyms');
    if (optimizeGymsValue === null) {
        store('optimize_gyms', true);
        optimizeGyms = true;
    } else {
        optimizeGyms = (optimizeGymsValue === 'true');
    }
    
    const optimizePokestopsValue = retrieve('optimize_pokestops');
    if (optimizePokestopsValue === null) {
        store('optimize_pokestops', false);
        optimizePokestops = false;
    } else {
        optimizePokestops = (optimizePokestopsValue === 'true');
    }
    
    const optimizeSpawnpointsValue = retrieve('optimize_spawnpoints');
    if (optimizeSpawnpointsValue === null) {
        store('optimize_spawnpoints', false);
        optimizeSpawnpoints = false;
    } else {
        optimizeSpawnpoints = (optimizeSpawnpointsValue === 'true');
    }
    
    const mapModeValue = retrieve('map_mode');
    if (mapModeValue === null) {
        store('map_mode', 'generator');
        mapMode = 'generator';
    } else {
        mapMode = mapModeValue;
    }
    
    const circleSizeValue = retrieve('circlesize');
    if (circleSizeValue === null) {
        store('circlesize', 500);
        circleSize = 500;
    } else {
        circleSize = circleSizeValue;
    }
    
    const optimizationAttemptsValue = retrieve('optimizationattempts');
    if (optimizationAttemptsValue === null) {
        store('optimizationattempts', 100);
        optimizationAttempts = 100;
    } else {
        optimizationAttempts = optimizationAttemptsValue;
    }
    
}

function store(name, value) {
    localStorage.setItem(name, value);
}

function retrieve(name) {
    return localStorage.getItem(name);
}
        </script>
        
        <div id="map_canvas"></div>
        
        <div class="modal" id="modalSettings" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Settings</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="mapMode">Map Operation Mode:</label>
                        <div class="input-group mb-3" style="width:100%">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-primary active">
                                    <input type="radio" name="mapMode" id="route-generator" autocomplete="off"> Route Generator
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" name="mapMode" id="route-optimizer" autocomplete="off"> Route Optimizer
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" name="mapMode" id="poi-viewer" autocomplete="off"> POI Viewer
                                </label>
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Optimization Attempts:</span>
                            </div>
                            <input id="optimization-attempts" name="optimizationAttempts" type="text" class="form-control" aria-label="Optimization attempts">
                            <div class="input-group-append">
                                <span class="input-group-text">Tries</span>
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Circle Radius:</span>
                            </div>
                            <input id="circle-size" name="circleSize" type="text" class="form-control" aria-label="Circle Radius (in meters)">
                            <div class="input-group-append">
                                <span class="input-group-text">Meters</span>
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="showGyms" id="show-gyms" autocomplete="off"> On
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="showGyms" id="hide-gyms" autocomplete="off"> Off
                                </label>
                            </div>
                            <div class="input-group-append">
                                <span style="padding: .375rem .75rem;">Show known gyms</span>
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="btn-group btn-group-toggle"data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="showPokestops" id="show-pokestops" autocomplete="off"> On
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="showPokestops" id="hide-pokestops" autocomplete="off"> Off
                                </label>
                            </div>
                            <div class="input-group-append" width>
                                <span style="padding: .375rem .75rem;">Show known pokestops</span>
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="showSpawnpoints" id="show-spawnpoints" autocomplete="off"> On
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="showSpawnpoints" id="hide-spawnpoints" autocomplete="off"> Off
                                </label>
                            </div>
                            <div class="input-group-append">
                                <span style="padding: .375rem .75rem;">Show known spawn points</span>
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="showUnknownPOIs" id="show-unknownpois" autocomplete="off"> On
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="showUnknownPOIs" id="hide-unknownpois" autocomplete="off"> Off
                                </label>
                            </div>
                            <div class="input-group-append">
                                <span style="padding: .375rem .75rem;">Show unnamed POIs only</span>
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="showOptimizeGyms" id="show-optimizegyms" autocomplete="off"> On
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="showOptimizeGyms" id="hide-optimizegyms" autocomplete="off"> Off
                                </label>
                            </div>
                            <div class="input-group-append">
                                <span style="padding: .375rem .75rem;">Optimize for gyms</span>
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="showOptimizePokestops" id="show-optimizepokestops" autocomplete="off"> On
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="showOptimizePokestops" id="hide-optimizepokestops" autocomplete="off"> Off
                                </label>
                            </div>
                            <div class="input-group-append">
                                <span style="padding: .375rem .75rem;">Optimize for pokestops</span>
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="showOptimizeSpawnpoints" id="show-optimizespawnpoints" autocomplete="off"> On
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="showOptimizeSpawnpoints" id="hide-optimizespawnpoints" autocomplete="off"> Off
                                </label>
                            </div>
                            <div class="input-group-append">
                                <span style="padding: .375rem .75rem;">Optimize for spawnpoints</span>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="save-settings" class="btn btn-primary">Save changes</button>
                        <button type="button"  id="cancel-settings" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="modalOutput" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Output</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="mapMode">Generated route:</label>
                        <div class="input-group mb-3">
                            <textarea id="allCircles" style="height:400px;" class="form-control" aria-label="Route output"></textarea>
                        </div>
                        <div class="input-group">
                            <button id="selectAllAndCopy" class="btn btn-secondary" type="button">Copy to clipboard</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="modalImport" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Polygon</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="mapMode">Polygon data:</label>
                        <div class="input-group mb-3">
                            <textarea name="importPolygonData" id="polygon-data" style="height:400px;" class="form-control" aria-label="Polygon data"></textarea>
                            Note: if running route optimization, you should be viewing the map area where this polygon will be drawn so that current data points will be loaded into the map.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="save-polygon" class="btn btn-primary">Import</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php } ?>
