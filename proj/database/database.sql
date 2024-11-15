
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS ShoppingCart;
DROP TABLE IF EXISTS Wishlist;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS ProductHistory;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Condition;
DROP TABLE IF EXISTS Message;
DROP TABLE IF EXISTS Deal;
DROP TABLE IF EXISTS BuyHistory;
DROP TABLE IF EXISTS SellHistory;
DROP TABLE IF EXISTS AdminChanges;


CREATE TABLE Category(
    CategoryId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name NVARCHAR UNIQUE NOT NULL 
);


CREATE TABLE Condition(
    ConditionId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name NVARCHAR UNIQUE NOT NULL 
);


CREATE TABLE User(
    UserId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name NVARCHAR NOT NULL,
    Username VARCHAR NOT NULL,
    Email NVARCHAR NOT NULL UNIQUE,
    Password NVARCHAR NOT NULL,
    Location NVARCHAR NOT NULL,
    JoinedDate DATE NOT NULL,
    Admin INTEGER NOT NULL CHECK(Admin IN (0, 1)), -- O FALSE 1 TRUE
    Hasphoto INTEGER NOT NULL CHECK(Hasphoto IN (0, 1)) DEFAULT 0 -- O FALSE 1 TRUE
);


CREATE TABLE Product(
    ProductId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name NVARCHAR NOT NULL,
    /*
    Brand NVARCHAR,
    Model NVARCHAR,
    */
    Description NVARCHAR NOT NULL,
    Price DOUBLE NOT NULL,
    Location NVARCHAR NOT NULL,
    NumberImages INTEGER NOT NULL,
    UserId INTEGER NOT NULL,
    CategoryId INTEGER NOT NULL, 
    ConditionId INTEGER NOT NULL, 
    FOREIGN KEY (UserId) REFERENCES User(UserId)
                ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (CategoryId) REFERENCES Category(CategoryId)
                ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (ConditionId) REFERENCES Condition(ConditionId)
                ON DELETE SET NULL ON UPDATE CASCADE
);


