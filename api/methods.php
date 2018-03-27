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
    public static function userRegister($username,$password,$fullname,$gender,$birthdate,$address,$userType,$image,$phone)
    {
        global $con;
        $username=mysqli_real_escape_string($con,$username);
        $password=mysqli_real_escape_string($con,$password);
        $fullname=mysqli_real_escape_string($con,$fullname);
        $gender=mysqli_real_escape_string($con,$gender);
        $birthdate=mysqli_real_escape_string($con,$birthdate);
        $address=mysqli_real_escape_string($con,$address);
        $userType=mysqli_real_escape_string($con,$userType);
        $image=mysqli_real_escape_string($con,$image);
        $phone=mysqli_real_escape_string($con,$phone);

        $q = mysqli_query($con,"insert into users set username='".$username."',
                                                            password='".$password."',
                                                            fullname='".$fullname."',
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
        $userCheck = self::checkAuth($username,$password);
        global $con;
        $userId = mysqli_real_escape_string($con,$userId);
        if ($userCheck)
        {
            if ($userId<=0)
            {
                $userId = $userCheck->id;
            }
            $user = null;
            $q = mysqli_query($con,"SELECT *,(select count(*) from journeys where journeys.userId = u.id) as numJourneys,(select count(*) from rides where rides.userId = u.id) as numRides from users as u where id='".$userId."'");
            if ($r=mysqli_fetch_array($q)) {
                $user = array(  "id"=>$r["id"],
                                "username"=>$r["username"],
                                "fullname"=>$r["fullname"],
                                "gender"=>$r["gender"],
                                "birthdate"=>$r["birthdate"],
                                "address"=>$r["address"],
                                "userType"=>$r["userType"],
                                "image"=>$r["image"],
                                "phone"=>$r["phone"],
                                "numJourneys"=>$r["numJourneys"],
                                "numRides"=>$r["numRides"]);
            }
            if ($user!=null) {
                return json_encode($user);
            }else
            {
                return json_encode(null);
            }
        }else
            return json_encode(array("auth"=>"false"));
    }
    public static function setUserDetails($username,$password,$fullname,$gender,$birthdate,$address,$image,$phone,$newPassword,$oldPassword)
    {
        $user = self::checkAuth($username,$oldPassword);
        global $con;
        $fullname=mysqli_real_escape_string($con,$fullname);
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
                                                            fullname='".$fullname."',
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
    public static function getJourneys($username,$password,$userId,$start,$num){
        $user = self::checkAuth($username,$password);
        global $con;
        if ($userId<=0)
        {
            $userId = $user->id;
        }
        $userId = mysqli_real_escape_string($con,$userId);
        $start = mysqli_real_escape_string($con,$start);
        $num = mysqli_real_escape_string($con,$num);
        if ($user)
        {
            $qU = mysqli_query($con,"SELECT * from users as u where id='".$userId."'");
            $userDetails = null;
            if ($r=mysqli_fetch_array($qU)) {
                $userDetails = array(  "id"=>$r["id"],
                                        "username"=>$r["username"],
                                        "fullname"=>$r["fullname"],
                                        "gender"=>$r["gender"],
                                        "birthdate"=>$r["birthdate"],
                                        "address"=>$r["address"],
                                        "userType"=>$r["userType"],
                                        "image"=>$r["image"],
                                        "phone"=>$r["phone"]);
            }
            $q = mysqli_query($con,"select * from journeys where userId='".$userId."' limit ".$start.",".$num);
            $journeys = array();
            while($r=mysqli_fetch_array($q))
            {
                //startLocation	endLocation	goingDate	seats	genderPrefer	carDescription
                array_push($journeys,array( "id"=>$r["id"],
                                            "startLocationX"=>$r["startLocationX"],
                                            "startLocationY"=>$r["startLocationY"],
                                            "endLocationX"=>$r["endLocationX"],
                                            "endLocationY"=>$r["endLocationY"],
                                            "goingDate"=>$r["goingDate"],
                                            "seats"=>$r["seats"],
                                            "genderPrefer"=>$r["genderPrefer"],
                                            "carDescription"=>$r["carDescription"],
                                            "status"=>$r["status"],
                                            "user"=>$userDetails));
            }
            return json_encode(array("journeys"=>$journeys));
        }else
            return json_encode(array("auth"=>"false"));
    }
    public static function setNewJourney($username,$password,$startLocationX,$startLocationY,$endLocationX,$endLocationY,$goingDate,$seats,$genderPrefer,$carDescription){
        $user = self::checkAuth($username,$password);
        global $con;
        $startLocationX = mysqli_real_escape_string($con,$startLocationX);
        $startLocationY = mysqli_real_escape_string($con,$startLocationY);
        $endLocationX = mysqli_real_escape_string($con,$endLocationX);
        $endLocationY = mysqli_real_escape_string($con,$endLocationY);
        $goingDate = mysqli_real_escape_string($con,$goingDate);
        $seats = mysqli_real_escape_string($con,$seats);
        $genderPrefer = mysqli_real_escape_string($con,$genderPrefer);
        $carDescription = mysqli_real_escape_string($con,$carDescription);


        if ($user)
        {
            $q = mysqli_query($con,"insert into journeys set userId='".$user->id."',
                                                                   startLocationX='".$startLocationX."',
                                                                   startLocationY='".$startLocationY."',
                                                                   endLocationX='".$endLocationX."',
                                                                   endLocationY='".$endLocationY."',
                                                                   goingDate='".$goingDate."',
                                                                   seats='".$seats."',
                                                                   genderPrefer='".$genderPrefer."',
                                                                   status='0',
                                                                   carDescription='".$carDescription."'");
            if ($q)
            return json_encode(array("status"=>"".mysqli_insert_id($con)));
            else
                return json_encode(array("status"=>"-1"));
        }else
            return json_encode(array("auth"=>"-1"));
    }
    public static function getRides($username,$password,$userId,$start,$num){
        $user = self::checkAuth($username,$password);
        global $con;

        if ($userId<=0)
        {
            $userId = $user->id;
        }
        $userId = mysqli_real_escape_string($con,$userId);
        $start = mysqli_real_escape_string($con,$start);
        $num = mysqli_real_escape_string($con,$num);
        if ($user)
        {
            $qU = mysqli_query($con,"SELECT * from users as u where id='".$userId."'");
            $userDetails = null;
            if ($r=mysqli_fetch_array($qU)) {
                $userDetails = array(  "id"=>$r["id"],
                    "username"=>$r["username"],
                    "fullname"=>$r["fullname"],
                    "gender"=>$r["gender"],
                    "birthdate"=>$r["birthdate"],
                    "address"=>$r["address"],
                    "userType"=>$r["userType"],
                    "image"=>$r["image"],
                    "phone"=>$r["phone"]);
            }
            $q = mysqli_query($con,"select *,j.userId as journeyUserId from rides r,journeys j where r.userId='".$userId."' and r.journeyId=j.id limit ".$start.",".$num);
            $rides = array();
            while ($r = mysqli_fetch_array($q))
            {
                $qJU = mysqli_query($con,"SELECT * from users as u where id='".$r["journeyUserId"]."'");
                $journeyUserDetails = null;
                if ($Jr=mysqli_fetch_array($qJU)) {
                    $journeyUserDetails = array(  "id"=>$Jr["id"],
                        "username"=>$Jr["username"],
                        "fullname"=>$Jr["fullname"],
                        "gender"=>$Jr["gender"],
                        "birthdate"=>$Jr["birthdate"],
                        "address"=>$Jr["address"],
                        "userType"=>$Jr["userType"],
                        "image"=>$Jr["image"],
                        "phone"=>$Jr["phone"]);
                }
                array_push($rides,array("id"=>$r["id"],
                                        "user"=>$userDetails,
                                        "journey"=>array( "id"=>$r["id"],
                                            "startLocationX"=>$r["startLocationX"],
                                            "startLocationY"=>$r["startLocationY"],
                                            "endLocationX"=>$r["endLocationX"],
                                            "endLocationY"=>$r["endLocationY"],
                                            "goingDate"=>$r["goingDate"],
                                            "seats"=>$r["seats"],
                                            "genderPrefer"=>$r["genderPrefer"],
                                            "carDescription"=>$r["carDescription"],
                                            "status"=>$r["status"],
                                            "user"=>$journeyUserDetails),
                                        "meetingLocationX"=>$r["meetingLocationX"],
                                        "meetingLocationY"=>$r["meetingLocationY"],
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

            $q = mysqli_query($con,"select *,j.id as jid,u.id as uid from journeys j,users u where j.userId=u.id and j.id='".$journeyId."'");
            $journey = null;
            if ($r = mysqli_fetch_array($q)) {
                $qRides = mysqli_query($con, "select *,r.id as rid,u.id as uid from rides r,users u where r.userId = u.id and r.journeyId = '" . $journeyId . "'");
                $rides = array();
                while ($rRides = mysqli_fetch_array($qRides)) {
                    $userDetails = array(  "id"=>$rRides["uid"],
                        "username"=>$rRides["username"],
                        "fullname"=>$rRides["fullname"],
                        "gender"=>$rRides["gender"],
                        "birthdate"=>$rRides["birthdate"],
                        "address"=>$rRides["address"],
                        "userType"=>$rRides["userType"],
                        "image"=>$rRides["image"],
                        "phone"=>$rRides["phone"]);
                    array_push($rides, array("id" => $rRides["rid"],
                        "user" => $userDetails,
                        "journeyId" => $rRides["journeyId"],
                        "meetingLocationX" => $rRides["meetingLocationX"],
                        "meetingLocationY" => $rRides["meetingLocationY"],
                        "orderStatus" => $rRides["orderStatus"]));
                }
                //need to get the user details and put it in the array
                $userDetails = array(  "id"=>$r["uid"],
                    "username"=>$r["username"],
                    "fullname"=>$r["fullname"],
                    "gender"=>$r["gender"],
                    "birthdate"=>$r["birthdate"],
                    "address"=>$r["address"],
                    "userType"=>$r["userType"],
                    "image"=>$r["image"],
                    "phone"=>$r["phone"]);
                $journey = array("id" => $r["jid"],
                    "startLocation" => $r["startLocation"],
                    "endLocation" => $r["endLocation"],
                    "goingDate" => $r["goingDate"],
                    "seats" => $r["seats"],
                    "genderPrefer" => $r["genderPrefer"],
                    "carDescription" => $r["carDescription"],
                    "status"=> $r["status"],
                    "user"=>$userDetails,
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
            $qRides = mysqli_query($con,"select *,r.id as rid,u.id as uid from rides r,users u where r.userId = u.id and r.id = '".$rideId."'");
            $ride = null;
            if ($rRides = mysqli_fetch_array($qRides)) {
                $qJourney = mysqli_query($con, "select *,j.id as jid,u.id as uid from journeys j,users u where j.userId=u.id and j.id = '" . $rRides["journeyId"] . "'");
                $rJourney = mysqli_fetch_array($qJourney);
                $journey = null;
                if ($rJourney) {
                    $userDetails = array(  "id"=>$rJourney["uid"],
                        "username"=>$rJourney["username"],
                        "fullname"=>$rJourney["fullname"],
                        "gender"=>$rJourney["gender"],
                        "birthdate"=>$rJourney["birthdate"],
                        "address"=>$rJourney["address"],
                        "userType"=>$rJourney["userType"],
                        "image"=>$rJourney["image"],
                        "phone"=>$rJourney["phone"]);
                    $journey = array("id" => $rJourney["jid"],
                        "startLocation" => $rJourney["startLocation"],
                        "endLocation" => $rJourney["endLocation"],
                        "goingDate" => $rJourney["goingDate"],
                        "seats" => $rJourney["seats"],
                        "genderPrefer" => $rJourney["genderPrefer"],
                        "carDescription" => $rJourney["carDescription"],
                        "status"=>$rJourney["status"],
                        "user"=>$userDetails);
                }
                $userDetails = array(  "id"=>$rRides["uid"],
                    "username"=>$rRides["username"],
                    "fullname"=>$rRides["fullname"],
                    "gender"=>$rRides["gender"],
                    "birthdate"=>$rRides["birthdate"],
                    "address"=>$rRides["address"],
                    "userType"=>$rRides["userType"],
                    "image"=>$rRides["image"],
                    "phone"=>$rRides["phone"]);
                $ride = array("id" => $rRides["rid"],
                    "meetingLocationX" => $rRides["meetingLocationX"],
                    "meetingLocationY" => $rRides["meetingLocationY"],
                    "orderStatus" => $rRides["orderStatus"],
                    "user" => $userDetails,
                    "journey" => $journey);
            }
            return json_encode($ride);
        }else
            return json_encode(array("auth"=>"false"));
    }
    public static function setRideOnJourney($username,$password,$journeyId,$meetingLocationX,$meetingLocationY){

        $user = self::checkAuth($username,$password);
        global $con;
        $journeyId = mysqli_real_escape_string($con,$journeyId);
        $meetingLocationX = mysqli_real_escape_string($con,$meetingLocationX);
        $meetingLocationY = mysqli_real_escape_string($con,$meetingLocationY);
        if ($user)
        {
            $q = mysqli_query($con,"select COUNT(r.id) as jnum,(select seats from journeys where journeys.id = '".$journeyId."') as seats from journeys j JOIN rides r on j.id = r.journeyId where j.id = '".$journeyId."' and r.orderStatus = 1");
            $status = "fail";
            $rideid = -1;
            if ($r = mysqli_fetch_array($q))
            {
                if ($r["jnum"]<$r["seats"])
                {
                    $qInsert = mysqli_query($con,"insert into rides set userId='".$user->id."',
                                                                              journeyId='".$journeyId."',
                                                                              meetingLocationX='".$meetingLocationX."',
                                                                              meetingLocationY='".$meetingLocationY."',
                                                                              orderStatus = 0");
                    if ($qInsert)
                    {
                        $status = "success";
                        $rideid = mysqli_insert_id($con);
                    }
                }else
                    $status = "noAvailableSeats";
            }


            return json_encode(array("status"=>$status,
                                     "rideId"=>$rideid));
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
    public static function filterJourneys($username,$password,$startPointX,$startPointY,$endPointX,$endPointY,$goingDate,$sortBy){
        $user = self::checkAuth($username,$password);
        global $con;

        $startPointX = mysqli_real_escape_string($con,$startPointX);
        $startPointY = mysqli_real_escape_string($con,$startPointY);
        $endPointX = mysqli_real_escape_string($con,$endPointX);
        $endPointY = mysqli_real_escape_string($con,$endPointY);
        $goingDate = mysqli_real_escape_string($con,$goingDate);

        $radius = 0.0208044;//0.0138044;
        $radiusX2 = $radius * $radius;
        if ($user)
        {
            //$q = mysqli_query($con,"select *,u.id uid,j.id jid from journeys j , users u where j.userId=u.id");
            /*$q = mysqli_query($con,"SELECT *,u.id uid,j.id jid FROM journeys j , users u WHERE j.userId=u.id AND goingDate >= '".$goingDate."' AND
POWER( (POWER( (startLocationX-".$startPointX.") ,2) + POWER( (startLocationY-".$startPointY.") ,2)) ,2) < ".$radiusX2."
AND
POWER( (POWER( (endLocationX-".$endPointX.") ,2) + POWER( (endLocationY-".$endPointY.") ,2)) ,2) < ".$radiusX2);*/
            $q = mysqli_query($con,"SELECT *,u.id uid,j.id jid FROM journeys j , users u WHERE j.userId=u.id AND goingDate >= '".$goingDate."' AND
(POWER( (startLocationX-".$startPointX.") ,2) + POWER( (startLocationY-".$startPointY.") ,2))  < ".$radiusX2."
AND
(POWER( (endLocationX-".$endPointX.") ,2) + POWER( (endLocationY-".$endPointY.") ,2)) < ".$radiusX2);
            $journeys = array();
            while($r=mysqli_fetch_array($q))
            {
                $userDetails = array(  "id"=>$r["uid"],
                    "username"=>$r["username"],
                    "fullname"=>$r["fullname"],
                    "gender"=>$r["gender"],
                    "birthdate"=>$r["birthdate"],
                    "address"=>$r["address"],
                    "userType"=>$r["userType"],
                    "image"=>$r["image"],
                    "phone"=>$r["phone"]);
                //startLocation	endLocation	goingDate	seats	genderPrefer	carDescription
                array_push($journeys,array( "id"=>$r["jid"],
                    "startLocationX"=>$r["startLocationX"],
                    "startLocationY"=>$r["startLocationY"],
                    "endLocationX"=>$r["endLocationX"],
                    "endLocationY"=>$r["endLocationY"],
                    "goingDate"=>$r["goingDate"],
                    "seats"=>$r["seats"],
                    "genderPrefer"=>$r["genderPrefer"],
                    "carDescription"=>$r["carDescription"],
                    "status"=>$r["status"],
                    "user"=>$userDetails));
            }
            return json_encode(array("journeys"=>$journeys));
        }else
            return json_encode(array("auth"=>"false"));
    }
    public static function changeJourneyStatusAndGetRiders($username,$password,$journyid,$status){

        $user = self::checkAuth($username,$password);
        global $con;
        $journyid = mysqli_real_escape_string($con,$journyid);
        $status = mysqli_real_escape_string($con,$status);
        if ($user)
        {
            $q = mysqli_query($con,"select * from journeys where userId=".intval($user->id)." and id=".$journyid);

            $status = "fail";
            if ($r = mysqli_fetch_array($q))
            {
                $proc = mysqli_query($con,"update journeys set status=".intval($status)." where id=".$journyid);
                if ($proc){
                    $status =  "success";
                }
            }


            return json_encode(array("status"=>$status));
        }else
            return json_encode(array("auth"=>"false"));
    }
    /*public static function getCustomJourney($username,$password,$journyid){

    }*/
    //does not return the journey details
    public static function getRidersOfJourney($username,$password,$journeyid){
        $user = self::checkAuth($username,$password);
        global $con;
        $journeyid = mysqli_real_escape_string($con,$journeyid);
        if ($user)
        {
            $q = mysqli_query($con,"select * from journeys where userId=".intval($user->id)." and id=".$journeyid);
            $rides = array();
            if ($r = mysqli_fetch_array($q))
            {

                //does not return the journey details
                $ride = mysqli_query($con,"select *,r.id as rid from rides r, user u where r.userId=u.id");
                while ($row = mysqli_fetch_array($ride))
                {
                    $userDetails = array(  "id"=>$row["userId"],
                        "username"=>$row["username"],
                        "fullname"=>$row["fullname"],
                        "gender"=>$row["gender"],
                        "birthdate"=>$row["birthdate"],
                        "address"=>$row["address"],
                        "userType"=>$row["userType"],
                        "image"=>$row["image"],
                        "phone"=>$row["phone"]);



                    array_push($rides,array("id"=>$row["rid"],
                                            "user"=>$userDetails,
                                            "journeyid"=>$row["journeyId"],
                                            "meetingLocationX"=>$row["meetingLocationX"],
                                            "meetingLocationY"=>$row["meetingLocationY"],
                                            "status"=>$row["orderStatus"]));
                }
            }


            return json_encode(array("rides"=>$rides));
        }else
            return json_encode(array("auth"=>"false"));
    }
    public static function getStatusOfRide($username,$password,$rideid){
        $user = self::checkAuth($username,$password);
        global $con;
        $rideid = mysqli_real_escape_string($con,$rideid);
        if ($user)
        {
            $q = mysqli_query($con,"select orderStatus from rides where userId=".intval($user->id)." and id=".$rideid);
            $status = 0;
            if ($r = mysqli_fetch_array($q))
            {
                $status = $r["orderStatus"];
            }


            return json_encode(array("rideStatus"=>$status));
        }else
            return json_encode(array("auth"=>"false"));
    }
    public static function getNumberOfJourneys(){
        global $con;

        $q = mysqli_query($con,"select count(*) as num journeys where goingDate > '".date("Y-m-d H:i:s")."'");

        if($r=mysqli_fetch_array($q)) {
            return json_encode(array("number" => $r["num"]));
        }else
        {
            return json_encode(array("error"=>"can't count journyes"));
        }
    }
    public static function getEventAtDate($date){
        global $con;
        $time = strtotime($date);
        $theDate = date("Y-m-d",$time);
        $afterOneDay = date("Y-m-d",strtotime('+1 day', $theDate));
        $q = mysqli_query($con,"SELECT * FROM `events` where startDateTime = '".$theDate." 00:00:00' or ( startDateTime > '".$theDate."' and startDateTime < '".$afterOneDay."' )");
        $event = array();
        while ($r=mysqli_fetch_array($q))
        {
            array_push($event,array("id"=>$r["id"],
                                    "title"=>$r["title"],
                                    "description"=>$r["description"],
                                    "startDateTime"=>$r["startDateTime"],
                                    "imageUrl"=>$r["imageUrl"]));
        }
        return json_encode(array("events"=>$event));
    }
    public static function getEvents(){
        global $con;
        $date = date("Y-m-d");
        $q = mysqli_query($con,"SELECT * FROM `events` where startDateTime > '".$date."' or startDateTime='".$date." 00:00:00'");
        $event = array();
        while ($r=mysqli_fetch_array($q))
        {
            array_push($event,array("id"=>$r["id"],
                "title"=>$r["title"],
                "description"=>$r["description"],
                "startDateTime"=>$r["startDateTime"],
                "imageUrl"=>$r["imageUrl"]));
        }
        return json_encode(array("events"=>$event));
    }
    public static function getAnnouns(){
        global $con;
        $date = date("Y-m-d");
        $q = mysqli_query($con,"SELECT * FROM `announcement` where startDate > '".$date."' or startDate='".$date."'");
        $announs = array();
        while ($r=mysqli_fetch_array($q))
        {
            array_push($announs,array("id"=>$r["id"],
                "name"=>$r["name"],
                "description"=>$r["description"],
                "startDate"=>$r["startDate"],
                "endDate"=>$r["endDate"],
                "imageUrl"=>$r["imageUrl"]));
        }
        return json_encode(array("announcement"=>$announs));
    }
    public static function getJobs(){
        global $con;
        $date = date("Y-m-d");
        $q = mysqli_query($con,"SELECT * FROM `jobs` where endDate < '".$date."' or endtDate='".$date."'");
        $jobs = array();
        while ($r=mysqli_fetch_array($q))
        {
            array_push($jobs,array("id"=>$r["id"],
                "jobTitle"=>$r["jobTitle"],
                "description"=>$r["description"],
                "endDate"=>$r["endDate"]));
        }
        return json_encode(array("jobs"=>$jobs));
    }
    public static function getTransportation(){
        global $con;

        $q = mysqli_query($con,"SELECT * FROM `transportation`");
        $fromRawabi = array(); // type of 0
        $fromRamallah = array(); // type of 1
        $catch = null;
        while ($r=mysqli_fetch_array($q))
        {
            if ($r["type"]==0)
            {
                $catch = &$fromRawabi;
            }else
                $catch = &$fromRamallah;

            array_push($catch,array("id"=>$r["id"],
                "type"=>$r["type"],
                "time"=>$r["timegoind"]));
        }
        return json_encode(array("fromRawabi"=>$fromRawabi,"fromRamallah"=>$fromRamallah));
    }
    public static function getMedia(){

    }
}