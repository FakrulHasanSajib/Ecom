<?php
if (extension_loaded('gd')) {
    echo "GD is ENABLED. Version: " . gd_info()['GD Version'];
} else {
    echo "GD is NOT ENABLED.";
    echo "\nConfiguration File: " . php_ini_loaded_file();
    echo "\nExtension Directory: " . ini_get('extension_dir');
}
