![RealDeviceMap-tools](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-header.png)
# RealDeviceMap-tools
Tools for managing RealDeviceMap data

## rdm-tools.php
### Features
* View/hide known gyms, stops, and spawnpoints
* Optionally show only unknown gyms/stops
* Nest polygon import from OSM data, set to last datetime of Niantic OSM sync
* CSV and GeoJSON polygon import
* GeoJSON polygon export
* Coordinate generation - blanket fill polygons with route points
* Coordinate optimization - fill polygons with route points optimized for existing known gyms/stops/spawnpoints
* Instance import - view your instance and add/remove route points, then reexport and upload to RDM

### Installation
Simply upload rdm-tools.php to your favorite webserver, point the database variables (found currently at line 1409) to your RDM DB's IP, and configure your username/password.

### Usage
The map has a variety of control buttons for performing different functions: 

#### ![Map Settings](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-settings.png) Settings
* Nest Migration Date - select the last nest migration
* Optimization Attempts - number of passes to attempt to optimize coordinates during optimization
* Circle Size - View distance radius (in meters) to route for

#### Map mode
* ![Map Mode - Routing](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-routing.png) Enables full functionality including polygon and routing functions
* ![Map Mode - Viewing](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-viewing.png) For viewing points of interest and spawnpoints only. Enables the option to filter unknown points of interest.

#### View mode
* ![View Mode - Gyms](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-view-gyms.png) Enables/disables viewing gyms as red dots on the map.
* ![View Mode - Pokestops](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-view-stops.png) Enables/disables viewing pokestops as green dots on the map.
* ![View Mode - Spawnpoints](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-view-spawns.png) Enables/disables viewing spawnpoints as blue dots on the map.

#### Routing and Drawing
* ![Draw Polygon](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-draw-polygon.png) Enables drawing of polygons on the map.
* ![Manual route placement](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-place-circle.png) Enables/disables manual placement of route points. Click on the map to drop a route point in the routing layer based on view radius setting.
* ![Import Nests](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-import-nests.png) Pulls nest data from OSM and places polygons in the current map bounds covering all parks.
* ![Import Polygon](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-import-polygon.png) Import CSV or GeoJSON polygon data. GeoJSON can contain multiple polygons, each one will be placed individually.
* ![Import Instance](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-import-instance.png) Imports an instance from your RDM DB and places the route points in the routing layer based on view radius setting.
* ![Clear Routing Layer](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-clear-routing-layer.png) Clears the current route from the map, leaving polygons behind
* ![Clear All Layers](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-clear-all-layers.png) Clears all route points and polygons from map.
* ![Generate Route](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-generate-route.png) Generates a blanket route over all polygons on the map.
* ![Optimize Route](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-optimize-route.png) Takes all visible points - gyms, stops, and spawns - and optimizes a route between them based on view radius (circle size).
* ![Get Output](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/map-get-output.png) Opens a textbox to generate lat,lon points for all routing layer coordinates, to be saved as an instance in RDM.

#### Polygon options
![Polygon Options](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/polygon-options.png)

Clicking on any polygon will allow you to generate a spawn report, remove it from the map, or export as GeoJSON.

To generate a spawn report, first make sure you are viewing spawnpoints and pokestops as they must be visible on your map to generate the query for your database server. Keep in mind the Nest Migration Date setting. The query generated for your database will only grab spawn data from that point forward. If you are just looking to get data for your area and not specifically reporting on a nest, set the date accordingly before generating your report. Once generated, a modal window will open showing a table of all the spawn counts for that polygon.

#### Route options
![Route Options](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/route-point-options.png)

After a route is generated, you can click on any point of the route to remove it from the map.

## Example outputs
![Example spawn route](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/example-blanket-route-spawn.png)

Example route showing a blanket route of 75m circles covering a town for finding new spawn points.

![Example gym route](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/example-blanket-route-gym.png)

Example route showing the same polygon covered with 500m circles for finding new raids and stops.

![Example optimized gym route](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/example-optimized-route-gyms.png)

Example optimization covering gyms in a polygon

![Example nest route](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/example-blanket-route-nest.png)

Example optimization covering all nests in map bounds, for finding new spawnpoints in nests

![Example optimized nest route](https://raw.githubusercontent.com/abakedapplepie/RealDeviceMap-tools/assets/example-optimized-route-nests.png)

Example optimization covering known spawnpoints in multiple nests. Note that you can remove overlapping circles by clicking on them and choosing delete - the optimization routine still needs some work.
