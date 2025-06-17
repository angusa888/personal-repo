CREATE DATABASE pantryDB;

USE pantryDB;



CREATE TABLE login (
    userId INT AUTO_INCREMENT PRIMARY KEY, 
    username VARCHAR(255) NOT NULL UNIQUE,
    PasswordHash VARCHAR(128) NOT NULL
);

CREATE TABLE users_pantry (
    userId INT NOT NULL,
    ingredients TEXT(255) NOT NULL,
    FOREIGN KEY (userId) REFERENCES login(userId) ON DELETE CASCADE
);

CREATE TABLE recipes (
    recipeId INT AUTO_INCREMENT PRIMARY KEY,
    recipe JSON NOT NULL
);

CREATE TABLE recipeIngredients (
    ingredient VARCHAR(255),
    matchingRecipes TEXT(255),
    PRIMARY KEY (ingredient)
);