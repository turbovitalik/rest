Consider this as my code example. A few years ago this code has been written as a test assignment for position of PHP Developer in Dutch software company. The task was the following: to implement REST API service with basic endpoints for GET, POST, PUT http methods. No authorization required. No framework - only vanilla PHP.

**Requirements:**
- PHP 5.6+
- MySQL

**Database schema:**

CREATE TABLE address (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(30) NOT NULL,
    street VARCHAR(30) NOT NULL,
    house_number VARCHAR(10) NOT NULL,
    postal_code VARCHAR(10) NOT NULL,
    city VARCHAR(30) NOT NULL,
    country VARCHAR(30) NOT NULL
);

INSERT INTO address (label, street, house_number, postal_code, city, country) VALUES
    ('Home Address', 'Baker Street', '221b', '10789', 'London', 'United Kingdom'),
    ('Work Address', 'Freedom Street', '12', '34895', 'Ukraine', 'Kyiv'),
    ('Bar Address', 'Jack Daniel\'s Street', '43', '20341-FA', 'USA', 'New York');
