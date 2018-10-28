<?php if ($_POST['data']) { map_helper_init(); } else { ?><!DOCTYPE html>
<html>
    <head>
        <title>RealDeviceMap Toolbox</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <style>
            html, body {
                height: 100%;
            }
            #map {
                height: 100%;
            }
            .modal-loader .modal-dialog{
                display: table;
                position: relative;
                margin: 0 auto;
                top: calc(50% - 24px);
            }

            .modal-loader .modal-dialog .modal-content{
                background-color: transparent;
                border: none;
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
        <script src="https://cdn.jsdelivr.net/npm/leaflet-geometryutil@0.9.0/src/leaflet.geometryutil.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@turf/turf@5/turf.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/osmtogeojson@3.0.0-beta.3/osmtogeojson.js"></script>
        
<script type="text/javascript">
//map and control vars
var map;

var drawControl,
    buttonSettingsModal,
    buttonTrash,
    buttonModalOutput,
    buttonModalImportInstance,
    buttonModalImportPolygon;

//data vars
var gyms = [],
    pokestops = [],
    spawnpoints = [];

//options vars
var settings = {
    showGyms: null,
    showPokestops: null,
    showSpawnpoints: null,
    showUnknownPOIs: null,
    circleSize: null,
    optimizationAttempts: null,
    mapMode: null
};

//map layer vars
var gymLayers,
    pokestopLayers,
    spawnpointLayers,
    editableLayers,
    circleLayers;


$(document).ready(function() {
    $('#getOutput').click(function() {
        $('#outputCircles').empty();
        var allCircles = circleLayers.getLayers();
        for (i=0;i<allCircles.length;i++) {
            var circleLatLng = allCircles[i].getLatLng();
            $('#outputCircles').val(function(index, text) {
                if (i != allCircles.length-1) {
                    return text + (circleLatLng.lat + "," + circleLatLng.lng) + "\n" ;
                }
                return text + (circleLatLng.lat + "," + circleLatLng.lng);
            });
        }
    });
});

$(document).on("click", ".deleteLayer", function() {
    var id = $(this).attr('data-layer-id');
    var container = $(this).attr('data-layer-container');
    switch (container) {
        case 'circleLayers':
            circleLayers.removeLayer(parseInt(id));
            break;
        case 'editableLayers':
            editableLayers.removeLayer(parseInt(id));
            break;
    }
});

$(function(){
    processSettings();
    initMap();
    loadData();
    setMapMode();
    setOptionsDisplay();

    $('#modeRouteGenerator').parent().on('click', function(event) {
        setOptionsDisplay('modeRouteGenerator');
    });
    $('#modeRouteOptimizer').parent().on('click', function(event) {
        setOptionsDisplay('modeRouteOptimizer');
    });
    $('#modePoiViewer').parent().on('click', function(event) {
        setOptionsDisplay('modePoiViewer');
    });
    $('#modeNestHelper').parent().on('click', function(event) {
        setOptionsDisplay('modeNestHelper');
    });

    $('#fetchNests').on('click', function(event) {
        fetchNests();
    });

    $('#generateRoute').on('click', function(event) {
        generateRoute();
    });

    $('#generateOptimizedRoute').on('click', function(event) {
        generateOptimizedRoute();
    });

    $('#savePolygon').on('click', function(event) {
        //TODO: add error handling
        //TODO: add check for json or txt
        //TODO: add support for multi poly json array
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

        $('#modalImport').modal('hide');
    });

     $('#importInstance').on('click', function(event) {
         var name = $("#importInstanceName" ).val();
         getInstance(name);
     });

    $('#modalSettings').on('hidden.bs.modal', function(event) {
        if ($('#modeRouteGenerator').parent().hasClass('active') !== false) {
            var mapMode = 'modeRouteGenerator';
        } else if ($('#modeRouteOptimizer').parent().hasClass('active') !== false) {
            var mapMode = 'modeRouteOptimizer';
        } else if ($('#modePoiViewer').parent().hasClass('active') !== false) {
            var mapMode = 'modePoiViewer';
        } else if ($('#modeNestHelper').parent().hasClass('active') !== false) {
            var mapMode = 'modeNestHelper';
        }

        var showGyms = $('#showGyms').parent().hasClass('active');
        var showPokestops = $('#showPokestops').parent().hasClass('active');
        var showSpawnpoints = $('#showSpawnpoints').parent().hasClass('active');
        var showUnknownPOIs = $('#showUnknownPOIs').parent().hasClass('active');
        var circleSize = $('#circleSize').val();
        var optimizationAttempts = $('#optimizationAttempts').val();

        const newSettings = {
            showGyms: showGyms,
            showPokestops: showPokestops,
            showSpawnpoints: showSpawnpoints,
            showUnknownPOIs: showUnknownPOIs,
            circleSize: circleSize,
            optimizationAttempts: optimizationAttempts,
            mapMode: mapMode
        };

        Object.keys(settings).forEach(function(key) {
            if (settings[key] != newSettings[key]) {
                settings[key] = newSettings[key];
            }
        });

        processSettings();
        setMapMode();
        loadData();
    });

    $('#cancelSettings').on('click', function(event) {
        //reset settings to stored values
        processSettings();
    });

    $("#selectAllAndCopy").click(function () {
        $(this).parents("#output-body").children("#outputCircles").select();
        document.execCommand('copy');
        $(this).text("Copied!");
    });

})

function initMap() {
    var attrOsm = 'Map data &copy; <a href="http://openstreetmap.org/">OpenStreetMap</a> contributors';
    var attrOverpass = 'POI via <a href="http://www.overpass-api.de/">Overpass API</a>';
    var osm = new L.TileLayer(
    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: [attrOsm, attrOverpass].join(', ')
    });

    map = L.map('map').addLayer(osm).setView([42.548197, -83.14684], 13);

    circleLayers = new L.FeatureGroup();
    map.addLayer(circleLayers);

    editableLayers = new L.FeatureGroup();
    map.addLayer(editableLayers);

    pokestopLayers = new L.LayerGroup();
    map.addLayer(pokestopLayers);

    gymLayers = new L.LayerGroup();
    map.addLayer(gymLayers);

    spawnpointLayers = new L.LayerGroup();
    map.addLayer(spawnpointLayers);

    circleLayers.on('layeradd', function (layerevent) {
        var layer = layerevent.layer;
        layer.bindPopup(function (layer) {
            return '<button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayers" data-layer-id=' + layer._leaflet_id + ' type="button">Delete</button>';
        });
    });

    editableLayers.on('layeradd', function (layerevent) {
        var layer = layerevent.layer;
        layer.bindPopup(function (layer) {
            var output = '<button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="editableLayers" data-layer-id=' + layer._leaflet_id + ' type="button">Delete</button>';
            if (layer.tags.name !== null) {
                output = "<span>" + layer.tags.name + "</span><br />" + output;
            }
            return output;
        });
    });

    buttonModalSettings = L.easyButton({
        states: [{
            stateName: 'openSettingsModal',
            icon: 'fas fa-cog',
            title: 'Open settings',
            onClick: function (control){
                setOptionsDisplay();
				if (settings.showGyms == true) {
                    $('#showGyms').parent().addClass('active');
                    $('#hideGyms').parent().removeClass('active');
				} else {
                    $('#showGyms').parent().removeClass('active');
                    $('#hideGyms').parent().addClass('active');
				}

				if (settings.showPokestops == true) {
                    $('#showPokestops').parent().addClass('active');
                    $('#hidePokestops').parent().removeClass('active');
				} else {
                    $('#showPokestops').parent().removeClass('active');
                    $('#hidePokestops').parent().addClass('active');
				}

				if (settings.showSpawnpoints == true) {
                    $('#showSpawnpoints').parent().addClass('active');
                    $('#hideSpawnpoints').parent().removeClass('active');
				} else {
                    $('#showSpawnpoints').parent().removeClass('active');
                    $('#hideSpawnpoints').parent().addClass('active');
				}

				if (settings.showUnknownPOIs == true) {
                    $('#showUnknownPOIs').parent().addClass('active');
                    $('#hideUnknownPOIs').parent().removeClass('active');
				} else {
                    $('#showUnknownPOIs').parent().removeClass('active');
                    $('#hideUnknownPOIs').parent().addClass('active');
				}

				if (settings.circleSize != null) {
                    $('#circleSize').val(settings.circleSize);
				} else {
                    $('#circleSize').val('500');
				}

				if (settings.optimizationAttempts != null) {
                    $('#optimizationAttempts').val(settings.optimizationAttempts);
				} else {
                    $('#optimizationAttempts').val('100');
				}

                setMapMode();
                $('#modalSettings').modal('show');
            }
        }]
    }).addTo(map);

    drawControl = new L.Control.Draw({
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
    }).addTo(map);

    buttonModalImportPolygon = L.easyButton({
        states: [{
            stateName: 'openImportPolygonModal',
            icon: 'fas fa-draw-polygon',
            title: 'Import polygon',
            onClick: function (control){
                $('#modalImportPolygon').modal('show');
            }
        }]
    }).addTo(map);

    buttonModalImportInstance = L.easyButton({
        states: [{
            stateName: 'openImportInstanceModal',
            icon: 'fas fa-file-import',
            title: 'Import instance',
            onClick: function (control){
                getInstance();
                $('#modalImportInstance').modal('show');
            }
        }]
    }).addTo(map);

    buttonTrash = L.easyButton({
        states: [{
            stateName: 'clearMap',
            icon: 'fas fa-trash',
            title: 'Clear map',
            onClick: function (control){
                circleLayers.clearLayers();
                editableLayers.clearLayers();
            }
        }]
    }).addTo(map);

    buttonModalOutput = L.easyButton({
        states: [{
            stateName: 'openOutputModal',
            icon: 'fas fa-check',
            title: 'Get output',
            onClick: function (control){
                $('#modalOutput').modal('show');
            }
        }]
    }).addTo(map);

    map.on('draw:created', function (e) {
        var layer = e.layer;
        editableLayers.addLayer(layer);
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

function setOptionsDisplay(mode = settings.mapMode) {
    switch (mode) {
        case 'modeRouteGenerator':
            $('#circleSize').parent('.input-group').show();
            $('#optimizationAttempts').parent('.input-group').hide();
            $('#showGyms').parent().parent().parent().show();
            $('#showPokestops').parent().parent().parent().show();
            $('#showSpawnpoints').parent().parent().parent().show();
            $('#showUnknownPOIs').parent().parent().parent().hide();

            $('#fetchNests').parent().hide();
            $('#generateRoute').parent().show();
            $('#generateOptimizedRoute').parent().hide();

            $('#showUnknownPOIs').parent().removeClass('active');
            $('#hideUnknownPOIs').parent().addClass('active');

            $('#modeRouteGenerator').parent().addClass('active');
            $('#modeRouteOptimizer').parent().removeClass('active');
            $('#modePoiViewer').parent().removeClass('active');
            $('#modeNestHelper').parent().removeClass('active');
            break;

        case 'modeRouteOptimizer':
            $('#circleSize').parent('.input-group').show();
            $('#optimizationAttempts').parent('.input-group').show();
            $('#showGyms').parent().parent().parent().show();
            $('#showPokestops').parent().parent().parent().show();
            $('#showSpawnpoints').parent().parent().parent().show();
            $('#showUnknownPOIs').parent().parent().parent().hide();

            $('#fetchNests').parent().hide();
            $('#generateRoute').parent().hide();
            $('#generateOptimizedRoute').parent().show();

            $('#showUnknownPOIs').parent().removeClass('active');
            $('#hideUnknownPOIs').parent().addClass('active');

            $('#modeRouteGenerator').parent().removeClass('active');
            $('#modeRouteOptimizer').parent().addClass('active');
            $('#modePoiViewer').parent().removeClass('active');
            $('#modeNestHelper').parent().removeClass('active');
            break;

        case 'modePoiViewer':
            $('#circleSize').parent('.input-group').hide();
            $('#optimizationAttempts').parent('.input-group').hide();
            $('#showPokestops').parent().parent().parent().show();
            $('#showGyms').parent().parent().parent().show();
            $('#showSpawnpoints').parent().parent().parent().show();
            $('#showUnknownPOIs').parent().parent().parent().show();

            $('#fetchNests').parent().hide();
            $('#generateRoute').parent().hide();
            $('#generateOptimizedRoute').parent().hide();

            $('#modeRouteGenerator').parent().removeClass('active');
            $('#modeRouteOptimizer').parent().removeClass('active');
            $('#modePoiViewer').parent().addClass('active');
            $('#modeNestHelper').parent().removeClass('active');
            break;

        case 'modeNestHelper':
            $('#circleSize').parent('.input-group').hide();
            $('#optimizationAttempts').parent('.input-group').hide();
            $('#showPokestops').parent().parent().parent().show();
            $('#showGyms').parent().parent().parent().show();
            $('#showSpawnpoints').parent().parent().parent().show();
            $('#showUnknownPOIs').parent().parent().parent().hide();

            $('#fetchNests').parent().show();
            $('#generateRoute').parent().show();
            $('#generateOptimizedRoute').parent().show();

            $('#modeRouteGenerator').parent().removeClass('active');
            $('#modeRouteOptimizer').parent().removeClass('active');
            $('#modePoiViewer').parent().removeClass('active');
            $('#modeNestHelper').parent().addClass('active');
            break;
    }
}

function setMapMode(){
    setOptionsDisplay();

    switch (settings.mapMode) {
        case 'modeRouteGenerator':
            $('#showUnknownPOIs').parent().removeClass('active');
            $('#showUnknownPOIs').parent().addClass('active');
            settings.showUnknownPOIs = false;

            map.addControl(drawControl);
            buttonModalImportPolygon.addTo(map);
            buttonModalImportInstance.addTo(map);
            buttonTrash.addTo(map);
            buttonModalOutput.addTo(map);
            break;

        case 'modeRouteOptimizer':
            $('#showUnknownPOIs').parent().removeClass('active');
            $('#showUnknownPOIs').parent().addClass('active');
            settings.showUnknownPOIs = false;

            map.addControl(drawControl);
            buttonModalImportPolygon.addTo(map);
            buttonModalImportInstance.removeFrom(map);
            buttonTrash.addTo(map);
            buttonModalOutput.addTo(map);
            break;

        case 'modePoiViewer':
            editableLayers.clearLayers();
            circleLayers.clearLayers();

            map.removeControl(drawControl);
            buttonModalImportPolygon.removeFrom(map);
            buttonModalImportInstance.removeFrom(map);
            buttonTrash.removeFrom(map);
            buttonModalOutput.removeFrom(map);
            break;

        case 'modeNestHelper':
            $('#show-gyms').parent().removeClass('active');
            $('#hide-gyms').parent().addClass('active');
            settings.showGyms = false;
            $('#show-pokestops').parent().removeClass('active');
            $('#hide-pokestops').parent().addClass('active');
            settings.showPokestops = false;

            map.addControl(drawControl);
            buttonModalImportPolygon.addTo(map);
            buttonModalImportInstance.addTo(map);
            buttonTrash.addTo(map);
            buttonModalOutput.addTo(map);
            break;
    }
    processSettings();
    loadData();
}

function generateOptimizedRoute() {
    circleLayers.clearLayers();

    var newCircle,
        currentLatLng,
        point;

    var points = [];

    editableLayers.eachLayer(function (layer) {
        var poly = layer.toGeoJSON();
        var line = turf.polygonToLine(poly);

        if (settings.showGyms == true) {
            gymLayers.eachLayer(function (layer) {
                currentLatLng = [layer.getLatLng().lat, layer.getLatLng().lng];
                point = turf.point([currentLatLng[1], currentLatLng[0]]);
                if (turf.inside(point, poly)) {
                    points.push({'latitude': point.geometry.coordinates[1], 'longitude': point.geometry.coordinates[0]});
                }
            });
        }
        if (settings.showPokestops == true) {
            pokestopLayers.eachLayer(function (layer) {
                currentLatLng = [layer.getLatLng().lat, layer.getLatLng().lng];
                point = turf.point([currentLatLng[1], currentLatLng[0]]);
                if (turf.inside(point, poly)) {
                    points.push({'latitude': point.geometry.coordinates[1], 'longitude': point.geometry.coordinates[0]});
                }
            });
        }
        if (settings.showSpawnpoints == true) {
            spawnpointLayers.eachLayer(function (layer) {
                currentLatLng = [layer.getLatLng().lat, layer.getLatLng().lng];
                point = turf.point([currentLatLng[1], currentLatLng[0]]);
                if (turf.inside(point, poly)) {
                    points.push({'latitude': point.geometry.coordinates[1], 'longitude': point.geometry.coordinates[0]});
                }
            });
        }
    });
    const data = {
		'get_optimization': true,
        'circle_size': settings.circleSize,
        'optimization_attempts': settings.optimizationAttempts,
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
                    radius: settings.circleSize
                });
                newCircle.addTo(circleLayers);
            });
        }
    });
}

