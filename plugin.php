<?php
/*
 * Plugin Name:       NetGear - Netservice Plugin OOP Structure
 * Description:       Struttura per plugin orientati agli oggetti
 * Version:           0.1
 * Author:            Mattia Mariselli
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

function bootrap_netgear(){
    $dir = dirname(__FILE__).'/class/';
    require_once $dir . 'net-gear-hook.php';
    require_once $dir . 'net-gear-page-controller.php';
    require_once $dir . 'net-gear.php';
    //
    //NetGear::showError();
    NetGear::bootstrap();
}

bootrap_netgear();