# bs5-socket-editable
Example editable bootstrap using php web socket
## Installation
```
composer install
```
## Create Example Database
```
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100)
);
```
## Run Socket
```
php server.php
```
Done!