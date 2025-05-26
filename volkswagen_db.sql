CREATE DATABASE volkswagen_db;

USE volkswagen_db;

CREATE TABLE models (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    year INT NOT NULL
);

CREATE TABLE engines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model_id INT NOT NULL,
    engine_type VARCHAR(255) NOT NULL,
    FOREIGN KEY (model_id) REFERENCES models(id)
);

CREATE TABLE user_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model_id INT NOT NULL,
    engine_id INT NOT NULL,
    FOREIGN KEY (model_id) REFERENCES models(id),
    FOREIGN KEY (engine_id) REFERENCES engines(id)
);
