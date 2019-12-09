CREATE TABLE `spending` (
 `id` int(10) DEFAULT NULL AUTO_INCREMENT,
 `category` varchar(100) NOT NULL,
 `count` int(10) DEFAULT NULL,
 `total` decimal(10, 2) DEFAULT NULL,
 PRIMARY KEY (`id`)
);

INSERT INTO spending VALUES
  (1, "Groceries", 0, 0.00),
  (2, "Eating out", 0, 0.00),
  (3, "Clothing", 0, 0.00),
  (4, "Gas", 0, 0.00),
  (5, "Entertainment", 0, 0.00),
  (6, "Self care", 0, 0.00),
  (7, "Bills", 0, 0.00),
  (8, "Repairs", 0, 0.00),
  (9, "Personal", 0, 0.00);
  
CREATE TABLE `income` (
 `id` int(10) DEFAULT NULL AUTO_INCREMENT,
 `category` varchar(100) NOT NULL,
 `count` int(10) DEFAULT NULL,
 `total` decimal(10, 2) DEFAULT NULL,
 PRIMARY KEY (`id`)
);
  
INSERT INTO income VALUES
  (1, "Paycheck", 0, 0.00),
  (2, "Gift", 0, 0.00),
  (3, "Found", 0, 0.00),
  (4, "Other", 0, 0.00);
