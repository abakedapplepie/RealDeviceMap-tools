<?php 

define('DB_TYPE', "mysql");
define('DB_HOST', "1.2.3.4");
define('DB_USER', "rdmuser");
define('DB_PSWD', "password");
define('DB_NAME', "rdmdb");
define('DB_PORT', 3306);

if ($_POST['data']) { map_helper_init(); } else { ?><!DOCTYPE html>
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
    <script type="text/javascript">
    var pokemon = ["Bulbasaur","Ivysaur","Venusaur","Charmander","Charmeleon","Charizard","Squirtle","Wartortle","Blastoise","Caterpie","Metapod","Butterfree","Weedle","Kakuna","Beedrill","Pidgey","Pidgeotto","Pidgeot","Rattata","Raticate","Spearow","Fearow","Ekans","Arbok","Pikachu","Raichu","Sandshrew","Sandslash","Nidoran♀","Nidorina","Nidoqueen","Nidoran♂","Nidorino","Nidoking","Clefairy","Clefable","Vulpix","Ninetales","Jigglypuff","Wigglytuff","Zubat","Golbat","Oddish","Gloom","Vileplume","Paras","Parasect","Venonat","Venomoth","Diglett","Dugtrio","Meowth","Persian","Psyduck","Golduck","Mankey","Primeape","Growlithe","Arcanine","Poliwag","Poliwhirl","Poliwrath","Abra","Kadabra","Alakazam","Machop","Machoke","Machamp","Bellsprout","Weepinbell","Victreebel","Tentacool","Tentacruel","Geodude","Graveler","Golem","Ponyta","Rapidash","Slowpoke","Slowbro","Magnemite","Magneton","Farfetch’d","Doduo","Dodrio","Seel","Dewgong","Grimer","Muk","Shellder","Cloyster","Gastly","Haunter","Gengar","Onix","Drowzee","Hypno","Krabby","Kingler","Voltorb","Electrode","Exeggcute","Exeggutor","Cubone","Marowak","Hitmonlee","Hitmonchan","Lickitung","Koffing","Weezing","Rhyhorn","Rhydon","Chansey","Tangela","Kangaskhan","Horsea","Seadra","Goldeen","Seaking","Staryu","Starmie","Mr. Mime","Scyther","Jynx","Electabuzz","Magmar","Pinsir","Tauros","Magikarp","Gyarados","Lapras","Ditto","Eevee","Vaporeon","Jolteon","Flareon","Porygon","Omanyte","Omastar","Kabuto","Kabutops","Aerodactyl","Snorlax","Articuno","Zapdos","Moltres","Dratini","Dragonair","Dragonite","Mewtwo","Mew","Chikorita","Bayleef","Meganium","Cyndaquil","Quilava","Typhlosion","Totodile","Croconaw","Feraligatr","Sentret","Furret","Hoothoot","Noctowl","Ledyba","Ledian","Spinarak","Ariados","Crobat","Chinchou","Lanturn","Pichu","Cleffa","Igglybuff","Togepi","Togetic","Natu","Xatu","Mareep","Flaaffy","Ampharos","Bellossom","Marill","Azumarill","Sudowoodo","Politoed","Hoppip","Skiploom","Jumpluff","Aipom","Sunkern","Sunflora","Yanma","Wooper","Quagsire","Espeon","Umbreon","Murkrow","Slowking","Misdreavus","Unown","Wobbuffet","Girafarig","Pineco","Forretress","Dunsparce","Gligar","Steelix","Snubbull","Granbull","Qwilfish","Scizor","Shuckle","Heracross","Sneasel","Teddiursa","Ursaring","Slugma","Magcargo","Swinub","Piloswine","Corsola","Remoraid","Octillery","Delibird","Mantine","Skarmory","Houndour","Houndoom","Kingdra","Phanpy","Donphan","Porygon2","Stantler","Smeargle","Tyrogue","Hitmontop","Smoochum","Elekid","Magby","Miltank","Blissey","Raikou","Entei","Suicune","Larvitar","Pupitar","Tyranitar","Lugia","Ho-Oh","Celebi","Treecko","Grovyle","Sceptile","Torchic","Combusken","Blaziken","Mudkip","Marshtomp","Swampert","Poochyena","Mightyena","Zigzagoon","Linoone","Wurmple","Silcoon","Beautifly","Cascoon","Dustox","Lotad","Lombre","Ludicolo","Seedot","Nuzleaf","Shiftry","Taillow","Swellow","Wingull","Pelipper","Ralts","Kirlia","Gardevoir","Surskit","Masquerain","Shroomish","Breloom","Slakoth","Vigoroth","Slaking","Nincada","Ninjask","Shedinja","Whismur","Loudred","Exploud","Makuhita","Hariyama","Azurill","Nosepass","Skitty","Delcatty","Sableye","Mawile","Aron","Lairon","Aggron","Meditite","Medicham","Electrike","Manectric","Plusle","Minun","Volbeat","Illumise","Roselia","Gulpin","Swalot","Carvanha","Sharpedo","Wailmer","Wailord","Numel","Camerupt","Torkoal","Spoink","Grumpig","Spinda","Trapinch","Vibrava","Flygon","Cacnea","Cacturne","Swablu","Altaria","Zangoose","Seviper","Lunatone","Solrock","Barboach","Whiscash","Corphish","Crawdaunt","Baltoy","Claydol","Lileep","Cradily","Anorith","Armaldo","Feebas","Milotic","Castform","Kecleon","Shuppet","Banette","Duskull","Dusclops","Tropius","Chimecho","Absol","Wynaut","Snorunt","Glalie","Spheal","Sealeo","Walrein","Clamperl","Huntail","Gorebyss","Relicanth","Luvdisc","Bagon","Shelgon","Salamence","Beldum","Metang","Metagross","Regirock","Regice","Registeel","Latias","Latios","Kyogre","Groudon","Rayquaza","Jirachi","Deoxys","Turtwig","Grotle","Torterra","Chimchar","Monferno","Infernape","Piplup","Prinplup","Empoleon","Starly","Staravia","Staraptor","Bidoof","Bibarel","Kricketot","Kricketune","Shinx","Luxio","Luxray","Budew","Roserade","Cranidos","Rampardos","Shieldon","Bastiodon","Burmy","Wormadam","Mothim","Combee","Vespiquen","Pachirisu","Buizel","Floatzel","Cherubi","Cherrim","Shellos","Gastrodon","Ambipom","Drifloon","Drifblim","Buneary","Lopunny","Mismagius","Honchkrow","Glameow","Purugly","Chingling","Stunky","Skuntank","Bronzor","Bronzong","Bonsly","Mime Jr.","Happiny","Chatot","Spiritomb","Gible","Gabite","Garchomp","Munchlax","Riolu","Lucario","Hippopotas","Hippowdon","Skorupi","Drapion","Croagunk","Toxicroak","Carnivine","Finneon","Lumineon","Mantyke","Snover","Abomasnow","Weavile","Magnezone","Lickilicky","Rhyperior","Tangrowth","Electivire","Magmortar","Togekiss","Yanmega","Leafeon","Glaceon","Gliscor","Mamoswine","Porygon-Z","Gallade","Probopass","Dusknoir","Froslass","Rotom","Uxie","Mesprit","Azelf","Dialga","Palkia","Heatran","Regigigas","Giratina","Cresselia","Phione","Manaphy","Darkrai","Shaymin","Arceus"];
    </script>
<script type="text/javascript">
//map and control vars
var map;

var manualCircle = false;

var drawControl,
  buttonManualCircle,
  buttonImportNests,
  buttonModalImportPolygon,
  buttonModalImportInstance,
  buttonTrash,
  buttonTrashRoute,
  barShowPolyOpts,
  buttonGenerateRoute,
  buttonOptimizeRoute,
  barRoute,
  buttonModalOutput,
  buttonMapModePoiViewer,
  buttonMapModeRouteGenerator,
  buttonShowGyms,
  buttonShowPokestops,
  buttonShowSpawnpoints,
  buttonShowUnknownPois,
  buttonSettingsModal;

//data vars
var gyms = [],
  pokestops = [],
  spawnpoints = [];

//options vars
var settings = {
  showGyms: null,
  showPokestops: null,
  showSpawnpoints: null,
  showUnknownPois: null,
  circleSize: null,
  optimizationAttempts: null,
  nestMigrationDate: null,
  mapMode: null,
  mapCenter: null,
  mapZoom: null
};

//map layer vars
var gymLayer,
  pokestopLayer,
  spawnpointLayer,
  editableLayer,
  circleLayer,
  nestLayer;

$(function(){
  loadSettings();
  initMap();
  setMapMode();
  setShowMode();

  $('#nestMigrationDate').datetimepicker('sideBySide', true)

  $('#savePolygon').on('click', function(event) {
    var polygonData = [];
    var importReady = true
    //TODO: add error handling
    //TODO: add check for json or txt
    //TODO: add support for multi poly json array
    var importType = $("#importPolygonForm input[name=importPolygonDataType]:checked").val()

     if (importType == 'importPolygonDataTypeCoordList') {
      polygonData.push(csvtoarray($('#importPolygonData').val()));
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
        var newPolygon = L.polygon(polygon, polygonOptions).bindPopup(function (layer) {
          var output = '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">Imported Polygon</span></div>' +
                       '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm getSpawnReport" data-layer-container="editableLayer" data-layer-id=' +
                       layer._leaflet_id +
                       ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Get spawn report</span></div></div>' +
                       '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="editableLayer" data-layer-id=' +
                       layer._leaflet_id +
                       ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Remove from map</span></div></div>' +
                       '<div class="input-group"><button class="btn btn-secondary btn-sm exportLayer" data-layer-container="editableLayer" data-layer-id=' +
                       layer._leaflet_id +
                       ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Export Polygon</span></div></div>';
          return output;
        }).addTo(editableLayer);
      });
    }
    $('#modalImport').modal('hide');
  });

  $('#importInstance').on('click', function(event) {
     var name = $("#importInstanceName" ).val();
     getInstance(name);
   });

  $('#modalSpawnReport').on('hidden.bs.modal', function(event) {
    $('#spawnReportTable > tbody').empty();
    $('#modalSpawnReport .modal-title').text();
  });

  $('#modalOutput').on('hidden.bs.modal', function(event) {
    $('#outputCircles').val('');
  });

  $('#modalSettings').on('hidden.bs.modal', function(event) {
    var circleSize = $('#circleSize').val();
    var optimizationAttempts = $('#optimizationAttempts').val();
    var nestMigrationDate = moment($("#nestMigrationDate").datetimepicker('date')).local().format('X');

    const newSettings = {
      circleSize: circleSize,
      optimizationAttempts: optimizationAttempts,
      nestMigrationDate: nestMigrationDate
    };

    Object.keys(newSettings).forEach(function(key) {
      if (settings[key] != newSettings[key]) {
        settings[key] = newSettings[key];
        storeSetting(key);
      }
    });
  });

  $('#cancelSettings').on('click', function(event) {
    processSettings(true);
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

  map = L.map('map', {
    zoomDelta: 0.25, 
    zoomSnap: 0.25,
    wheelPxPerZoomLevel: 30}).addLayer(osm).setView(settings.mapCenter, settings.mapZoom);

  circleLayer = new L.FeatureGroup();
  circleLayer.addTo(map);

  editableLayer = new L.FeatureGroup();
  editableLayer.addTo(map);

  gymLayer = new L.LayerGroup();
  gymLayer.addTo(map);

  pokestopLayer = new L.LayerGroup();
  pokestopLayer.addTo(map);

  spawnpointLayer = new L.LayerGroup();
  spawnpointLayer.addTo(map);

  nestLayer = new L.LayerGroup();
  nestLayer.addTo(map);

  buttonMapModePoiViewer = L.easyButton({
    id: 'enableMapModePoiViewer',
    states: [{
      stateName: 'enableMapModePoiViewer',
      icon: 'fas fa-binoculars',
      title: 'POI Viewer',
      onClick: function (btn) {
        settings.mapMode = 'PoiViewer';
        storeSetting('mapMode');
        setMapMode();
      }
    }]
  })

  buttonMapModeRouteGenerator = L.easyButton({
    id: 'enableMapModeRouteGenerator',
    states: [{
      stateName: 'enableMapModeRouteGenerator',
      icon: 'fas fa-route',
      title: 'Route Generator',
      onClick: function (btn) {
        settings.mapMode = 'RouteGenerator';
        storeSetting('mapMode');
        setMapMode();
      }
    }]
  })

  var barMapMode = L.easyBar([buttonMapModeRouteGenerator, buttonMapModePoiViewer], { position: 'topright' }).addTo(map);

  buttonShowGyms = L.easyButton({
    id: 'showGyms',
    states: [{
      stateName: 'enableShowGyms',
      icon: 'fas fa-dumbbell',
      title: 'Hide gyms',
      onClick: function (btn) {
        settings.showGyms = false;
        storeSetting('showGyms');
        setShowMode();
        }
    }, {
      stateName: 'disableShowGyms',
      icon: 'fas fa-dumbbell',
      title: 'Show gyms',
      onClick: function (btn) {
        settings.showGyms = true;
        storeSetting('showGyms');
        setShowMode();
      }
    }]
  })

  buttonShowPokestops = L.easyButton({
    id: 'showPokestops',
    states: [{
      stateName: 'enableShowPokestops',
      icon: 'fas fa-map-pin',
      title: 'Hide pokestops',
      onClick: function (btn) {
        settings.showPokestops = false;
        storeSetting('showPokestops');
        setShowMode();
      }
    }, {
      stateName: 'disableShowPokestops',
      icon: 'fas fa-map-pin',
      title: 'Show pokestops',
      onClick: function (btn) {
        settings.showPokestops = true;
        storeSetting('showPokestops');
        setShowMode();
      }
    }]
  })

  buttonShowSpawnpoints = L.easyButton({
    id: 'showSpawnpoints',
    states:[{
      stateName: 'enableShowSpawnpoints',
      icon: 'fas fa-paw',
      title: 'Hide spawnpoints',
      onClick: function (btn) {
        settings.showSpawnpoints = false;
        storeSetting('showSpawnpoints');
        setShowMode();
      }
    }, {
      stateName: 'disableShowSpawnpoints',
      icon: 'fas fa-paw',
      title: 'Show spawnpoints',
      onClick: function (btn) {
        settings.showSpawnpoints = true;
        storeSetting('showSpawnpoints');
        setShowMode();
      }
    }]
  })

  buttonShowUnknownPois = L.easyButton({
    id: 'showUnknownPois',
    states:[{
      stateName: 'enableShowUnknownPois',
      icon: 'fas fa-question-circle',
      title: 'Show all POIs',
      onClick: function (btn) {
        settings.showUnknownPois = false;
        storeSetting('showUnknownPois');
        setShowMode();
      }
    }, {
      stateName: 'disableShowUnknownPois',
      icon: 'fas fa-question-circle',
      title: 'Show unknown POIs only',
      onClick: function (btn) {
        settings.showUnknownPois = true;
        storeSetting('showUnknownPois');
        setShowMode();
      }
    }]
  })

  var barShowPOIs = L.easyBar([buttonShowGyms, buttonShowPokestops, buttonShowSpawnpoints, buttonShowUnknownPois], { position: 'topright' }).addTo(map);

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
      featureGroup: editableLayer,
      edit: false,
      remove: false,
      poly: false
    }
  }).addTo(map);

  buttonManualCircle = L.easyButton({
    states: [{
      stateName: 'enableManualCircle',
      icon: 'far fa-circle',
      title: 'Enable manual circle mode',
      onClick: function (btn) {
        manualCircle = true;
        btn.state('disableManualCircle');
      }
    }, {
      stateName: 'disableManualCircle',
      icon: 'fas fa-circle',
      title: 'Disable manual circle mode',
      onClick: function (btn) {
        manualCircle = false;
        btn.state('enableManualCircle');
      }
    }]
  });

  buttonImportNests = L.easyButton({
    states: [{
      stateName: 'openImportPolygonModal',
      icon: 'fas fa-tree',
      title: 'Import nests from OSM',
      onClick: function (control){
        getNests();
      }
    }]
  });

  buttonModalImportPolygon = L.easyButton({
    states: [{
      stateName: 'openImportPolygonModal',
      icon: 'fas fa-draw-polygon',
      title: 'Import polygon',
      onClick: function (control){
        $('#modalImportPolygon').modal('show');
      }
    }]
  });

  buttonModalImportInstance = L.easyButton({
    states: [{
      stateName: 'openImportInstanceModal',
      icon: 'fas fa-truck-loading',
      title: 'Import instance',
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
      title: 'Clear route',
      onClick: function (control){
        circleLayer.clearLayers();
      }
    }]
  });

  buttonTrash = L.easyButton({
    states: [{
      stateName: 'clearMap',
      icon: 'fas fa-trash',
      title: 'Clear all shapes',
      onClick: function (control){
        circleLayer.clearLayers();
        editableLayer.clearLayers();
        nestLayer.clearLayers();
      }
    }]
  });

  barShowPolyOpts = L.easyBar([buttonManualCircle, buttonImportNests, buttonModalImportPolygon, buttonModalImportInstance, buttonTrashRoute, buttonTrash], { position: 'topleft' }).addTo(map);

  buttonGenerateRoute = L.easyButton({
    id: 'generateRoute',
    states:[{
      stateName: 'generateRoute',
      icon: 'fas fa-cookie',
      title: 'Generate route',
      onClick: function (btn) {
        generateRoute();
      }
    }]
  })

  buttonOptimizeRoute = L.easyButton({
    id: 'optimizeRoute',
    states:[{
      stateName: 'optimizeRoute',
      icon: 'fas fa-cookie-bite',
      title: 'Generate optimized route',
      onClick: function (btn) {
        generateOptimizedRoute();
      }
    }]
  })

  barRoute = L.easyBar([buttonGenerateRoute, buttonOptimizeRoute], { position: 'topleft' }).addTo(map);

  buttonModalOutput = L.easyButton({
    states: [{
      stateName: 'openOutputModal',
      icon: '<strong>GO</strong>',
      title: 'Get output',
      onClick: function (control){
        $('#modalOutput').modal('show');
      }
    }]
  }).addTo(map);

  buttonModalSettings = L.easyButton({
    position: 'bottomleft',
    states: [{
      stateName: 'openSettingsModal',
      icon: 'fas fa-cog',
      title: 'Open settings',
      onClick: function (control){

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

        if (settings.nestMigrationDate != null) {
          $('#nestMigrationDate').datetimepicker('date', moment.unix(settings.nestMigrationDate).utc().local().format('MM/DD/YYYY HH:mm'));
        }
        $('#modalSettings').modal('show');
      }
    }]
  }).addTo(map);

  map.on('draw:created', function (e) {
    var layer = e.layer;
    layer.bindPopup(function (layer) {
      var output = '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">Polygon</span></div>' +
                   '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm getSpawnReport" data-layer-container="editableLayer" data-layer-id=' +
                   layer._leaflet_id +
                   ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Get spawn report</span></div></div>' +
                   '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="editableLayer" data-layer-id=' +
                   layer._leaflet_id +
                   ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Remove from map</span></div></div>' +
                   '<div class="input-group"><button class="btn btn-secondary btn-sm exportLayer" data-layer-container="editableLayer" data-layer-id=' +
                   layer._leaflet_id +
                   ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Export Polygon</span></div></div>';
      return output;
    }).addTo(editableLayer);
  });

  map.on('draw:drawstart', function(e) {
    manualCircle = false;
    buttonManualCircle.state('enableManualCircle');

  });

  map.on('zoomend', function() {
    settings.mapCenter = map.getCenter();
    storeSetting('mapCenter');
    settings.mapZoom = map.getZoom();
    storeSetting('mapZoom');
    loadData();
  });

  map.on('moveend', function() {
    settings.mapCenter = map.getCenter();
    storeSetting('mapCenter');
    settings.mapZoom = map.getZoom();
    storeSetting('mapZoom');
    loadData();
  });

  map.on('dragend', function() {
    settings.mapCenter = map.getCenter();
    storeSetting('mapCenter');
    settings.mapZoom = map.getZoom();
    storeSetting('mapZoom');
    loadData();
  });

  map.on('click', function(e) {
    if (manualCircle === true) {
      var newCircle = new L.circle(e.latlng, {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: settings.circleSize
      }).bindPopup(function (layer) {
        return '<button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayer" data-layer-id=' + layer._leaflet_id + ' type="button">Delete</button></div>';
      }).addTo(circleLayer);
    }
  });
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

  if (settings.showSpawnpoints !== false) {
    buttonShowSpawnpoints.state('enableShowSpawnpoints');
    buttonShowSpawnpoints.button.style.backgroundColor = '#B7E9B7';
  } else {
    spawnpointLayer.clearLayers();
    buttonShowSpawnpoints.state('disableShowSpawnpoints');
    buttonShowSpawnpoints.button.style.backgroundColor = '#E9B7B7';
  }

  if (settings.showUnknownPois !== false) {
    buttonShowUnknownPois.state('enableShowUnknownPois');
    buttonShowUnknownPois.button.style.backgroundColor = '#B7E9B7';
  } else {
    buttonShowUnknownPois.state('disableShowUnknownPois');
    buttonShowUnknownPois.button.style.backgroundColor = '#E9B7B7';
  }
  loadData();
}

