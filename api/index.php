<?php
/**
 * Created by PhpStorm.
 * User: Mohammad Shawahneh
 * Date: 11/12/2017
 * Time: 7:53 PM
 */
// headers for not caching the results

$ind = "yes";
include ("../conf.php");
include ("methods.php");
$action=@$_POST["action"];
/*header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

// headers to tell that result is JSON
header('Content-type: application/json');
*/
switch ($action)
{
    case"userAuth":
        echo methods::userAuth($_POST["username"],$_POST["password"]);
        break;
}