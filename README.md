<h1 align="center">Foodie</h1>

## About Foodie

Foodie is an online ordering site designed primarily for use in the food industry. 
This site allows food company to quickly and easily manage an online menu which customers can browse and use to 
place orders with just a few clicks. 

Structure is divided in 3 main components:

1. Admin system – control to add companies and company admins 
2. Producer system – allows producer company to control what can be ordered by the users  
3. User system  - provides the functionality for customers to place their orders.
 
The admin system is only available to super admin of the site. 
Admin have functionality of creating companies. There are two types of companies: producer and customer. 
Producer companies produce food while customer companies buy food from producer companies.
Admin can also create company's admins. 
Admins of producer companies are producer admins while admins of customer companies are customer admins.
He have the option to update or delete companies or admins and also can view all users and change their roles.

The producer system is available only to food producer company’s owners (producer admins) and employees (producer users). 
Producer admin can create meal categories and add meals into them with the option to update or delete them. 
Once an order is placed on the web page, it is entered into database and then retrieved, in real time on producer company’s end.
On company’s account all meals in the order are displayed, along with their corresponding options and delivery details. 
Both producer admins and users can quickly go through the orders and produce the necessary meals in received order 
as well as update order's status which have already been processed or cancel order if necessary.

User of the ordering site have functionality of creating account with option of choosing work company. 
If the user choose producer company his role is producer user, choosing customer company role is customer user, or if he doesn't pick company his role is user.
All admins are also ordinary users and can order the meals.
After the users logs in, he is presented with the list of all available restaurants (producer companies).
When the user choose the producer company, he sees an interactive menu with all available meals 
sorted in meal's categories. Then the user can select the meals from the menu, define quantity and provide delivery information.
User can review the current order with price details, and have option to cancel the current order. 
Payment can be processed on delivery or paid online by entering card details.
Producer companies can offer discount to their employees.

Customer admin can submit the group order with all orders from his company's customer uses with total price.


## Features

Foodie is made in Laravel. Laravel is accessible, powerful, and provides tools required for large, robust applications.

- API RESTFul project
- [Prettus -l5- Repository layer](https://github.com/andersao/l5-repository)
- [Fractal - Transformer](https://github.com/spatie/laravel-fractal)
- [Passport - Laravel API authentication package](https://github.com/laravel/passport)
- [Cashier -  Stripe's billing services](https://github.com/laravel/cashier)
- [Simple, fast routing engine](https://laravel.com/docs/routing)
- [Powerful dependency injection container](https://laravel.com/docs/container)
- Expressive, intuitive [database](https://laravel.com/docs/eloquent)
- Database agnostic [schema migrations](https://laravel.com/docs/migrations)
- Full with PHPDoc


## Installation

Follow the instructions from provided links.

You need to install [VirtualBox 6.x](https://www.virtualbox.org/wiki/Downloads) as well as [Vagrant](https://www.vagrantup.com/downloads.html).

[Laravel Homestead](https://laravel.com/docs/6.x/homestead) is an official, pre-packaged Vagrant box that provides a wonderful development environment 
without requiring to install PHP, a web server, and any other server software on local machine.

###### Homestead yaml config
```html
---
ip: "192.168.10.10"
memory: 2048
cpus: 2
provider: virtualbox

authorize: ~/.ssh/id_rsa.pub

keys:
    - ~/.ssh/id_rsa

folders:
    - map: ~/code/foodie
      to: /home/vagrant/foodie

sites:
    - map: foodie.local
      to: /home/vagrant/foodie/public

databases:
    - homestead
    - foodie

features:
    - mariadb: false
    - ohmyzsh: false
    - webdriver: false

# ports:
#     - send: 50000
#       to: 5000
#     - send: 7777
#       to: 777
#       protocol: udp
```

###### Add into etc/hosts
```html
192.168.10.10 foodie.local
```

Install [Laravel](https://laravel.com/docs/6.x/installation). The Laravel framework has a few system requirements. 
All of these requirements are satisfied by the Laravel Homestead virtual machine, so it's highly recommended that you 
use Homestead as your local Laravel development environment.

Clone project Foodie from remote repository [Git](https://git.quantox.tech/foodie/foodie-api) into code/foodie folder.

###### In Laravel .env file enter these parameters
```html
DB_DATABASE=foodie
DB_USERNAME=homestead
DB_PASSWORD=secret
```

You can find database model of Foodie project in _install folder. 
Create database named foodie and connect with foodie project.
Run migrations with command `php artisan migrate`.

To install packages type command `composer install`.

Next, you should run the `php artisan passport:install` command and place encryption keys into .env file.

You can register with [stripe](https://stripe.com/) if you wish to process payment and place stripe keys into .env file.

Install [Postman](https://www.postman.com/) with command `sudo snap install postman`. 
Postman is a collaboration platform for API development.
Download foodie.postman_collection from _install folder and import them into postman workspace.

## Contributing

Thank you for considering contributing to the Foodie!
