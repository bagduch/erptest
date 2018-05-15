ALTER TABLE tblcustomerservices ADD COLUMN `serverid` int(10) DEFAULT NULL COMMENT "ID of the server it's associated with, per tblservers, or null for nothing" AFTER packageid;
DELETE FROM tblcustomfieldslinks WHERE id=3;
ALTER TABLE tblcustomfieldslinks ADD UNIQUE (cfid,serviceid);
