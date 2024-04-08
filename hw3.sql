CREATE DATABASE HotelManager;
USE HotelManager;

CREATE TABLE Hotel (
hotel_id INT NOT NULL,
name VARCHAR(255) NOT NULL,
phone_number VARCHAR(20) NOT NULL,
login_id INT NOT NULL,
street_address VARCHAR(255),
amenitites TEXT,
PRIMARY KEY(hotel_id));

CREATE TABLE Room (
room_id INT NOT NULL,
hotel_id INT NOT NULL,
price DECIMAL(10, 2) NOT NULL,
date DATE NOT NULL,
features TEXT,
PRIMARY KEY(room_id),
FOREIGN KEY(hotel_id) REFERENCES Hotel(hotel_id));

DESCRIBE Hotel;
DESCRIBE Room;
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'PassyW0rdy!';
