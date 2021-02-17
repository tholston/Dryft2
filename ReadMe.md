# DRyft

This application makes use of [Docker](https://www.docker.com) to provide every developer with a uniform environment. You must first install Docker to make proper use of it. Otherwise you will need to modify `bootstrap.php` to support your specific environment.

# Getting Started

Once Docker is installed, navigate to the top project directory in a terminal and use the following commands:

* `docker-compose build` — to download the requisite images and set up an image for the app
* `docker-compose up -d` — to start the virtual machine and make the application available

# Docker Quicklinks

After starting the container(s), you can access the application and database administration tools using these links:

* [Application](http://localhost)
* [Adminer (DB)](http://localhost:8080)
