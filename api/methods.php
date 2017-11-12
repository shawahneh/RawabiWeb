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
                                                            fname_s='".$fname."',
                                                            lname_s='".$lname."',
                                                            gender_b='".$gender."',
                                                            birthdate_d='".$birthdate."',
                                                            address_s='".$address."',
                                                            userType_i='".$userType."',
                                                            image_s='".$image."',
                                                            phone_i='".$phone."'");
        if ($q)
        {
            return json_encode(array("registration"=>"success"));
        }else
            return json_encode(array("registration"=>"failed"));
    }
}