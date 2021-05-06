/*DROP DATABASE wiki;*/
CREATE DATABASE wiki;
USE wiki;

CREATE TABLE Article
(
	ID_Article           INTEGER NOT NULL,
	Name                 VARCHAR(200) NOT NULL UNIQUE,
	Date                 DATE NULL CHECK (DATE >= '2021-05-06'),
	Short_story          TEXT(20000) NULL,
	Rating               FLOAT NULL,
	Media                BLOB NULL,
	ID_User              INTEGER NOT NULL,
	ID_Subject           INTEGER NOT NULL
);

CREATE TABLE Block
(
	ID_Block             INTEGER NOT NULL,
	Name                 VARCHAR(200) NULL,
	Text                 TEXT(20000) NULL,
	ID_Article           INTEGER NOT NULL,
	ID_User              INTEGER NOT NULL
);

CREATE TABLE Country
(
	ID_Country           INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Name                 VARCHAR(200) NOT NULL UNIQUE
);

CREATE TABLE Subject
(
	ID_Subject           INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Name                 VARCHAR(200) NOT NULL UNIQUE,
	Description          TEXT(20000) NULL
);

CREATE TABLE User
(
	ID_User              INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Login                VARCHAR(200) NOT NULL UNIQUE,
	Password             VARCHAR(200) NOT NULL,
	FIO                  VARCHAR(2000) NOT NULL,
	ID_Country           INTEGER NOT NULL,
	State                BOOLEAN NOT NULL,
	Dith_Day             DATE NOT NULL,
	SocNet               TEXT(20000) NULL,
	Mail                 VARCHAR(2000) NULL,
	Photo                BLOB NULL
);

ALTER TABLE Article
ADD PRIMARY KEY (ID_Article,ID_User);

ALTER TABLE Article CHANGE ID_Article ID_Article INTEGER NOT NULL AUTO_INCREMENT;

ALTER TABLE Article
ADD FOREIGN KEY R_39 (ID_Subject) REFERENCES Subject (ID_Subject);

ALTER TABLE Block
ADD PRIMARY KEY (ID_Block,ID_Article,ID_User);

ALTER TABLE Block CHANGE ID_Block ID_Block INTEGER NOT NULL AUTO_INCREMENT;

ALTER TABLE Article
ADD FOREIGN KEY R_38 (ID_User) REFERENCES User (ID_User);

ALTER TABLE Block
ADD FOREIGN KEY R_37 (ID_Article, ID_User) REFERENCES Article (ID_Article, ID_User);

ALTER TABLE User
ADD FOREIGN KEY R_35 (ID_Country) REFERENCES Country (ID_Country);