-- CREATE RECORDS DB
CREATE DATABASE records;

-- CREATE GRADES TABLE
CREATE TABLE `grades`
(
    `ID`                INT AUTO_INCREMENT PRIMARY KEY,
    `Name`              TEXT,
    `Subject`           TEXT,
    `Section`           TEXT,
    `Grade`             FLOAT,
    `Session`           TEXT
);