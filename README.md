# psychic-waffle
E-comerce webpage backed by SQL database

used languages : HTML, CSS, JavaScript, PHP and SQL 

DESCPTION
This project is a PHP-based e-commerce web application designed for a car dealership. It allows users to browse vehicles, create accounts, leave reviews, and place orders. Admin users can manage products, users, orders, and reviews through a dashboard.


FETURES
User registration and login system
Product browsing (cars with details like model, price, fuel type)
Shopping/order system
Customer reviews and ratings
Admin dashboard for managing:
Users
Products
Orders
Reviews

HOW TO USE
Clone or download this repository
Place the project folder in your htdocs directory (if using XAMPP)
Start Apache and MySQL in XAMPP
Open phpMyAdmin and:
Create a database (e.g. cardealership)
Import the provided database.sql file

Update database connection in the project:

$conn = new mysqli("localhost", "username", "password", "cardealership");

Open your browser and go to:

http://localhost/your-folder-name
