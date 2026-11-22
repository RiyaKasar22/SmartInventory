CREATE DATABASE IF NOT EXISTS smart_inventory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smart_inventory;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  category VARCHAR(80) DEFAULT 'General',
  quantity INT NOT NULL DEFAULT 0,
  min_stock INT NOT NULL DEFAULT 5,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  cost_price DECIMAL(10,2) NOT NULL DEFAULT 0,
  expiry_date DATE NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  sold_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Optional sample records
INSERT INTO products (name,category,quantity,min_stock,price,cost_price,expiry_date) VALUES
('Milk','Dairy',18,8,32,25,DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
('Bread','Bakery',6,5,30,20,DATE_ADD(CURDATE(), INTERVAL 5 DAY)),
('Rice 5kg','Grocery',24,5,320,270,NULL),
('Orange Juice','Beverages',4,8,85,62,DATE_ADD(CURDATE(), INTERVAL 2 DAY));
