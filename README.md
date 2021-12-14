# Wallpaper-api

Simple API for interacting with images and their categories.
Has tests and described endpoints by swagger.
Also uses docker

## Launching

The app uses docker, to run it just use `docker-compose up --build`

## API documentation

To see the existing routes, first generate the documentation:

`php artisan l5swagger: generate`

Documentation will be available at(only in development mode): http://localhost:8080/api/documentation
