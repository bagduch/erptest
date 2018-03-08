#!/bin/bash
mysqldump --skip-dump-date --add-drop-database --no-data -B ra > db/01_robotacct_schema.sql
mysqldump --skip-dump-date -n -t --skip-add-drop-table -B ra \
	--ignore-table=ra.tblactivitylog \
	--ignore-table=ra.tbladminlog \
	--ignore-table=ra.tblclients \
	--ignore-table=ra.tblconfiguration \
	--ignore-table=ra.tblemails \
	--ignore-table=ra.tblinvoices \
	--ignore-table=ra.tblinvoiceitems \
	> db/02_robotacct_data.sql
mysqldump --skip-dump-date -n -t --skip-add-drop-table -B ra --tables tblconfiguration | sed "s/),(/),\n\t(/g" > db/952_tblconfiguration.sql
sed -i 's/ AUTO_INCREMENT=[0-9]*/ AUTO_INCREMENT=0/g' db/01_robotacct_schema.sql