function setMapMode(){
  switch (settings.mapMode) {
    case 'RouteGenerator':
      buttonMapModePoiViewer.button.style.backgroundColor = '#E9B7B7';
      buttonMapModeRouteGenerator.button.style.backgroundColor = '#B7E9B7';

      $('.leaflet-draw').show();
      buttonShowUnknownPois.disable();
      barShowPolyOpts.enable();
      barRoute.enable();
      buttonModalOutput.enable();
      break;

    case 'PoiViewer':
      buttonMapModePoiViewer.button.style.backgroundColor = '#B7E9B7';
      buttonMapModeRouteGenerator.button.style.backgroundColor = '#E9B7B7';

      editableLayer.clearLayers();
      circleLayer.clearLayers();
      nestLayer.clearLayers();

      $('.leaflet-draw').hide();
      buttonShowUnknownPois.enable();
      barShowPolyOpts.disable();
      barRoute.disable();
      buttonModalOutput.disable();
      break;
  }
  loadData();
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
        circleLayer.clearLayers();
          points = result.area;
          if (points.length > 0 ) {
            points.forEach(function(item) {
              newCircle = L.circle(item, {
                color: '#87CEFA',
                fillOpacity: 0.5,
                radius: settings.circleSize
              }).bindPopup(function (layer) {
                return '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayer" data-layer-id=' + layer._leaflet_id + ' type="button">Delete</button></div>';
              }).addTo(circleLayer);
            });
          }
      }
    });
  }
}

