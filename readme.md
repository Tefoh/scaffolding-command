# scaffold command

## create all necessary files with one command
* model
* resource controller with views
* request class
* seeder
* factory
* migration
* unit and feature tests
* resource views
    * index
    * create
    * show
    * edit

## installation
```bash
composer require tefoh/scaffolding-command
```
next
```bash
php artisan vendor:publish --tag=scaffolding-stubs
```

### usage
```bash
php artisan scaffolding {your entity name}
```
enjoy :)
