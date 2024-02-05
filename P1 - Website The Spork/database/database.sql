.mode column
.header on
PRAGMA foreign_keys=ON;

DROP TABLE IF EXISTS Address;

CREATE TABLE IF NOT EXISTS Address(
    addressID INTEGER,           -- address ID
    street VARCHAR NOT NULL,     -- street name  with floor and door number
    city VARCHAR NOT NULL,       -- city name
    state VARCHAR NOT NULL,      -- state name
    postalCode VARCHAR NOT NULL, -- postal code, may have a '-'
    country VARCHAR NOT NULL,    -- country name
    CONSTRAINT PKAddressID PRIMARY KEY(addressID)
);

DROP TABLE IF EXISTS Image;

CREATE TABLE IF NOT EXISTS Image(
    imageID INTEGER,
    path VARCHAR NOT NULL, -- path of the image, in the /images folder
    CONSTRAINT PKImageId PRIMARY KEY(imageID),
    CONSTRAINT UniquePath UNIQUE(path)  -- Check if path is primary key
);

DROP TABLE IF EXISTS User;

CREATE TABLE IF NOT EXISTS User(
    username VARCHAR,                  -- unique username
    password VARCHAR NOT NULL,         -- password stored in sha512
    fullName VARCHAR NOT NULL,         -- real name
    phoneNumber VARCHAR NOT NULL,      -- phone number with regional indicator
    emailAddress VARCHAR NOT NULL,     -- email address
    imageID INTEGER NOT NULL,          -- user profile picture
    addressID INTEGER NOT NULL,
    CONSTRAINT PKUsername PRIMARY KEY(username), -- Check if username is primary key
    CONSTRAINT UniquePhoneNR UNIQUE(phoneNumber), -- Check if phone number is unique to that user
    CONSTRAINT IsPhoneNR CHECK(phoneNumber LIKE '+%'), -- Check if phone number starts with a '+'
    CONSTRAINT UniqueEmail UNIQUE(emailAddress), -- Check if email is unique to that use
    CONSTRAINT IsEmail CHECK(emailAddress LIKE '%@%'), -- Check if email has '@' in it
    CONSTRAINT FKImageID FOREIGN KEY(imageID) REFERENCES Image ON DELETE CASCADE ON UPDATE CASCADE, -- Check if image path references an existing image
    CONSTRAINT FKAddressID FOREIGN KEY(addressID) REFERENCES Address ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS Restaurant;

CREATE TABLE IF NOT EXISTS Restaurant(
    restaurantID INTEGER,        -- restaurant ID
    name VARCHAR NOT NULL,       -- restaurant name
    averageRating REAL,          -- average restaurant rating 0-5 with 1 decimal place, derived from all the ratings
    categories VARCHAR,          -- restaurant categories (i.e. vegan, vegetarian, etc), separated by commas
    priceRange VARCHAR NOT NULL, -- price range, 2 values separated by a comma
    username VARCHAR NOT NULL,   -- username of the owner
    addressID INTEGER NOT NULL,
    imageID INTEGER NOT NULL,
    CONSTRAINT PKrestaurantID PRIMARY KEY(restaurantID), -- Check if restaurant ID is primary key
    CONSTRAINT ValidAverageRating CHECK(averageRating BETWEEN 0 and 5 OR NULL), -- Check if average rating is between 0 and 5
    CONSTRAINT ValidPriceRange CHECK(priceRange > 0), -- Check if price range is positive
    CONSTRAINT UniqueAddressID UNIQUE(addressID),
    CONSTRAINT FKusername FOREIGN KEY(username) REFERENCES User ON DELETE CASCADE ON UPDATE CASCADE, -- Check if username references an existing username
    CONSTRAINT FKAddressID FOREIGN KEY(addressID) REFERENCES Address ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FKImageID FOREIGN KEY(imageID) REFERENCES Image ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS MenuItem;

CREATE TABLE IF NOT EXISTS MenuItem(
    itemID INTEGER,         -- item ID
    name VARCHAR NOT NULL,  -- item name
    price REAL NOT NULL,    -- price, with up to 2 decimal places
    categories VARCHAR,     -- item categories (i.e. vegan, vegetarian, etc), separated by commas
    allergens VARCHAR,      -- item allergens (i.e. lactose, gluten, etc), separated by commas
    imageID INTEGER,      -- item image
    restaurantID INTEGER NOT NULL,
    CONSTRAINT PKitemID PRIMARY KEY(itemID), -- Check if item ID is primary key
    CONSTRAINT ValidPrice CHECK(price > 0), -- Check if price is valid
    CONSTRAINT FKimageID FOREIGN KEY(imageID) REFERENCES Image ON DELETE CASCADE ON UPDATE CASCADE, -- Check if image path references an existing image
    CONSTRAINT FKRestaurantID FOREIGN KEY(restaurantID) REFERENCES Restaurant ON DELETE CASCADE ON UPDATE CASCADE -- Check if  restaurantID references an existing restaurant
);

DROP TABLE IF EXISTS Pedido;

CREATE TABLE IF NOT EXISTS Pedido(
    pedidoID INTEGER,                 -- pedidoID
    deliveryStatus VARCHAR NOT NULL,  -- delivery status of the order, may be one of three: to be delivered, delivering, delivered
    price REAL NOT NULL,              -- price, with up to 2 decimal places
    orderDate DATE NOT NULL,       -- date when order was made, in epoch format
    username VARCHAR NOT NULL,        -- username of the person that ordered
    restaurantID INTEGER NOT NULL,    -- restaurant ID that will take the order
    CONSTRAINT PKPedidoID PRIMARY KEY(pedidoID), -- Check if pedidoID is primary key
    CONSTRAINT ValidPrice CHECK(price > 0),  -- Check if price is valid
    CONSTRAINT FKusername FOREIGN KEY(username) REFERENCES User ON DELETE CASCADE ON UPDATE CASCADE, -- Check if  username references an existing user
    CONSTRAINT FKRestaurantID FOREIGN KEY(restaurantID) REFERENCES Restaurant ON DELETE CASCADE ON UPDATE CASCADE -- Check if restaurant id references an existing restaurant
);

DROP TABLE IF EXISTS Quantity;

CREATE TABLE IF NOT EXISTS Quantity(
    quantity INTEGER NOT NULL, -- item quantity
    totalPrice INTEGER NOT NULL,
    pedidoID INTEGER NOT NULL,  -- order ID
    itemID INTEGER NOT NULL,   -- item ID
    CONSTRAINT PKOrderItemID PRIMARY KEY(pedidoID, itemID), -- Check if orderID and itemID are primary key
    CONSTRAINT ValidQuantity CHECK(quantity > 0), -- Check if quantity is valid
    CONSTRAINT FKPedidoID FOREIGN KEY(pedidoID) REFERENCES Pedido ON DELETE CASCADE ON UPDATE CASCADE, -- Check if pedidoID references an existing order
    CONSTRAINT FKItemID FOREIGN KEY(itemID) REFERENCES MenuItem ON DELETE CASCADE ON UPDATE CASCADE -- Check if item ID references an item
);

DROP TABLE IF EXISTS Review;

CREATE TABLE IF NOT EXISTS Review(
    reviewID INTEGER,            -- review ID
    text VARCHAR,                -- review text
    rating INTEGER NOT NULL,     -- review rating, integer from 1 to 5
    reviewDate DATE NOT NULL, -- date when review was made, in epoch format
    username VARCHAR NOT NULL,   -- username of the customer that left the review
    restaurantID INTEGER NOT NULL,    -- restaurant ID of the restaurant that is being reviewed
    CONSTRAINT PKReviewID PRIMARY KEY(reviewID), -- Check if review ID is primary key
    CONSTRAINT ValidRating CHECK(rating BETWEEN 1 AND 10), -- Check if rating is between 1 and 5
    CONSTRAINT FKusername FOREIGN KEY(username) REFERENCES User ON DELETE CASCADE ON UPDATE CASCADE, -- Check if username references an existing user
    CONSTRAINT FKRestaurantID FOREIGN KEY(restaurantID) REFERENCES Restaurant ON DELETE CASCADE ON UPDATE CASCADE -- Check if restaurant id references an existing restaurant
);

DROP TABLE IF EXISTS Comment;

CREATE TABLE IF NOT EXISTS Comment(
    commentID INTEGER,             -- comment ID
    text VARCHAR NOT NULL,         -- comment text
    commentDate DATE NOT NULL,  -- date when comment was made, in epoch format
    reviewID INTEGER NOT NULL,     -- review id of which the comment is replying to
    username VARCHAR,              -- user that left the comment
    CONSTRAINT PKCommentID PRIMARY KEY(commentID), -- Check if comment ID is primary key
    CONSTRAINT FKReviewID FOREIGN KEY(reviewID) REFERENCES Review ON DELETE CASCADE ON UPDATE CASCADE, -- Check if  reviewID references an existing review
    CONSTRAINT FKusername FOREIGN KEY(username) REFERENCES User ON DELETE CASCADE ON UPDATE CASCADE -- Check if username references an existing user
);

DROP TABLE IF EXISTS FavouriteDish;

CREATE TABLE IF NOT EXISTS FavouriteDish(
    username VARCHAR NOT NULL,
    itemID INTEGER NOT NULL,
    CONSTRAINT PKUsernameItemID PRIMARY KEY(username, itemID),
    CONSTRAINT FKUsername FOREIGN KEY(username) REFERENCES User ON DELETE CASCADE ON UPDATE CASCADE, -- Check if username references an existing customer
    CONSTRAINT FKItemID FOREIGN KEY(itemID) REFERENCES MenuItem ON DELETE CASCADE ON UPDATE CASCADE -- Check if item ID references an existing menu item
);

DROP TABLE IF EXISTS FavouriteRestaurant;

CREATE TABLE IF NOT EXISTS FavouriteRestaurant(
    username VARCHAR NOT NULL,
    restaurantID INTEGER NOT NULL,
    CONSTRAINT PKUsernameRestaurantID PRIMARY KEY(username, restaurantID),
    CONSTRAINT FKUsername FOREIGN KEY(username) REFERENCES User ON DELETE CASCADE ON UPDATE CASCADE, -- Check if username references an existing customer
    CONSTRAINT FKRestaurantID FOREIGN KEY(restaurantID) REFERENCES Restaurant ON DELETE CASCADE ON UPDATE CASCADE -- Check if restaurant ID references an existing restaurant
);

DROP TABLE IF EXISTS RestaurantImages;

CREATE TABLE IF NOT EXISTS RestaurantImages(
    restaurantID INTEGER NOT NULL,
    imagePath VARCHAR NOT NULL,
    CONSTRAINT FKRestaurantID FOREIGN KEY(restaurantID) REFERENCES Restaurant ON DELETE CASCADE ON UPDATE CASCADE, -- Check if restaurant ID references an existing restaurant
    CONSTRAINT FKImagePath FOREIGN KEY(imagePath) REFERENCES Image ON DELETE CASCADE ON UPDATE CASCADE, -- Check if image path references an existing image
    CONSTRAINT UniqueRestaurantIDImagePath PRIMARY KEY(restaurantID, imagePath)
);

DROP TABLE IF EXISTS RestaurantImages;

CREATE TABLE IF NOT EXISTS ReviewImages(
    reviewID INTEGER NOT NULL,
    imagePath VARCHAR NOT NULL,
    CONSTRAINT FKReviewID FOREIGN KEY(reviewID) REFERENCES Review ON DELETE CASCADE ON UPDATE CASCADE, -- Check if  reviewID references an existing review
    CONSTRAINT FKImagePath FOREIGN KEY(imagePath) REFERENCES Image ON DELETE CASCADE ON UPDATE CASCADE, -- Check if image path references an existing image
    CONSTRAINT UniqueRestaurantIDImagePath PRIMARY KEY(reviewID, imagePath)
);

-- Addresses --
-- User
INSERT INTO Address(street, city, state, postalCode, country) VALUES('teste', 'teste', 'teste', 'teste', 'teste'); -- ID: 1
INSERT INTO Address(street, city, state, postalCode, country) VALUES('Quinta Lama 31', 'Porto', 'Porto', '4745-520', 'Portugal'); -- ID: 2
INSERT INTO Address(street, city, state, postalCode, country) VALUES('R Conselheiro João Cunha 75', 'Viana Do Castelo', 'Viana do Castelo', '4900-342', 'Portugal'); -- ID: 3
INSERT INTO Address(street, city, state, postalCode, country) VALUES('Avenida Almirante Reis 39', 'Alenquer', 'Lisboa', '2580-651', 'Portugal'); -- ID: 4
INSERT INTO Address(street, city, state, postalCode, country) VALUES('R Alto Cruz 86', 'Torre Dona Chama', 'Bragança', '5385-095', 'Portugal'); -- ID: 5
INSERT INTO Address(street, city, state, postalCode, country) VALUES('Rua Diogo Cão 92', 'Monte dos Baldios dos Tojos', 'Évora', '7200-014', 'Portugal'); -- ID: 6
-- Restaurant
INSERT INTO Address(street, city, state, postalCode, country) VALUES('Av. da Boavista 604', 'Porto', 'Porto', '4149-071', 'Portugal'); -- ID: 7
INSERT INTO Address(street, city, state, postalCode, country) VALUES('R. Direita das Campinas 324', 'Porto', 'Porto', '4100-207', 'Portugal'); -- ID: 8
INSERT INTO Address(street, city, state, postalCode, country) VALUES('R. Padre António 39', 'Maia', 'Porto', '4470-136', 'Portugal'); -- ID: 9
INSERT INTO Address(street, city, state, postalCode, country) VALUES('R. Dr. António Teixeira de Melo 2', 'Matosinhos', 'Porto', '4450-187', 'Portugal'); -- ID: 10
INSERT INTO Address(street, city, state, postalCode, country) VALUES('R. Nova do Seixo 1251', 'Custóias', 'Porto', '4460-840', 'Portugal'); -- ID: 11

-- Images --
-- People
INSERT INTO Image(path) VALUES('docs/profiles/default.jpg'); -- ID: 1
INSERT INTO Image(path) VALUES('docs/profiles/admin.jpg'); -- ID: 2
INSERT INTO Image(path) VALUES('docs/profiles/owner1.jpg'); -- ID: 3
INSERT INTO Image(path) VALUES('docs/profiles/owner2.jpg'); -- ID: 4
INSERT INTO Image(path) VALUES('docs/profiles/owner3.jpg'); -- ID: 5
INSERT INTO Image(path) VALUES('docs/profiles/owner4.jpg'); -- ID: 6
INSERT INTO Image(path) VALUES('docs/profiles/owner5.jpg'); -- ID: 7
INSERT INTO Image(path) VALUES('docs/profiles/user1.jpg'); -- ID: 8
INSERT INTO Image(path) VALUES('docs/profiles/user2.jpg'); -- ID: 9
INSERT INTO Image(path) VALUES('docs/profiles/user3.jpg'); -- ID: 10
INSERT INTO Image(path) VALUES('docs/profiles/user4.jpg'); -- ID: 11
-- Restaurants
INSERT INTO Image(path) VALUES('docs/restaurants/culpado_por_cristina.jpg'); -- ID: 12
INSERT INTO Image(path) VALUES('docs/restaurants/diabeto.jpg'); -- ID: 13
INSERT INTO Image(path) VALUES('docs/restaurants/fook_yue_seafood_restaurant.jpg'); -- ID: 14
INSERT INTO Image(path) VALUES('docs/restaurants/impasta_inc.jpg'); -- ID: 15
INSERT INTO Image(path) VALUES('docs/restaurants/jefs_dinner.jpg'); -- ID: 16
-- Items
INSERT INTO Image(path) VALUES('docs/items/dr_perky.jpg'); -- ID: 17
INSERT INTO Image(path) VALUES('docs/items/mountain_frost.jpg'); -- ID: 18
INSERT INTO Image(path) VALUES('docs/items/nice.jpg'); -- ID: 19
INSERT INTO Image(path) VALUES('docs/items/redball.jpg'); -- ID: 20
INSERT INTO Image(path) VALUES('docs/items/dr_bob.jfif'); -- ID: 21
INSERT INTO Image(path) VALUES('docs/items/water.jfif'); -- ID: 22
INSERT INTO Image(path) VALUES('docs/items/pasta1.jpg'); -- ID: 23
INSERT INTO Image(path) VALUES('docs/items/pasta2.jpg'); -- ID: 24
INSERT INTO Image(path) VALUES('docs/items/pasta3.jpg'); -- ID: 25
INSERT INTO Image(path) VALUES('docs/items/pasta4.jpg'); -- ID: 26
INSERT INTO Image(path) VALUES('docs/items/burguer1.jpg'); -- ID: 27
INSERT INTO Image(path) VALUES('docs/items/burguer2.jpg'); -- ID: 28
INSERT INTO Image(path) VALUES('docs/items/burguer3.jpg'); -- ID: 29
INSERT INTO Image(path) VALUES('docs/items/burguer4.jpg'); -- ID: 30
INSERT INTO Image(path) VALUES('docs/items/burguer5.jpg'); -- ID: 31
INSERT INTO Image(path) VALUES('docs/items/jefs1.jpg'); -- ID: 32
INSERT INTO Image(path) VALUES('docs/items/jefs2.jpg'); -- ID: 33
INSERT INTO Image(path) VALUES('docs/items/jefs3.jpg'); -- ID: 34
INSERT INTO Image(path) VALUES('docs/items/jefs4.jpg'); -- ID: 35
INSERT INTO Image(path) VALUES('docs/items/seafood1.jpg'); -- ID: 36
INSERT INTO Image(path) VALUES('docs/items/seafood2.jpg'); -- ID: 37
INSERT INTO Image(path) VALUES('docs/items/seafood3.jpg'); -- ID: 38
INSERT INTO Image(path) VALUES('docs/items/seafood4.jpg'); -- ID: 39
INSERT INTO Image(path) VALUES('docs/items/seafood5.jpg'); -- ID: 40
INSERT INTO Image(path) VALUES('docs/items/vegan1.jpg'); -- ID: 41
INSERT INTO Image(path) VALUES('docs/items/vegan2.jpg'); -- ID: 42
INSERT INTO Image(path) VALUES('docs/items/vegan3.jpg'); -- ID: 43
INSERT INTO Image(path) VALUES('docs/items/vegan4.jpg'); -- ID: 44
INSERT INTO Image(path) VALUES('docs/items/vegan5.jpg'); -- ID: 45

-- Users --
-- Admin
INSERT INTO User(username, password, fullName, phoneNumber, emailAddress, imageID, addressID) VALUES('admin', '$2y$12$sNUE40MNJF82EaoCHYpC7uADZPkGeEZYMhtPLbzk2daq0x6bI.u1a', 'admin', '+351919191919', 'admin@fakemail.com', 2, 2);
-- Owners
INSERT INTO User(username, password, fullName, phoneNumber, emailAddress, imageID, addressID) VALUES('SimonChowdhury', '$2y$12$sNUE40MNJF82EaoCHYpC7uADZPkGeEZYMhtPLbzk2daq0x6bI.u1a', 'Simon Chowdhury', '+351968374562', 'simon_chowdhurry@fakemail.com', 3, 2);
INSERT INTO User(username, password, fullName, phoneNumber, emailAddress, imageID, addressID) VALUES('CristinaFerr', '$2y$12$sNUE40MNJF82EaoCHYpC7uADZPkGeEZYMhtPLbzk2daq0x6bI.u1a', 'Cristina Ferraz', '+351923458740', 'CristinaFF@fakemail.com', 4, 2);
INSERT INTO User(username, password, fullName, phoneNumber, emailAddress, imageID, addressID) VALUES('Luana_Raith', '$2y$12$sNUE40MNJF82EaoCHYpC7uADZPkGeEZYMhtPLbzk2daq0x6bI.u1a', 'Luana Raith', '+351934508576', 'lumraith@fakemail.com', 5, 3);
INSERT INTO User(username, password, fullName, phoneNumber, emailAddress, imageID, addressID) VALUES('TumeloTemitope32', '$2y$12$sNUE40MNJF82EaoCHYpC7uADZPkGeEZYMhtPLbzk2daq0x6bI.u1a', 'Tumelo Temitope', '+351960488574', 'tramadhanit@fakemail.com', 6, 3);
INSERT INTO User(username, password, fullName, phoneNumber, emailAddress, imageID, addressID) VALUES('DemeterFeng', '$2y$12$sNUE40MNJF82EaoCHYpC7uADZPkGeEZYMhtPLbzk2daq0x6bI.u1a', 'Demeter Feng', '+351923456789', 'demeterfengCCP@fakemail.com', 7, 4);
-- Customers
INSERT INTO User(username, password, fullName, phoneNumber, emailAddress, imageID, addressID) VALUES('CarlosFranco', '$2y$12$sNUE40MNJF82EaoCHYpC7uADZPkGeEZYMhtPLbzk2daq0x6bI.u1a', 'Carlos Franco', '+351934851726', 'carlos_franco_avelino@fakemail.com', 1, 4);
INSERT INTO User(username, password, fullName, phoneNumber, emailAddress, imageID, addressID) VALUES('LuizaMaria', '$2y$12$sNUE40MNJF82EaoCHYpC7uADZPkGeEZYMhtPLbzk2daq0x6bI.u1a', 'Luiza Maria', '+351964820479', 'luizamaria1998@fakemail.com', 8 ,5);
INSERT INTO User(username, password, fullName, phoneNumber, emailAddress, imageID, addressID) VALUES('MaxMag12', '$2y$12$sNUE40MNJF82EaoCHYpC7uADZPkGeEZYMhtPLbzk2daq0x6bI.u1a', 'Maximino Magalhães', '+351964820478', 'maxmag@fakemail.com', 9, 5);
INSERT INTO User(username, password, fullName, phoneNumber, emailAddress, imageID, addressID) VALUES('SanchoPanza', '$2y$12$sNUE40MNJF82EaoCHYpC7uADZPkGeEZYMhtPLbzk2daq0x6bI.u1a', 'Tácito Vilar', '+351931112209', 'sanchopanza234@fakemail.com', 10, 6);
INSERT INTO User(username, password, fullName, phoneNumber, emailAddress, imageID, addressID) VALUES('Jef', '$2y$12$sNUE40MNJF82EaoCHYpC7uADZPkGeEZYMhtPLbzk2daq0x6bI.u1a', 'Eufémia Henriques', '+351922224453', 'femiarique@fakemail.com', 11, 6);

-- Restaurants --
INSERT INTO Restaurant(name, categories, priceRange, username, addressID, imageID) VALUES('Culpado, por Cristina', 'vegan,vegetariano,gourmet', '1,10', 'CristinaFerr', 7, 12); -- ID: 1
INSERT INTO Restaurant(name, categories, priceRange, username, addressID, imageID) VALUES('Diabeto', 'fast-food,hamburguers', '3,20', 'TumeloTemitope32', 8,13); -- ID: 2
INSERT INTO Restaurant(name, categories, priceRange, username, addressID, imageID) VALUES('Fook Yue Seafood Restaurant', 'marisco,asiático,oriental', '1,40', 'DemeterFeng', 9, 14); -- ID: 3
INSERT INTO Restaurant(name, categories, priceRange, username, addressID, imageID) VALUES('Impasta Inc', 'italiano,massa', '10,200', 'Luana_Raith', 10,15); -- ID: 4
INSERT INTO Restaurant(name, categories, priceRange, username, addressID, imageID) VALUES('Jefs Dinner', 'tradicional,português', '1,15', 'SimonChowdhury', 11,16); -- ID: 5
-- ver o que fazer com o price range

-- Menu Items --
-- #1 Culpado por Cristina 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Dr. Perky', 2.00, 'refrigerante,bebida', NULL, 17, 1); -- ID: 1
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Mountain Frost', 1.99, 'refrigerante,bebida', NULL, 18, 1); -- ID: 2
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Nice', 1.85, 'refrigerante,bebida', NULL, 19, 1); -- ID: 3
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Red ball', 1.99, 'refrigerante,bebida', NULL, 20, 1); -- ID: 4
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Salada Ucraniana', 8.99, 'vegetariano,comida', 'lactose,aipo', 41, 1); -- ID: 5
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Black truffle avec lor', 75.00, 'vegetariano,comida', NULL, 42, 1); -- ID: 6
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Guilty Salad', 11.75, 'vegan,vegetariano,comida', 'frutos secos', 43, 1); -- ID: 7
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Vert pâturage', 10.99, 'vegetariano,comida', 'lactose, dióxido de enxofre', 44, 1); -- ID: 8
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Salada de atum artificial', 7.50, 'vegan,vegetariano,comida', NULL, 45, 1); -- ID: 9

-- #2 Diabeto
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Dr. Perky', 2.00, 'refrigerante,bebida', NULL, 17, 2); -- ID: 10
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Mountain Frost', 1.99, 'refrigerante,bebida', NULL, 18, 2); -- ID: 11 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Nice', 1.85, 'refrigerante,bebida', NULL, 19, 2); -- ID: 12
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Dr. Bob', 1.99, 'refrigerante,bebida', NULL, 21, 2); -- ID: 13
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Àgua', 5.00, 'àgua,bebida', NULL, 22, 2); -- ID: 14
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Cheeseburguer', 6.50, 'comida', 'lactose', 27, 2); -- ID: 15
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('O Rústico', 8.50, 'comida', 'ovos,lactose', 28, 2); -- ID: 16
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Vegieburguer', 7.5, 'comida', 'vegetariano', 29, 2); -- ID: 17
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('The Diabeto', 15.00, 'comida', NULL, 30, 2); -- ID: 18
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('US Style', 10.00, 'comida', NULL, 31, 2); -- ID: 19

-- #3 Fook Yue Seafood
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Dr. Perky', 2.00, 'refrigerante,bebida', NULL, 17, 3); -- ID: 20
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Mountain Frost', 1.99, 'refrigerante,bebida', NULL, 18, 3); -- ID: 21
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Nice', 1.85, 'refrigerante,bebida', NULL, 19, 3); -- ID: 22
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Dr. Bob', 1.99, 'refrigerante,bebida', NULL, 21, 3); -- ID: 23
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Àgua', 5.00, 'àgua,bebida', NULL, 22, 3); -- ID: 24
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Camarão com Leguminosas', 6.70, 'comida', 'marisco', 36, 3); -- ID: 25
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Sortido de Ostras temperadas', 5.00, 'comida', 'marisco,crustáceo', 37, 3); -- ID: 26
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Marisco à la cart', 6.20, 'comida', 'marisco,crustáceo', 38, 3); -- ID: 27
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Salada de polvo', 9.80, 'comida', 'marisco', 39, 3); -- ID: 28
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Posta de Pescada na grelha', 4.90, 'comida', 'marisco', 40, 3); -- ID: 29

-- #4 Impasta Inc
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Dr. Perky', 2.00, 'refrigerante,bebida', NULL, 17, 4); -- ID: 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Mountain Frost', 1.99, 'refrigerante,bebida', NULL, 18, 4); -- ID: 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Dr. Bob', 1.99, 'refrigerante,bebida', NULL, 21, 4); -- ID:
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Àgua', 3.50, 'àgua,bebida', NULL, 22, 4); -- ID: 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Bolonhesa', 12.00, 'massa,comida', 'ovos', 23, 4); -- ID: 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Macarrão com almondegas', 10.00, 'pasta,comida', 'ovos', 24, 4); -- ID: 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Massa do mar', 17.00, 'massa,comida', 'ovos,crustáceos', 25, 4); -- ID: 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Pennette com molho de tomate', 8.50, 'massa,comida', NULL, 26, 4); -- ID: 

