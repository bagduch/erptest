<?php
/*
**********************************************

      *** Addon Module Example Hook ***

This is a demo hook file for an addon module.
Addon Modules can utilise all of the WHMCS
hooks in exactly the same way as a normal hook
file would, and can contain multiple hooks.

For more info, please refer to the hooks docs
 @   http://wiki.whmcs.com/Hooks

**********************************************
*/

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
