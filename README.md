# IN4398-Advanced-Practical-IoT-and-Seminar

We aim to create a modular device capable of monitoring the growing environment of plants. 
Possible applications would be greenhouses, garden stores or even large private/public gardens.

This system would consists of three separate parts which will be elaborated on in this document.
- The hardware of the system
- The server collecting all data
- The User interface (UI) 


Our current plan consists of:
- create a (small) device which measures the soil- and air humidity, temperature and possibly other values like pH.
- design a raspberry pi based server to collect all the data
- create a web or app based user interface for this server

## Hardware
  The hardware for this project will be based on an ESP module sice these microcontrollers have wifi capabillities. The ESP32 even contains bluetooth functionallity which could be beneficial. All ESP modules currently support mesh capabillities, which could be an interesting feature in the future.
  
  The device must be water-proof since it cannot be retrieved every time it rains or the crops are watered.
  
  #### sensors
  - Soil moisture
  - air humidity
  - temperature
  - pH value
  - Status of the own battery/power source
  
  #### power
  Preferably the device will run on solar enery and contain a small battery to serve as backup. The first prototypes will most likely use a USB powerbank (which is possibly charged using a built-in solar panel).
  In a later stage this solar panel could be built into the device.
  
  
## Server
The server will in early stages be a raspberry pi running a database where all the information is stored.
Next to this database will run a program where individual devices can connect to as clients. This program (heceforth called the server) will keep track of all connected clients and their status. It will handle all authentifications required to communicate in a secure way.

## Interface
The interface is the place where a user can login to view the collected data.
This could be either a webapp running on the same raspberry pi as the server or a mobile application.

Irrelevant of the type of user interface: It could/should support a login page to restrict some information to outsiders or different authorisation levels.

#### webApp
A web based application would require the user to use his/her (mobile) device to browse to a website and view all required information.
Big advantage of this type of user interface is that the device used to view the UI is almost irrelevant as long as it's a reasonably modern device.
A disadvantage is that the site is a central point of information and could significanlty increase the workload of the server if not handled carefully.

#### Mobile app
Disadvantages:
- each type of device needs a slightly different coding (android vs apple vs pc)
- the app must be installed before anyone could work with the data

advantages:
- Works a lot smoother on most devices since its tailor-made
