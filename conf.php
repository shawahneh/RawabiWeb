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
$con = mysqli_connect("localhost","root","","techcamp");

if(mysqli_connect_errno())
{
    print_f("Connection Failed: %s\n",mysqli_connect_error());
}