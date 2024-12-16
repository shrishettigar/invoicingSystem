
## About Laravel

Invoicing App
This is a simple invoicing application that allows customers to add products to their cart, delete items from their cart, view their cart items, and complete the checkout process to generate an invoice. The app uses Laravel as the backend framework and includes several key features related to cart management and invoicing.

## Features

- Product Management.
- Category Management.
- Customer Management.
- Cart Operations.
- Invoice Generation.

## Requirements

 1. PHP >= 8.0
 2. Composer
 3. SQLite
 4. Laravel >= 10.0

## Installation
- Clone the Repository

    git clone https://github.com/shrishettigar/invoicingSystem.git
    cd invoicing-app

- Install Dependencies

    Run the following command to install all necessary dependencies:

        composer install

- Set Up DB

    Configure the environment variables for your database connection in the .env file:

        DB_CONNECTION=sqlite
        DB_DATABASE=/path_to_your_database/database.sqlite

- Generate Application Key

    php artisan key:generate

- Run Migrations

    php artisan migrate

- Serve the Application

    php artisan serve

        OR 

    php -S localhost:8000 -t public/

- End points

    Download the collection file  <b>InvoiceApp.postman_collection </b> from main folder

