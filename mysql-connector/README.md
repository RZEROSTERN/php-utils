#MySQL Connector for PHP 5.3+
This MySQL connector is made using the mysqli library, due the deprecation of the default mysql method. Main idea by @yagarasu (he doesn't have GitHub) and maker of the previous versions done with the mysql method.
##How to play?
Working with this connector is very, very easy. You should use more time in making a popcorn bag than implementing the class.
Step 1: Require the class file or apply an autoload method
```php
require('MySQLDatabase.class.php');
```
Step 2: Create a new instance of the class.
```php
$instance = new MySQLDatabase(HOST, USER, PASSWORD, DATABASE);
```
Step 3: VOILA! You may use the methods mentioned below.
###Methods
The available methods on this class are:
###Licensing
Released under Creative Commons CC-BY-SA 4.0.