function generateRoute() {
    circleLayers.clearLayers();

    var xMod = Math.sqrt(0.75);
    var yMod = Math.sqrt(0.568);


    editableLayers.eachLayer(function (layer) {
        var poly = layer.toGeoJSON();
        var line = turf.polygonToLine(poly);
        var newCircle;

        var currentLatLng = layer.getBounds().getNorthEast();

        var startLatLng = L.GeometryUtil.destination(currentLatLng, 90, settings.circleSize*1.5);
        var endLatLng = L.GeometryUtil.destination(L.GeometryUtil.destination(layer.getBounds().getSouthWest(), 270, settings.circleSize*1.5), 180, settings.circleSize);

        var row = 0;
        var heading = 270;
        var i = 0;
        while(currentLatLng.lat > endLatLng.lat) {

            do {
                var point = turf.point([currentLatLng.lng, currentLatLng.lat]);
                var distance = turf.pointToLineDistance(point, line, { units: 'meters' });
                if (distance <= settings.circleSize || distance == 0 || turf.inside(point, poly)) {
                    newCircle = L.circle(currentLatLng, {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5,
                        radius: settings.circleSize
                    });
                    newCircle.addTo(circleLayers);
                }
                currentLatLng = L.GeometryUtil.destination(currentLatLng, heading, (xMod*settings.circleSize*2));
                i++;
            }while((heading == 270 && currentLatLng.lng > endLatLng.lng) || (heading == 90 && currentLatLng.lng < startLatLng.lng));

            currentLatLng = L.GeometryUtil.destination(currentLatLng, 180, (yMod*settings.circleSize*2));

            rem = row%2;
            if (rem == 1) {
                heading = 270;
            } else {
                heading = 90;
            }
            currentLatLng = L.GeometryUtil.destination(currentLatLng, heading, (xMod*settings.circleSize)*3);
            row++;
        }
    });
}

