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
                array_push($journeys,array("startLocation"=>$r["startLocation"],
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
}