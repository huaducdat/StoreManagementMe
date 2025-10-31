USE db_products;

CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    birthday DATE
);

CREATE TABLE products(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(150),
    description TEXT,
    price DECILMAL(10,2),
    created_at DATETIME CURRENT_TIMESTAMP,
    updated_at DATETIME CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCE users(id) ON DELETE CASCADE
);

INSERT INTO users (fullname, email, password, address, birthday) 
VALUES ('Admin User', 'admin@example.com', '$2y$10$examplehash', 'Nam Dinh', '+84-854065289');