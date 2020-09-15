# IN4398-Advanced-Practical-IoT-and-Seminar
## Write-up

We aim to create a modular device capable of monitoring the growing environment of plants. 
Possible applications would be greenhouses, garden stores or even large private/public gardens.
The idea is to work with a modular setup, consisting of a central server and various different sensor modules that can be added on demand.
This results in a scalable system that allows you to add and (re)move sensors depending on your needs.

This system would consists of three separate parts which will be elaborated on in this document.
- The hardware of the system
- The server collecting all data
- The user interface (UI) 


Our current plan consists of:
- Creating a (small) device which measures the soil- and air humidity, temperature and possibly other values like pH
- Designing a Raspberry Pi based server to collect all the data
- Creating a web or app based user interface for this server

## Hardware
The hardware for this project will be based on an ESP module since these microcontrollers have wifi capabilities, and are compact in size.
The ESP32 even contains bluetooth functionality which could be beneficial.
All ESP modules currently support mesh capabilities, which could be an interesting feature in the future.

#### Sensors
- Soil moisture
- Air humidity
- Temperature
- pH value
- Status of the own battery/power source

#### Power
Preferably the device will run on solar energy and contain a small battery to serve as backup.
The first prototypes will most likely use a USB powerbank (which is possibly charged using a built-in solar panel).
In a later stage this solar panel could be built into the device.

#### Requirements
The sensors need to be water-, dust-, and uv-resistant to ensure they can stay outside without worry.  
The sensors need to be small and lightweight as to not be a cumbersome process to install and remove.  
The sensors need to work wirelessly to prevent the need for running cables.

## Server
The server will in early stages be a Raspberry Pi running a database where all the information is stored.
Next to this database will run a program where individual devices can connect to as clients.
This program (henceforth called the server) will keep track of all connected clients and their status.
It will handle all authentications required to communicate in a secure way.

#### Requirements
The server should be able to identify which sensor is sending data to it.  
The server should be able to accept all the sensor data, and store this in a practical format (either database or file).  
The server should be able to communicate the collected sensor data with the user interface through an API, or by sharing a database.

## Interface
The interface is the place where a user can login to view the collected data.
This could be either a webapp running on the same raspberry pi as the server or a mobile application.

### Options
 
#### (Progressive) Web App
A web based application would require the user to use his/her (mobile) device to browse to a website and view all required information.
Big advantage of this type of user interface is that the device used to view the UI is almost irrelevant as long as it's a reasonably modern device.
A disadvantage is that the site is a central point of information and could significanlty increase the workload of the server if not handled carefully.

#### Native Mobile App
Disadvantages:
- Each type of device needs a slightly different coding (android vs apple vs pc)
- The app must be installed before anyone could work with the data

Advantages:
- Works a lot smoother on most devices since its tailor-made

#### Requirements
The user interface has to authenticate a username and password.  
The user interface has to allow adding and removing of sensors.  
The user interface has to show sensor data in (adjustable) graphs.  
The user interface has to allow for setting of notifications in certain circumstances (e.g. pH of sensor X lower than value Y)

# Implementation ideas
## network layout
mDNS could be used to name the pi and easily find in on the local network (e.g. raspberrypi.local)
- port 80 would serve as the location of the webApp or api for the mobile app
- port 5050 could be used for the network of known ESP's
- port 5051 could be used for the 'new device' network. A correctly flashed ESP would connect over this port to the specified server hostname or make a broadcast call on this port. This action would let the server know there is a new device on the network, add it to the 'known devices' table in a database and, after confirmation by poth parties, let the ESP switch to port 5050 for regular operation.
-> alternatively the new device could also connect to the specified server hostname and, since it is not currently in the 'known devices' table, be recognised as new. Initalisation will follow and both devices will operate normally after.

## database tables
tables will be added as headers followed by a short description. 
Column names will be between square brackets followed by their function
#### known_devices
This table tracks the currently known devices and their status, any dew devices will be added to this table.
- [id] this table contains a unique id (UID/UUID) of the device. This id could be given to new devices when they first connect to the server.
- [created_at] when was this device first discovered on the network
- [updated_at] when was the last update received from this device

#### location
since the sensors will be spread throughout a certain area, this table keeps track of where they are. Its intended use is either providing an alias in case of individual sensors (e.g. 'passion flower balcony') or coordinates (e.g. in a greenhouse).
- [device_id] this is a foreign key to the 'known devices' table
- [alias/location] an alias in case of individual sensors (e.g. 'passion flower balcony') or coordinates (e.g. in a greenhouse). ***this must be reviewed/decided before actual implementation due to variable type*** It is also possible to move the 'alias' column to the 'known_devices' table or even it's own table and use location in an arbitrary coordinate system here.

#### settings
will the user use a coordinate system, inidvidual aliases, both, dark mode, etc

#### users
this table will hold all users allowed to login to the system. 
- [id] UID for the user
- [first_name]
- [last_name]
- [authorisation]
- [username]
- [email_address]
- [password]
