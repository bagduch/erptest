<?php


function hdtolls_hook_login($vars) {
    # Hook code goes here
    //echo 'test';
}

function hdtolls_hook_logout($vars) {
    # Hook code goes here
    //echo 'test';
}

add_hook("ClientLogin",1,"hdtolls_hook_login");
add_hook("ClientLogout",1,"hdtolls_hook_logout");