function fetchNests() {
    circleLayers.clearLayers();
    editableLayers.clearLayers();

	const bounds = map.getBounds();
    const overpassApiEndpoint = 'http://overpass-api.de/api/interpreter';

    var queryBbox = [ // s, e, n, w
		bounds.getSouthWest().lat,
		bounds.getSouthWest().lng,
		bounds.getNorthEast().lat,
        bounds.getNorthEast().lng
    ].join(',');

    var queryDate = "2018-04-09T01:32:00Z";

    var queryOptions = [
        '[out:json]',
        '[bbox:' + queryBbox + ']',
        '[date:"' + queryDate + '"]'
    ].join('');

    var queryNestWays = [
        'way["leisure"="park"];',
        'way["leisure"="recreation_ground"];',
        'way["landuse"="recreation_ground"];'
    ].join('');

    //var overPassQuery = '[bbox:{{bbox}}][timeout:240][date:"2018-04-09T01:32:00Z"];(way["leisure"="park"];way["landuse"="recreation_ground"];way["leisure"="recreation_ground"];);out;>;out skel qt;'
    var overPassQuery = queryOptions + ';(' + queryNestWays + ')' + ';out;>;out skel qt;';

    $.ajax({
        url: overpassApiEndpoint,
        type: 'GET',
        dataType: 'json',
        data: {'data': overPassQuery},
        success: function (result) {
            var geoJsonFeatures = osmtogeojson(result);
            geoJsonFeatures.features.forEach(function(feature) {
                feature = turf.flip(feature);
                var polygon = L.polygon(feature.geometry.coordinates, {
                    clickable: false,
                    color: "#3388ff",
                    fill: true,
                    fillColor: null,
                    fillOpacity: 0.2,
                    opacity: 0.5,
                    stroke: true,
                    weight: 4
                });
                polygon.tags = {};
                polygon.tags.name = feature.properties.tags.name;
                polygon.addTo(editableLayers);
            });
        }
    });

    /* nestLayers.eachLayer(function (layer) {
        var poly = layer.toGeoJSON();
        var line = turf.polygonToLine(poly);

        pokestopLayers.eachLayer(function (layer) {
            currentLatLng = [layer.getLatLng().lat, layer.getLatLng().lng];
            point = turf.point([currentLatLng[1], currentLatLng[0]]);
            if (turf.inside(point, poly)) {
                points.push({'latitude': point.geometry.coordinates[1], 'longitude': point.geometry.coordinates[0]});
            }
        });

        spawnpointLayers.eachLayer(function (layer) {
            currentLatLng = [layer.getLatLng().lat, layer.getLatLng().lng];
            point = turf.point([currentLatLng[1], currentLatLng[0]]);
            if (turf.inside(point, poly)) {
                points.push({'latitude': point.geometry.coordinates[1], 'longitude': point.geometry.coordinates[0]});
            }
        });

        const data = {
            'get_spawndata': true,
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
                });
            }
        });
    }); */
}