function generateOptimizedRoute() {
  circleLayer.clearLayers();

  var newCircle,
    currentLatLng,
    point;

  var points = [];

  var route = function(layer) {
    var poly = layer.toGeoJSON();
    var line = turf.polygonToLine(poly);

    if (settings.showGyms == true) {
      gyms.forEach(function(item) {
        point = turf.point([item.lng, item.lat]);
        if (turf.inside(point, poly)) {
          points.push(item)
        }
      });
    }
    if (settings.showPokestops == true) {
      stops.forEach(function(item) {
        point = turf.point([item.lng, item.lat]);
        if (turf.inside(point, poly)) {
          points.push(item)
        }
      });
    }
    if (settings.showSpawnpoints == true) {
      spawns.forEach(function(item) {
        point = turf.point([item.lng, item.lat]);
        if (turf.inside(point, poly)) {
          points.push(item)
        }
      });
    }
  }

  editableLayer.eachLayer(function (layer) {
     route(layer);
  });

  nestLayer.eachLayer(function (layer) {
     route(layer);
  });

  const data = {
    'get_optimization': true,
    'circle_size': settings.circleSize,
    'optimization_attempts': settings.optimizationAttempts,
    'points': points
  };
  const json = JSON.stringify(data);

  var sent = points.length;

  $.ajax({
    url: this.href,
    type: 'POST',
    dataType: 'json',
    data: {'data': json},
    success: function (result) {
      var recieved = result.bestAttempt.length;
      console.log("sent: " + sent + ", recieved: " + recieved);
      result.bestAttempt.forEach(function(point) {
         newCircle = L.circle([point.lat, point.lng], {
          color: 'red',
          fillColor: '#f03',
          fillOpacity: 0.5,
          radius: settings.circleSize
        }).bindPopup(function (layer) {
          return '<button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayer" data-layer-id=' + layer._leaflet_id + ' type="button">Delete</button></div>';
        }).addTo(circleLayer);
      });
    }
  });
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
            radius: settings.circleSize
          }).bindPopup(function (layer) {
            return '<button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayer" data-layer-id=' + layer._leaflet_id + ' type="button">Delete</button></div>';
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

function getSpawnReport(layer) {
  var reportStops = [],
    reportSpawns = [];

  var poly = layer.toGeoJSON();
  var line = turf.polygonToLine(poly);

  stops.forEach(function(item) {
    point = turf.point([item.lng, item.lat]);
    if (turf.inside(point, poly)) {
      reportStops.push(item.id);
    }
  });
  spawns.forEach(function(item) {
    point = turf.point([item.lng, item.lat]);
    if (turf.inside(point, poly)) {
      reportStops.push(item.id);
    }
  });

  const data = {
    'get_spawndata': true,
    'nest_migration_timestamp': settings.nestMigrationDate,
    'stops': reportStops,
    'spawns': reportSpawns
  };
  const json = JSON.stringify(data);

  $.ajax({
    url: this.href,
    type: 'POST',
    dataType: 'json',
    data: {'data': json},
    success: function (result) {
      if (result.spawns !== null) {
        result.spawns.forEach(function(item) {
          if (typeof layer.tags !== 'undefined') {
            $('#modalSpawnReport  .modal-title').text('Spawn Report - ' + layer.tags.name);
          }
          $('#spawnReportTable > tbody:last-child').append('<tr><td>' +pokemon[item.pokemon_id-1] + '</td><td>' + item.count + '</td></tr>');
          $('#modalSpawnReport').modal('show');
        });
      } else {
          if (typeof layer.tags !== 'undefined') {
          $('#modalSpawnReport  .modal-title').text('Spawn Report - ' + layer.tags.name);
        }
        $('#spawnReportTable > tbody:last-child').append('<tr><td colspan="2">No data available.</td></tr>');
        $('#modalSpawnReport').modal('show');

      }
    }
  });
}

function getNests() {
  circleLayer.clearLayers();
  editableLayer.clearLayers();
  nestLayer.clearLayers();

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
            var name = '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">Nest: ' + layer.tags.name + '</span></div>';
          }
          var output = name +
                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm getSpawnReport" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Get spawn report</span></div></div>' +
                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Remove from map</span></div></div>' +
                  '<div class="input-group"><button class="btn btn-secondary btn-sm exportLayer" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Export Polygon</span></div></div>';
          return output;
        });
      });
    }
  });
}

