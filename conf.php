<?php
/**
 * Created by PhpStorm.
 * User: Mohammad Shawawhneh
 * Date: 11/10/2017
 * Time: 12:12 AM
 */
$con = mysqli("localhost","root","","techcamp");

if(mysqli_connect_errno())
{
    print_f("Connection Failed: %s\n",mysqli_connect_error());
}