CREATE TABLE "USER_T" (
	"userid"	INTEGER,
	"username"	TEXT,
	"email"	VARCHAR(255),
	"password"	VARCHAR(255),
    "token" VARCHAR(255)
    "date" DATE
	PRIMARY KEY("userid")
);

CREATE TABLE "USER_T" (
	userid	INTEGER,
	username	VARCHAR(255),
	email	VARCHAR(255),
	password	VARCHAR(255),
    token VARCHAR(255),
    date DATE
	PRIMARY KEY(userid),
);

CREATE TABLE "USER_T"(
    userid INTEGER,
    username TEXT,
    email VARCHAR(255),
    PASSWORD VARCHAR(255),
    token VARCHAR(255),
    DATE DATE PRIMARY KEY(userid),
);

CREATE TABLE "USER_T" (
	userid	INTEGER,
	username	VARCHAR(255),
	email	VARCHAR(255),
	password	VARCHAR(255),
    token VARCHAR(255),
    date DATE,
	PRIMARY KEY(userid),
);

CREATE TABLE "USER_T"(
    userid INTEGER,
    username VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255),
    token VARCHAR(255),
    date DATE,
    PRIMARY KEY(userid)
);


CREATE TABLE USER_T(
    userid INTEGER,
    username VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255),
    token VARCHAR(255),
    date DATE DEFAULT CURRENT_TIMESTAMP,
    verified BOOLEAN DEFAULT 0,
    PRIMARY KEY(userid)
);

CREATE TABLE `test`.`verify_t` (`id` INT NOT NULL AUTO_INCREMENT , `code` INT NOT NULL , `expires` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `test`.`comment_t` (`commentID` INT NOT NULL , `postID` INT NOT NULL , `content` VARCHAR(255) NOT NULL ) ENGINE = InnoDB;


CREATE TABLE POST_T( 
    postID INT NOT NULL, 
    title VARCHAR(255) NOT NULL,
    content VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    PRIMARY KEY (postID),
    FOREIGN KEY (author) REFERENCES user_t(userid)
)

CREATE TABLE COMMENT_T(
    commentID INT NOT NULL, 
    postID INT NOT NULL, 
    content VARCHAR(255) NOT NULL, 
    author INT NOT NULL,
    PRIMARY KEY (commentID),
    FOREIGN KEY (postID) REFERENCES post_t(postID),
    FOREIGN KEY (author) REFERENCES user_t(userid)
)
