CREATE DATABASE cityfindr;

USE cityfindr;

CREATE TABLE userlogin (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    passwordHash VARCHAR(255) NOT NULL,
    streetAddress VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    postalCode VARCHAR(20),
    country VARCHAR(100)
);

CREATE TABLE userProfile (
    userId INT NOT NULL, 
    preferences JSON NOT NULL DEFAULT {},
    FOREIGN KEY (userId) REFERENCES user(userId)
);

CREATE TABLE organizations (
    organizationId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    timeOfMeetings TIMESTAMP NOT NULL,
    streetAddress VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    postalCode VARCHAR(20),
    country VARCHAR(100),
    description TEXT,
    phoneNumber VARCHAR(20),
    email VARCHAR(100),
    rating TINYINT,
    tags JSON DEFAULT '{}',  
    status ENUM('Active', 'Inactive', 'Suspended')
);

CREATE TABLE events (
    eventId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    createdBy INT NOT NULL, 
    organizationId INT NULL,
    timeOfEvent TIMESTAMP NOT NULL,
    streetAddress VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    postalCode VARCHAR(20),
    country VARCHAR(100),
    description TEXT,
    phoneNumber VARCHAR(20),
    email VARCHAR(100),
    rating TINYINT,
    tags JSON DEFAULT '{}', 
    FOREIGN KEY (organizationId) REFERENCES organizations(organizationId),
    FOREIGN KEY (createdBy) REFERENCES user(userId)
);

CREATE TABLE tags (
    tag VARCHAR(255) NOT NULL
);
