![RealDeviceMap-tools](assets/map-header.png?raw=true)
# RealDeviceMap-tools
Tools for managing RealDeviceMap data

### Changes
* Autogeneration of the correct circle size for raid instances depending on the specific latitude, option in settings to choose between iv-, raid- or own radius
* Color Picker built in for instance- and polygon-import
* Export of multiple polygons at once in json-, poracle geofences- & pmsf/rdm nestlist-format or simple list of coordinates with option for naming polygons/nests and saving as file
* Added average points per circle in output 
* Placement of own POIs in Wayfarer mode and draggability with 20m-range bound onto
* Performance improvement
* Passive mode for imported circle instances, they have another color, can't be dragged and won't be exported
* Hide option for spawnpoints before a choosable date
* Polygon export in PoracleJS format
* Wayfarer mode with choosable S2 cell levels and count of POIs in L14 cells
* Import of nomination data from Wayfarer with optional 20m range - checkout out [Wayfarer direct export](https://github.com/PickleRickVE/wayfarer-direct-export) or [Wayfarer Planner](https://gitlab.com/AlfonsoML/wayfarer) by AlfonsoML 
* Instances with multiple polygons are imported as single ones, no longer a big polygon
* Import of administrative boundaries
* Route optimization with traveling salesman
* Button to go to your actual location
* Searchfield for cities
* Option for own tileset in config
* Multilanguage support
* Select language and tileset in settings
* Fixed copy to clipboard
* Count of points in circles

### Features
* View/hide gyms, stops, 70m range around stops and spawnpoints
* Optionally show only unknown gyms/stops/spawnpoints
* Nest polygon import from OSM data, set to last datetime of Niantic OSM sync
* CSV and GeoJSON polygon import
* GeoJSON polygon export
* Coordinate generation - blanket fill polygons with route points
* Coordinate optimization - fill polygons with route points optimized for existing known gyms/stops/spawnpoints
* Instance import - view your RDM instances and add/remove route points, then reexport and upload to RDM
* GeoJSON polygon import as Nest allows to maintain an own list of nests

### Installation
Git clone https://github.com/PickleRickVE/RealDeviceMap-tools, rename config.example.php to config.php and add your credentials for the RDM DB.

### Usage
The map has a variety of control buttons for performing different functions: 

#### ![Map Settings](assets/map-settings.png?raw=true) Settings
* Nest Migration Date - select the last nest migration.
* Optimization Attempts - number of passes to attempt to optimize coordinates during optimization.
* Circle Size - View distance radius (in meters) to route for.
* Generate all nests - Get a report of spawns for all nests in current map bounds. _See below_.

#### Map mode
* ![Map Mode - Routing](assets/map-routing.png?raw=true) Enables full functionality including polygon and routing functions.
* ![Map Mode - Viewing](assets/map-viewing.png?raw=true) For viewing points of interest and spawnpoints only. Enables the option to filter unknown points of interest.

#### View mode
* ![View Mode - Gyms](assets/map-view-gyms.png?raw=true) Enables/disables viewing gyms as red dots on the map.
* ![View Mode - Pokestops](assets/map-view-stops.png?raw=true) Enables/disables viewing pokestops as green dots on the map.
* ![View Mode - Spawnpoints](assets/map-view-spawns.png?raw=true) Enables/disables viewing spawnpoints as blue dots on the map.
* ![View Mode - Stop range](assets/map-view-range.png?raw=true) Enables/disables viewing of 70m range around pokestops as light green circles.

#### Routing and Drawing
* ![Draw Polygon](assets/map-draw-polygon.png?raw=true) Enables drawing of polygons on the map.
* ![Manual route placement](assets/map-place-circle.png?raw=true) Enables/disables manual placement of route points. Click on the map to drop a route point in the routing layer based on view radius setting.
* ![Import Nests](assets/map-import-nests.png?raw=true) Pulls nest data from OSM and places polygons in the current map bounds covering all parks.
* ![Import Polygon](assets/map-import-polygon.png?raw=true) Import CSV or GeoJSON polygon data. GeoJSON can contain multiple polygons, each one will be placed individually. Choose whether to import as editable polygon(s) or nest(s).
* ![Import Instance](assets/map-import-instance.png?raw=true) Imports an instance from your RDM DB and places the route points in the routing layer based on view radius setting.
* ![Clear Routing Layer](assets/map-clear-routing-layer.png?raw=true) Clears the current route from the map, leaving polygons behind
* ![Clear All Layers](assets/map-clear-all-layers.png?raw=true) Clears all route points and polygons from map.
* ![Generate Route](assets/map-generate-route.png?raw=true) Generates a blanket route over all polygons on the map.
* ![Optimize Route](assets/map-optimize-route.png?raw=true) Takes all visible points - gyms, stops, and spawns - and optimizes a route between them based on view radius (circle size).
* ![Get Output](assets/map-get-output.png?raw=true) Opens a textbox to generate lat,lon points for all routing layer coordinates, to be saved as an instance in RDM.

#### Polygon options
![Polygon Options](assets/polygon-options.png?raw=true)

Clicking on any polygon will allow you to generate a spawn report, remove it from the map, or export as GeoJSON.

To generate a spawn report, keep in mind the Nest Migration Date setting. The query generated for your database will only grab spawn data from that point forward. If you are just looking to get data for your area and not specifically reporting on a nest, set the date accordingly before generating your report. Once generated, a modal window will open showing a table of all the spawn counts for that polygon.

![Example spawn report](assets/example-spawn-report.png?raw=true)

Example spawn report for a local nest.

#### Generate a spawn report for all nests
In the settings menu, a button is available to create a report for all nests in your current map bounds. Make sure you import nest polygons first!

![Example spawn report](assets/example-all-nests-report.png?raw=true)

Example multi-nest report

#### Route options
![Route Options](assets/route-point-options.png?raw=true)

After a route is generated, you can click on any point of the route to remove it from the map.

## Example outputs
![Example spawn route](assets/example-blanket-route-spawn.png?raw=true)

Example route showing a blanket route of 75m circles covering a town for finding new spawn points.

![Example gym route](assets/example-blanket-route-gym.png?raw=true)

Example route showing the same polygon covered with 500m circles for finding new raids and stops.

![Example optimized gym route](assets/example-optimized-route-gyms.png?raw=true)

Example optimization covering gyms in a polygon

![Example nest route](assets/example-blanket-route-nest.png?raw=true)

Example optimization covering all nests in map bounds, for finding new spawnpoints in nests

![Example optimized nest route](assets/example-optimized-route-nests.png?raw=true)

Example optimization covering known spawnpoints in multiple nests. Note that you can remove overlapping circles by clicking on them and choosing delete - the optimization routine still needs some work.

### Thanks
* Credit to [abakedapplepie](https://github.com/abakedapplepie) and his creditors for the base
* Credit to [xxleevo](https://github.com/xxleevo), [un1matr1x](https://github.com/Un1matr1x) and Alex for their contributions
* Credit to [lovasoa](https://github.com/lovasoa) for the traveling salesman script
