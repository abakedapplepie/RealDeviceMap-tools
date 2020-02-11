# RealDeviceMap-tools
Tools for managing RealDeviceMap data
![RealDeviceMap-tools](assets/map-header.png?raw=true)

### Changes
* Added more details to the import of administrative boundaries
* Distance between points on routes is colored
* Option to show routes for instances, generate new routes for instances or drawn circles and visualize it directly
* Added Dockerfile to generate a local image
* Autogeneration of the correct circle size for raid instances depending on the specific latitude, option in settings to choose between iv-, raid- or own radius
* Color Picker built in for instance- and polygon-import

### Installation
Git clone https://github.com/PickleRickVE/RealDeviceMap-tools, copy config.example.php to config.php in /config and add your credentials for the RDM DB or follow the wiki for docker installation.

### Usage
For more info please use the [wiki](https://github.com/PickleRickVE/RealDeviceMap-tools/wiki).

### Thanks
* Credit to [abakedapplepie](https://github.com/abakedapplepie) and his creditors for the base
* Credit to [lovasoa](https://github.com/lovasoa) for the traveling salesman script
* Credit to [leevo](https://github.com/xxleevo) for various ideas 