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
include "User.php";
class  methods
{
    //this method not for API it is used on the api methods inside php side
    public static function checkAuth($username,$password)
    {
        $user = user::getAuth($username,$password);
        return $user;
    }
    //these methods for API
    public static function userAuth($username,$password)
    {

        if (self::checkAuth($username,$password))
        {
            return json_encode(array("auth"=>"true"));
        }else
            return json_encode(array("auth"=>"false"));
    }
    public static function userRegister($username,$password,$fname,$lname,$gender,$birthdate,$address,$userType,$image,$phone)
    {
        global $con;
        $username=mysqli_real_escape_string($con,$username);
        $password=mysqli_real_escape_string($con,$password);
        $fname=mysqli_real_escape_string($con,$fname);
        $lname=mysqli_real_escape_string($con,$lname);
        $gender=mysqli_real_escape_string($con,$gender);
        $birthdate=mysqli_real_escape_string($con,$birthdate);
        $address=mysqli_real_escape_string($con,$address);
        $userType=mysqli_real_escape_string($con,$userType);
        $image=mysqli_real_escape_string($con,$image);
        $phone=mysqli_real_escape_string($con,$phone);

        $q = mysqli_query($con,"insert into users set username='".$username."',
                                                            password='".$password."',
                                                            fname='".$fname."',
                                                            lname='".$lname."',
                                                            gender='".$gender."',
                                                            birthdate='".$birthdate."',
                                                            address='".$address."',
                                                            userType='".$userType."',
                                                            image='".$image."',
                                                            phone='".$phone."'");
        if ($q)
        {
            return json_encode(array("registration"=>"success"));
        }else
            return json_encode(array("registration"=>"failed"));
    }
    public static function getUserDetails($username,$password,$userId)
    {
        $user = self::checkAuth($username,$password);
        global $con;
        $userId = mysqli_real_escape_string($con,$userId);
        if ($user)
        {
            $user = null;
            $q = mysqli_query($con,"SELECT *,(select count(*) from journeys where journeys.userId = u.id) as numJourneys,(select count(*) from rides where rides.userId = u.id) as numRides from users as u where id='".$userId."'");
            if ($r=mysqli_fetch_array($q)) {
                $user = array("username"=>$r["username"],
                                "fname"=>$r["fname"],
                                "lname"=>$r["lname"],
                                "gender"=>$r["gender"],
                                "birthdate"=>$r["birthdate"],
                                "address"=>$r["address"],
                                "userType"=>$r["userType"],
                                "image"=>$r["image"],
                                "phone"=>$r["phone"],
                                "numJourneys"=>$r["numJourneys"],
                                "numRides"=>$r["numRides"]);
            }
            return json_encode($user);
        }else
            return json_encode(array("auth"=>"false"));
    }
    public static function setUserDetails($username,$password,$fname,$lname,$gender,$birthdate,$address,$image,$phone,$newPassword,$oldPassword)
    {
        $user = self::checkAuth($username,$oldPassword);
        global $con;
        $fname=mysqli_real_escape_string($con,$fname);
        $lname=mysqli_real_escape_string($con,$lname);
        $gender=mysqli_real_escape_string($con,$gender);
        $birthdate=mysqli_real_escape_string($con,$birthdate);
        $address=mysqli_real_escape_string($con,$address);
        $image=mysqli_real_escape_string($con,$image);
        $phone=mysqli_real_escape_string($con,$phone);
        $newPassword = mysqli_real_escape_string($con,$newPassword);
        $oldPassword = mysqli_real_escape_string($con,$oldPassword);
        $status = "fail";
        if ($user)
        {
            if ($newPassword == "")
            {
                $newPassword = $oldPassword;
            }
            $q = mysqli_query($con,"update users set  password='".$newPassword."',
                                                            fname='".$fname."',
                                                            lname='".$lname."',
                                                            gender='".$gender."',
                                                            birthdate='".$birthdate."',
                                                            address='".$address."',
                                                            image='".$image."',
                                                            phone='".$phone."' where id = '".$user->id."'");
            if ($q)
            {
                $status = "success";
            }
            return json_encode(array("status"=>$status));
        }else
            return json_encode(array("status"=>"oldPasswordNotCorrect"));




    }
    public static function getMyJourneys($username,$password){
        $user = self::checkAuth($username,$password);
        global $con;
        if ($user)
        {
            $q = mysqli_query($con,"select * from journeys where userId='".$user->id."'");
            $journeys = array();
            while($r=mysqli_fetch_array($q))
            {
                //startLocation	endLocation	goingDate	seats	genderPrefer	carDescription
                array_push($journeys,array( "id"=>$r["id"],
                                            "startLocation"=>$r["startLocation"],
                                            "endLocation"=>$r["endLocation"],
                                            "goingDate"=>$r["goingDate"],
                                            "seats"=>$r["seats"],
                                            "genderPrefer"=>$r["genderPrefer"],
                                            "carDescription"=>$r["carDescription"]));
            }
            return json_encode(array("journeys"=>$journeys));
        }else
            return json_encode(array("auth"=>"false"));
    }
    public static function getMyRides($username,$password){
        $user = self::checkAuth($username,$password);
        global $con;
        if ($user)
        {
            $q = mysqli_query($con,"select * from rides where userId='".$user->id."'");
            $rides = array();
            while ($r = mysqli_fetch_array($q))
            {
                array_push($rides,array("id"=>$r["id"],
                                        "userId"=>$r["userId"],
                                        "journeyId"=>$r["journeyId"],
                                        "meetingLocation"=>$r["meetingLocation"],
                                        "orderStatus"=>$r["orderStatus"]));
            }
            return json_encode(array("rides"=>$rides));
        }else
            return json_encode(array("auth"=>"false"));
    }
    public static function getJourneyDetails($username,$password,$journeyId){
        $user = self::checkAuth($username,$password);
        global $con;
        if ($user)
        {
            $q = mysqli_query($con,"select * from journeys where id='".$journeyId."'");
            $journey = null;
            if ($r = mysqli_fetch_array($q)) {
                $qRides = mysqli_query($con, "select * from rides where journeyId = '" . $journeyId . "'");
                $rides = array();
                while ($rRides = mysqli_fetch_array($qRides)) {
                    array_push($rides, array("id" => $rRides["id"],
                        "userId" => $rRides["userId"],
                        "journeyId" => $rRides["journeyId"],
                        "meetingLocation" => $rRides["meetingLocation"],
                        "orderStatus" => $rRides["orderStatus"]));
                }
                $journey = array("id" => $r["id"],
                    "startLocation" => $r["startLocation"],
                    "endLocation" => $r["endLocation"],
                    "goingDate" => $r["goingDate"],
                    "seats" => $r["seats"],
                    "genderPrefer" => $r["genderPrefer"],
                    "carDescription" => $r["carDescription"],
                    "rides" => $rides);
            }
            return json_encode($journey);
        }else
            return json_encode(array("auth"=>"false"));
    }
    public static function getRideDetails($username,$password,$rideId)
    {
        $user = self::checkAuth($username,$password);
        global $con;
        if ($user)
        {
            $qRides = mysqli_query($con,"select * from rides where id = '".$rideId."'");
            $ride = null;
            if ($rRides = mysqli_fetch_array($qRides)) {
                $qJourney = mysqli_query($con, "select * from journeys where id = '" . $rRides["journeyId"] . "'");
                $rJourney = mysqli_fetch_array($qJourney);
                $journey = null;
                if ($rJourney) {
                    $journey = array("id" => $rJourney["id"],
                        "startLocation" => $rJourney["startLocation"],
                        "endLocation" => $rJourney["endLocation"],
                        "goingDate" => $rJourney["goingDate"],
                        "seats" => $rJourney["seats"],
                        "genderPrefer" => $rJourney["genderPrefer"],
                        "carDescription" => $rJourney["carDescription"]);
                }
                $ride = array("id" => $rRides["id"],
                    "userId" => $rRides["userId"],
                    "journeyId" => $rRides["journeyId"],
                    "meetingLocation" => $rRides["meetingLocation"],
                    "orderStatus" => $rRides["orderStatus"],
                    "journey" => $journey);
            }
            return json_encode($ride);
        }else
            return json_encode(array("auth"=>"false"));
    }
    public static function setRideOnJourney($username,$password,$journeyId,$meetingLocation){

        $user = self::checkAuth($username,$password);
        global $con;
        $journeyId = mysqli_real_escape_string($con,$journeyId);
        $meetingLocation = mysqli_real_escape_string($con,$meetingLocation);
        if ($user)
        {
            $q = mysqli_query($con,"select COUNT(r.id) as jnum,(select seats from journeys where journeys.id = '".$journeyId."') as seats from journeys j JOIN rides r on j.id = r.journeyId where j.id = '".$journeyId."' and r.orderStatus = 1");
            $status = "fail";
            if ($r = mysqli_fetch_array($q))
            {
                if ($r["jnum"]<$r["seats"])
                {
                    $qInsert = mysqli_query($con,"insert into rides set userId='".$user->id."',
                                                                              journeyId='".$journeyId."',
                                                                              meetingLocation='".$meetingLocation."',
                                                                              orderStatus = 0");
                    if ($qInsert)
                    {
                        $status = "success";
                    }
                }else
                    $status = "noAvailableSeats";
            }


            return json_encode(array("status"=>$status));
        }else
            return json_encode(array("auth"=>"false"));
    }
    public static function changeRideStatus($username,$password,$rideId,$orderStatus)
    {
        $user = self::checkAuth($username,$password);
        global $con;
        $rideId = mysqli_real_escape_string($con,$rideId);
        $orderStatus = mysqli_real_escape_string($con,$orderStatus);
        if ($user)
        {
            $status = "fail";
            $q = mysqli_query($con,"select * from journeys j , rides r where j.id = r.journeyId and j.userId='".$user->id."' and r.id = '".$rideId."'");
            if ($r = mysqli_fetch_array($q))
            {
                $qChange = mysqli_query($con,"update rides set orderStatus='".$orderStatus."' where id = '".$rideId."'");
                if ($qChange)
                {
                    $status = "success";
                }
            }else
            {
                $status = "rideNotRelatedToUser";
            }

            return json_encode(array("status"=>$status));
        }else
            return json_encode(array("auth"=>"false"));
    }
}