--SQL for DRIVE CRM Database Tables

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `level` INT NOT NULL,
    `title` TEXT NOT NULL,
    `fname` TEXT NOT NULL,
    `lname` TEXT NOT NULL,
    `username` TEXT NOT NULL,
    `password` MEDIUMTEXT NOT NULL,
    `email` TEXT NOT NULL,
    `pod` TEXT NOT NULL,
    `office` TEXT NOT NULL,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS tickets;

CREATE TABLE tickets (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `clientid` BIGINT NOT NULL,
    `assigned` BIGINT,
    `status` TEXT NOT NULL,
    `request_type` TEXT NOT NULL,
    `date` DATETIME,
    `due_date` DATE,
    `priority` TEXT,
    `edit_date` DATETIME,
    `message` MEDIUMTEXT NOT NULL,
    `userid` BIGINT NOT NULL,
    `pod` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`assigned`) REFERENCES users(id),
    FOREIGN KEY (`userid`) REFERENCES users(id),
    FOREIGN KEY (`clientid`) REFERENCES clients(id)
);

DROP TABLE IF EXISTS ticket_notes;

CREATE TABLE ticket_notes (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `ticketid` BIGINT NOT NULL,
    `userid` BIGINT NOT NULL,
    `date` DATETIME,
    `message` MEDIUMTEXT NOT NULL,
    `edit_date` DATETIME,
    `edit_by` BIGINT,
    `pod` TEXT,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`ticketid`) REFERENCES tickets(id),
    FOREIGN KEY (`userid`) REFERENCES users(id),
    FOREIGN KEY (`edit_by`) REFERENCES users(id)
);

DROP TABLE IF EXISTS notes;

CREATE TABLE notes (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `clientid` BIGINT NOT NULL,
    `userid` BIGINT NOT NULL,
    `date` DATETIME,
    `message` MEDIUMTEXT NOT NULL,
    `office` TEXT NOT NULL,
    `pod` TEXT NOT NULL,
    `edit_date` DATETIME,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`clientid`) REFERENCES clients(`id`),
    FOREIGN KEY (`userid`) REFERENCES users(`id`)
);

DROP TABLE IF EXISTS clients;

CREATE TABLE clients (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `office` TEXT NOT NULL,
    `pod` TEXT NOT NULL,
    `name` TEXT NOT NULL,
    `social` TEXT,
    `web` TEXT,
    `contracts` MEDIUMTEXT,
    `url` MEDIUMTEXT,
    `contact_name` TEXT NOT NULL,
    `contact_email` TEXT NOT NULL,
    `contact_phone` TEXT NOT NULL,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS accounts;

CREATE TABLE accounts (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `clientid` BIGINT NOT NULL,
    `name` TEXT NOT NULL,
    `url` MEDIUMTEXT NOT NULL,
    `username` MEDIUMTEXT NOT NULL,
    `password` MEDIUMTEXT NOT NULL,
    `pod` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`clientid`) REFERENCES clients(id)
);

