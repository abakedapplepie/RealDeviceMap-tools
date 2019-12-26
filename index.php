<?php
error_reporting(0);

include('./config.php');

if ($_POST['data']) { map_helper_init(); } else { ?><!DOCTYPE html>
<html>
  <head>
    <title>RDM-Tools</title>
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
      .nestName {
        min-width: 175px;
        text-align: center;
        font-weight: bold;
      }
      .buttonOff {
        background: #ccc;
      }
      .easy-button-container.disabled, .easy-button-button.disabled {
        display: none;
      }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-easybutton@2.0.0/src/easy-button.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-toolbar@0.4.0-alpha.1/dist/leaflet.toolbar.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.67.0/dist/L.Control.Locate.min.css" />
    <link rel="stylesheet" href="./leaflet-search.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.EasyButton/2.3.0/easy-button.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-geometryutil@0.9.0/src/leaflet.geometryutil.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@5/turf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/osmtogeojson@3.0.0-beta.3/osmtogeojson.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-toolbar@0.4.0-alpha.1/dist/leaflet.toolbar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/s2-geometry@1.2.10/src/s2geometry.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-path-drag@1.1.0/dist/L.Path.Drag.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.67.0/dist/L.Control.Locate.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="./en.js"></script>
    <script type="text/javascript" src="./de.js"></script>
    <script type="text/javascript" src="./fr.js"></script>
    <script type="text/javascript" src="./salesman.js"></script>
    <script type="text/javascript" src="./leaflet-search.js"></script>

<script type="text/javascript">
var debug = false;
//map and control vars
var map;
var manualCircle = false;
var newPOI = false;
var adBoundsLv = null;
var csvImport = null;
var copyOutput = null;
var subs = enSubs;
var drawControl,
  buttonManualCircle,
  buttonImportNests,
  buttonModalImportPolygon,
  buttonModalImportSubmissions,
  buttonModalImportInstance,
  buttonTrash,
  buttonTrashRoute,
  buttonGenerateRoute,
  buttonOptimizeRoute,
  buttonModalOutput,
  buttonMapModePoiViewer,
  buttonMapModeRouteGenerator,
  buttonShowGyms,
  buttonShowPokestops,
  buttonShowPokestopsRange,
  buttonShowSpawnpoints,
  buttonHideOldSpawnpoints,
  buttonShowUnknownPois,
  buttonSettingsModal,
  buttonClearSubs,
  buttonViewCells,
  buttonNewPOI,
  barShowPolyOpts,
  barOutput,
  barWayfarer,
  barRightOpts,
  barMapMode;
//data vars
var gyms = [],
  pokestops = [],
  pokestoprange = [],
  spawnpoints = [],
  spawnpoints_u = [];
//options vars
var settings = {
  showGyms: null,
  showPokestops: null,
  showPokestopsRange: null,
  showSpawnpoints: null,
  showUnknownPois: null,
  hideOldSpawnpoints: null,
  oldSpawnpointsTimestamp: null,
  circleSize: null,
  optimizationAttempts: null,
  nestMigrationDate: null,
  spawnReportLimit: null,
  mapMode: null,
  mapCenter: null,
  mapZoom: null,
  viewCells: null,
  cellsLevel0: null,
  cellsLevel0Check: false,
  cellsLevel1: null,
  cellsLevel1Check: false,
  cellsLevel2: null,
  cellsLevel2Check: false,
  s2CountPOI: false,
  tlLink: null,
  tlChoice: null,
  language: null
};
//map layer vars
var gymLayer,
  pokestopLayer,
  pokestopRangeLayer,
  spawnpointLayer,
  editableLayer,
  circleS2Layer,
  circleLayer,
  bgLayer,
  nestLayer,
  viewCellLayer,
  subsLayer;
$(function(){
  loadSettings();
  getLanguage();
  initMap();
  setMapMode();
  setShowMode();
  $('#nestMigrationDate').datetimepicker('sideBySide', true)
  $('#oldSpawnpointsTimestamp').datetimepicker('sideBySide', true)
  $('#savePolygon').on('click', function(event) {
    var polygonData = [];
    var importReady = true
    //TODO: add error handling
    //TODO: add check for json or txt
    var importType = $("#importPolygonForm input[name=importPolygonDataType]:checked").val()
     if (importType == 'importPolygonDataTypeCoordList') {
      polygonData.push(csvtoarray($('#importPolygonData').val().trim()));
      importReady = true;
    } else if (importType == 'importPolygonDataTypeGeoJson') {
      var geoJson = JSON.parse($('#importPolygonData').val());
      if (geoJson.type == 'FeatureCollection') {
        geoJson.features.forEach(function(feature) {
          if (feature.type == 'Feature' && feature.geometry.type == 'Polygon' && importReady == true) {
            polygonData.push(turf.flip(feature).geometry.coordinates);
            importReady = true;
          } else {
            importReady = false;
          }
        });
      } else {
        if (geoJson.type == 'Feature' && geoJson.geometry.type == 'Polygon') {
          polygonData.push(turf.flip(geoJson).geometry.coordinates);
          importReady = true;
        }
      }
    } else {
      importReady = false;
    }
    if (importReady = true) {
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
      polygonData.forEach(function(polygon) {
        var newPolygon = L.polygon(polygon, polygonOptions).addTo(editableLayer);
      });
    }
    $('#modalImport').modal('hide');
  });
  $('#saveNestPolygon').on('click', function(event) {
    //TODO: add error handling
    //TODO: add check for json or txt
    var importType = $("#importPolygonForm input[name=importPolygonDataType]:checked").val()
    if (importType == 'importPolygonDataTypeGeoJson') {
      geoJson = JSON.parse($('#importPolygonData').val());
      if (geoJson.type == 'FeatureCollection') {
        geoJson.features.forEach(function(feature) {
          feature = turf.flip(feature);
          var polygon = L.polygon(feature.geometry.coordinates, {
            clickable: false,
            color: "#ff8833",
            fill: true,
            fillColor: null,
            fillOpacity: 0.2,
            opacity: 0.5,
            stroke: true,
            weight: 4
          });
          polygon.tags = {};
          polygon.tags.name = feature.properties.name;
          polygon.addTo(nestLayer);
          polygon.bindPopup(function (layer) {
          if (typeof layer.tags.name !== 'undefined') {
            var name = '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">' + subs.nest + ': ' + layer.tags.name + '</span></div>';
          }
          var output = name +
                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm getSpawnReport" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.getSpawnReport + '</span></div></div>' +
                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.removeMap + '</span></div></div>' +
                  '<div class="input-group"><button class="btn btn-secondary btn-sm exportLayer" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.exportPolygon + '</span></div></div>';
          return output;
          });
        });
      }
    } 
    $('#modalImport').modal('hide');
  });
  $('#importSubmissions').on('click', function(event) {
    subsLayer.clearLayers();
    var pointsData = [];
    if (csvImport != null) {
      pointsData = csvtoarray(csvImport);
      csvImport = null;
      $('#csvOpener').val('');
    } else {
      pointsData = csvtoarray($('#importSubmissionsData').val().trim());
      $('#importSubmissionsData').val('');
    }
    var formatCheck = pointsData[0][0];
    if (formatCheck != 'id'){
      pointsData.forEach(function(item) {      
        var marker = L.marker([item[0], item[1]], {
          draggable: true
        }).bindPopup('<span>' + item[2] + '</span>').addTo(subsLayer);
        if ($('#submissionRangeCheck').is(':checked')) {
          marker.rangeID = addPOIRange(marker);
          marker.on('drag', function() {
            subsLayer.removeLayer(marker.rangeID);
            marker.rangeID = addPOIRange(marker);
          })
        };
      });
    } else if (formatCheck == 'id') {
      pointsData.shift();
      pointsData.forEach(function(item) {
        var marker = L.marker([item[4], item[5]], {
          draggable: true
        }).bindPopup('<div style="max-width: 150px;"><p align="center">' + item[2] + '</p><img src="' + item[10] + '" width="150px"></div>').addTo(subsLayer);
        if ($('#submissionRangeCheck').is(':checked')) {
          marker.rangeID = addPOIRange(marker);
          marker.on('drag', function() {
            subsLayer.removeLayer(marker.rangeID);
            marker.rangeID = addPOIRange(marker);
          })
        };
      });
    } else {
      alert('Something went horribly wrong');
    }
  });
  $('#importInstance').on('click', function(event) {
     var name = $("#importInstanceName" ).val();
     var color = $("#instanceColor" ).val();
     getInstance(name,color);
   });
  $('#getOptimizedRoute').on('click', function(event) {
    var optimizeForGyms = $('#optimizeForGyms').is(':checked');
    var optimizeForPokestops = $('#optimizeForPokestops').is(':checked');
    var optimizeForSpawnpoints = $('#optimizeForSpawnpoints').is(':checked');
    var optimizeForUnknownSpawnpoints = $('#optimizeForUnknownSpawnpoints').is(':checked');
    var optimizeNests = $('#optimizeNests').is(':checked');
    var optimizePolygons = $('#optimizePolygons').is(':checked');
    var optimizeCircles = $('#optimizeCircles').is(':checked');
    generateOptimizedRoute(optimizeForGyms, optimizeForPokestops, optimizeForSpawnpoints, optimizeForUnknownSpawnpoints, optimizeNests, optimizePolygons, optimizeCircles);
   });
  $('#getAdBounds').on('click', function(event) {
    if ($('#adBoundsLv6').is(':checked')) {
      adBoundsLv = '6';
    } else if ($('#adBoundsLv8').is(':checked')) {
      adBoundsLv = '8';
    } else {
      adBoundsLv = '9';
    }  
    getAdBounds();
   });
  $('#modalSpawnReport').on('hidden.bs.modal', function(event) {
    $('#spawnReportTable > tbody').empty();
    $('#spawnReportTableMissed > tbody').empty();
    $('#modalSpawnReport .modal-title').text();
  });
  $('#modalOutput').on('hidden.bs.modal', function(event) {
    $('#outputCircles').val('');
    $('#outputCirclesCount').val('');
    $('#outputAvgPt').val('');
    $(document.getElementById('copyCircleOutput')).text(subs.copyClipboard);
  });
  $('#modalSettings').on('hidden.bs.modal', function(event) {
    var tileset = null;
    var circleSize = $('#circleSize').val();
    var spawnReportLimit = $('#spawnReportLimit').val();
    var optimizationAttempts = $('#optimizationAttempts').val();
    var cellsLevel0 = $('#cellsLevel0').val();
    var cellsLevel0Check = $('#cellsLevel0Check').is(":checked");
    var cellsLevel1Check = $('#cellsLevel1Check').is(":checked");
    var cellsLevel2Check = $('#cellsLevel2Check').is(":checked");
    var s2CountPOICheck = $('#s2CountPOI').is(":checked");
    var nestMigrationDate = moment($("#nestMigrationDate").datetimepicker('date')).local().format('X');
    var oldSpawnpointsTimestamp = moment($("#oldSpawnpointsTimestamp").datetimepicker('date')).local().format('X');
    var oldTlChoice = settings.tlChoice;
    var tlChoice = $('#tlChoice').val();
    if (tlChoice == 'carto') {
      tileset = 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png';
    } else if (tlChoice == 'own') {
      tileset = '<?php echo OWN_TS ?>';
    } else if (tlChoice == 'osm') {
      tileset = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    }
    var oldLang = settings.language;
    var language = $('#language').val();
    const newSettings = {
      circleSize: circleSize,
      optimizationAttempts: optimizationAttempts,
      cellsLevel0: cellsLevel0,
      cellsLevel0Check: cellsLevel0Check,
      cellsLevel1: 14,
      cellsLevel1Check: cellsLevel1Check,
      cellsLevel2: 17,
      cellsLevel2Check: cellsLevel2Check,
      s2CountPOI: s2CountPOICheck,
      tlChoice: tlChoice,
      tlLink: tileset,
      nestMigrationDate: nestMigrationDate,
      oldSpawnpointsTimestamp: oldSpawnpointsTimestamp,
      spawnReportLimit: spawnReportLimit,
      language: language
    };
    Object.keys(newSettings).forEach(function(key) {
      if (settings[key] != newSettings[key]) {
        settings[key] = newSettings[key];
        storeSetting(key);
      }
    });
    if (settings.language != oldLang) {
      getLanguage();
      location.reload();
    }
    if (settings.tlChoice != oldTlChoice) {
      location.reload();
    }
    updateS2Overlay() 
  });
  $('#cancelSettings').on('click', function(event) {
    processSettings(true);
  });
  $("#copyCircleOutput").click(function () {
    document.getElementById('outputCircles').select();
    document.execCommand('copy');
    $(this).text(subs.copied);
  });
  $("#copyPolygonOutput").click(function () {
    document.getElementById(copyOutput).select();
    document.execCommand('copy');
    $(this).text(subs.copied);
  });
});
function initMap() {
  var attrOsm = 'Map data &copy; <a href="https://openstreetmap.org/">OpenStreetMap</a> contributors';
  var attrOverpass = 'POI via <a href="https://www.overpass-api.de/">Overpass API</a>';
  var osm = new L.TileLayer(
  settings.tlLink, {  
    attribution: [attrOsm, attrOverpass].join(', ')
  });
  map = L.map('map', {
    zoomDelta: 0.25,
    zoomSnap: 0.25,
    zoomControl: true,
    wheelPxPerZoomLevel: 30}).addLayer(osm).setView(settings.mapCenter, settings.mapZoom);
  circleLayer = new L.FeatureGroup();
  circleLayer.addTo(map);
  bgLayer = new L.FeatureGroup();
  bgLayer.addTo(map);
  editableLayer = new L.FeatureGroup();
  editableLayer.addTo(map);
  circleS2Layer = new L.FeatureGroup();
  circleS2Layer.addTo(map);
  gymLayer = new L.LayerGroup();
  gymLayer.addTo(map);
  pokestopLayer = new L.LayerGroup();
  pokestopLayer.addTo(map);
  pokestopRangeLayer = new L.LayerGroup();
  pokestopRangeLayer.addTo(map);
  spawnpointLayer = new L.LayerGroup();
  spawnpointLayer.addTo(map);
  viewCellLayer = new L.LayerGroup();
  viewCellLayer.addTo(map);
  pokestopCellLayer = new L.LayerGroup();
  pokestopCellLayer.addTo(map);
  spawnpointCellLayer = new L.LayerGroup();
  spawnpointCellLayer.addTo(map);
  nestLayer = new L.LayerGroup();
  nestLayer.addTo(map);
  subsLayer = new L.LayerGroup();
  subsLayer.addTo(map);
  
  // Buttons left
  searchControl = new L.Control.Search({
    url: 'https://nominatim.openstreetmap.org/search?format=json&q={s}',
    jsonpParam: 'json_callback',
    propertyName: 'display_name',
    propertyLoc: ['lat','lon'],
    marker: false,
    autoCollapse: true,
    autoType: false,
    minLength: 2
  }).addTo(map);
  buttonLocate = L.control.locate({
    id: 'getOwnLocation',
    position: 'topleft',
    title: subs.getOwnLocation,
    setView: 'once',
    drawCircle: false,
    drawMarker: false,
    icon: 'fas fa-crosshairs'
  }).addTo(map);
  drawControl = new L.Control.Draw({
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
      featureGroup: editableLayer,
      edit: true,
      remove: false,
      poly: false
    }
  }).addTo(map);

  // barShowPolyOpts
  buttonManualCircle = L.easyButton({
    states: [{
      stateName: 'enableManualCircle',
      icon: 'far fa-circle',
      title: subs.enableManualCircle,
      onClick: function (btn) {
        manualCircle = true;
        btn.state('disableManualCircle');
      }
    }, {
      stateName: 'disableManualCircle',
      icon: 'fas fa-circle',
      title: subs.disableManualCircle,
      onClick: function (btn) {
        manualCircle = false;
        btn.state('enableManualCircle');
      }
    }]
  });
  buttonImportNests = L.easyButton({
    states: [{
      stateName: 'openImportNestsModal',
      icon: 'fas fa-tree',
      title: subs.importOSM,
      onClick: function (control){
        getNests();
      }
    }]
  });
  buttonImportAdBounds = L.easyButton({
    states: [{
      stateName: 'openImportAdBoundsModal',
      icon: 'far fa-map',
      title: subs.importAdBounds,
      onClick: function (control){
        $('#modalAdBounds').modal('show');
      }
    }]
  });
  buttonModalImportPolygon = L.easyButton({
    states: [{
      stateName: 'openImportPolygonModal',
      icon: 'fas fa-draw-polygon',
      title: subs.importPolygon,
      onClick: function (control){
        $('#modalImportPolygon').modal('show');
      }
    }]
  });
  buttonModalImportInstance = L.easyButton({
    states: [{
      stateName: 'openImportInstanceModal',
      icon: 'fas fa-truck-loading',
      title: subs.importInstance,
      onClick: function (control){
        getInstance();
        $('#modalImportInstance').modal('show');
      }
    }]
  });
  buttonTrashRoute = L.easyButton({
    states: [{
      stateName: 'clearMapRoute',
      icon: 'fas fa-times-circle',
      title: subs.clearRoute,
      onClick: function (control){
        circleLayer.clearLayers();
      }
    }]
  });
  barShowPolyOpts = L.easyBar([buttonManualCircle, buttonImportNests, buttonImportAdBounds, buttonModalImportPolygon, buttonModalImportInstance, buttonTrashRoute], { position: 'topleft' }).addTo(map);
  
  // barOutput
  buttonGenerateRoute = L.easyButton({
    id: 'generateRoute',
    states:[{
      stateName: 'generateRoute',
      icon: 'fas fa-cookie',
      title: subs.generateRoute,
      onClick: function (btn) {
        generateRoute();
      }
    }]
  });
  buttonOptimizeRoute = L.easyButton({
    id: 'optimizeRoute',
    states:[{
      stateName: 'optimizeRoute',
      icon: 'fas fa-cookie-bite',
      title: subs.generateOptimizedRoute,
      onClick: function (btn) {
        $('#modalOptimize').modal('show');
      }
    }]
  });
  buttonModalOutput = L.easyButton({
    states: [{
      stateName: 'openOutputModal',
      icon: 'far fa-clipboard',
      title: subs.getOutput,
      onClick: function (control){
        $('#modalOutput').modal('show');
      }
    }]
  });
  barOutput = L.easyBar([buttonGenerateRoute, buttonOptimizeRoute, buttonModalOutput], { position: 'topleft' }).addTo(map);

  // barWayfarer
  buttonModalImportSubmissions = L.easyButton({
    states: [{
      stateName: 'openImportSubmissionsModal',
      icon: 'far fa-dot-circle',
      title: subs.importSubmissions,
      onClick: function (control){
        $('#modalImportSubmissions').modal('show');
      }
    }]
  });
  buttonNewPOI = L.easyButton({
    states: [{
      stateName: 'enableNewPOI',
      icon: 'fas fa-map-marker-alt',
      title: subs.enableNewPOI,
      onClick: function (btn) {
        newPOI = true;
        btn.state('disableNewPOI');
      }
    }, {
      stateName: 'disableNewPOI',
      icon: 'fas fa-map-marker',
      title: subs.disableNewPOI,
      onClick: function (btn) {
        newPOI = false;
        btn.state('enableNewPOI');
      }
    }]
  });
  buttonClearSubs = L.easyButton({
    states: [{
      stateName: 'clearSubs',
      icon: 'fas fa-times-circle',
      title: subs.clearSubs,
      onClick: function (control){
        subsLayer.clearLayers();
      }
    }]
  });
  buttonViewCells = L.easyButton({
    id: 'viewCells',
    states: [{
      stateName: 'enableViewCells',
      icon: 'fas fa-square',
      title: subs.hideViewingCells,
      onClick: function (btn) {
        settings.viewCells = false;
        storeSetting('viewCells');
        setShowMode();
        }
    }, {
      stateName: 'disableViewCells',
      icon: 'far fa-square',
      title: subs.showViewingCells,
      onClick: function (btn) {
        settings.viewCells = true;
        storeSetting('viewCells');
        setShowMode();
      }
    }]
  });
  barWayfarer = L.easyBar([buttonModalImportSubmissions, buttonNewPOI, buttonClearSubs, buttonViewCells], { position: 'topleft' }).addTo(map);

  // Buttons right
  // Bar mapMode
  buttonMapModePoiViewer = L.easyButton({
    id: 'enableMapModePoiViewer',
    states: [{
      stateName: 'enableMapModePoiViewer',
      icon: 'fas fa-binoculars',
      title: subs.poiViewer,
      onClick: function (btn) {
        settings.mapMode = 'PoiViewer';
        storeSetting('mapMode');
        setMapMode();
      }
    }]
  });
  buttonMapModeRouteGenerator = L.easyButton({
    id: 'enableMapModeRouteGenerator',
    states: [{
      stateName: 'enableMapModeRouteGenerator',
      icon: 'fas fa-route',
      title: subs.routeGenerator,
      onClick: function (btn) {
        settings.mapMode = 'RouteGenerator';
        storeSetting('mapMode');
        setMapMode();
      }
    }]
  });
  barMapMode = L.easyBar([buttonMapModeRouteGenerator, buttonMapModePoiViewer], { position: 'topright' }).addTo(map);

  //Bar showPOIs
  buttonShowGyms = L.easyButton({
    id: 'showGyms',
    states: [{
      stateName: 'enableShowGyms',
      icon: 'fas fa-dumbbell',
      title: subs.hideGyms,
      onClick: function (btn) {
        settings.showGyms = false;
        storeSetting('showGyms');
        setShowMode();
        }
    }, {
      stateName: 'disableShowGyms',
      icon: 'fas fa-dumbbell',
      title: subs.showGyms,
      onClick: function (btn) {
        settings.showGyms = true;
        storeSetting('showGyms');
        setShowMode();
      }
    }]
  });
  buttonShowPokestops = L.easyButton({
    id: 'showPokestops',
    states: [{
      stateName: 'enableShowPokestops',
      icon: 'fas fa-map-pin',
      title: subs.hidePokestops,
      onClick: function (btn) {
        settings.showPokestops = false;
        storeSetting('showPokestops');
        setShowMode();
      }
    }, {
      stateName: 'disableShowPokestops',
      icon: 'fas fa-map-pin',
      title: subs.showPokestops,
      onClick: function (btn) {
        settings.showPokestops = true;
        storeSetting('showPokestops');
        setShowMode();
      }
    }]
  });
  buttonShowPokestopsRange = L.easyButton({
    id: 'showPokestopsRange',
    states: [{
      stateName: 'enableShowPokestopsRange',
      icon: 'fas fa-layer-group',
      title: subs.hidePokestopRange,
      onClick: function (btn) {
        settings.showPokestopsRange = false;
        storeSetting('showPokestopsRange');
        setShowMode();
      }
    }, {
      stateName: 'disableShowPokestopsRange',
      icon: 'fas fa-layer-group',
      title: subs.showPokestopRange,
      onClick: function (btn) {
        settings.showPokestopsRange = true;
        storeSetting('showPokestopsRange');
        setShowMode();
      }
    }]
  });
  buttonShowSpawnpoints = L.easyButton({
    id: 'showSpawnpoints',
    states:[{
      stateName: 'enableShowSpawnpoints',
      icon: 'fas fa-paw',
      title: subs.hideSpawnpoints,
      onClick: function (btn) {
        settings.showSpawnpoints = false;
        storeSetting('showSpawnpoints');
        setShowMode();
      }
    }, {
      stateName: 'disableShowSpawnpoints',
      icon: 'fas fa-paw',
      title: subs.showSpawnpoints,
      onClick: function (btn) {
        settings.showSpawnpoints = true;
        storeSetting('showSpawnpoints');
        setShowMode();
      }
    }]
  });
  buttonHideOldSpawnpoints = L.easyButton({
    id: 'hideOldSpawnpoints',
    states:[{
      stateName: 'enableHideOldSpawnpoints',
      icon: 'fas fa-history',
      title: subs.hideOldSpawnpoints,
      onClick: function (btn) {
        settings.hideOldSpawnpoints = false;
        storeSetting('hideOldSpawnpoints');
        setShowMode();
      }
    }, {
      stateName: 'disableHideOldSpawnpoints',
      icon: 'fas fa-history',
      title: subs.showOldSpawnpoints,
      onClick: function (btn) {
        settings.hideOldSpawnpoints = true;
        storeSetting('hideOldSpawnpoints');
        setShowMode();
      }
    }]
  })
  buttonShowUnknownPois = L.easyButton({
    id: 'showUnknownPois',
    states:[{
      stateName: 'enableShowUnknownPois',
      icon: 'fas fa-question-circle',
      title: subs.showAllPOIS,
      onClick: function (btn) {
        settings.showUnknownPois = false;
        storeSetting('showUnknownPois');
        setShowMode();
      }
    }, {
      stateName: 'disableShowUnknownPois',
      icon: 'fas fa-question-circle',
      title: subs.showUnknownPOIS,
      onClick: function (btn) {
        settings.showUnknownPois = true;
        storeSetting('showUnknownPois');
        setShowMode();
      }
    }]
  });
  barShowPOIs = L.easyBar([buttonShowGyms, buttonShowPokestops, buttonShowPokestopsRange, buttonShowSpawnpoints, buttonHideOldSpawnpoints, buttonShowUnknownPois], { position: 'topright' }).addTo(map);

  // Bar rightOpts
  buttonTrash = L.easyButton({
    states: [{
      stateName: 'clearMap',
      icon: 'fas fa-trash',
      title: subs.clearShapes,
      onClick: function (control){
        bgLayer.clearLayers();
        circleLayer.clearLayers();
        editableLayer.clearLayers();
        nestLayer.clearLayers();
        subsLayer.clearLayers();
      }
    }]
  });
  buttonTrash.button.style.backgroundColor = '#B7E9B7';
  buttonModalSettings = L.easyButton({
    position: 'topright',
    states: [{
      stateName: 'openSettingsModal',
      icon: 'fas fa-cog',
      title: subs.openSettings,
      onClick: function (control){
        if (settings.circleSize != null) {
          $('#circleSize').val(settings.circleSize);
        } else {
          $('#circleSize').val('500');
        }
        if (settings.spawnReportLimit != null) {
          $('#spawnReportLimit').val(settings.spawnReportLimit);
        } else {
          $('#spawnReportLimit').val('0');
        }
        if (settings.optimizationAttempts != null) {
          $('#optimizationAttempts').val(settings.optimizationAttempts);
        } else {
          $('#optimizationAttempts').val('10');
        }
        if (settings.cellsLevel0 != null) {
          $('#cellsLevel0').val(settings.cellsLevel0);
        } else {
          $('#cellsLevel0').val();
        }
        if (settings.nestMigrationDate != null) {
          $('#nestMigrationDate').datetimepicker('date', moment.unix(settings.nestMigrationDate).utc().local().format('MM/DD/YYYY HH:mm'));
        }
        if (settings.oldSpawnpointsTimestamp != null) {
          $('#oldSpawnpointsTimestamp').datetimepicker('date', moment.unix(settings.oldSpawnpointsTimestamp).utc().local().format('MM/DD/YYYY HH:mm'));
        }
        if (settings.language != null) {
          $('#language').val(settings.language);
        } else {
          $('#language').val('en');
        }
        if (settings.tlChoice != null) {
          $('#tlChoice').val(settings.tlChoice);
        } else {
          $('#tlChoice').val('osm');
        }
        $('#modalSettings').modal('show');
      }
    }]
  });
  buttonModalSettings.button.style.backgroundColor = '#B7E9B7';
  barRightOpts = L.easyBar([buttonTrash, buttonModalSettings], { position: 'topright' }).addTo(map);

  map.on('draw:drawstart', function(e) {
    manualCircle = false;
    newPOI = false;
    buttonManualCircle.state('enableManualCircle');
    buttonNewPOI.state('enableNewPOI');
  });

  map.on('draw:created', function (e) {
    var layer = e.layer;
    layer.addTo(editableLayer);
  });
  nestLayer.on('layeradd', function(e) {
    var layer = e.layer;
    layer.bindPopup(function (layer) {
      if (typeof layer.tags.name !== 'undefined') {
        var name = '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">' + subs.nest + ': ' + layer.tags.name + '</span></div>';
      }
      var output = name +
        '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">' + subs.polygon + '</span></div>' +
        '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm getSpawnReport" data-layer-container="nestLayer" data-layer-id=' +
        layer._leaflet_id +
        ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.getSpawnReport + '</span></div></div>' +
        '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="nestLayer" data-layer-id=' +
        layer._leaflet_id +
        ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.removeMap + '</span></div></div>' +
        '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm exportLayer" data-layer-container="nestLayer" data-layer-id=' +
        layer._leaflet_id +
        ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.exportPolygon + '</span></div></div>' +
        '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm exportPoints" data-layer-container="nestLayer" data-layer-id=' +
        layer._leaflet_id +
        ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.exportVP + '</span></div></div>' +
        '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm countPoints" data-layer-container="nestLayer" data-layer-id=' +
        layer._leaflet_id +
        ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.countVP + '</span></div></div>';
      return output;
    });
  });
  editableLayer.on('layeradd', function(e) {
    var layer = e.layer;
    layer.bindPopup(function (layer) {
      var output = '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">' + subs.polygon + '</span></div>' +
                   '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm getSpawnReport" data-layer-container="editableLayer" data-layer-id=' +
                   layer._leaflet_id +
                   ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.getSpawnReport + '</span></div></div>' +
                   '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="editableLayer" data-layer-id=' +
                   layer._leaflet_id +
                   ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.removeMap + '</span></div></div>' +
                   '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm exportLayer" data-layer-container="editableLayer" data-layer-id=' +
                   layer._leaflet_id +
                   ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.exportPolygon + '</span></div></div>' +
                   '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm exportPoints" data-layer-container="editableLayer" data-layer-id=' +
                   layer._leaflet_id +
                   ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.exportVP + '</span></div></div>' +
                   '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm countPoints" data-layer-container="editableLayer" data-layer-id=' +
                   layer._leaflet_id +
                   ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.countVP + '</span></div></div>';
      return output;
    });
  });
  circleLayer.on('layerremove', function(e) {
    var layer = e.layer;
    layer.s2cells.forEach(function(item) {
      circleS2Layer.removeLayer(parseInt(item));
    });
  });
  circleLayer.on('layeradd', function(e) {
    drawCircleS2Cells(e.layer);
    circleLayer.removeFrom(map).addTo(map);
    e.layer.on('drag', function() {
      drawCircleS2Cells(e.layer)
    })
    e.layer.on('drag', function() {
    circleLayer.removeFrom(map).addTo(map);
    })
  });
  map.on('moveend', function() {
    settings.mapCenter = map.getCenter();
    storeSetting('mapCenter');
    settings.mapZoom = map.getZoom();
    storeSetting('mapZoom');
    loadData();
  });
  map.on('click', function(e) {
    if (manualCircle === true) {
      if(settings.circleSize != 70){
      var newCircle = new L.circle(e.latlng, {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.4,
        draggable: true,
        radius: settings.circleSize
      }).bindPopup(function (layer) {
        return '<button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayer" data-layer-id=' + layer._leaflet_id + ' type="button">' + subs.delete + '</button></div>';
      }).addTo(circleLayer);
      }else{
      var newCircle = new L.circle(e.latlng, {
        color: 'red',
        fillColor: 'red',
        fillOpacity: 0.2,
        draggable: true,
        radius: 70
      }).bindPopup(function (layer) {
        return '<button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayer" data-layer-id=' + layer._leaflet_id + ' type="button">' + subs.delete + '</button></div>';
      }).addTo(circleLayer);
    }
    
    }
  });
  subsLayer.on('layerremove', function(e) {
    var layer = e.layer;
    layer.forEach(function(item) {
      subsLayer.removeLayer(parseInt(item));
    });
  });

  map.on('click', function(e) {
    if (newPOI === true) {
      let marker = L.marker(e.latlng, {
        draggable: true
      }).bindPopup(function (layer) {
        return '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="subsLayer" data-layer-id=' + layer._leaflet_id + ' type="button">' + subs.delete + '</button><button class="btn btn-secondary btn-sm exportPOIs" data-layer-container="subsLayer" data-layer-id=' + layer._leaflet_id + ' type="button">' + subs.exportPOIs + '</button></div>'}).addTo(subsLayer);
      marker.rangeID = addPOIRange(marker);
      marker.on('drag', function() {
        subsLayer.removeLayer(marker.rangeID);
        marker.rangeID = addPOIRange(marker);
      })
    };
  });
}
function addPOIRange (layer) {
  let range = L.circle(layer.getLatLng(), {
    color: 'black',
    fillColor: 'red',
    radius: 20,
    weight: 1,
    opacity: 1,
    fillOpacity: 0.3
  }).addTo(subsLayer);
  return range._leaflet_id;
}
function drawCircleS2Cells(layer) {
  if (typeof layer.s2cells !== 'undefined') {
    layer.s2cells.forEach(function(item) {
      circleS2Layer.removeLayer(parseInt(item));
    });
  }
  var center = layer.getLatLng()
  var radius = layer.getRadius();
  layer.s2cells = [];
  function addPoly(cell) {
    const vertices = cell.getCornerLatLngs()
    const poly = L.polygon(vertices,{
      color: 'black',
      opacity: 0.8,
      weight: 2,
      fillOpacity: 0.0
    });
    var line = turf.polygonToLine(poly.toGeoJSON());
    var point = turf.point([center.lng, center.lat]);
    var distance = turf.pointToLineDistance(point, line, { units: 'meters' });
    if (distance <= radius) {
      circleS2Layer.addLayer(poly);
      layer.s2cells.push(poly._leaflet_id);
    }
  }
  if (radius < 1000 && radius > 200) {
    var count = 10;
    let cell = S2.S2Cell.FromLatLng(layer.getLatLng(), 15)
    let steps = 1
    let direction = 0
    do {
        for (let i = 0; i < 2; i++) {
            for (let i = 0; i < steps; i++) {
                addPoly(cell)
                cell = cell.getNeighbors()[direction % 4]
            }
            direction++
        }
        steps++
    } while (steps < count)
  }
}
function setShowMode() {
  if (settings.showGyms !== false) {
    buttonShowGyms.state('enableShowGyms');
    buttonShowGyms.button.style.backgroundColor = '#B7E9B7';
  } else {
    gymLayer.clearLayers();
    buttonShowGyms.state('disableShowGyms');
    buttonShowGyms.button.style.backgroundColor = '#E9B7B7';
  }
  if (settings.showPokestops !== false) {
    buttonShowPokestops.state('enableShowPokestops');
    buttonShowPokestops.button.style.backgroundColor = '#B7E9B7';
  } else {
    pokestopLayer.clearLayers();
    buttonShowPokestops.state('disableShowPokestops');
    buttonShowPokestops.button.style.backgroundColor = '#E9B7B7';
  }
  if (settings.showPokestopsRange !== false) {
    buttonShowPokestopsRange.state('enableShowPokestopsRange');
    buttonShowPokestopsRange.button.style.backgroundColor = '#B7E9B7';
  } else {
    pokestopRangeLayer.clearLayers();
    buttonShowPokestopsRange.state('disableShowPokestopsRange');
    buttonShowPokestopsRange.button.style.backgroundColor = '#E9B7B7';
  }
  if (settings.showSpawnpoints !== false) {
    buttonShowSpawnpoints.state('enableShowSpawnpoints');
    buttonShowSpawnpoints.button.style.backgroundColor = '#B7E9B7';
  } else {
    spawnpointLayer.clearLayers();
    buttonShowSpawnpoints.state('disableShowSpawnpoints');
    buttonShowSpawnpoints.button.style.backgroundColor = '#E9B7B7';
  }
  if (settings.hideOldSpawnpoints !== false) {
    buttonHideOldSpawnpoints.state('enableHideOldSpawnpoints');
    buttonHideOldSpawnpoints.button.style.backgroundColor = '#B7E9B7';
  } else {
    spawnpointLayer.clearLayers();
    buttonHideOldSpawnpoints.state('disableHideOldSpawnpoints');
    buttonHideOldSpawnpoints.button.style.backgroundColor = '#E9B7B7';
  }
  if (settings.showUnknownPois !== false) {
    buttonShowUnknownPois.state('enableShowUnknownPois');
    buttonShowUnknownPois.button.style.backgroundColor = '#B7E9B7';
  } else {
    buttonShowUnknownPois.state('disableShowUnknownPois');
    buttonShowUnknownPois.button.style.backgroundColor = '#E9B7B7';
  }
  if (settings.viewCells !== false) {
    buttonViewCells.state('enableViewCells');
  } else {
    buttonViewCells.state('disableViewCells');
  }
  loadData();
}
function setMapMode(){
  switch (settings.mapMode) {
    case 'RouteGenerator':
      buttonMapModePoiViewer.button.style.backgroundColor = '#E9B7B7';
      buttonMapModeRouteGenerator.button.style.backgroundColor = '#B7E9B7';
      $('.leaflet-draw').show();
      barShowPolyOpts.enable();
      barOutput.enable();
      barWayfarer.disable();
      newPOI = false;
      break;
    case 'PoiViewer':
      buttonMapModePoiViewer.button.style.backgroundColor = '#B7E9B7';
      buttonMapModeRouteGenerator.button.style.backgroundColor = '#E9B7B7';
      $('.leaflet-draw').hide();
      barShowPolyOpts.disable();
      barOutput.disable();
      barWayfarer.enable();
      manualCircle = false;
      break;
  }
}
function getInstance(instanceName = null, color = '#1090fa') {
  if (instanceName === null) {
    //get names of all instances
    const data = {
      'get_instance_names': true,
    };
    const json = JSON.stringify(data);
  if (debug !== false) { console.log(json) }
    $.ajax({
      url: this.href,
      type: 'POST',
      dataType: 'json',
      data: {'data': json},
      success: function (result) {
    if (debug !== false) { console.log(result) }
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
    if (debug !== false) { console.log(json) }
    $.ajax({
      url: this.href,
      type: 'POST',
      dataType: 'json',
      data: {'data': json},
      success: function (result) {
        points = result.data.area;
        if (points.length > 0 ) {
          if (result.type == 'circle_pokemon' || result.type == 'circle_raid') {
            points.forEach(function(item) {
             if ($('#instanceMode').is(':checked')) {
              newCircle = L.circle(item, {
                color: '#b410fa',
                fillOpacity: 0.4,
                draggable: false,
                radius: settings.circleSize
              }).addTo(bgLayer);
             } else {
              newCircle = L.circle(item, {
                color: color,
                fillOpacity: 0.5,
                draggable: true,
                radius: settings.circleSize
              }).bindPopup(function (layer) {
                return '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayer" data-layer-id=' + layer._leaflet_id + ' type="button">' + subs.delete + '</button></div>';
              }).addTo(circleLayer);
             }

            });
          } else if (result.type == 'auto_quest' || result.type == 'pokemon_iv') {
            points.forEach(function(coords) {
              newPolygon = L.polygon(coords, polygonOptions).addTo(editableLayer);
            });
          }
        }
      }
    });
  }
}
function generateOptimizedRoute(optimizeForGyms, optimizeForPokestops, optimizeForSpawnpoints, optimizeForUnknownSpawnpoints, optimizeNests, optimizePolygons, optimizeCircles) {
  $("#modalLoading").modal('show');
  var newCircle,
    currentLatLng,
    point;
  var pointsOut = [];
  var data = {
    'get_optimization': true,
    'circle_size': settings.circleSize,
    'optimization_attempts': settings.optimizationAttempts,
    'do_tsp': false,
    'points': []
  };
  var routeLayers = function(layer) {
    var points = [];
    var poly = layer.toGeoJSON();
    var line = turf.polygonToLine(poly);
    if (optimizeForGyms == true) {
      gyms.forEach(function(item) {
        point = turf.point([item.lng, item.lat]);
        if (turf.inside(point, poly)) {
          points.push(item)
        }
      });
    }
    if (optimizeForPokestops == true) {
      pokestops.forEach(function(item) {
        point = turf.point([item.lng, item.lat]);
        if (turf.inside(point, poly)) {
          points.push(item)
        }
      });
    }
    if (optimizeForSpawnpoints == true) {
      spawnpoints.forEach(function(item) {
        point = turf.point([item.lng, item.lat]);
        if (turf.inside(point, poly)) {
          points.push(item)
        }
      });
    }
    if (optimizeForUnknownSpawnpoints == true) {
      spawnpoints_u.forEach(function(item) {
        point = turf.point([item.lng, item.lat]);
        if (turf.inside(point, poly)) {
          points.push(item)
        }
      });
    }
    if(points.length > 0) {
      getRoute(points);
    }
  }
  var routeCircles = function(layer) {
    var points = []
    var radius = layer.getRadius();
    var bounds = layer.getBounds();
    var center = bounds.getCenter();
    if (optimizeForGyms == true) {
      gyms.forEach(function(item) {
        var workingLatLng = L.latLng(item.lat, item.lng);
        var distance = workingLatLng.distanceTo(center)
        if (distance <= radius) {
          points.push(item);
        }
      });
    }
    if (optimizeForPokestops == true) {
      pokestops.forEach(function(item) {
        var workingLatLng = L.latLng(item.lat, item.lng);
        var distance = workingLatLng.distanceTo(center)
        if (distance <= radius) {
          points.push(item);
        }
      });
    }
    if (optimizeForSpawnpoints == true) {
      spawnpoints.forEach(function(item) {
        var workingLatLng = L.latLng(item.lat, item.lng);
        var distance = workingLatLng.distanceTo(center)
        if (distance <= radius) {
          points.push(item);
        }
      });
    }
    if (optimizeForUnknownSpawnpoints == true) {
      spawnpoints_u.forEach(function(item) {
        var workingLatLng = L.latLng(item.lat, item.lng);
        var distance = workingLatLng.distanceTo(center)
        if (distance <= radius) {
          points.push(item);
        }
      });
    }
    if(points.length > 0) {
      return points;
    }
  }
  var getRoute = function(points) {
    data.points = _.uniq(points);
    const json = JSON.stringify(data);
    if (debug !== false) { console.log(data) }
    console.log(json)
    $.ajax({
      beforeSend: function() {
      },
      url: this.href,
      type: 'POST',
      dataType: 'json',
      data: {'data': json},
      success: function (result) {
        if (debug !== false) { console.log(result) }
          result.bestAttempt.forEach(function(point) {
           newCircle = L.circle([point.lat, point.lng], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            draggable: true,
            radius: settings.circleSize
          }).bindPopup(function (layer) {
            return '<button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayer" data-layer-id=' + layer._leaflet_id + ' type="button">' + subs.delete + '</button></div>';
          }).addTo(circleLayer);
        });
      },
      complete: function() { }
    });
  }
  if (optimizePolygons == true) {
    editableLayer.eachLayer(function (layer) {
       routeLayers(layer);
    });
  }
  if (optimizeNests == true) {
    nestLayer.eachLayer(function (layer) {
       routeLayers(layer);
    });
  }
  if (optimizeCircles == true) {
    circleLayer.eachLayer(function (layer) {
      pointsOut = pointsOut.concat(routeCircles(layer));
      circleLayer.removeLayer(layer);
    });
    getRoute(pointsOut);
  }
  $("#modalLoading").modal('hide');
}
function generateRoute() {
  circleLayer.clearLayers();
  var xMod = Math.sqrt(0.75);
  var yMod = Math.sqrt(0.568);
  var route = function(layer) {
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
            draggable: true,
            radius: settings.circleSize
          }).bindPopup(function (layer) {
            return '<button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayer" data-layer-id=' + layer._leaflet_id + ' type="button">' + subs.delete + '</button></div>';
          }).addTo(circleLayer);
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
  }
  editableLayer.eachLayer(function (layer) {
     route(layer);
  });
  nestLayer.eachLayer(function (layer) {
     route(layer);
  });
}
function prepareData(layerBounds) {
  spawnpoints = [];
  pokestops = [];
  gyms = [];
  let bounds;
  if (layerBounds != undefined) {
    bounds = layerBounds;
  } else if (circleLayer.getLayers().length > 1) {
    bounds = circleLayer.getBounds();
  } else {
    bounds = map.getBounds();
  }
  const data = {
    'get_data': true,
    'min_lat': bounds.getSouthWest().lat,
    'max_lat': bounds.getNorthEast().lat,
    'min_lng': bounds.getSouthWest().lng,
    'max_lng': bounds.getNorthEast().lng,
    'show_gyms': false,
    'show_pokestops': false,
    'show_spawnpoints': true,
    'show_unknownpois': false
  };
  const json = JSON.stringify(data);
  $.ajax({
    url: this.href,
    type: 'POST',
    async: false,
    dataType: 'json',
    data: {'data': json},
    success: function (result) {
      pokestops = result.pokestops;
      spawnpoints = result.spawnpoints;
      gyms = result.gyms;
    },
    error: function () {
      alert('Something went horribly wrong');
    }
  });
}
function getSpawnReport(layer) {
  var reportStops = [],
    reportSpawns = [];
  var poly = layer.toGeoJSON();
  var line = turf.polygonToLine(poly);
  pokestops.forEach(function(item) {
    point = turf.point([item.lng, item.lat]);
    if (turf.inside(point, poly)) {
      reportStops.push(item.id);
    }
  });
  spawnpoints.forEach(function(item) {
    point = turf.point([item.lng, item.lat]);
    if (turf.inside(point, poly)) {
      reportSpawns.push(item.id);
    }
  });
  const data = {
    'get_spawndata': true,
    'nest_migration_timestamp': settings.nestMigrationDate,
    'spawn_report_limit': settings.spawnReportLimit,
    'stops': reportStops,
    'spawns': reportSpawns
  };
  const json = JSON.stringify(data);
  if (debug !== false) { console.log(json) }
  $.ajax({
    beforeSend: function() {
      $("#modalLoading").modal('show');
    },
    url: this.href,
    type: 'POST',
    dataType: 'json',
    data: {'data': json},
    success: function (result) {
      console.log(result)
      if (debug !== false) { console.log(result) }
      if (result.spawns !== null) {
        result.spawns.forEach(function(item) {
          if (typeof layer.tags !== 'undefined') {
            $('#modalSpawnReport  .modal-title').text(subs.spawnReport + layer.tags.name);
          }
          $('#spawnReportTable > tbody:last-child').append('<tr><td>' + pokemon[item.pokemon_id-1] + '</td><td>' + item.count + '</td></tr>');
        });
      } else {
          if (typeof layer.tags !== 'undefined') {
          $('#modalSpawnReport  .modal-title').text(subs.spawnReport + layer.tags.name);
        }
        $('#spawnReportTable > tbody:last-child').append('<tr><td colspan="2">' + subs.noData + '</td></tr>');
      }
    },
    complete: function() {
      $("#modalLoading").modal('hide');
      $('#modalSpawnReport').modal('show');
    }
  });
}
function getAdBounds() {
  bgLayer.clearLayers();
  circleLayer.clearLayers();
  editableLayer.clearLayers();
  nestLayer.clearLayers();
  const bounds = map.getBounds();
  const overpassApiEndpoint = 'https://overpass-api.de/api/interpreter';
  var queryBbox = [ // s, e, n, w
    bounds.getSouthWest().lat,
    bounds.getSouthWest().lng,
    bounds.getNorthEast().lat,
    bounds.getNorthEast().lng
  ].join(',');
  var queryDate = "2019-02-16T00:00:00Z";
  var queryOptions = [
    '[out:json]',
    '[timeout:620]',
    '[bbox:' + queryBbox + ']',
    '[date:"' + queryDate + '"]'
  ].join('');
  var queryAdBounds = [
    'relation[admin_level=' + adBoundsLv + '];',
  ].join('');
  var overPassQuery = queryOptions + ';(' + queryAdBounds + ')' + ';out;>;out skel qt;';
  $.ajax({
    beforeSend: function() {
      $("#modalLoading").modal('show');
    },
    url: overpassApiEndpoint,
    type: 'GET',
    dataType: 'json',
    data: {'data': overPassQuery},
    success: function (result) {
      var geoJsonFeatures = osmtogeojson(result);
      geoJsonFeatures.features.forEach(function(feature) {
        if (feature.geometry.type == 'Polygon' || feature.geometry.type == 'MultiPolygon') { 
          feature = turf.flip(feature);
          var polygon = L.polygon(feature.geometry.coordinates, {
          clickable: false,
          color: "#a83297",
          fill: true,
          fillColor: '#a83297',
          fillOpacity: 0.1,
          opacity: 1.0,
          stroke: true,
          weight: 2
          });
          polygon.tags = {};
          polygon.tags.name = feature.properties.tags.name;
          polygon.addTo(editableLayer);
          polygon.bindPopup(function (layer) {
            if (typeof layer.tags.name !== 'undefined') {
              var name = '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">' + layer.tags.name + '</span></div>';
            }
            var output = name +
                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="editableLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.removeMap + '</span></div></div>' +

                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm exportLayer" data-layer-container="editableLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.exportPolygon + '</span></div></div>' +

                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm exportPoints" data-layer-container="editableLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.exportVP + '</span></div></div>' +

                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm countPoints" data-layer-container="editableLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.countVP + '</span></div></div>';
            return output;
          });
        }
      });
    },
    complete: function() {
      $("#modalLoading").modal('hide');
    }
  });
}
function getNests() {
  bgLayer.clearLayers();
  circleLayer.clearLayers();
  editableLayer.clearLayers();
  nestLayer.clearLayers();
  const bounds = map.getBounds();
  const overpassApiEndpoint = 'https://overpass-api.de/api/interpreter';
  var queryBbox = [ // s, e, n, w
    bounds.getSouthWest().lat,
    bounds.getSouthWest().lng,
    bounds.getNorthEast().lat,
    bounds.getNorthEast().lng
  ].join(',');
  var queryDate = "2019-02-16T00:00:00Z";
  var queryOptions = [
    '[out:json]',
    '[bbox:' + queryBbox + ']',
    '[date:"' + queryDate + '"]'
  ].join('');
  var queryNestWays = [
    'way["leisure"="park"];',
    'way["leisure"="recreation_ground"];',
    'way["leisure"="pitch"];',
    'way["leisure"="playground"];',
    'way["leisure"="golf_course"];',
    'way["landuse"="recreation_ground"];',
    'way["landuse"="meadow"];',
    'way["landuse"="grass"];',
  ].join('');
  var overPassQuery = queryOptions + ';(' + queryNestWays + ')' + ';out;>;out skel qt;';
  if (debug !== false) { console.log(overPassQuery) }
  $.ajax({
    beforeSend: function() {
      $("#modalLoading").modal('show');
    },
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
          color: "#ff8833",
          fill: true,
          fillColor: null,
          fillOpacity: 0.2,
          opacity: 0.5,
          stroke: true,
          weight: 4
        });
        polygon.tags = {};
        polygon.tags.name = feature.properties.tags.name;
        polygon.addTo(nestLayer);
        polygon.bindPopup(function (layer) {
          if (typeof layer.tags.name !== 'undefined') {
            var name = '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">' + subs.nest + ': ' + layer.tags.name + '</span></div>';
          }
          var output = name +
                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm getSpawnReport" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.getSpawnReport + '</span></div></div>' +
                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.removeMap + '</span></div></div>' +
                  '<div class="input-group"><button class="btn btn-secondary btn-sm exportLayer" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">' + subs.exportPolygon + '</span></div></div>';
          return output;
        });
      });
    },
    complete: function() {
      $("#modalLoading").modal('hide');
    }
  });
}
function splitCsv(str){
  var result = [];
  var strBuf = '';
  var start = 0 ;
  var marker = false;
  for (var i = 0; i< str.length; i++){

    if (str[i] === '"'){
      marker = !marker;
    }
    if (str[i] === ',' && !marker){
      result.push(str.substr(start, i - start));
      start = i+1;
    }
  }
  if (start <= str.length){
    result.push(str.substr(start, i - start));
  }
  for (var r = 0; r < result.length; r++) {
    for (var j = 0; j < result[r].length; j++) {
      if (result[r][j] === '"') {
        result[r] = result[r].slice(1,-1);
      }
    }
  }
  return result;
};
function csvtoarray(dataString) {
  var lines = dataString
    .split(/\n/)           // Convert to one string per line
    .map(function(lineStr) {
      return splitCsv(lineStr);   // Convert each line to array (,)
    })
  return lines;
}
function loadData() {
  const bounds = map.getBounds();
  const data = {
    'get_data': true,
    'min_lat': bounds.getSouthWest().lat,
    'max_lat': bounds.getNorthEast().lat,
    'min_lng': bounds.getSouthWest().lng,
    'max_lng': bounds.getNorthEast().lng,
    'show_gyms': true,
    'show_pokestops': true,
    'show_spawnpoints': true,
    'show_unknownpois': settings.showUnknownPois
  };
  const json = JSON.stringify(data);
  $.ajax({
    url: this.href,
    type: 'POST',
    dataType: 'json',
    data: {'data': json},
    success: function (result) {
      pokestopLayer.clearLayers();
      pokestopRangeLayer.clearLayers();
      gymLayer.clearLayers();
      spawnpointLayer.clearLayers();
      gyms = [];
      pokestops = [];
      pokestoprange = [];
      spawnpoints = [];
      spawnpoints_u = [];
      if (result.gyms != null && settings.showGyms === true) {
        result.gyms.forEach(function(item) {
          gyms.push(item);
          var radius = (6/8) + ((7/8) * (map.getZoom() - 11)) // Depends on Zoomlevel
          var weight = (1/8) + ((1/8) * (map.getZoom() - 11)) // Depends on Zoomlevel
            if(item.ex == 1){
              var marker = L.circleMarker([item.lat, item.lng], {
              color: 'black',
              fillColor: 'maroon',
              radius: radius,
              weight: weight,
              opacity: 1,
              fillOpacity: 0.8
            }).addTo(map);
            marker.tags = {};
            marker.tags.id = item.id;
            marker.bindPopup("<span>ID: " + item.id + "<br>" + item.name + subs.exEligible + "</span>").addTo(gymLayer);
            }
            else{
              var marker = L.circleMarker([item.lat, item.lng], {
              color: 'black',
              fillColor: 'orange',
              radius: radius,
              weight: weight,
              opacity: 1,
              fillOpacity: 0.8
            }).addTo(map);
            marker.tags = {};
            marker.tags.id = item.id;
            marker.bindPopup("<span>ID: " + item.id + "<br>" + item.name + "</span>").addTo(gymLayer);
            }
        });
      }
      if (result.pokestops != null && settings.showPokestops === true) {
        result.pokestops.forEach(function(item) {
          pokestops.push(item);
          var radius = (6/8) + ((6/8) * (map.getZoom() - 11)) // Depends on Zoomlevel
          var weight = (1/8) + ((1/8) * (map.getZoom() - 11)) // Depends on Zoomlevel
            var marker = L.circleMarker([item.lat, item.lng], {
              color: 'black',
              fillColor: 'green',
              radius: radius,
              weight: weight,
              opacity: 1,
              fillOpacity: 0.8
            }).addTo(map);
            marker.tags = {};
            marker.tags.id = item.id;
            marker.bindPopup("<span>ID: " + item.id + "<br>" + item.name + "</span>").addTo(pokestopLayer);
        });
      }
      if (result.pokestops != null && settings.showPokestopsRange === true) {
        result.pokestops.forEach(function(item) {
          pokestoprange.push(item);
            var marker = L.circle([item.lat, item.lng], {
              color: 'green',
              radius: 70,
              opacity: 0.2
            }).addTo(map);
            marker.tags = {};
            marker.tags.id = item.id;
            marker.bindPopup("<span>ID: " + item.id + "</span>").addTo(pokestopRangeLayer);
        });
      }
      if (result.spawnpoints != null && settings.showSpawnpoints === true) {
        if (settings.hideOldSpawnpoints != false){ 
          var oldSpawnpointsTimestamp = settings.oldSpawnpointsTimestamp;
          result.spawnpoints.forEach(function(item) {
            if (item.despawn_sec != null && item.updated >= oldSpawnpointsTimestamp) {
              spawnpoints.push(item);
            } else if (item.updated >= oldSpawnpointsTimestamp){
              spawnpoints_u.push(item);
              spawnpoints.push(item);
            }
            var radius = (6/8) + ((4/8) * (map.getZoom() - 11)) // Depends on Zoomlevel
            var weight = (1/8) + ((1/8) * (map.getZoom() - 11)) // Depends on Zoomlevel
            if (settings.showSpawnpoints === true){
              if (item.despawn_sec != null && item.updated >= oldSpawnpointsTimestamp) {
                var marker = L.circleMarker([item.lat, item.lng], {
                  color: 'black',
                  fillColor: 'blue',
                  radius: radius,
                  weight: weight,
                  opacity: 1,
                  fillOpacity: 0.8
                }).addTo(map);
                marker.tags = {};
                marker.tags.id = item.id;
                var despawn_time = new Date(parseInt(item.despawn_sec)*1000).toISOString().slice(-10, -5);
                marker.bindPopup("<span>ID: " + item.id + "</span>\n" + subs.despawnTime + despawn_time).addTo(spawnpointLayer);
              } else if (item.updated >= oldSpawnpointsTimestamp) {
                var marker = L.circleMarker([item.lat, item.lng], {
                  color: 'black',
                  fillColor: 'red',
                  radius: radius,
                  weight: weight,
                  opacity: 1,
                  fillOpacity: 0.8
                }).addTo(map);
                marker.tags = {};
                marker.tags.id = item.id;
                marker.bindPopup("<span>ID: " + item.id + "</span>\n" + subs.unknownDespawnTime).addTo(spawnpointLayer);
              }
            }
          });
        } else {
          result.spawnpoints.forEach(function(item) {
            if (item.despawn_sec != null) {
              spawnpoints.push(item);
            } else {
              spawnpoints_u.push(item);
              spawnpoints.push(item);
            }
            var radius = (6/8) + ((4/8) * (map.getZoom() - 11)) // Depends on Zoomlevel
            var weight = (1/8) + ((1/8) * (map.getZoom() - 11)) // Depends on Zoomlevel
            if (settings.showSpawnpoints === true){
              if (item.despawn_sec != null){
                var marker = L.circleMarker([item.lat, item.lng], {
                  color: 'black',
                  fillColor: 'blue',
                  radius: radius,
                  weight: weight,
                  opacity: 1,
                  fillOpacity: 0.8
                }).addTo(map);
                marker.tags = {};
                marker.tags.id = item.id;
                var despawn_time = new Date(parseInt(item.despawn_sec)*1000).toISOString().slice(-10, -5);
                marker.bindPopup("<span>ID: " + item.id + "</span>\n" + subs.despawnTime + despawn_time).addTo(spawnpointLayer);
              } else {
                var marker = L.circleMarker([item.lat, item.lng], {
                  color: 'black',
                  fillColor: 'red',
                  radius: radius,
                  weight: weight,
                  opacity: 1,
                  fillOpacity: 0.8
                }).addTo(map);
                marker.tags = {};
                marker.tags.id = item.id;
                marker.bindPopup("<span>ID: " + item.id + "</span>\n" + subs.unknownDespawnTime).addTo(spawnpointLayer);
              }
            }
          });
        }
      }
    }
  });
  updateS2Overlay()
}
$(document).ready(function() {
  $('input[type=radio][name=exportPolygonDataType]').change(function() {
    if (this.value == 'exportPolygonDataTypeCoordsList') {
      $('#exportPolygonDataCoordsList').show();
      $('#exportPolygonDataGeoJson').hide();
      $('#exportPolygonDataPoracle').hide();
      copyOutput = 'exportPolygonDataCoordsList';
      $(document.getElementById('copyPolygonOutput')).text(subs.copyClipboard);
    } else if (this.value == 'exportPolygonDataTypeGeoJson') {
      $('#exportPolygonDataCoordsList').hide();
      $('#exportPolygonDataGeoJson').show();
      $('#exportPolygonDataPoracle').hide();
      copyOutput = 'exportPolygonDataGeoJson';
      $(document.getElementById('copyPolygonOutput')).text(subs.copyClipboard);
    } else if (this.value == 'exportPolygonDataTypePoracle') {
      $('#exportPolygonDataCoordsList').hide();
      $('#exportPolygonDataGeoJson').hide();
      $('#exportPolygonDataPoracle').show();
      copyOutput = 'exportPolygonDataPoracle';
      $(document.getElementById('copyPolygonOutput')).text(subs.copyClipboard);
    }
  });
  $('#getOutput').click(function() {
    $('#outputCircles').val('');
    var allCircles = circleLayer.getLayers();
    var avgPt = 0;
    var exportType = $("#modalOutput input[name=exportCoordsType]:checked").val()
    if (exportType == 'sorted') {
      $.ajax({
        beforeSend: function() {
          $("#modalLoading").modal('show');
        },
        success: function() {
          var points = [];
          for (i=0;i<allCircles.length;i++) {
            var circle = allCircles[i].getLatLng();
            var Point = {
              x: circle.lat,
              y: circle.lng
            }
            points.push(Point);
          };
          var temp1 = '9'.repeat((circlesCount().toString().length)-1);
          var temp2 = Math.ceil(circlesCount()/10);
          var temp_coeff = '0.99999' + temp1 + temp2;
          var solution = solve(points, temp_coeff); 
          var orderedPoints = solution.map(i => points [i]); 
          for (i=0;i<orderedPoints.length;i++) {
            $('#outputCircles').val(function(index, text) {
              if (i != orderedPoints.length-1) {
                return text + (orderedPoints[i].x + "," + orderedPoints[i].y) + "\n" ;
              }
              return text + (orderedPoints[i].x + "," + orderedPoints[i].y);
            });
          }
          $('#outputCirclesCount').val(circlesCount());
          avgPt = (countPointsInCircles()) / (circlesCount());
          $('#outputAvgPt').val(avgPt.toFixed(2));
        },
        complete: function() {
          $("#modalLoading").modal('hide');
        }
      });
    } else {
      for (i=0;i<allCircles.length;i++) {
        var circleLatLng = allCircles[i].getLatLng();
        $('#outputCircles').val(function(index, text) {
          if (i != allCircles.length-1) {
            return text + (circleLatLng.lat + "," + circleLatLng.lng) + "\n" ;
          }
          return text + (circleLatLng.lat + "," + circleLatLng.lng);
        });
      }
      $('#outputCirclesCount').val(circlesCount());
      avgPt = (countPointsInCircles()) / (circlesCount());
      $('#outputAvgPt').val(avgPt.toFixed(2));
    }
  });
});
$(document).on("click", ".deleteLayer", function() {
  var id = $(this).attr('data-layer-id');
  var container = $(this).attr('data-layer-container');
  switch (container) {
    case 'circleLayer':
      circleLayer.removeLayer(parseInt(id));
      break;
    case 'editableLayer':
      editableLayer.removeLayer(parseInt(id));
      break;
    case 'nestLayer':
      nestLayer.removeLayer(parseInt(id));
      break;
    case 'subsLayer':
      let markerID = parseInt(id);
      let rangeID = subsLayer.getLayer(markerID).rangeID;
      subsLayer.removeLayer(markerID);
      subsLayer.removeLayer(rangeID);
      break;
  }
});
$(document).on("click", ".getSpawnReport", function() {
  var id = $(this).attr('data-layer-id');
  var layer;
  var container = $(this).attr('data-layer-container');
  switch (container) {
    case 'editableLayer':
      layer = editableLayer.getLayer(parseInt(id));
      break;
    case 'nestLayer':
      layer = nestLayer.getLayer(parseInt(id));
      break;
  }
  prepareData(layer._bounds);
  getSpawnReport(layer);
});
function countPointsInCircles(display) {
  prepareData();
  var count = 0;
  var includedGyms = [];
  var includedStops = [];
  var includedSpawnpoints = [];
  circleLayer.eachLayer(function(layer){
    var radius = layer.getRadius();
    var circleCenter = layer.getLatLng();  
    if (settings.showGyms == true) {
      gyms.forEach(function(item) {
        var point =  L.latLng(item.lat,item.lng);
        if(circleCenter.distanceTo(point) <= radius && includedGyms.indexOf(item) === -1){
          count++;
          includedGyms.push(item);
        }
      });
    }
    if (settings.showPokestops == true) {
      pokestops.forEach(function(item) {
        var point =  L.latLng(item.lat,item.lng);
        if(circleCenter.distanceTo(point) <= radius && includedStops.indexOf(item) === -1){
          count++;
          includedStops.push(item);
        }
      });
    }
    if (settings.showSpawnpoints == true) {
      spawnpoints.forEach(function(item) {
        var point =  L.latLng(item.lat,item.lng);
        if(circleCenter.distanceTo(point) <= radius && includedSpawnpoints.indexOf(item) === -1){
          count++;
          includedSpawnpoints.push(item);
        }
      });
    }
  });
  if (display == true) {
    alert(subs.countTotal + count + '\n' + subs.countGyms + includedGyms.length + '\n' + subs.countStops + includedStops.length + '\n' + subs.countSpawnpoints + includedSpawnpoints.length);
  }
  return count;
}
$(document).on("click", "#getCirclesCount", function() {
  let display = true;
  countPointsInCircles(display);
});          
$(document).load("#modalContent", getLanguage());
$(document).on("click", "#getAllNests", function() {
  $("#modalLoading").modal('show');
  prepareData();
  nestLayer.eachLayer(function(layer) {
    var reportStops = [],
      reportSpawns = [];
    var center = layer.getBounds().getCenter()
    var poly = layer.toGeoJSON();
    var line = turf.polygonToLine(poly);
    pokestops.forEach(function(item) {
      point = turf.point([item.lng, item.lat]);
      if (turf.inside(point, poly)) {
        reportStops.push(item.id);
      }
    });
    spawnpoints.forEach(function(item) {
      point = turf.point([item.lng, item.lat]);
      if (turf.inside(point, poly)) {
        reportSpawns.push(item.id);
      }
    });
    const data = {
      'get_spawndata': true,
      'nest_migration_timestamp': settings.nestMigrationDate,
      'spawn_report_limit': settings.spawnReportLimit,
      'stops': reportStops,
      'spawns': reportSpawns
    };
    const json = JSON.stringify(data);
    if (debug !== false) { console.log(json) }
    $.ajax({
      url: this.href,
      type: 'POST',
      dataType: 'json',
      data: {'data': json},
      success: function (result) {
        if (debug !== false) { console.log(result) }
        if (result.spawns !== null) {
          if (typeof layer.tags.name !== 'undefined') {
            $('#spawnReportTable > tbody:last-child').append('<tr><td colspan="2"><strong>' + subs.spawnReport + layer.tags.name + '</strong> <em style="font-size:xx-small">' + subs.at + ' ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + '</em></td></tr>');
          } else {
            $('#spawnReportTable > tbody:last-child').append('<tr><td colspan="2"><strong>' + subs.spawnReport + subs.unnamed + '</strong> ' + subs.at + ' <em style="font-size:xx-small">' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + '</em></td></tr>');
          }
          result.spawns.forEach(function(item) {
            $('#spawnReportTable > tbody:last-child').append('<tr><td>' + pokemon[item.pokemon_id-1] + '</td><td>' + item.count + '</td></tr>');
          });
        } else {
          if (typeof layer.tags.name !== 'undefined') {
            $('#spawnReportTableMissed > tbody:last-child').append('<tr><td colspan="2"><em style="font-size:xx-small"><strong>' + layer.tags.name + '</strong> ' + subs.at + ' ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + subs.skipped + '</em></td></tr>');
          } else {
            $('#spawnReportTableMissed > tbody:last-child').append('<tr><td colspan="2"><em style="font-size:xx-small"><strong>' + sub.unnamed + '</strong> ' + subs.at + ' ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + subs.skipped + '</em></td></tr>');
          }
        }
      },
      complete: function() {
        $("#modalLoading").modal('hide');
        $('#modalSpawnReport  .modal-title').text(subs.nestReport);
        $('#modalSettings').modal('hide');
        $('#modalSpawnReport').modal('show');
      }
    });
  });
});
$(document).on("click", ".exportLayer", function() {
  $(document.getElementById('copyPolygonOutput')).text(subs.copyClipboard);
  var id = $(this).attr('data-layer-id');
  var layer;
  var container = $(this).attr('data-layer-container');
  switch (container) {
    case 'editableLayer':
      layer = editableLayer.getLayer(parseInt(id));
      break;
    case 'nestLayer':
      layer = nestLayer.getLayer(parseInt(id));
      break;
  }
  // geojson
  var polyjson = JSON.stringify(layer.toGeoJSON());
  $('#exportPolygonDataGeoJson').val(polyjson);
  // simple coords
  var polycoords = '';
  turf.flip(layer.toGeoJSON()).geometry.coordinates[0].forEach(function(item) {
    polycoords += item[0] + ',' + item[1] + "\n";
  });
  $('#exportPolygonDataCoordsList').val(polycoords);
  // poracle
  var po_start = '  {\n    "name": "polygon",\n    "color": "#6CB1E1",\n    "id": 0,\n    "path": [\n';
  var po_end = '    ]\n  }';
  var po_coords = '';
  turf.flip(layer.toGeoJSON()).geometry.coordinates[0].forEach(function(item) {
    po_coords += '      [\n        ' + item[0] + ',\n        ' + item[1] + '\n      ],\n';
  });
  po_coords = po_coords.slice(0, -2) + '\n';
  var poracle = po_start + po_coords + po_end;
  $('#exportPolygonDataPoracle').val(poracle);
  $('#exportPolygonDataGeoJson').hide();
  $('#exportPolygonDataPoracle').hide();
  copyOutput = 'exportPolygonDataCoordsList'
  $('#modalExportPolygon').modal('show');
});
$(document).on("click", ".exportPOIs", function() {
  let id = $(this).attr('data-layer-id');
  let layer = subsLayer.getLayer(parseInt(id));
  let poicoords = 'Lat, Lon: ' + layer._latlng.lat + ', ' + layer._latlng.lng;
  alert(subs.exportPOILabel + '\n' + poicoords)
});
$(document).on("click", ".exportPoints", function() {
  var id = $(this).attr('data-layer-id');
  var layer;
  var container = $(this).attr('data-layer-container');
  switch (container) {
    case 'editableLayer':
      layer = editableLayer.getLayer(parseInt(id));
      break;
    case 'nestLayer':
      layer = nestLayer.getLayer(parseInt(id));
      break;
  }
  var poly = layer.toGeoJSON();
  var line = turf.polygonToLine(poly);
  var gymcoords = '';
  var stopcoords = '';
  var spawncoords = '';
  if (settings.showGyms == true) {
    gyms.forEach(function(item) {
      point = turf.point([item.lng, item.lat]);
      if (turf.inside(point, poly)) {
        gymcoords += item.lat + ',' + item.lng + "\n";
      }
    });
  }
  if (settings.showPokestops == true) {
    pokestops.forEach(function(item) {
      point = turf.point([item.lng, item.lat]);
      if (turf.inside(point, poly)) {
        stopcoords += item.lat + ',' + item.lng + "\n";
      }
    });
  }
  if (settings.showSpawnpoints == true) {
    spawnpoints.forEach(function(item) {
      point = turf.point([item.lng, item.lat]);
      if (turf.inside(point, poly)) {
        spawncoords += item.lat + ',' + item.lng + "\n";
      }
    });
  }
  $('#exportPolygonPointsGyms').val('');
  $('#exportPolygonPointsPokestops').val('');
  $('#exportPolygonPointsSpawnpoints').val('');
  $('#exportPolygonPointsGyms').val(gymcoords);
  $('#exportPolygonPointsPokestops').val(stopcoords);
  $('#exportPolygonPointsSpawnpoints').val(spawncoords);
  $('#modalExportPolygonPoints').modal('show');
});
$(document).on("click", ".countPoints", function() {
  let id = $(this).attr('data-layer-id');
  let layer;
  let container = $(this).attr('data-layer-container');
  switch (container) {
    case 'editableLayer':
      layer = editableLayer.getLayer(parseInt(id));
      break;
    case 'nestLayer':
      layer = nestLayer.getLayer(parseInt(id));
      break;
  }
  let count = 0;
  let gymCount = 0;
  let stopCount = 0;
  let spawnpointCount = 0;
  let poly = layer.toGeoJSON();
  let line = turf.polygonToLine(poly);
  prepareData(layer._bounds);
  if (settings.showGyms == true) {
    gyms.forEach(function(item) {
      point = turf.point([item.lng, item.lat]);
      if (turf.inside(point, poly)) {
        count++;
        gymCount++;
      }
    });
  }
  if (settings.showPokestops == true) {
    pokestops.forEach(function(item) {
      point = turf.point([item.lng, item.lat]);
      if (turf.inside(point, poly)) {
        count++;
        stopCount++;
      }
    });
  }
  if (settings.showSpawnpoints == true) {
    spawnpoints.forEach(function(item) {
      point = turf.point([item.lng, item.lat]);
      if (turf.inside(point, poly)) {
        count++;
        spawnpointCount++;
      }
    });
  }
  alert(subs.countTotal + count + '\n' + subs.countGyms + gymCount + '\n' + subs.countStops + stopCount + '\n' + subs.countSpawnpoints + spawnpointCount);
});
function cellCount(poly) {
  var points = 0;
  cell = poly.toGeoJSON();
  gyms.forEach(function(item) {
    point = turf.point([item.lng, item.lat]);
    if (turf.inside(point, cell)) {
      points++;
    }
  });
  pokestops.forEach(function(item) {
    point = turf.point([item.lng, item.lat]);
    if (turf.inside(point, cell)) {
      points++;
    }
  });
  return points;
};
function loadSettings() {
  const defaultSettings = {
    showGyms: false,
    showPokestops: false,
    showPokestopsRange: false,
    showSpawnpoints: false,
    showUnknownPois: false,
    hideOldSpawnpoints: false,
    circleSize: 500,
    optimizationAttempts: 10,
    nestMigrationDate: 1539201600,
    oldSpawnpointsTimestamp: 1569438000,
    spawnReportLimit: 10,
    mapMode: 'RouteGenerator',
    mapCenter: [42.548197, -83.14684],
    mapZoom: 13,
    viewCells: false,
    tlLink: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    tlChoice: 'osm',
    language: 'en'
  }
  Object.keys(settings).forEach(function(key) {
    storedSetting = retrieveSetting(key);
    if (storedSetting !== null) {
      settings[key] = storedSetting;
    } else {
      settings[key] = defaultSettings[key];
      storeSetting(key)
    }
  });
}
function getLanguage() {
  if (settings.language == null) {
    loadSettings()
  }
  if (settings.language == 'de') {
    subs = deSubs;
    pokemon = dePokemon;
  } else if (settings.language == 'fr') {
    subs = frSubs;
    pokemon = frPokemon;
  } else {
    subs = enSubs;
    pokemon = enPokemon;
  }
}
function circlesCount() {
  // Count all available circles.
  var count = 0;
  var allCircles = circleLayer.getLayers();
  for (i=0;i<allCircles.length;i++) {
    count++
  };
  return count;
}
function storeSetting(key) {
  localStorage.setItem(key, JSON.stringify(settings[key]));
}
function retrieveSetting(key) {
  var value;
  if (localStorage.getItem(key) !== 'undefined') {
    value = JSON.parse(localStorage.getItem(key));
  } else {
    value = null;
  }
  return value;
}
function showS2Cells0(level, style, mp) {
  // Credit goes to the PMSF project
  const bounds = map.getBounds()
  const size = L.CRS.Earth.distance(bounds.getSouthWest(), bounds.getNorthEast()) / 4000 + 1 | 0
  const count = (2 ** level * size >> 11)/mp
  function addPoly(cell) {
    const vertices = cell.getCornerLatLngs()
    const poly = L.polygon(vertices, Object.assign({opacity: 0.5, fillOpacity: 0.0}, style))
    if (cell.level === settings.cellsLevel0) {
      viewCellLayer.addLayer(poly)
    }
  }
  // add cells spiraling outward
  let cell = S2.S2Cell.FromLatLng(bounds.getCenter(), level)
  let steps = 1
  let direction = 0
  do {
    for (let i = 0; i < 2; i++) {
      for (let i = 0; i < steps; i++) {
        if (bounds.intersects(cell.getCornerLatLngs())) {
          addPoly(cell)
        }
        cell = cell.getNeighbors()[direction % 4]
      }
      direction++
    }
    steps++
  } while (steps < count)
}
function showS2Cells1(level, style) {
  // Credit goes to the PMSF project
  const bounds = map.getBounds()
  const size = L.CRS.Earth.distance(bounds.getSouthWest(), bounds.getNorthEast()) / 4000 + 1 | 0
  const count = 2 ** level * size >> 11
  function addPoly(cell) {
    const vertices = cell.getCornerLatLngs()
    const poly = L.polygon(vertices, Object.assign({opacity: 0.5, fillOpacity: 0.0}, style))
    if (cell.level === settings.cellsLevel1) {
      viewCellLayer.addLayer(poly)
      if (settings.s2CountPOI != false) {
        prepareData(poly._bounds);  
        var poiCount = cellCount(poly).toString();
        var marker = L.circleMarker([vertices[3].lat, vertices[3].lng], { stroke: false, radius: 1, fillOpacity: 0.0 }); 
        marker.bindTooltip(poiCount, {permanent: true, textOnly: true, opacity: 0.8, direction: 'center', offset: [25, -20] })
        viewCellLayer.addLayer(marker);
      }
    }
  }
  // add cells spiraling outward
  let cell = S2.S2Cell.FromLatLng(bounds.getCenter(), level)
  let steps = 1
  let direction = 0
  do {
    for (let i = 0; i < 2; i++) {
      for (let i = 0; i < steps; i++) {
        if (bounds.intersects(cell.getCornerLatLngs())) {
          addPoly(cell)
        }
        cell = cell.getNeighbors()[direction % 4]
      }
      direction++
    }
    steps++
  } while (steps < count)
}
function showS2Cells2(level, style) {
    // Credit goes to the PMSF project
    const bounds = map.getBounds()
    const size = L.CRS.Earth.distance(bounds.getSouthWest(), bounds.getNorthEast()) / 4000 + 1 | 0
    const count = 2 ** level * size >> 11
    function addPoly(cell) {
        const vertices = cell.getCornerLatLngs()
        const poly = L.polygon(vertices, Object.assign({opacity: 0.5, fillOpacity: 0.0}, style))
        if (cell.level === settings.cellsLevel2) {
            viewCellLayer.addLayer(poly)
        }
    }
    // add cells spiraling outward
    let cell = S2.S2Cell.FromLatLng(bounds.getCenter(), level)
    let steps = 1
    let direction = 0
    do {
        for (let i = 0; i < 2; i++) {
            for (let i = 0; i < steps; i++) {
                if (bounds.intersects(cell.getCornerLatLngs())) {
                  addPoly(cell)
                }
                cell = cell.getNeighbors()[direction % 4]
            }
            direction++
        }
        steps++
    } while (steps < count)
}
function updateS2Overlay() {
    if (settings.viewCells && (map.getZoom() >= 13.5) && (settings.cellsLevel0 < 20)) {
        viewCellLayer.clearLayers()
        if (settings.cellsLevel0Check != false) {
          showS2Cells0(settings.cellsLevel0, {color: 'Red', weight: 1}, 1)
        }
        if ((settings.cellsLevel1Check != false) && (settings.s2CountPOI == false)) {
          showS2Cells1(settings.cellsLevel1, {color: 'Blue', weight: 2})
        }
        if ((settings.cellsLevel1Check != false) && (settings.s2CountPOI != false)) {
          viewCellLayer.clearLayers()
          if (map.getZoom() < 14.5){
            map.setZoom(14.5)
            console.log('Zoom adapted for L14 cells with POI Count')
          }
          showS2Cells1(settings.cellsLevel1, {color: 'Blue', weight: 2})
        }  
        if (settings.cellsLevel2Check != false) {
          showS2Cells2(settings.cellsLevel2, {color: 'Green', weight: 1})
        }        
        editableLayer.removeFrom(map).addTo(map);
        nestLayer.removeFrom(map).addTo(map);
        circleLayer.removeFrom(map).addTo(map);
    } else if (settings.viewCells && (map.getZoom() < 13.0)) {
        viewCellLayer.clearLayers()
        console.log('View cells are currently hidden, zoom in')
    } else if (settings.viewCells && (settings.cellsLevel0 > 19) && (settings.cellsLevel0Check != false)) {
        viewCellLayer.clearLayers()
        if (map.getZoom() < 17.5){
          map.setZoom(17.5)
          console.log('Zoom adapted for L20 cells')
        } 
        showS2Cells0(settings.cellsLevel0, {color: 'Red', weight: 0.5}, 8)        
    } else {
        viewCellLayer.clearLayers()
    }
}
</script>

</head>
  <body>
    <div id="map"></div>

    <div class="modal" id="modalSettings" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><script type="text/javascript">document.write(subs.settings);</script></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><script type="text/javascript">document.write(subs.routeOptAtt);</script></span>
              </div>
              <input id="optimizationAttempts" name="optimizationAttempts" type="text" class="form-control" aria-label="Optimization attempts">
              <div class="input-group-append">
                <span class="input-group-text"><script type="text/javascript">document.write(subs.tries);</script></span>
              </div>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><script type="text/javascript">document.write(subs.s2cells0);</script></span>
              </div>
              <input id="cellsLevel0" name="cellsLevel0" type="text" aria-label="cells Level 0" style="padding-left: 10px; width: 40px;">
              <div>
                <input type="checkbox" name="cellsLevel0Check" id="cellsLevel0Check" style="margin-left: 15px; vertical-align: bottom;">
              </div>
            </div>

            <div class="input-group mb-3">
              <div>
                <span class="input-group-text"><script type="text/javascript">document.write(subs.s2cells1);</script><br>
                <script type="text/javascript">document.write(subs.s2CountPOI);</script></span>
              </div>
              <div>
                  <input type="checkbox" name="cellsLevel1Check" id="cellsLevel1Check" style="margin-left: 15px; vertical-align: bottom;"><br>
                  <input type="checkbox" name="s2CountPOI" id="s2CountPOI" style="margin-left: 15px; vertical-align: -0.8em;">
              </div>
            </div>

            <div class="input-group mb-3">
              <div>
                <span class="input-group-text"><script type="text/javascript">document.write(subs.s2cells2);</script></span>
              </div>
              <div>
                  <input type="checkbox" name="cellsLevel2Check" id="cellsLevel2Check" style="margin-left: 15px; vertical-align: bottom;">
              </div>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><script type="text/javascript">document.write(subs.circleRadius);</script></span>
              </div>
              <input id="circleSize" name="circleSize" type="text" class="form-control" aria-label="Circle Radius (in meters)">
              <div class="input-group-append">
                <span class="input-group-text"><script type="text/javascript">document.write(subs.meters);</script></span>
              </div>
            </div>

            <div class="input-group mb-3 date" id="nestMigrationDate" data-target-input="nearest">
              <div class="input-group-prepend">
                <span class="input-group-text"><script type="text/javascript">document.write(subs.lastNestMigration);</script></span>
              </div>
              <input type="text" class="form-control datetimepicker-input" data-target="#nestMigrationDate"/>
              <div class="input-group-append" data-target="#nestMigrationDate" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><script type="text/javascript">document.write(subs.spawnReportLimit);</script></span>
              </div>
              <input id="spawnReportLimit" name="spawnReportLimit" type="text" class="form-control" aria-label="Spawn report limit">
              <div class="input-group-append">
                <span class="input-group-text"><script type="text/javascript">document.write(subs.pokemon);</script></span>
              </div>
            </div>

            <label><script type="text/javascript">document.write(subs.oldSpawnpointsTitle);</script></label>
            <div class="input-group mb-3 date" id="oldSpawnpointsTimestamp" data-target-input="nearest">
              <input type="text" class="form-control datetimepicker-input" data-target="#oldSpawnpointsTimestamp"/>
              <div class="input-group-append" data-target="#oldSpawnpointsTimestamp" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
            </div>

            <div class="form-group">
              <label for="language"><script type="text/javascript">document.write(subs.selectLanguage);</script></label>
              <select class="form-control" id="language">
                <option value="en">English</option>
                <option value="de">Deutsch</option>
                <option value="fr" disabled>Français</option>
              </select>
            </div>

            <div class="form-group">
              <label for="tlChoice"><script type="text/javascript">document.write(subs.chooseTileset);</script></label>
              <select class="form-control" id="tlChoice">
                <option value="osm">Standard</option>
                <option value="carto">Lite</option>
                <option value="own"><script type="text/javascript">document.write(subs.ownTileset);</script></option>
              </select>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" id="saveSettings" class="btn btn-primary" data-dismiss="modal"><script type="text/javascript">document.write(subs.close);</script></button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="modalOutput" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><script type="text/javascript">document.write(subs.output);</script></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label for="mapMode"><script type="text/javascript">document.write(subs.generatedRoute)</script></label>
            <div class="input-group mb-3">
              <textarea id="outputCircles" style="height:200px;" class="form-control" aria-label="Route output"></textarea>
            </div>
            <dl class="row">
              <dt class="col-sm-7"><script type="text/javascript">document.write(subs.countCircles)</script></dt>
              <dd class="col-sm-3"><output id="outputCirclesCount" aria-label="Circle count output"></output></dd>
              <dt class="col-sm-7"><script type="text/javascript">document.write(subs.outputAvgPt)</script></dt>
              <dd class="col-sm-3"><output id="outputAvgPt" aria-label="Average ppc output"></output></dd>
            </dl>
            <div class="btn-toolbar" style="margin-bottom: 20px;">
              <div class="btn-group mr-2" role="group" aria-label="">
                <button id="getOutput" class="btn btn-primary float-left" type="button"><script type="text/javascript">document.write(subs.getOutput);</script></button>
              </div>
              <div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="exportCoordsType" id="exportCoordsTypeUnsorted" value="unsorted" checked>
                  <label class="form-check-label" for="exportCoordsTypeUnsorted"><script type="text/javascript">document.write(subs.coordTypeUnsorted);</script></label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="exportCoordsType" id="exportCoordsTypeSorted" value="sorted">
                  <label class="form-check-label" for="exportCoordsTypeSorted"><script type="text/javascript">document.write(subs.coordTypeSorted);</script></label>
                </div>
              </div>
              <div class="btn-group" role="group" aria-label=""  style='margin-left: 20px;'>
                <button id="copyCircleOutput" class="btn btn-secondary float-right" type="button"><script type="text/javascript">document.write(subs.copyClipboard);</script></button>
              </div>
            </div>
            <div class="btn-toolbar">
              <div class="btn-group" role="group" aria-label="">
                <button id="getAllNests" class="btn btn-primary float-left" type="button" style='margin-right: 5px;'><script type="text/javascript">document.write(subs.getAllNests);</script></button>
              </div>
              <div class="btn-group" role="group" aria-label="">
                <button id="getCirclesCount" class="btn btn-primary float-right" type="button"><script type="text/javascript">document.write(subs.countPoints);</script></button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal"><script type="text/javascript">document.write(subs.close);</script></button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="modalImportInstance" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><script type="text/javascript">document.write(subs.importInstance);</script></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label for="importInstanceName"><script type="text/javascript">document.write(subs.selectInstance);</script></label>
            <div class="input-group mb-3">
              <select name="importInstanceName" id="importInstanceName" class="form-control" aria-label="Select an instance to import">
              </select>
            </div>
            <div class="input-group" style="margin-bottom: 15px;">
              <div>
                <label><script type="text/javascript">document.write(subs.instanceMode);</script></label>
              </div>
              <div>
                  <input type="checkbox" name="instanceMode" id="instanceMode" style="margin-left: 15px;">
              </div>
            </div>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><script type="text/javascript">document.write(subs.instanceColor);</script></span>
              </div>
              <input id="instanceColor" name="instanceColor" type="text" class="form-control" value="#1090fa" aria-label="Hexadecimal Color for Imported Instance"/>

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="importInstance" class="btn btn-primary" data-dismiss="modal"><script type="text/javascript">document.write(subs.importInstance);</script></button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><script type="text/javascript">document.write(subs.close);</script></button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal" id="modalImportPolygon" tabindex="-1" role="dialog">
      <form id="importPolygonForm">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><script type="text/javascript">document.write(subs.importPolygon);</script></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <label for="importPolygonDataType"><script type="text/javascript">document.write(subs.polygonDataType);</script></label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="importPolygonDataType" id="importPolygonDataTypeCoordList" value="importPolygonDataTypeCoordList" checked>
                <label class="form-check-label" for="importPolygonDataTypeCoordList"><script type="text/javascript">document.write(subs.coordinateList);</script></label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="importPolygonDataType" id="importPolygonDataTypeGeoJson" value="importPolygonDataTypeGeoJson">
                <label class="form-check-label" for="importPolygonDataTypeGeoJson"><script type="text/javascript">document.write(subs.geoJson);</script></label>
              </div>
              <label for="importPolygonData"><script type="text/javascript">document.write(subs.polygonData);</script></label>
              <div class="input-group mb">
                <textarea name="importPolygonData" id="importPolygonData" style="height:400px;" class="form-control" aria-label="Polygon data"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="savePolygon" class="btn btn-primary"><script type="text/javascript">document.write(subs.import);</script></button>
              <button type="button" id="saveNestPolygon" class="btn btn-secondary"><script type="text/javascript">document.write(subs.importNest);</script></button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><script type="text/javascript">document.write(subs.close);</script></button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <div class="modal" id="modalImportSubmissions" tabindex="-1" role="dialog">
      <form id="importSubmissionsForm">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><script type="text/javascript">document.write(subs.importSubmissions);</script></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <label for="importSubmissionsData"><script type="text/javascript">document.write(subs.poiData);</script></label>
              <div class="input-group mb">
                <textarea name="importSubmissionsData" id="importSubmissionsData" style="height:250px;" class="form-control" aria-label="Submissions data"></textarea>
              </div>
            </div>
            <div class="modal-body">
              <label for="csvOpener"><script type="text/javascript">document.write(subs.csvOpener);</script></label>
              <input id="csvOpener" type='file' accept='text/csv' onchange='openFile(event)'>
                <script>
                  var openFile = function(event) {
                    var input = event.target;
                    var reader = new FileReader();
                    reader.onload = function(){
                      csvImport = reader.result;
                    };
                    reader.readAsText(input.files[0]);
                  };
                </script>
            </div>
            <div class="modal-body">       
              <label for="submissionRangeCheck"><script type="text/javascript">document.write(subs.submissionRangeCheck);</script></label>
              <input type="checkbox" name="submissionRangeCheck" id="submissionRangeCheck" style="margin-left: 15px; vertical-align: middle;">
            </div>
            <div class="modal-footer">
              <button type="button" id="importSubmissions" class="btn btn-primary" data-dismiss="modal"><script type="text/javascript">document.write(subs.import);</script></button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><script type="text/javascript">document.write(subs.close);</script></button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <div class="modal" id="modalExportPolygon" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><script type="text/javascript">document.write(subs.exportPolygon);</script></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <label for="exportPolygonDataType"><script type="text/javascript">document.write(subs.polygonDataType);</script></label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="exportPolygonDataType" id="exportPolygonDataTypeCoordsList" value="exportPolygonDataTypeCoordsList" checked>
                <label class="form-check-label" for="exportPolygonDataTypeCoordsList"><script type="text/javascript">document.write(subs.coordinateList);</script></label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="exportPolygonDataType" id="exportPolygonDataTypeGeoJson" value="exportPolygonDataTypeGeoJson">
                <label class="form-check-label" for="exportPolygonDataTypeGeoJson"><script type="text/javascript">document.write(subs.geoJson);</script></label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="exportPolygonDataType" id="exportPolygonDataTypePoracle" value="exportPolygonDataTypePoracle">
                <label class="form-check-label" for="exportPolygonDataTypePoracle"><script type="text/javascript">document.write(subs.poracle);</script></label>
              </div>
            <label for="exportPolygonData"><script type="text/javascript">document.write(subs.polygonData);</script></label>
            <div class="input-group mb">
              <textarea name="exportPolygonDataGeoJson" id="exportPolygonDataGeoJson" style="height:400px;" class="form-control" aria-label="Polygon data"></textarea>
            </div>
            <div class="input-group mb">
              <textarea name="exportPolygonDataCoords" id="exportPolygonDataCoordsList" style="height:400px;" class="form-control" aria-label="Polygon data"></textarea>
            </div>
            <div class="input-group mb">
              <textarea name="exportPolygonDataPoracle" id="exportPolygonDataPoracle" style="height:400px;" class="form-control" aria-label="Polygon data"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button id="copyPolygonOutput" class="btn btn-secondary float-left" type="button"><script type="text/javascript">document.write(subs.copyClipboard);</script></button>
            <button type="button" id="exportPolygonClose" class="btn btn-primary" data-dismiss="modal"><script type="text/javascript">document.write(subs.close);</script></button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="modalExportPolygonPoints" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><script type="text/javascript">document.write(subs.exportPolygonPoints);</script></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label for="exportPolygonData"><script type="text/javascript">document.write(subs.gyms);</script></label>
            <div class="input-group mb">
              <textarea name="exportPolygonPointsGyms" id="exportPolygonPointsGyms" style="height:200px;" class="form-control" aria-label="Gym data"></textarea>
            </div>
            <label for="exportPolygonData"><script type="text/javascript">document.write(subs.pokestops);</script></label>
            <div class="input-group mb">
              <textarea name="exportPolygonPointsPokestops" id="exportPolygonPointsPokestops" style="height:200px;" class="form-control" aria-label="Pokestop data"></textarea>
            </div>
            <label for="exportPolygonData"><script type="text/javascript">document.write(subs.spawnpoints);</script></label>
            <div class="input-group mb">
              <textarea name="exportPolygonPointsSpawnpoints" id="exportPolygonPointsSpawnpoints" style="height:200px;" class="form-control" aria-label="Spawnpoint data"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="exportPolygonPointsClose" class="btn btn-primary" data-dismiss="modal"><script type="text/javascript">document.write(subs.close);</script></button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="modalOptimize" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><script type="text/javascript">document.write(subs.optimize);</script></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="optimizeTypePOI" id="optimizeForGyms" checked>
                <label class="form-check-label" for="optimizeForGyms"><script type="text/javascript">document.write(subs.optimizeGyms);</script></label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="optimizeTypePOI" id="optimizeForPokestops">
                <label class="form-check-label" for="optimizeForPokestops"><script type="text/javascript">document.write(subs.optimizePokestops);</script></label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="optimizeTypePOI" id="optimizeForSpawnpoints">
                <label class="form-check-label" for="optimizeForSpawnpoints"><script type="text/javascript">document.write(subs.optimizeSpawnpoints);</script></label>
              </div>
              <div class="form-check" style="margin-bottom: 10px;">
                <input class="form-check-input" type="radio" name="optimizeTypePOI" id="optimizeForUnknownSpawnpoints">
                <label class="form-check-label" for="optimizeForUnknownSpawnpoints"><script type="text/javascript">document.write(subs.optimizeUnknownSpawnpoints);</script></label>
              </div>
            </div>
            <hr>
            <div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="optimizeTypeLayer" id="optimizePolygons" checked>
                <label class="form-check-label" for="optimizePolygons"><script type="text/javascript">document.write(subs.optimizePiP);</script></label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="optimizeTypeLayer" id="optimizeNests">
                <label class="form-check-label" for="optimizeNests"><script type="text/javascript">document.write(subs.optimizePiNP);</script></label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="optimizeTypeLayer" id="optimizeCircles">
                <label class="form-check-label" for="optimizeCircles"><script type="text/javascript">document.write(subs.optimizePiC);</script></label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="getOptimizedRoute" class="btn btn-primary" data-dismiss="modal"><script type="text/javascript">document.write(subs.getOptimization);</script></button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="modalAdBounds" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><script type="text/javascript">document.write(subs.adBoundsHeader);</script></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="selectAdBoundsLv" id="adBoundsLv6">
                <label class="form-check-label" for="adBoundsLv6"><script type="text/javascript">document.write(subs.adBoundsLv6);</script></label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="selectAdBoundsLv" id="adBoundsLv8">
                <label class="form-check-label" for="adBoundsLv8"><script type="text/javascript">document.write(subs.adBoundsLv8);</script></label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="selectAdBoundsLv" id="adBoundsLv9" checked>
                <label class="form-check-label" for="adBoundsLv9"><script type="text/javascript">document.write(subs.adBoundsLv9);</script></label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="getAdBounds" class="btn btn-primary" data-dismiss="modal"><script type="text/javascript">document.write(subs.getAdBounds);</script></button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="modalSpawnReport" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-sm" id="spawnReportTable">
              <thead>
                <tr>
                  <th scope="col"><script type="text/javascript">document.write(subs.pokemon);</script>:</th>
                  <th scope="col"><script type="text/javascript">document.write(subs.count);</script></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            <table class="table table-sm" id="spawnReportTableMissed">
              <tbody>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><script type="text/javascript">document.write(subs.close);</script></button>
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
</html>

<?php
}
function initDB($DB_HOST, $DB_USER, $DB_PSWD, $DB_NAME, $DB_PORT) {
  $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;port=$DB_PORT;charset=utf8mb4";
  $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => true,
  ];
  $pdo = new PDO($dsn, $DB_USER, $DB_PSWD, $options);
  return $pdo;
}
function map_helper_init() {
  global $db;
  $db = initDB(DB_HOST, DB_USER, DB_PSWD, DB_NAME, DB_PORT);
  $args = json_decode($_POST['data']);
  if (isset($args->get_spawndata)) {
  if ($args->get_spawndata === true) { getSpawnData($args); }
  }
  if (isset($args->get_data)) {
  if ($args->get_data === true) { getData($args); }
  }
  if (isset($args->get_optimization)) {
  if ($args->get_optimization === true) { getOptimization($args); }
  }
  if (isset($args->get_instance_data)) {
  if ($args->get_instance_data === true) { getInstanceData($args); }
  }
  if (isset($args->get_instance_names)) {
  if ($args->get_instance_names === true) { getInstanceNames($args); }
  }
}
function getInstanceData($args) {
  global $db;
  $sql_instancedata = "SELECT data, type FROM instance WHERE name = :name";
  if (isset($args->instance_name)) {
    $stmt = $db->prepare($sql_instancedata);
    $stmt->bindValue(':name', $args->instance_name, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch();
    $result['data'] = json_decode($result['data']);
    echo json_encode($result);
  } else {
    echo json_encode(array('status'=>'Error: no instance name?'));
    return;
  }
}
function getInstanceNames($args) {
  global $db;
  $sql_instances = "SELECT name, type FROM instance";
  $result = $db->query($sql_instances)->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($result);
}
function getSpawnData($args) {
  global $db;
  $binds = array();
  if (isset($args->spawns) || isset($args->stops)) {
    if (isset($args->spawns) && count($args->spawns) > 0) {
      $spawns_in  = str_repeat('?,', count($args->spawns) - 1) . '?';
      $binds = array_merge($binds, $args->spawns);
    }
    if (isset($args->stops) && count($args->stops) > 0) {
      $stops_in  = str_repeat('?,', count($args->stops) - 1) . '?';
      $binds = array_merge($binds, $args->stops);
    }
    if ($stops_in && $spawns_in) {
      $points_string = "(pokestop_id IN (" . $stops_in . ") OR spawn_id IN (" . $spawns_in . "))";
    } else if ($stops_in) {
      $points_string = "pokestop_id IN (" . $stops_in . ")";
    } else if ($spawns_in) {
      $points_string = "spawn_id IN (" . $spawns_in . ")";
    } else {
      echo json_encode(array('spawns' => null, 'status'=>'Error: no points!'));
      return;
    }
    if (is_numeric($args->nest_migration_timestamp) && (int)$args->nest_migration_timestamp == $args->nest_migration_timestamp) {
      $ts = $args->nest_migration_timestamp;
    } else {
      $ts = 0;
    }
    $binds[] = $ts;
    if (is_numeric($args->spawn_report_limit) && (int)$args->spawn_report_limit == $args->spawn_report_limit && (int)$args->spawn_report_limit != 0) {
      $limit = " LIMIT " . $args->spawn_report_limit;
    } else {
      $limit = '';
    }
    $sql_spawn = "SELECT pokemon_id, COUNT(pokemon_id) as count FROM pokemon WHERE " . $points_string . " AND first_seen_timestamp >= ? GROUP BY pokemon_id ORDER BY count DESC" . $limit;
    $stmt = $db->prepare($sql_spawn);
    try {
     $stmt->execute($binds);
     } catch (PDOException $e) {
      //var_dump($e);
      var_dump(array('sql_spawnpoint' => $sql_spawn));
      var_dump(array('binds_count' => count($binds), 'stop_count' => count($args->stops), 'spawn_count' => count($args->spawns)));
      var_dump($args);
    }
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  echo json_encode(array('spawns' => $result, 'sql' => $sql_spawn));
}
function getData($args) {
  global $db;
  $binds = array();
  if ($args->show_unknownpois === true) {
    $show_unknown_mod = "name IS ? AND ";
    $show_unknown_mod_sp = "despawn_sec IS NULL AND ";
    $binds[] = null;
  }
  $sql_gym = "SELECT id, lat, lon as lng, ex_raid_eligible as ex, name FROM gym WHERE " . $show_unknown_mod . "lat > ? AND lon > ? AND lat < ? AND lon < ?";
  $stmt = $db->prepare($sql_gym);
  $stmt->execute(array_merge($binds, [$args->min_lat, $args->min_lng, $args->max_lat, $args->max_lng]));
  $gyms = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $sql_pokestop = "SELECT id, lat, lon as lng, name FROM pokestop WHERE " . $show_unknown_mod . "lat > ? AND lon > ? AND lat < ? AND lon < ?";
  $stmt = $db->prepare($sql_pokestop);
  $stmt->execute(array_merge($binds, [$args->min_lat, $args->min_lng, $args->max_lat, $args->max_lng]));
  $stops = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $sql_spawnpoint = "SELECT id, despawn_sec, lat, lon as lng, updated FROM spawnpoint WHERE " . $show_unknown_mod_sp . "lat > ? AND lon > ? AND lat < ? AND lon < ?";
  $stmt = $db->prepare($sql_spawnpoint);
  $stmt->execute([$args->min_lat, $args->min_lng, $args->max_lat, $args->max_lng]);
  $spawns = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode(array('gyms' => $gyms, 'pokestops' => $stops, 'spawnpoints' => $spawns, 'sql_gym' => $sql_gym, 'sql_pokestop' => $sql_pokestop, 'sql_spawnpoint' => $sql_spawnpoint ));
}
function getOptimization($args) {
  global $db;
  if (isset($args->points)) {
    $points = $args->points;
  } else {
    echo json_encode(array('status'=>'Error: no points'));
    return;
  }
  $best_attempt = array();
  for($x=0; $x<$args->optimization_attempts;$x++) {
    shuffle($points);
    $working_gyms = $points;
    $attempt = array();
    while(count($working_gyms) > 0) {
      $gym1 = array_pop($working_gyms);
      foreach ($working_gyms as $i => $gym2) {
        $dist = haversine($gym1, $gym2);
        if ($dist < $args->circle_size) {
          unset($working_gyms[$i]);
        }
      }
      $attempt[] = $gym1;
    }
    if(count($best_attempt) == 0 || count($attempt) < count($best_attempt)) {
      $best_attempt = $attempt;
    }
  }
  if ($args->do_tsp) {
    $working_gyms = $best_attempt;
    $index = rand(0,count($working_gyms)-1);
    $gym1 = $working_gyms[$index];
    while(count($working_gyms) > 0) {
      unset($working_gyms[$index]);
      $final_attempt[] = $gym1;
      unset($working_gyms[$index]);
      foreach ($working_gyms as $i => $gym2) {
        $dist = haversine($gym1, $gym2);
        while ($distances[$dist]) {
          $dist++;
        }
        $distances[$dist] = $gym2;
        $index = $i;
      }
      ksort($distances);
      $closest_gym = array_shift($distances);
      $gym1 = $closest_gym;
    }
    $best_attempt = $final_attempt;
  }
  echo json_encode(array('bestAttempt' => $best_attempt));
}
function haversine($gym1, $gym2) {
  $r = 6371000;
  $latFrom = ($gym1->lat * M_PI / 180);
  $lngFrom = ($gym1->lng * M_PI / 180);
  $latTo = ($gym2->lat * M_PI / 180);
  $lngTo = ($gym2->lng * M_PI / 180);
  $latDelta = $latTo - $latFrom;
  $lngDelta = $lngTo - $lngFrom;
  $a = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
      cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)));
  return $a * $r;
}
?>