<?php

require_once 'config/constants.php';

if (file_exists(AMBER_ROOT_PATH . '/public_html' . $_SERVER['REQUEST_URI']))
{
 return false; // serve the requested resource as-is.
}
else
{
 require_once AMBER_ROOT_PATH.'/public_html/index.php';
}
