# RealDeviceMap-tools
Tools for managing RealDeviceMap data

## rdm-tools.php
This file contains all tools in one go.

### Features
* Nest polygon importer
  * Query Overpass for nests from the latest PoGo OSM sync
  * Currently only queries "parks" and "recreation areas"
* Coordinate polygon importer
  * Import a pre-generated polygon coordinate set
  * GeoJSON polygon importing coming soon
* Route generator
  * Generate a snaking route of coordinates for all polygons on the map
* Route optimizer
  * Generate a route optimized for existing points - gyms, stops, and/or spawns. 
* Instance importer
  * Import an instance from RDM. View the coordinates, delete unneeded coordinates, etc. Does not save back to DB, manually save in Dashboard.
* Delete any generated circle by clicking on it
* When viewing gyms, stops, and spawns you can click on the marker to get the ID from the database
* When viewing gyms and stops, turn on the unknown POI feature to show POIs that you do not have the metadata for yet. Useful for using the Ingress Intel map.

#### Nest scanning
The best way to use the nest scanning feature is to import the nest polygons, generate a route, run that route in RDM to get a list of all spawnpoints into your RDM DB, then go back and import nest polygons and run an optimized route on those polygons.

#### Features coming soon
* GeoJSON polygon importing
* Manually add circles to your current route
* Query RDM for last pokemon spawned - for running nest reports (eventually)
  * Along those lines my grand scheme is to poll the spawns db while nest scanning to get statistics over time to build a highly confident nest report

Have a feature you would like to see? I'm in the RDM Discord, hit me up.

# The following are deprecated:

These tools are available in the main rdm-tools.php file and will no longer be maintained separately.

## circle-generator.html
Using Leaflet and OSM now.

Draw one or more polygons using the polygon editor (polygon icon), when each polygon is completed a set of coordinates will be generated and represented as circles based on your circle size setting, which you can change under the toolbox menu. To view the output, click the checkbox icon.

## unknown-helper.php
Connects to your database and shows you all the gyms and pokestops that you have listed as unknown on a map. Use this information to pull data from the Ingress Intel map. Pokestops are green, gyms are red.
