-- Inserty práv
INSERT INTO ROBOCODE.PRIVILEGES(name, valid) VALUES ('Nepotvrdený používateľ','Y');
INSERT INTO ROBOCODE.PRIVILEGES(name, valid) VALUES ('Žiak','Y');
INSERT INTO ROBOCODE.PRIVILEGES(name, valid) VALUES ('Správca','Y');
INSERT INTO ROBOCODE.PRIVILEGES(name, valid) VALUES ('Admin','Y');

-- Inserty teamov
INSERT INTO ROBOCODE.TEAMS(name,exp) VALUES('Zelený tím',0);
INSERT INTO ROBOCODE.TEAMS(name,exp) VALUES('Červený tím',0);
INSERT INTO ROBOCODE.TEAMS(name,exp) VALUES('Žltý tím',0);
INSERT INTO ROBOCODE.TEAMS(name,exp) VALUES('Admin tím',0);

-- Inserty kategórií eventov
INSERT INTO ROBOCODE.EVENTS_CATEGORY(name) VALUES('Hodina');
INSERT INTO ROBOCODE.EVENTS_CATEGORY(name) VALUES('Súťaž');
