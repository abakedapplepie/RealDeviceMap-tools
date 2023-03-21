# RealDeviceMap-tools
Tools for managing RealDeviceMap data
![RealDeviceMap-tools](assets/map-header.png?raw=true)

## Changes
* Added more details to the import of administrative boundaries
* Distance between points on routes is colored
* Option to show routes for instances, generate new routes for instances or drawn circles and visualize it directly
* Added Dockerfile to generate a local image
* Autogeneration of the correct circle size for raid instances depending on the specific latitude, option in settings to choose between iv-, raid- or own radius
* Color Picker built in for instance- and polygon-import

## Installation
1. `git clone https://github.com/PickleRickVE/RealDeviceMap-tools`
2. Enter the new folder `cd RealDeviceMap-tools` and go to the config folder `cd config`
2. Edit `nano config.env.php` and add your credentials for the RDM and/or Nest database.
3. Edit `nano .htpasswd` to set a username and password. The default user is **admin** with password **admin**, you can generate one [here](https://www.web2generators.com/apache-tools/htpasswd-generator).

### Optional: when running Docker
1. Paste the contents from docker-compose.example.yml in the files where RDM & it's database is located and edit where needed
2. Check Dockerfile and uncomment the line with .htaccess if you want to use a password
3. Run `docker-compose up -d --build rdm_tools`

## Updates
1. Use git pull in the folder
2. When using Docker, re-run `docker-compose up -d --build rdm_tools`

## Usage
For more info please use the [wiki](https://github.com/PickleRickVE/RealDeviceMap-tools/wiki).

## Thanks
* Credit to [abakedapplepie](https://github.com/abakedapplepie) and his creditors for the base
* Credit to [lovasoa](https://github.com/lovasoa) for the traveling salesman script
* Credit to [leevo](https://github.com/xxleevo) for various ideas 