CREATE TABLE ProductHistory(
    HistoryId INTEGER PRIMARY KEY AUTOINCREMENT,
    Name NVARCHAR NOT NULL,
    Price DOUBLE NOT NULL,
    SellerId INTEGER NOT NULL,
    BuyerId INTEGER NOT NULL,
    SellDate DATE NOT NULL,
    FOREIGN KEY (SellerId) REFERENCES User(UserId)
                ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (BuyerId) REFERENCES User(UserId)
                ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE Message( 
    MessageId INTEGER PRIMARY KEY AUTOINCREMENT,
    Text Text NOT NULL,
    Date Text NOT NULL,
    Type INTEGER NOT NULL CHECK(Type IN (0, 1)), -- O FALSE 1 TRUE
    SenderId INTEGER NOT NULL,
    ProductId INTEGER NOT NULL,
    BuyerId INTEGER NOT NULL,
    FOREIGN KEY (SenderId) REFERENCES User(UserId)
                ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (ProductId) REFERENCES Product(ProductId)
                ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (BuyerId) REFERENCES User(UserId)
                ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE Deal(
    Price DOUBLE NOT NULL,
    Date Text NOT NULL,
    ProductId INTEGER NOT NULL UNIQUE,
    BuyerId INTEGER NOT NULL,
    FOREIGN KEY (ProductId) REFERENCES Product(ProductId)
                ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (BuyerId) REFERENCES User(UserId)
                ON DELETE CASCADE ON UPDATE CASCADE 
);


CREATE TABLE ShoppingCart(
    UserId INTEGER NOT NULL,
    ProductId INTEGER NOT NULL,
    FOREIGN KEY (UserId) REFERENCES User(UserId)
                ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (ProductId) REFERENCES Product(ProductId) 
                ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE Wishlist(
    UserId INTEGER NOT NULL,
    ProductId INTEGER NOT NULL,
    FOREIGN KEY (UserId) REFERENCES User(UserId)
                ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (ProductId) REFERENCES Product(ProductId)
                ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE AdminChanges( 
    ChangeId INTEGER PRIMARY KEY AUTOINCREMENT,
    UserId INTEGER,
    Description Text NOT NULL,
        FOREIGN KEY (UserId) REFERENCES User(UserId) ON DELETE SET NULL ON UPDATE CASCADE
);






INSERT INTO User (Name, Username, Email, Password, Location, JoinedDate, Admin, Hasphoto) VALUES 
('John Doe', 'johndoe', 'qwert@gmail.com', '$2y$12$XxwyxCfDRDwfCUSkcR8Og..FYs.yj4p1TeDkGDsv5nv.VFUw2sPfa','Porto', '25-04-2024',1,1), /* qwert */
('Jane Smith', 'janesmith', 'janesmith@example.com', '$2y$12$XxwyxCfDRDwfCUSkcR8Og..FYs.yj4p1TeDkGDsv5nv.VFUw2sPfa','Arouca', '25-04-2024',0, 1), 
('Alice Johnson', 'alicej', 'alice@example.com', '$2y$12$XxwyxCfDRDwfCUSkcR8Og..FYs.yj4p1TeDkGDsv5nv.VFUw2sPfa','Lisboa', '26-04-2024',0, 1), 
('Emily Davis', 'emilyd', 'emily@example.com', '$2y$12$XxwyxCfDRDwfCUSkcR8Og..FYs.yj4p1TeDkGDsv5nv.VFUw2sPfa','Cascais', '26-04-2024',0, 1), 
('Michael Brown', 'michaelb', 'michael@example.com', '$2y$12$XxwyxCfDRDwfCUSkcR8Og..FYs.yj4p1TeDkGDsv5nv.VFUw2sPfa','Coimbra', '27-04-2024',0, 1), 
('Sophia Wilson', 'sophiaw', 'sophia@example.com', '$2y$12$XxwyxCfDRDwfCUSkcR8Og..FYs.yj4p1TeDkGDsv5nv.VFUw2sPfa','Serra da Estrela', '12-02-2024',0, 0); 



INSERT INTO Category (Name) VALUES 
('Electronics'),
('Clothing'),
('Books'),
('Home & Garden');


INSERT INTO Condition (Name) VALUES 
('New'),
('Used'),
('Refurbished');


INSERT INTO Product (Name, Description, Price, Location, NumberImages, UserId, CategoryId, ConditionId) VALUES 
('Laptop', '15.6" Laptop with Intel Core i5', 799.99, 'Porto', 3, 1, 1, 1),
('T-shirt','Black T-shirt with Nike logo', 29.47, 'Arouca', 2, 2, 2, 1),
('Book','Crime and Punishment by Fyodor Dostoevsky', 12.72, 'Arouca', 1, 2, 3, 1),
('Smartphone','6.2" Smartphone with Snapdragon 865',899.99, 'Porto', 1, 1, 1, 1),
('Jeans','Blue jeans with slim fit', 49.99, 'Lisboa', 1, 3, 2, 1),
('Desk Lamp','Desk lamp with adjustable arm', 19.99, 'Lisboa', 1, 3, 4, 1),
('Camera', '20MP Digital Camera with Zoom Lens', 499.99, 'Porto', 1, 4, 1, 1),
('Running Shoes', 'Nike Running Shoes for Men', 79.99, 'Arouca', 1, 5, 2, 1),
('Headphones', 'Wireless Bluetooth Headphones', 129.99, 'Porto', 1, 6, 4, 1),
('Watch', 'Luxury Analog Watch with Leather Strap', 299.99, 'Lisboa', 1, 4, 3, 1),
('Backpack', 'Water-resistant Backpack for Hiking', 39.99, 'Porto', 1, 2, 4, 1),
('Gaming Chair', 'Ergonomic Gaming Chair with Lumbar Support', 199.99, 'Lisboa', 1, 3, 4, 1),
('Dumbbells Set', 'Adjustable Dumbbells Set for Home Gym', 149.99, 'Arouca', 1, 5, 3, 1),
('Sunglasses', 'Polarized Sunglasses with UV Protection', 59.99, 'Porto', 1, 6, 2, 1),
('Digital Drawing Tablet', 'Graphics Tablet for Digital Artists', 129.99, 'Lisboa', 1, 1, 4, 1),
('Wireless Keyboard and Mouse Combo', 'Combo Set for Office and Gaming', 49.99, 'Porto', 1, 4, 4, 1),
('Portable Charger', '10000mAh Power Bank for Smartphones', 29.99, 'Arouca', 1, 2, 1, 1);


INSERT INTO Deal (Price, Date, ProductId, BuyerId) VALUES 
(100.99,'12-05-2024', 15, 4), 
(849.99,'11-05-2024', 4, 2), 
(14.99,'09-05-2024', 6, 5),
(449.99,'12-05-2024', 7, 3),   
(59.99,'10-05-2024', 8, 4), 
(99.99,'11-05-2024', 9, 1), 
(249.99,'09-05-2024', 10, 6),
(29.99,'12-05-2024', 11, 4),   
(149.99,'10-05-2024', 12, 5),    
(99.99,'11-05-2024', 13, 3),     
(39.99,'09-05-2024', 14, 1);  


INSERT INTO ProductHistory (Name, Price, SellerId, BuyerId, SellDate) VALUES 
('Phone', 399.99, 1, 2, '01-05-2024'),
('Laptop', 899.99, 2, 1, '02-03-2024'),
('Headphones', 99.99, 1, 3, '02-05-2024'),
('Tablet', 299.99, 3, 4, '05-05-2024'),  
('Smartwatch', 149.99, 4, 1, '07-04-2024'), 
('Gaming Console', 399.99, 1, 6, '12-03-2024'),
('Fitness Tracker', 79.99, 6, 1, '10-04-2024'),   
('Portable Speaker', 49.99, 2, 1, '15-05-2024'),
('Camera Lens', 199.99, 3, 5, '20-03-2024'),  
('Wireless Mouse', 19.99, 1, 6, '18-04-2024'),  
('External Hard Drive', 129.99, 1, 5, '22-05-2024'),
('Coffee Machine', 79.99, 5, 1, '25-03-2024'),
('Digital Camera', 299.99, 6, 3, '08-04-2024'), 
('Wireless Earbuds', 79.99, 3, 2, '14-05-2024'), 
('Electric Toothbrush', 39.99, 2, 1, '20-03-2024');

INSERT INTO AdminChanges (UserId, Description) VALUES
    (1, 'Change 1'),
    (2, 'Change 2'),
    (3, 'Change 3'),
    (4, 'Change 4'),
    (5, 'Change 5'),
    (6, 'Change 6');

INSERT INTO Message (Text, Date, Type, SenderId, ProductId, BuyerId) VALUES 
('Is the laptop still available?', '2024-04-19 07:45', 0, 2, 1, 2),
('Yes, it is. Are you interested in purchasing it?', '2024-04-19 08:20', 0, 1, 1, 2),
('Do you have any additional accessories for the laptop?', '2024-04-22 08:33', 0, 2, 1, 2),
('Yes, I have a laptop bag and a wireless mouse that I can include.', '2024-04-22 08:35', 0, 1, 1, 2),
('Great! Could you please send me some pictures of the laptop and accessories?', '2024-04-22 09:00', 0, 2, 1, 2),
('Sure, I will send them to you shortly.', '2024-04-22 09:05', 0, 1, 1, 2),
('I received the pictures. Everything looks good. Can we negotiate the price?', '2024-04-23 10:00', 0, 2, 1, 2),
('Certainly, what price were you thinking?', '2024-04-23 10:10', 0, 1, 1, 2),
('I was thinking of $600 for the laptop and accessories. Does that work for you?', '2024-04-23 10:12', 0, 2, 1, 2),
('I was hoping for a slightly higher price. How about $750?', '2024-04-23 11:00', 0, 1, 1, 2),
('That sounds reasonable. Lets finalize the deal at $750.', '2024-04-23 11:20', 0, 2, 1, 2),
('Agreed. When can we meet for the transaction?', '2024-04-23 11:22', 0, 1, 1, 2),
('Im available tomorrow morning. Shall we meet at the café at 10 AM?', '2024-04-23 12:12', 0, 2, 1, 2),
('That works for me. See you tomorrow!', '2024-04-23 12:14', 0, 1, 1, 2),
('Is the laptop still available?', '2024-04-19 00:00', 0, 3, 1, 3),
('Yes, it is. Are you interested in purchasing it?', '2024-04-19 08:44', 0, 1, 1, 3),
('Is the laptop still available?', '2024-04-19 09:00', 0, 4, 1, 4),
('Yes, it is. Are you interested in purchasing it?', '2024-04-19 09:20', 0, 1, 1, 4),
('Laptop available?', '2024-04-19 10:00', 0, 5, 1, 5),
('Yes, it is. Are you interested in purchasing it?', '2024-04-19 10:10', 0, 1, 1, 5),
('Laptop available?', '2024-04-19 08:00', 0, 6, 1, 6),
('Yes, it is. Are you interested in purchasing it?', '2024-04-19 10:10', 0, 1, 1, 6),
('What sizes are available for the T-shirt?', '2024-02-14 02:00', 0, 1, 2, 1),
('We have sizes S, M, and L in stock.', '2024-02-15 07:00', 0, 2, 2, 1),
('I''m interested in the cookbook. Is it still available?', '2024-03-17 15:00', 0, 1, 3, 1),
('Yes, it is. It''s a great cookbook!', '2024-03-17 17:00', 0, 2, 3, 1),
('What color is the dress?', '2024-04-19 19:00', 0, 3, 4, 3),
('The dress is black with a floral print.', '2024-04-19 19:27', 0, 1, 4, 3);


