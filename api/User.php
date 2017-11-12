<?php
/**
 * Created by PhpStorm.
 * User: Mohammad Shawahneh
 * Date: 11/12/2017
 * Time: 10:21 PM
 */
if ($ind!="yes")
{
    header("location: ./");
    exit();
}
class User
{
    public $id,$username,$fname,$lname,$gender,$birthdate,$address,$userType,$image,$phone;
    function __construct($id,$username,$fname,$lname,$gender,$birthdate,$address,$userType,$image,$phone)
    {
        $this->id = $id;
        $this->username = $username;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->gender = $gender;
        $this->birthdate = $birthdate;
        $this->address = $address;
        $this->userType = $userType;
        $this->image = $image;
        $this->phone = $phone;
    }
    public static function getAuth($username,$password){
        global $con;
        $username = mysqli_real_escape_string($con,$username);
        $password = mysqli_real_escape_string($con,$password);
        $q = mysqli_query($con,"select * from users where password = '".$password."' and username = '".$username."'");
        $r = mysqli_fetch_array($q);
        if (isset($r))
        {
            $user = new User($r["id"],$username,$r["fname"],$r["lname"],$r["gender"],$r["birthdate"],$r["address"],$r["userType"],$r["image"],$r["phone"]);
            return $user;
        }else
            return null;
    }
}