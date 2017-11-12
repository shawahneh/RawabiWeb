<?php
ob_start();
/**
 * Created by PhpStorm.
 * User: Mohammad Shawahneh
 * Date: 11/12/2017
 * Time: 8:04 PM
 */
if ($ind!="yes")
{
    header("location: ./");
    exit();
}
class  methods
{
    public static function userAuth($username,$password)
    {
        global $con;
        $username = mysqli_real_escape_string($con,$username);
        $password = mysqli_real_escape_string($con,$password);
        $q = mysqli_query($con,"select * from users where password = '".$password."' and username = '".$username."'");
        $r = mysqli_fetch_array($q);
        if (isset($r))
        {
            return json_encode(array("auth"=>"true"));
        }else
            return json_encode(array("auth"=>"false"));
    }
}