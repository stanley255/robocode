CREATE TABLE ROBOCODE.TEAMS(
  id   INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50),
  exp  INT DEFAULT 0
);

CREATE TABLE ROBOCODE.PRIVILEGES(
  id    INT PRIMARY KEY AUTO_INCREMENT,
  name  VARCHAR(50),
  valid VARCHAR(1),
  CHECK (valid='Y' OR valid='N')
);

CREATE TABLE ROBOCODE.USERS(
  id                     INT PRIMARY KEY AUTO_INCREMENT,
  username               VARCHAR(50),
  name                   VARCHAR(50),
  surname                VARCHAR(50),
  email                  VARCHAR(50),
  password               VARCHAR(300),
  registration_date      DATE,
  team_id                INT,
  exp                    INT DEFAULT 0,
  privilege              INT DEFAULT 0,
  FOREIGN KEY(team_id)   REFERENCES TEAMS(ID),
  FOREIGN KEY(privilege) REFERENCES PRIVILEGES(ID)
);

CREATE TABLE ROBOCODE.TEAMS_MAP(
  FK_USER_ID              INT,
  FK_TEAM_ID              INT,
  FOREIGN KEY(FK_USER_ID) REFERENCES USERS(ID),
  FOREIGN KEY(FK_TEAM_ID) REFERENCES TEAMS(ID)
);

CREATE TABLE ROBOCODE.LEVELS(
  id            INT PRIMARY KEY AUTO_INCREMENT,
  lvl           INT,
  min_exp_value INT,
  max_exp_value INT,
  description   VARCHAR(200)
);

CREATE TABLE ROBOCODE.ACHIEVEMENTS(
  id          INT PRIMARY KEY AUTO_INCREMENT,
  name        VARCHAR(50),
  description VARCHAR(200),
  category_id INT DEFAULT NULL,
  rarity_id   INT DEFAULT NULL,
  exp_bonus   INT
);

CREATE TABLE ROBOCODE.ACHIEVEMENTS_MAP(
  FK_USER_ID                     INT,
  FK_ACHIEVEMENT_ID              INT,
  FOREIGN KEY(FK_USER_ID)        REFERENCES USERS(ID),
  FOREIGN KEY(FK_ACHIEVEMENT_ID) REFERENCES ACHIEVEMENTS(ID)
);

CREATE TABLE ROBOCODE.POSTS(
  id                      INT PRIMARY KEY AUTO_INCREMENT,
  date                    DATE,
  FK_USER_ID              INT,
  text                    VARCHAR(2000),
  pinned                  VARCHAR(1),
  FOREIGN KEY(FK_USER_ID) REFERENCES USERS(ID),
  CHECK (pinned='Y' OR pinned='N')
);

CREATE TABLE ROBOCODE.EVENTS_CATEGORY(
  id   INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50)
);

CREATE TABLE ROBOCODE.EVENTS(
  id                                INT PRIMARY KEY AUTO_INCREMENT,
  fk_event_category_id              INT,
  start_stamp                       DATETIME,
  end_stamp                         DATETIME,
  description                       VARCHAR(50),
  fk_lector_id                      INT,
  FOREIGN KEY(fk_event_category_id) REFERENCES EVENTS_CATEGORY(ID),
  FOREIGN KEY(fk_lector_id)         REFERENCES USERS(ID)
);

CREATE TABLE ROBOCODE.ATTENDANCE(
  fk_event_id              INT,
  fk_user_id               INT,
  FOREIGN KEY(fk_event_id) REFERENCES EVENTS(ID),
  FOREIGN KEY(fk_user_id)  REFERENCES USERS(ID)
);
