# RealDeviceMap-tools
Tools for managing RealDeviceMap data

## rdm-tools.php
This file contains all tools in one go.

### More settings
Click the Cog icon to open settings and adjust them accordingly

### Map Modes
* Route generator
  * Generate a winding path of circles in a drawn polygon
* Route optimizer
  * Takes all data points in a drawn polygon and optimizes coordinates for efficiency
  * Can optimize gyms, stops, spawns, or all three (set your circle size accordingly)
* POI viewer
  * View gyms, stops, and spawns
  * Optionally view only unknown gyms and stops (for updating POI metadata using Ingress Intel Map)
  
### Polygon importer
Import a list of coords to draw a polygon. If importing a polygon for route optimization, you have to be viewing the full area of the map where the polygon will be placed so that all the data points will be loaded into the map



# The following are deprecated:

## circle-generator.html
Using Leaflet and OSM now.

Draw one or more polygons using the polygon editor (polygon icon), when each polygon is completed a set of coordinates will be generated and represented as circles based on your circle size setting, which you can change under the toolbox menu. To view the output, click the checkbox icon.

## unknown-helper.php
Connects to your database and shows you all the gyms and pokestops that you have listed as unknown on a map. Use this information to pull data from the Ingress Intel map. Pokestops are green, gyms are red.
