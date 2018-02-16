USE `ra`;
TRUNCATE TABLE tblactivitylog;
TRUNCATE TABLE tbladminlog;
DELETE FROM tblclients; -- foreign key constraiht