-- #5 Jefs Dinner
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Dr. Perky', 2.00, 'refrigerante,bebida', NULL, 17, 5); -- ID: 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Mountain Frost', 1.99, 'refrigerante,bebida', NULL, 18, 5); -- ID: 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Dr. Bob', 1.99, 'refrigerante,bebida', NULL, 21, 5); -- ID:
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Àgua', 3.50, 'àgua,bebida', NULL, 22, 5); -- ID: 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Cozido à Portuguesa', 14.00, 'cozido,carne,legumes', NULL, 32, 5); -- ID: 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Massa à Lavrador', 10.00, 'pasta,comida', NULL, 33, 5); -- ID: 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Arroz de Cabidela', 12.00, 'massa,comida', NULL, 34, 5); -- ID: 
INSERT INTO MenuItem(name, price, categories, allergens, imageID, restaurantID) VALUES('Francesinha', 11.00, 'massa,comida', NULL, 35, 5); -- ID: 

--Orders -Pedido
INSERT INTO Pedido(pedidoID, deliveryStatus,price,orderdate,username,restaurantID) VALUES(1,'Completed',3.0,'2022-06-05','admin',1);
INSERT INTO Pedido(pedidoID, deliveryStatus,price,orderdate,username,restaurantID) VALUES(2,'Requested',9.20,'2022-06-07','admin',3);
--INSERT INTO Pedido(pedidoID, deliveryStatus,price,orderdate,username,restaurantID) VALUES(3,'In transit',7.0,'2022-06-08','LuizaMaria',2);
--INSERT INTO Pedido(pedidoID, deliveryStatus,price,orderdate,username,restaurantID) VALUES(4,'In transit',12.0,'2022-06-10','MaxMag12',5);
--INSERT INTO Pedido(pedidoID, deliveryStatus,price,orderdate,username,restaurantID) VALUES(5,'Completed',8.30,'2022-06-11','MaxMag12',4);
--INSERT INTO Pedido(pedidoID, deliveryStatus,price,orderdate,username,restaurantID) VALUES(6,'requested',6.40,'2022-06-11','Jef',2);
--INSERT INTO Pedido(pedidoID, deliveryStatus,price,orderdate,username,restaurantID) VALUES(7,'Requested',9.20,'2022-06-07','CarlosFranco',3);

