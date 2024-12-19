# GoGora 

## Overview
GoGora is a web application designed for managing and reserving rides, primarily targeting passengers, managers, and administrators.
This system allows users to view available rides, reserve seats, manage schedules, and handle payments. 
The application provides functionalities tailored to each user role, enhancing the overall ride experience.
Anyway, sana makapasa na tayo. 

## Table of Contents (To be Updated) 
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Usage](#usage)
- [File Structure](#file-structure)
- [Contributing](#contributing)


## Features
### Passengers
- View and reserve available rides, schedules, and seats.
- Filter rides by date and type.
- Pay in advance (GCash simulation) or manually at the time of the ride.
- Check estimated wait time before the scheduled ride.

### Managers
- Add, update, and remove rides (CRUD).
- View statistics on ride usage and passenger bookings.
- Manage blacklisted passengers.
- Create priority lanes for instructors, pregnant women, and disabled individuals.

### Administrators
- Perform all manager functionalities.
- Handle user accounts (CRUD).
- Conduct system maintenance and log errors.

## Technologies Used
- **Frontend**: HTML, CSS, JavaScript, AJAX
- **Backend**: PHP, NodeJs, Express
- **Database**: MySQL, Mysql2

## Configurations and Installations
### Installations
- **A. XAMPP**
- Install xampp if xampp is still not installed in your device
- **B. NodeJS, Express, and Mysql2**
- **B.1 Package.json**
- should run upon installation of nodejs
- **B.2 Terminal Commands**
- npm install express
- npm install nodejs
- npm install mysql2
- npm init -y --> for creating and/or updating package.json

### Configurations
- **A. httpd.conf --> XAMPP**
- Change the listening port to "8080" or the line should be "Listen 8080"
- Modify all "Require all denied" lines to "Require all granted"

- **B. httpd.vhosts --> XAMPP**
- add the following lines 
- "<VirtualHost *:8080>
    DocumentRoot "C:/xampp/htdocs/GoGora-pt2/"
    ServerName Gogora
    </VirtualHost>" 
- "<VirtualHost *:8080>
   DocumentRoot "C:/xampp/htdocs/GoGora-pt2/view/admin/dashboard.html    
   ServerName gomingo
 </VirtualHost>" 

- **C. C:/Windows/System32/drivers/etc/hosts**
- Pre-requisites: get your ip address using your command prompt terminal
- edit with notepad++ , not unless notepad or hosts is already opened via administration 
- add the following ips in the list
- "<your ip address> gogora" for the hero passenger and manager access
- "<your ip address> gomingo" for the admin link

-**D. Firewalls and Antivirus**
 - Add port 80 at the Advanced Securtity inbound and outbound rules
 - disable firewall for the mean time

 - **E. Services**
 - Turn off or disable World Wide Publishing to disable IIS

## Running and Accessing the Servers and Website
### Running and Starting the Server
- **Xampp**
- Simply start Apache and Mysql
- **NodeJS**
- install nodejs <instructions found below>
- Enter command "node app.js" from Gogora-pt2 root
- **Database**
- For running the database start MYSQL via Xampp controller. This is used for both NodeJS(for UI purposes) and Xampp

### Running the Website
- **Passengers and Managers**
- enter "http://Gogora:8080" or simply "Gogora:8080"
- **Admin**
- enter "http://Gomingo:5000" or simply "Gomingo:5000"
- **Database**
- enter "locahost:8080/phpmyadmin"

## enjoy have fun !


