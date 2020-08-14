DROP DATABASE IF EXISTS webforum_database;
DROP USER IF EXISTS 'webforum_user'@'localhost';

CREATE DATABASE webforum_database;
CREATE USER 'webforum_user'@'localhost' IDENTIFIED BY 'webforum_password'; 
GRANT ALL PRIVILEGES ON webforum_database.* TO 'webforum_user'@'localhost'; 
FLUSH PRIVILEGES;
CREATE TABLE webforum_database.posts(id INT AUTO_INCREMENT, post TEXT, replyto INT, PRIMARY KEY (id));