function loadData() {

	const bounds = map.getBounds();

    const data = {
        'get_data': true,
		'min_lat': bounds.getSouthWest().lat,
		'max_lat': bounds.getNorthEast().lat,
		'min_lon': bounds.getSouthWest().lng,
		'max_lon': bounds.getNorthEast().lng,
		'show_gyms': settings.showGyms,
		'show_pokestops': settings.showPokestops,
		'show_spawnpoints': settings.showSpawnpoints,
        'show_unknownpois': settings.showUnknownPOIs
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

            if (result.gyms != null && settings.showGyms == true) {
                for (i=0;i<result.gyms.length;i++) {
                    var marker = L.circleMarker([result.gyms[i].lat, result.gyms[i].lon], {
                        color: 'red',
                        radius: 1,
                        opacity: 0.5
                    }).addTo(map);
                    marker.bindPopup("ID: " + result.gyms[i].id);
                    marker.addTo(gymLayers);
                }
            }

            if (result.pokestops != null && settings.showPokestops == true) {
                for (i=0;i<result.pokestops.length;i++) {
                    var marker = L.circleMarker([result.pokestops[i].lat, result.pokestops[i].lon], {
                        color: 'green',
                        radius: 1,
                        opacity: 0.5
                    }).addTo(map);
                    marker.bindPopup("ID: " + result.pokestops[i].id);
                    marker.addTo(pokestopLayers);
                }
            }

            if (result.spawnpoints != null && settings.showSpawnpoints == true) {
                for (i=0;i<result.spawnpoints.length;i++) {
                    var marker = L.circleMarker([result.spawnpoints[i].lat, result.spawnpoints[i].lon], {
                        color: 'blue',
                        radius: 1,
                        opacity: 0.5
                    }).addTo(map);
                    marker.bindPopup("ID: " + result.spawnpoints[i].id);
                    marker.addTo(spawnpointLayers);
                }
            }
        }
    });
}

