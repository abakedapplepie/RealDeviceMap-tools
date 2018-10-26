<?php
$DB_TYPE = "mysql";
$DB_HOST = "1.2.3.4";
$DB_USER = "rdmuser";
$DB_PSWD = "password";
$DB_NAME = "rdmdb";
$DB_PORT = 3306;


$db = new mysqli($DB_HOST, $DB_USER, $DB_PSWD, $DB_NAME, $DB_PORT);
if ($db->connect_error != '') {
    exit("Failed to connect to MySQL server!");
}
$db->set_charset('utf8');

$sql = "SELECT id, lat, lon FROM gym WHERE name IS null";
$result = $db->query($sql);
while ($point = $result->fetch_array()) {
    $gyms[] = array(
        'id' => $point['id'],
        'lat' => $point['lat'],
        'lon' => $point['lon']
    );
}
$gymjson = json_encode($gyms);

$sql = "SELECT id, lat, lon FROM pokestop WHERE name IS null";
$result = $db->query($sql);
while ($point = $result->fetch_array()) {
    $stops[] = array(
        'id' => $point['id'],
        'lat' => $point['lat'],
        'lon' => $point['lon']
    );
}
$stopjson = json_encode($stops);
?><!DOCTYPE html>
<html>
    <head>
        <title>Unknown Finder</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <style>
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            #map {
                height: 100%;
            }
        </style>
        
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>
        
        <script type="application/json" id="gyms"><?php echo $gymjson; ?></script>
        <script type="application/json" id="pokestops"><?php echo $stopjson; ?></script>
    </head>
    <body>    
        <div id="map"></div>
        </div>
        <script>
var gyms = JSON.parse(document.getElementById('gyms').innerHTML);
var pokestops = JSON.parse(document.getElementById('pokestops').innerHTML);

var map = L.map('map').setView([42.548197, -83.14684], 12);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
    maxZoom: 18
}).addTo(map);
for (i=0;i<gyms.length;i++) {
    var circle = L.circle([gyms[i].lat, gyms[i].lon], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: 10
    }).addTo(map);
    circle.bindPopup("ID: " + gyms[i].id);
}
for (i=0;i<pokestops.length;i++) {
    var circle = L.circle([pokestops[i].lat, pokestops[i].lon], {
        color: 'green',
        fillColor: '#0f3',
        fillOpacity: 0.5,
        radius: 10
    }).addTo(map);
    circle.bindPopup("ID: " + pokestops[i].id);
}    
        </script>
    </body>
</html>
