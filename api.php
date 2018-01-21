<?php
/**
 * Here you can execute API methods
 */

include_once 'includes/defines.php';

/* date is for writing new data from remote API */
$date = (isset($_GET['date']))?$_GET['date']:null;

$match = new Matches($date);

if (isset($_GET['getData'])) {
    echo $match->getData();
}

if (isset($_GET['setData'])) {
    echo $match->writeDataToDb();
}