function getInstance(instanceName = null) {
    if (instanceName === null) {
        //get names of all instances
        const data = {
            'get_instance_names': true,
        };
        const json = JSON.stringify(data);

        $.ajax({
            url: this.href,
            type: 'POST',
            dataType: 'json',
            data: {'data': json},
            success: function (result) {
                console.log(result);
                var select = $('#importInstanceName');
                select.empty();
                result.forEach(function(item) {
                    select.append($("<option>").attr('value',item.name).text(item.name + " (" + item.type + ")"));
                });
            }
        });
    } else {

        //get single instance
        const data = {
            'get_instance_data': true,
            'instance_name': instanceName
        };
        const json = JSON.stringify(data);

        $.ajax({
            url: this.href,
            type: 'POST',
            dataType: 'json',
            data: {'data': json},
            success: function (result) {
                circleLayers.clearLayers();
                    points = result.area;
                    if (points.length > 0 ) {
                        points.forEach(function(item) {
                            newCircle = L.circle(item, {
                                color: '#87CEFA',
                                fillOpacity: 0.5,
                                radius: settings.circleSize
                            });
                            newCircle.addTo(circleLayers);
                        });
                    }
            }
        });
    }
}

function processSettings() {
     const defaultSettings = {
        showGyms: true,
        showPokestops: true,
        showSpawnpoints: false,
        showUnknownPOIs: false,
        circleSize: 500,
        optimizationAttempts: 100,
        mapMode: 'modePoiViewer'
    }
    storedSettings = JSON.parse(localStorage.getItem('settings'));

    Object.keys(settings).forEach(function(key) {
        if (storedSettings !== null) {
            if (settings[key] === null) {
                settings[key] = storedSettings[key];
            }
        } else {
            settings[key] = defaultSettings[key];
        }
    });

    localStorage.setItem('settings', JSON.stringify(settings));

}