function csvtoarray(dataString) {
  var lines = dataString
    .split(/\n/)           // Convert to one string per line
    .map(function(lineStr) {
      return lineStr.split(",");   // Convert each line to array (,)
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
      gymLayer.clearLayers();
      spawnpointLayer.clearLayers();

      gyms = [];
      stops = [];
      spawns = [];

      if (result.gyms != null) {
        result.gyms.forEach(function(item) {
          gyms.push(item);
          if (settings.showGyms === true) {
            var marker = L.circleMarker([item.lat, item.lng], {
              color: 'red',
              radius: 2,
              opacity: 0.6
            }).addTo(map);
            marker.tags = {};
            marker.tags.id = item.id;
            marker.bindPopup("<span>ID: " + item.id + "</span>").addTo(gymLayer);
          }
        });
      }

      if (result.pokestops != null) {
        result.pokestops.forEach(function(item) {
          stops.push(item);
          if (settings.showPokestops === true) {
            var marker = L.circleMarker([item.lat, item.lng], {
              color: 'green',
              radius: 2,
              opacity: 0.6
            }).addTo(map);
            marker.tags = {};
            marker.tags.id = item.id;
            marker.bindPopup("<span>ID: " + item.id + "</span>").addTo(pokestopLayer);
          }
        });
      }showPokestops

      if (result.spawnpoints != null) {
        result.spawnpoints.forEach(function(item) {
          spawns.push(item);
          if (settings.showSpawnpoints === true) {
            var marker = L.circleMarker([item.lat, item.lng], {
              color: 'blue',
              radius: 1,
              opacity: 0.6
            }).addTo(map);
            marker.tags = {};
            marker.tags.id = item.id;
            marker.bindPopup("<span>ID: " + item.id + "</span>").addTo(spawnpointLayer);
          }
        });
      }
    }
  });
}

