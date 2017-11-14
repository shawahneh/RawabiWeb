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
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

// headers to tell that result is JSON
header('Content-type: application/json');

switch ($action)
{
    case"userAuth":
        echo methods::userAuth($_POST["username"],$_POST["password"]);
        break;
    case "userRegister":
        //$username,$password,$fname,$lname,$gender,$birthdate,$address,$userType,$image,$phone
        echo methods::userRegister($_POST["username"],$_POST["password"],$_POST["fname"],$_POST["lname"],$_POST["gender"],$_POST["birthdate"],$_POST["address"],$_POST["userType"],$_POST["image"],$_POST["phone"]);
        break;
    case "myJourneys":
        echo methods::getMyJourneys($_POST["username"],$_POST["password"]);
        break;
    case "getMyRides":
        echo methods::getMyRides($_POST["username"],$_POST["password"]);
        break;
    case "getJourneyDetails":
        echo methods::getJourneyDetails($_POST["username"],$_POST["password"],$_POST["journeyId"]);
        break;
    case "getRideDetails":
        echo methods::getRideDetails($_POST["username"],$_POST["password"],$_POST["rideId"]);
        break;
    case "setRideOnJourney":
        // this method get the journeyId and meetingLocation on this method i checked if there is available seats
        // if there is a seats available the output will be :  success
        // if there is no seats available for the selected journey : noAvailableSeats
        // if something went wrong it will be : fail
        echo  methods::setRideOnJourney($_POST["username"],$_POST["password"],$_POST["journeyId"],$_POST["meetingLocation"]);
        break;
    case "changeRideStatus":
        //Output :
        // if the ride related to journey for this user : success
        // if the ride is not relater to journey for the user : rideNotRelatedToUser
        // if something went wrong it will br : fail

        // the status is :
        // 0 : pending
        // 1 : accepted
        // 2 : rejected
        echo methods::changeRideStatus($_POST["username"],$_POST["password"],$_POST["rideId"],$_POST["orderStatus"]);
        break;
    case "getUserDetails":
        //this method return user details with the count of how much journeys the user have and rides
        echo methods::getUserDetails($_POST["username"],$_POST["password"],$_POST["userId"]);
        break;
}