function csvtoarray(dataString) {
    var lines = dataString
        .split(/\n/)                     // Convert to one string per line
        .map(function(lineStr) {
            return lineStr.split(",");   // Convert each line to array (,)
        })
    return lines;
}

</script>

    </head>
    <body>
        <div id="map"></div>

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
                                    <input type="radio" name="mapMode" id="modeRouteGenerator" autocomplete="off"> Generate Path
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" name="mapMode" id="modeRouteOptimizer" autocomplete="off"> Optimize Path
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" name="mapMode" id="modePoiViewer" autocomplete="off"> View POIs
                                </label>
                                <label class="btn btn-primary">
                                    <input type="radio" name="mapMode" id="modeNestHelper" autocomplete="off"> Nest Report
                                </label>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Optimization Attempts:</span>
                            </div>
                            <input id="optimizationAttempts" name="optimizationAttempts" type="text" class="form-control" aria-label="Optimization attempts">
                            <div class="input-group-append">
                                <span class="input-group-text">Tries</span>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Circle Radius:</span>
                            </div>
                            <input id="circleSize" name="circleSize" type="text" class="form-control" aria-label="Circle Radius (in meters)">
                            <div class="input-group-append">
                                <span class="input-group-text">Meters</span>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="showGyms" id="showGyms" autocomplete="off"> On
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="showGyms" id="hideGyms" autocomplete="off"> Off
                                </label>
                            </div>
                            <div class="input-group-append">
                                <span style="padding: .375rem .75rem;">Show known gyms</span>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <div class="btn-group btn-group-toggle"data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="showPokestops" id="showPokestops" autocomplete="off"> On
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="showPokestops" id="hidePokestops" autocomplete="off"> Off
                                </label>
                            </div>
                            <div class="input-group-append" width>
                                <span style="padding: .375rem .75rem;">Show known pokestops</span>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="showSpawnpoints" id="showSpawnpoints" autocomplete="off"> On
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="showSpawnpoints" id="hideSpawnpoints" autocomplete="off"> Off
                                </label>
                            </div>
                            <div class="input-group-append">
                                <span style="padding: .375rem .75rem;">Show known spawn points</span>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="showUnknownPOIs" id="showUnknownPOIs" autocomplete="off"> On
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="showUnknownPOIs" id="hideUnknownPOIs" autocomplete="off"> Off
                                </label>
                            </div>
                            <div class="input-group-append">
                                <span style="padding: .375rem .75rem;">Show unnamed POIs only</span>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <button id="fetchNests" class="btn btn-secondary" type="button">Go!</button>
                            <div class="input-group-append">
                                <span style="padding: .375rem .75rem;">Retrieve nests in current map bounds</span>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <button id="generateRoute" class="btn btn-secondary" type="button">Go!</button>
                            <div class="input-group-append">
                                <span style="padding: .375rem .75rem;">Generate route</span>
                            </div>
                        </div>
                        <div class="input-group mb">
                            <button id="generateOptimizedRoute" class="btn btn-secondary" type="button">Go!</button>
                            <div class="input-group-append">
                                <span style="padding: .375rem .75rem;">Generate optimized route</span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="saveSettings" class="btn btn-primary" data-dismiss="modal">Close</button>
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
                            <textarea id="outputCircles" style="height:400px;" class="form-control" aria-label="Route output"></textarea>
                        </div>
                        <div class="btn-toolbar">
                            <div class="btn-group mr-2" role="group" aria-label="">
                                <button id="getOutput" class="btn btn-secondary float-left" type="button">Get output</button>
                            </div>
                            <div class="btn-group" role="group" aria-label="">
                                <button id="selectAllAndCopy" class="btn btn-secondary float-right" type="button">Copy to clipboard</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="modalImportInstance" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Instance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="importPolygonData">Select an instance:</label>
                        <div class="input-group mb">
                            <select name="importInstanceName" id="importInstanceName" class="form-control" aria-label="Select an insance to import">
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="importInstance" class="btn btn-primary">Import Instance</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="modalImportPolygon" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Polygon</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="importPolygonData">Polygon data:</label>
                        <div class="input-group mb-3">
                            <textarea name="importPolygonData" id="importPolygonData" style="height:400px;" class="form-control" aria-label="Polygon data"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="savePolygon" class="btn btn-primary">Import</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal modal-loader" id="modalLoading" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-sm">
                <div class="modal-content" style="width: 48px">
                    <span class="fa fa-spinner fa-spin fa-3x"></span>
                </div>
            </div>
        </div>
    </body>