$(document).ready(function() {
  $('input[type=radio][name=exportPolygonDataType]').change(function() {
    if (this.value == 'exportPolygonDataTypeCoordsList') {
      $('#exportPolygonDataCoordsList').show();
      $('#exportPolygonDataGeoJson').hide();
    }
    else if (this.value == 'exportPolygonDataTypeGeoJson') {
      $('#exportPolygonDataCoordsList').hide();
      $('#exportPolygonDataGeoJson').show();
    }
  });
  $('#getOutput').click(function() {
    $('#outputCircles').val('');
    var allCircles = circleLayer.getLayers();
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
    case 'circleLayer':
      circleLayer.removeLayer(parseInt(id));
      break;
    case 'editableLayer':
      editableLayer.removeLayer(parseInt(id));
      break;
    case 'nestLayer':
      nestLayer.removeLayer(parseInt(id));
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
  getSpawnReport(layer);
});

$(document).on("click", "#getAllNests", function() {
  var missedCount = 0;
  nestLayer.eachLayer(function(layer) {
    var reportStops = [],
      reportSpawns = [];

    var center = layer.getBounds().getCenter()

    var poly = layer.toGeoJSON();
    var line = turf.polygonToLine(poly);

    stops.forEach(function(item) {
      point = turf.point([item.lng, item.lat]);
      if (turf.inside(point, poly)) {
        reportStops.push(item.id);
      }
    });
    spawns.forEach(function(item) {
      point = turf.point([item.lng, item.lat]);
      if (turf.inside(point, poly)) {
        reportStops.push(item.id);
      }
    });

    const data = {
      'get_spawndata': true,
      'nest_migration_timestamp': settings.nestMigrationDate,
      'stops': reportStops,
      'spawns': reportSpawns
    };
    const json = JSON.stringify(data);

    $.ajax({
      url: this.href,
      type: 'POST',
      dataType: 'json',
      data: {'data': json},
      success: function (result) {
        if (result.spawns !== null) {
          if (typeof layer.tags.name !== 'undefined') {
            $('#spawnReportTable > tbody:last-child').append('<tr><td colspan="2"><strong>Spawn Report - ' + layer.tags.name + '</strong> <em style="font-size:xx-small">at ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + '</em></td></tr>');
          } else {
            $('#spawnReportTable > tbody:last-child').append('<tr><td colspan="2"><strong>Spawn Report - Unnamed</strong> at <em style="font-size:xx-small">' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + '</em></td></tr>');
          }
          result.spawns.forEach(function(item) {
            $('#spawnReportTable > tbody:last-child').append('<tr><td>' +pokemon[item.pokemon_id-1] + '</td><td>' + item.count + '</td></tr>');
          });
        } else {
          if (typeof layer.tags.name !== 'undefined') {
            $('#spawnReportTableMissed > tbody:last-child').append('<tr><td colspan="2"><em style="font-size:xx-small"><strong>' + layer.tags.name + '</strong>  at ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + ' skipped, no data</em></td></tr>');
          } else {
            $('#spawnReportTableMissed > tbody:last-child').append('<tr><td colspan="2"><em style="font-size:xx-small"><strong>Unnamed</strong> at ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + ' skipped, no data</em></td></tr>');
          }
        }
      }
    });
    $('#modalSpawnReport  .modal-title').text('Nest Report - All Nests in View');
    $('#modalSettings').modal('hide');
    $('#modalSpawnReport').modal('show');
  });
});

$(document).on("click", ".exportLayer", function() {
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

  var polyjson = JSON.stringify(layer.toGeoJSON());
  $('#exportPolygonDataGeoJson').val(polyjson);
  
  var polycoords = '';
  turf.flip(layer.toGeoJSON()).geometry.coordinates[0].forEach(function(item) {
    polycoords += item[0] + ',' + item[1] + "\n";
  });
  
  $('#exportPolygonDataCoordsList').val(polycoords);

  $('#exportPolygonDataGeoJson').hide();
  $('#modalExportPolygon').modal('show');
});



function loadSettings() {

   const defaultSettings = {
    showGyms: true,
    showPokestops: true,
    showSpawnpoints: false,
    showUnknownPois: false,
    circleSize: 500,
    optimizationAttempts: 100,
    nestMigrationDate: 1539201600,
    mapMode: 'PoiViewer',
    mapCenter: [42.548197, -83.14684],
    mapZoom: 13
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

            <div class="input-group mb-3 date" id="nestMigrationDate" data-target-input="nearest">
              <div class="input-group-prepend">
                <span class="input-group-text">Last Nest Migration:</span>
              </div>
              <input type="text" class="form-control datetimepicker-input" data-target="#nestMigrationDate"/>
              <div class="input-group-append" data-target="#nestMigrationDate" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Route Optimization Attempts:</span>
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
            <div class="btn-toolbar">
              <div class="btn-group" role="group" aria-label="">
                <button id="getAllNests" class="btn btn-primary float-left" type="button">Get all nest reports</button>
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
                <button id="getOutput" class="btn btn-primary float-left" type="button">Get output</button>
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
            <label for="importInstanceName">Select an instance:</label>
            <div class="input-group mb">
              <select name="importInstanceName" id="importInstanceName" class="form-control" aria-label="Select an instance to import">
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
      <form id="importPolygonForm">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Import Polygon</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <label for="importPolygonDataType">Polygon data type:</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="importPolygonDataType" id="importPolygonDataTypeCoordList" value="importPolygonDataTypeCoordList" checked>
                <label class="form-check-label" for="importPolygonDataTypeCoordList">
                  Coordinate list (latitude,longitude on each new line)
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="importPolygonDataType" id="importPolygonDataTypeGeoJson" value="importPolygonDataTypeGeoJson">
                <label class="form-check-label" for="importPolygonDataTypeGeoJson">
                  GeoJSON
                </label>
              </div>
              <div class="form-check disabled">
                <input class="form-check-input" type="radio" name="importPolygonDataType" id="importPolygonDataTypeKmlGeo" value="importPolygonDataTypeKmlGeo" disabled>
                <label class="form-check-label" for="importPolygonDataTypeKmlGeo">
                  KML Geo (not yet implented)
                </label>
              </div>
              <label for="importPolygonData">Polygon data:</label>
              <div class="input-group mb">
                <textarea name="importPolygonData" id="importPolygonData" style="height:400px;" class="form-control" aria-label="Polygon data"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="savePolygon" class="btn btn-primary">Import</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <div class="modal" id="modalExportPolygon" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Export Polygon</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <label for="exportPolygonDataType">Polygon data type:</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="exportPolygonDataType" id="exportPolygonDataTypeCoordsList" value="exportPolygonDataTypeCoordsList" checked>
                <label class="form-check-label" for="exportPolygonDataTypeCoordsList">
                  Coordinate list (latitude,longitude on each new line)
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="exportPolygonDataType" id="exportPolygonDataTypeGeoJson" value="exportPolygonDataTypeGeoJson">
                <label class="form-check-label" for="exportPolygonDataTypeGeoJson">
                  GeoJSON
                </label>
              </div>
            <label for="exportPolygonData">Polygon data:</label>
            <div class="input-group mb">
              <textarea name="exportPolygonDataGeoJson" id="exportPolygonDataGeoJson" style="height:400px;" class="form-control" aria-label="Polygon data"></textarea>
            </div>
            <div class="input-group mb">
              <textarea name="exportPolygonDataCoords" id="exportPolygonDataCoordsList" style="height:400px;" class="form-control" aria-label="Polygon data"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="exportPolygonClose" class="btn btn-primary" data-dismiss="modal">Close</button>
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
                  <th scope="col">Pokemon</th>
                  <th scope="col">Count</th>
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

  $db = initDB(DB_HOST, DB_USER, DB_PSWD, DB_NAME, DB_PORT);

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

  if ($args->spawns || $args->stops) {

      $spawns_string = implode("','", $args->spawns);
      $stops_string = implode("','", $args->stops);

      if (is_numeric($args->nest_migration_timestamp) && (int)$args->nest_migration_timestamp == $args->nest_migration_timestamp) {
        $ts = $args->nest_migration_timestamp;
      } else {
        $ts = 0;
      }$sql_spawn = "SELECT pokemon_id, COUNT(pokemon_id) as count FROM rdmdb.pokemon WHERE (pokestop_id IN ('" . $stops_string . "') OR spawn_id IN ('" . $spawns_string . "')) AND first_seen_timestamp >= " . $ts . " GROUP BY pokemon_id ORDER BY count DESC";
      if ($stmt = $db->prepare($sql_spawn)) {

        $stmt->execute();

        $result = $stmt->get_result();
        while ($data = $result->fetch_array()) {
          $spawns[] = array(
            'pokemon_id' => $data['pokemon_id'],
            'count' => $data['count'],
          );
        }
      }
  }
  echo json_encode(array('spawns' => $spawns, 'sql' => $sql_spawn));
}

function getData($args) {
  global $db;
  $show_unknown_mod = ($args->show_unknownpois === true ? "name IS null AND " : "");

  $sql_gym = "SELECT id, lat, lon FROM gym WHERE " . $show_unknown_mod . "lat > ? AND lon > ? AND lat < ? AND lon < ?";
  if ($stmt = $db->prepare($sql_gym)) {
    $stmt->bind_param("dddd", $args->min_lat, $args->min_lng, $args->max_lat, $args->max_lng);

    $stmt->execute();
    $result = $stmt->get_result();
    while ($data = $result->fetch_array()) {
      $gyms[] = array(
        'id' => $data['id'],
        'lat' => $data['lat'],
        'lng' => $data['lon']
      );
    }
  }

  $sql_pokestop = "SELECT id, lat, lon FROM pokestop WHERE " . $show_unknown_mod . "lat > ? AND lon > ? AND lat < ? AND lon < ?";
  if ($stmt = $db->prepare($sql_pokestop)) {
    $stmt->bind_param("dddd", $args->min_lat, $args->min_lng, $args->max_lat, $args->max_lng);

    $stmt->execute();

    $result = $stmt->get_result();
    while ($data = $result->fetch_array()) {
      $stops[] = array(
        'id' => $data['id'],
        'lat' => $data['lat'],
        'lng' => $data['lon']
      );
    }
  }

  $sql_spawnpoint = "SELECT id, lat, lon FROM spawnpoint WHERE lat > ? AND lon > ? AND lat < ? AND lon < ?";
  if ($stmt = $db->prepare($sql_spawnpoint)) {
    $stmt->bind_param("dddd", $args->min_lat, $args->min_lng, $args->max_lat, $args->max_lng);

    $stmt->execute();

    $result = $stmt->get_result();
    while ($data = $result->fetch_array()) {
      $spawns[] = array(
        'id' => $data['id'],
        'lat' => $data['lat'],
        'lng' => $data['lon']
      );
    }
  }


  echo json_encode(array('gyms' => $gyms, 'pokestops' => $stops, 'spawnpoints' => $spawns));
}

function getOptimization($args) {
  global $db;
  $points = $args->points;
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
