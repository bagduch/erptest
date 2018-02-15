#!/bin/bash
mysqldump --add-drop-database --no-data -B ra > db/01_robotacct_schema.sql
mysqldump -n -t --skip-add-drop-table -B ra > db/02_robotacct_data.sql
sed -i 's/ AUTO_INCREMENT=[0-9]*/ AUTO_INCREMENT=0/g' db/01_robotacct_schema.sql