</html><?php
}



function map_helper_init() {
    global $db;
    //db vars
    $DB_TYPE = "mysql";
    $DB_HOST = "1.2.3.4";
    $DB_USER = "rdmuser";
    $DB_PSWD = "pw";
    $DB_NAME = "rdmdb";
    $DB_PORT = 3306;

    $db = initDB($DB_HOST, $DB_USER, $DB_PSWD, $DB_NAME, $DB_PORT);

    $args = json_decode($_POST['data']);
    if ($args->get_spawndata === true) { getSpawnData($args); }
    if ($args->get_data === true) { getData($args); }
    if ($args->get_optimization === true) { getOptimization($args); }
    if ($args->get_instance_data === true) { getInstanceData($args); }
    if ($args->get_instance_names === true) { getInstanceNames($args); }

}

function getInstanceData($args) {
    global $db;
    $sql_spawnpoint = "SELECT data FROM instance WHERE name = ?";
    if ($stmt = $db->prepare($sql_spawnpoint)) {
        $stmt->bind_param("s", $args->instance_name);

        $stmt->execute();

        $result = $stmt->get_result();
        while ($data = $result->fetch_array()) {
            $instance = $data;
        }
    }
    echo $instance[0];
}
function getInstanceNames($args) {
    global $db;
    $sql_spawnpoint = "SELECT name, type FROM instance";
    if ($stmt = $db->prepare($sql_spawnpoint)) {

        $stmt->execute();

        $result = $stmt->get_result();
        while ($data = $result->fetch_array()) {
            $instances[] = $data;
        }
    }
    echo json_encode($instances);
}

function initDB($DB_HOST, $DB_USER, $DB_PSWD, $DB_NAME, $DB_PORT) {
    $db = new mysqli($DB_HOST, $DB_USER, $DB_PSWD, $DB_NAME, $DB_PORT);
    if ($db->connect_error != '') {
        exit("Failed to connect to MySQL server!");
    }
    $db->set_charset('utf8');
    return $db;
}

function getSpawnData($args) {
    global $db;
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
    echo json_encode(array('spawns' => $spawns));
}

function getData($args) {
    global $db;
    $show_unknown_mod = ($args->show_unknownpois === true ? "name IS null AND " : "");
    if ($args->show_gyms === true) {

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

    if ($args->show_pokestops === true) {
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

    if ($args->show_spawnpoints === true) {
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
}

function getOptimization($args) {
    global $db;

    //optimization vars
    $DELAY = 5;
    $GYM_COUNT = 6;

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

?>
