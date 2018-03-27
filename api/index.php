<?php
/**
 * Created by PhpStorm.
 * User: Mohammad Shawahneh
 * Date: 11/12/2017
 * Time: 7:53 PM
 */
// headers for not caching the results

$ind = "yes";
include("conf.php");
include("methods.php");
$action = @$_POST["action"];
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');


// headers to tell that result is JSON
header('Content-type: application/json');

switch ($action) {
    //done
    case"userAuth":
        echo methods::userAuth($_POST["username"], $_POST["password"]);
        break;
    //done
    case "userRegister":
        /* for the gender :
         * for male set the value =  0
         * for female set the value = 1
         */
        //$username,$password,$fname,$lname,$gender,$birthdate,$address,$userType,$image,$phone
        echo methods::userRegister($_POST["username"], $_POST["password"], $_POST["fullname"], $_POST["gender"], $_POST["birthdate"], $_POST["address"], $_POST["image"], $_POST["phone"]);
        break;
    //done
    case "getUserDetails":
        //this method return user details with the count of how much journeys the user have and rides
        echo methods::getUserDetails($_POST["username"], $_POST["password"], $_POST["userId"]);
        break;
    case "setUserDetails":
        //for this method the user have to provide the current password which is the oldpassword to check if he/she is the same user not some one else
        //if the user do not need to change his password but he/she want to change other details he/she can leave the newPassword field empty
        echo methods::setUserDetails($_POST["username"], $_POST["password"], $_POST["fullname"], $_POST["gender"], $_POST["birthdate"], $_POST["address"], $_POST["image"], $_POST["phone"], $_POST["newPassword"], $_POST["oldPassword"]);
        break;
    //done
    case "getJourneys":
        //if userId <=0 then it will set the userId for the logged in user
        echo methods::getJourneys($_POST["username"], $_POST["password"], $_POST["userId"], $_POST["start"], $_POST["num"]);
        break;
    //done
    case "setNewJourney":
        //on success return id
        //on fail return -1
        echo methods::setNewJourney($_POST["username"], $_POST["password"], $_POST["startLocationX"], $_POST["startLocationY"], $_POST["endLocationX"], $_POST["endLocationY"], $_POST["goingDate"], $_POST["seats"], $_POST["genderPrefer"], $_POST["carDescription"]);
        break;
    //done
    case "getRides":
        //if userId <=0 then it will set the userId for the logged in user
        echo methods::getRides($_POST["username"], $_POST["password"], $_POST["userId"], $_POST["start"], $_POST["num"]);
        break;
    case "getJourneyDetails":
        echo methods::getJourneyDetails($_POST["username"], $_POST["password"], $_POST["journeyId"]);
        break;
    case "getRideDetails":
        echo methods::getRideDetails($_POST["username"], $_POST["password"], $_POST["rideId"]);
        break;
    case "setRideOnJourney":
        // this method get the journeyId and meetingLocation on this method i checked if there is available seats
        // if there is a seats available the output will be :  success
        // if there is no seats available for the selected journey : noAvailableSeats
        // if something went wrong it will be : fail
        echo methods::setRideOnJourney($_POST["username"], $_POST["password"], $_POST["journeyId"],$_POST["meetingLocationX"], $_POST["meetingLocationY"]);
        break;
    case "changeRideStatus":
        //Output :
        // if the ride related to journey for this user : success
        // if the ride is not relater to journey for the user : rideNotRelatedToUser
        // if something went wrong it will br : fail

        // you can find the status on the Ride class on the android section
        echo methods::changeRideStatus($_POST["username"], $_POST["password"], $_POST["rideId"], $_POST["orderStatus"]);
        break;
    case "filterJourneys":
        //right now it return every thing from the database.
        // do not take care about the sortBy parameter send anything right now for it.
        echo methods::filterJourneys($_POST["username"], $_POST["password"],$_POST["startPointX"],$_POST["startPointY"],$_POST["endPointX"],$_POST["endPointY"],$_POST["goingDate"],$_POST["sortBy"]);
        break;
    case "changeJourneyStatusAndGetRiders":
        echo methods::changeJourneyStatusAndGetRiders($_POST["username"],$_POST["password"],$_POST["journeyid"],$_POST["status"]);
        break;
    case "getRidersOfJourney":
        echo methods::getRidersOfJourney($_POST["username"],$_POST["password"],$_POST["journeyid"]);
        break;
    case "getStatusOfRide":
        echo methods::getStatusOfRide($_POST["username"],$_POST["password"],$_POST["rideid"]);
        break;
    case "getNumberOfJourneys":
        echo methods::getNumberOfJourneys();
        break;
    case "getEventAtDate":
        echo methods::getEventAtDate($_POST["date"]);
        break;
    case "getEvents":
        echo methods::getEvents();
        break;
    case "getAnnouns":
        echo methods::getAnnouns();
        break;
    case "getJobs":
        echo methods::getJobs();
        break;
    case "getTransportation":
        echo methods::getTransportation();
        break;
    case "getMedia":
        echo methods::getMedia();
        break;
        default:
        echo json_encode(array("auth" => $_POST));
}