#!/bin/bash
mysql -e "DELETE FROM tbladminlog" ra
mysql -e "DELETE FROM tblemails" ra
mysqldump --skip-dump-date --add-drop-database --no-data -B ra > db/01_robotacct_schema.sql
mysqldump --skip-dump-date -n -t --skip-add-drop-table -B ra --ignore-table=ra.tblclients > db/02_robotacct_data.sql
sed -i 's/ AUTO_INCREMENT=[0-9]*/ AUTO_INCREMENT=0/g' db/01_robotacct_schema.sql

