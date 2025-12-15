CREATE DATABASE IF NOT EXISTS tadreeb_db;
USE tadreeb_db;

CREATE TABLE IF NOT EXISTS users (
    
);

CREATE TABLE IF NOT EXISTS admins (
    
);

CREATE TABLE IF NOT EXISTS internships (
    

);

CREATE TABLE IF NOT EXISTS Internship (
    internshipID INT AUTO_INCREMENT PRIMARY KEY, -- internship id
    title VARCHAR(255) NOT NULL, -- title of company 
    major VARCHAR(150), -- majors targeted
    location VARCHAR(150), -- city 
    short_description TEXT, -- short desc goes on the outside of the card in the menu
    full_description LONGTEXT, -- long desc goes on the specific internship card when user clicks on it 
    requirements JSON, -- to qualify
    image_url VARCHAR(500), -- company logo
    application_link VARCHAR(500), -- url of application page
    deadline DATE,
);

CREATE TABLE IF NOT EXISTS contact_messages (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
email VARCHAR(150) NOT NULL,
subject VARCHAR(150) NOT NULL,
message TEXT NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
