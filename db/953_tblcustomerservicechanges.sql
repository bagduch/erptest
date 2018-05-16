ALTER TABLE tblcustomerservices ADD COLUMN `serverid` int(10) DEFAULT NULL COMMENT "ID of the server it's associated with, per tblservers, or null for nothing" AFTER packageid;
DELETE FROM tblcustomfieldslinks WHERE id=3;
ALTER TABLE tblcustomfieldslinks ADD UNIQUE (cfid,serviceid);

ALTER TABLE tblemailtemplates
    MODIFY COLUMN type ENUM("support","general","product","invoice","affiliate","admin") DEFAULT "general",
    MODIFY COLUMN name text NOT NULL DEFAULT "",
    MODIFY COLUMN subject text NOT NULL DEFAULT "",
    MODIFY COLUMN message text NOT NULL DEFAULT "",
    MODIFY COLUMN attachments text NOT NULL DEFAULT "",
    MODIFY COLUMN fromname text NOT NULL DEFAULT "",
    MODIFY COLUMN fromemail text NOT NULL DEFAULT "",
    MODIFY COLUMN disabled text NOT NULL DEFAULT "",
    MODIFY COLUMN custom text NOT NULL DEFAULT "",
    MODIFY COLUMN copyto text NOT NULL DEFAULT "",
    MODIFY COLUMN plaintext int(1) DEFAULT 0;
