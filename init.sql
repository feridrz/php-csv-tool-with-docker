CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       category VARCHAR(255) NOT NULL,
                       firstname VARCHAR(255) NOT NULL,
                       lastname VARCHAR(255) NOT NULL,
                       email VARCHAR(255) NOT NULL,
                       gender ENUM('male', 'female', 'other') NOT NULL,
                       birthDate DATE NOT NULL
);
-- Add more initialization SQL as needed
