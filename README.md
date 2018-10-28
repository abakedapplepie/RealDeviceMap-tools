# RealDeviceMap-tools
Tools for managing RealDeviceMap data

## rdm-tools.php
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

#### Usage
Under settings (the Cog icon) you will find various modes of operation.
* Circle Size is the radius of circles generated for your routes. Adjust for raids or pokemon (500 for raids, 75 for pokemon)
* Show gyms/pokestops/pokemon turns on and off viewing of existing points in your RDM RB
* Run route generator creates a blanket of coordinates covering all your current polygons (drawn, imported, or nest)
* Run route optimizer creates an optimized route that covers all known points in all your current polygons (drawn, imported, or nest)
* Retrieve nests will query Overpass for nests polygons and import them into your map
  * Queries for 2018-04-09T01:32:00Z - the last OSM Pogo import (the last nest update)
  * Queries "park" and "recreation_ground" only
* Optimization attempts is the number of times the optimization routine will shuffle the given points and try for a better attempt
* Show unknown POIs will only show POIs with a name of null
  * Instructions for using Ingress Intel map to generate SQL queries for importing unknown POIs coming soon.

The general flow is to create your polygons, then create a route based off those polygons. 

Click the top polygon button to draw your own polygon

Click the second polygon button to import a polygon

Click the garbage button to remove all circles (route coordinates) and polygons

Click the checkbox to output all route coordinates for saving in the RDM dashboard as an instance.

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
