<?php

function map_hbadmin_raadmin($hbadminid) {
    switch ($hbadminid) {
    case 1: // ben
        return 1;
        break;
    case 3: // guy
        return 3;
        break;
    case 9: // peter
        return 2;
        break;
    default:
        return 0;
        break;
    }
}
