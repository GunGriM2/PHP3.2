<?php

require "../vendor/autoload.php";

if ($_SERVER['REQUEST_URI'] == '/home') {
    require '../app/controllers/homepage.php';
}
