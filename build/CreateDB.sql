/*
    Tables       : TableName (always singular)
    Keys         : columnName
    Foreign keys : tableNameColumnName
*/

CREATE TABLE Member (
    /* GENERAL INFO */
    id INT NOT NULL AUTO_INCREMENT,
    uname VARCHAR(64) NOT NULL,
    passwd VARCHAR(255) NOT NULL,
    mail VARCHAR(64) NOT NULL,
    xp INT NOT NULL DEFAULT 0,
    createDate DATETIME NOT NULL DEFAULT NOW(),

    /* FIRST-ORDER INFO */
    fullname VARCHAR(64),
    gender CHAR(1),

    /* SECOND-ORDER INFO */
    country VARCHAR(64),
    city VARCHAR(64),

    /* THIRD-ORDER INFO */
    profession VARCHAR(64),
    /* TODO: ADD ADDITIONAL INFO */

    PRIMARY KEY (id)
);


CREATE TABLE Poll (
    memberID INT NOT NULL,

    id INT NOT NULL AUTO_INCREMENT,
    question VARCHAR(255) NOT NULL,
    pollType VARCHAR(7) NOT NULL,              # local or global
    optionType VARCHAR(7) NOT NULL,              # multi-option or single-option
    stat VARCHAR(7) NOT NULL DEFAULT 'open', # open or closed
    duration INT NOT NULL DEFAULT 20,

    /* LOCATION */
    latitude FLOAT(10,6) NOT NULL,
    longitude FLOAT(10,6) NOT NULL,
    diameter INT NOT NULL,


    PRIMARY KEY (id),
    FOREIGN KEY (memberID) REFERENCES Member(id)
);

CREATE TABLE PollOption (
    pollID INT NOT NULL,

    id INT NOT NULL AUTO_INCREMENT,
    content VARCHAR(255) NOT NULL,
    vote INT NOT NULL DEFAULT 0,

    PRIMARY KEY (id),
    FOREIGN KEY (pollID) REFERENCES Poll(id) ON DELETE CASCADE
);

CREATE TABLE Comment (
    memberID INT NOT NULL,

    id INT NOT NULL AUTO_INCREMENT,
    createDate DATETIME NOT NULL DEFAULT NOW(),
    content VARCHAR(255) NOT NULL,
    upVote INT NOT NULL DEFAULT 0,
    downVote INT NOT NULL DEFAULT 0,

    PRIMARY KEY (id),
    FOREIGN KEY (memberID) REFERENCES Member(id)
);

CREATE TABLE PollComment (
    id INT NOT NULL AUTO_INCREMENT,
    commentID INT NOT NULL,
    pollID INT NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (commentID) REFERENCES Comment(id),
    FOREIGN KEY (pollID) REFERENCES Poll(id) ON DELETE CASCADE
);

CREATE TABLE PollOptionComment (
    id INT NOT NULL AUTO_INCREMENT,
    pollOptionID INT NOT NULL,
    commentID INT NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (pollOptionID) REFERENCES PollOption(id) ON DELETE CASCADE,
    FOREIGN KEY (commentID) REFERENCES Comment(id) ON DELETE CASCADE
);

CREATE TABLE MemberPoll (
    memberID INT NOT NULL,
    pollID INT NOT NULL,

    id INT NOT NULL AUTO_INCREMENT,

    PRIMARY KEY (id),
    FOREIGN KEY (memberID) REFERENCES Member(id) ON DELETE CASCADE,
    FOREIGN KEY (pollID) REFERENCES Poll(id) ON DELETE CASCADE
);

CREATE TABLE MemberPollOption (
    memberPollID INT NOT NULL,
    pollOptionID INT NOT NULL,

    FOREIGN KEY (memberPollID) REFERENCES MemberPoll(id) ON DELETE CASCADE,
    FOREIGN KEY (pollOptionID) REFERENCES PollOption(id) ON DELETE CASCADE
);



/* TESTS */

/* users */
INSERT INTO `Member` (`id`, `uname`, `passwd`, `mail`, `xp`, `createDate`, `fullname`, `gender`, `country`, `city`, `profession`) VALUES (NULL, 'user1', 'user1passwd', 'user1mail', '0', CURRENT_TIMESTAMP, 'user1fullname', 'e', 'user1country', 'user1city', 'user1profession');
INSERT INTO `Member` (`id`, `uname`, `passwd`, `mail`, `xp`, `createDate`, `fullname`, `gender`, `country`, `city`, `profession`) VALUES (NULL, 'user2', 'user2passwd', 'user2mail', '0', CURRENT_TIMESTAMP, 'user2fullname', 'e', 'user2country', 'user2city', 'user2profession');
INSERT INTO `Member` (`id`, `uname`, `passwd`, `mail`, `xp`, `createDate`, `fullname`, `gender`, `country`, `city`, `profession`) VALUES (NULL, 'user3', 'user3passwd', 'user3mail', '0', CURRENT_TIMESTAMP, 'user3fullname', 'e', 'user3country', 'user3city', 'user3profession');
INSERT INTO `Member` (`id`, `uname`, `passwd`, `mail`, `xp`, `createDate`, `fullname`, `gender`, `country`, `city`, `profession`) VALUES (NULL, 'user4', 'user4passwd', 'user4mail', '0', CURRENT_TIMESTAMP, 'user4fullname', 'e', 'user4country', 'user4city', 'user4profession');


/* polls */
INSERT INTO `Poll` (`memberID`, `id`, `question`, `pollType`, `optionType`, `stat`, `duration`, `latitude`, `longitude`, `diameter`) VALUES ('1', 1, 'poll1question', 'local', 'single', 'open', '20', '53.293982', '-6.166417', '100');
INSERT INTO `PollOption` (`pollID`, `id`, `content`, `vote`) VALUES ('1', NULL, 'poll1option1', '15');
INSERT INTO `PollOption` (`pollID`, `id`, `content`, `vote`) VALUES ('1', NULL, 'poll1option2', '33');
INSERT INTO `PollOption` (`pollID`, `id`, `content`, `vote`) VALUES ('1', NULL, 'poll1option3', '323');

INSERT INTO `Poll` (`memberID`, `id`, `question`, `pollType`, `optionType`, `stat`, `duration`, `latitude`, `longitude`, `diameter`) VALUES ('2', 2, 'poll2question', 'local', 'single', 'open', '20', '14.293982', '-22.166417', '130');
INSERT INTO `PollOption` (`pollID`, `id`, `content`, `vote`) VALUES ('2', NULL, 'poll2option1', '15');
INSERT INTO `PollOption` (`pollID`, `id`, `content`, `vote`) VALUES ('2', NULL, 'poll2option2', '33');
INSERT INTO `PollOption` (`pollID`, `id`, `content`, `vote`) VALUES ('2', NULL, 'poll2option3', '323');

INSERT INTO `Poll` (`memberID`, `id`, `question`, `pollType`, `optionType`, `stat`, `duration`, `latitude`, `longitude`, `diameter`) VALUES ('2', 3, 'poll3question', 'local', 'single', 'open', '20', '23.293982', '-4.166417', '100');
INSERT INTO `PollOption` (`pollID`, `id`, `content`, `vote`) VALUES ('3', NULL, 'poll1option1', '15');
INSERT INTO `PollOption` (`pollID`, `id`, `content`, `vote`) VALUES ('3', NULL, 'poll1option2', '33');
