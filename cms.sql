CREATE DATABASE simple_cms;

USE simple_cms;

CREATE TABLE pages (
	id INT NOT NULL AUTO_INCREMENT,
	label VARCHAR(60),
	title VARCHAR(255),
	slug VARCHAR(60),
	body TEXT,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL,
	PRIMARY KEY(id)
);