-- Quantity
INSERT INTO Quantity(quantity, totalPrice, pedidoID, itemID) VALUES (3,6,1,1);
INSERT INTO Quantity(quantity, totalPrice, pedidoID, itemID) VALUES (2,17.98,1,5);
INSERT INTO Quantity(quantity, totalPrice, pedidoID, itemID) VALUES (1,1.99,2,2);
INSERT INTO Quantity(quantity, totalPrice, pedidoID, itemID) VALUES (1,75,2,6);

--Review

INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('Fui muito bem atendida, extrema simpatia pelo lado dos funcionários. Para além disso a comida estava ótima e não foi nada demorada.
Vou voltar cá de certeza',9,'2022-04-17','LuizaMaria',1); --Culpado por Cristina
INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('Destestei a comida. Os vegetais não pareciam "frescos" e tendo em conta o preço, esperava mais. Tirando isso fui bem atendido pelo
staff',4,'2022-04-29','LuizaMaria',1);
INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('Achei que o serviço se adequa ao preço, a comida não era excelente porém para o tipo de comida que é acho um preço justo pelo serviço',
7,'2022-05-05','LuizaMaria',1);

INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('Adorei a experiência. Para quem está habituado aos hamburgers processados das grande cadeias de fast food, isto é definitivamente
melhor. O sabor do hamburger é muito mais natural',
8,'2022-04-22','SanchoPanza',2); --Diabeto
INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('Gostei do restaurante, a decoração era bastante bonita. O atendimento foi bom, simpatia por parte dos funcionários e a comida
também não era nada má. Gostei bastante!',
9,'2022-04-25','SanchoPanza',2);
INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('Não fiquei fâ, não vou repetir.',
2,'2022-05-01','MaxMag12',2);

INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('Até foi agradável. Vim ao restaurante porque a minha mulher já queria experimentar há algum tempo e fiquei surpreendido. Não estava
à espera de ter uma refeição de "peixe" assim tão satisfatória.',
7,'2022-04-28','SanchoPanza',3); --Fook Yue Seafood
INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('Mau serviço. Fui extremamente mal-tratado pelos funcionários que me serviram um peixe que já não se encontrava em condições e ainda
acharam que tinham razão. Não volto mais cá',
1,'2022-05-02','Jef',3);
INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('Experiência interessante. Nunca frequentei um restaurante de marisco por ser mais virado para bifes mas gostei dos cozinhados.
Certamente voltarei cá para experimentar a lula',
8,'2022-05-15','SanchoPanza',3);

INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('Boa cozinha, adorei a massa carbonara que comi e o meu namorado adorou a bolonhesa dele. O preço é um bocado elevado mas no geral
acho que compensa.',
7,'2022-04-27','SanchoPanza',4); --Impasta
INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('Gostei bastante, especialmente da massa de espinafres, estava divinal!',
10,'2022-05-03','LuizaMaria',4);
INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('Boa comida mas pelo o preço, conheço outros restaurantes que servem melhor. Mas de resto nada a apontar',
6,'2022-05-07','CarlosFranco',4);

INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('Adorei, restaurante típico português é incrível!', 8,'2022-04-29','Jef',5);
INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('A ideia do restaurante é boa, mas a comida nem tanto.', 2,'2022-05-01','SanchoPanza',5);
INSERT INTO Review(text, rating, reviewDate, username, restaurantID) VALUES('O meu arroz de cabidela estava de outro mundo, gostei bantante!', 7,'2022-05-04','LuizaMaria',5);

--Comment

INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Concordo integralmente contigo Luiza, Adorei!', '2022-04-18', 1,'CarlosFranco'); --Culpado por Cristina
INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Não estou totalmente de acordo, o serviço comigo foi muito fraco :(', '2022-04-20', 1,'MaxMag12');
INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Pedimos desculpa pelo que passou no nosso restaurante, deve ter sido "um daqueles dias". Volte quando quiser para uma reavaliação do estabelecimento', '2022-05-01', 2,'CristinaFerr');
INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Tive uma experiência semelhante e concordo!', '2022-05-08', 3,'Jef');

INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Agradecemos a sua review, esperamos vê-lo por cá novamente', '2022-04-23', 4,'TumeloTemitope32'); --Diabeto
INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Concordo plenamente.', '2022-04-28', 5,'SanchoPanza');
INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Agradecemos a sua honesta opinião. Esperemos que cá volte para uma experiência melhorada', '2022-05-03', 6,'TumeloTemitope32');

INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Ainda bem que gostou, estamos cá sempre ao seu dispor!', '2022-05-02', 7,'DemeterFeng'); --Fook Yue Seafood
INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Aconteceu-me o mesmo, péssimo serviço.', '2022-05-19', 8,'CarlosFranco');
INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Same, tinha a mesma opinião que o senhor e acabei a refeição a dizer exatamente o mesmo.', '2022-05-17', 9,'MaxMag12');

INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Apreciamos o seu agrado, esperamos um reencontro!', '2022-04-29', 10,'Luana_Raith'); --Impasta
INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Comi bolonhesa e partilho a mesma opinião.', '2022-05-03', 11,'SanchoPanza');
INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Respeitamos a opinião de todos os clientes até para efetuar melhorias no futuro, obrigado!', '2022-05-10', 12,'Luana_Raith');

INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Adoramos reviews destas, obrigado pela preferência!', '2022-04-30', 13,'SimonChowdhury'); --Jefs
INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Por acaso discordo, achei a comida a melhor parte', '2022-05-03', 14,'MaxMag12');
INSERT INTO Comment(text, commentDate, reviewID, username) VALUES('Claro, um restaurante com o meu nome tinha de ser bom.', '2022-05-10', 15,'Jef');