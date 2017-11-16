<?php session_start();
$con=mysqli_connect('localhost','root','');



$db_con=mysqli_select_db($con,'tcamp');
if ( !$con ) {
}
if ( !$db_con ) {
}
$sql0="set names 'utf8'";
mysqli_query($con, $sql0);
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

?>
<?php
function selectdb($con,$tablename,$ifwhere,$where,$is,$and){
    $end=[];
    $sql = "SELECT * from $tablename $ifwhere $where $is ";
    $result = mysqli_query($con, $sql);
    while ($row = $result->fetch_assoc()) {
        $end[]=$row;
    }

    return json_encode($end);
}
function  selectdb_bool($con,$tablename,$ifwhere,$where,$is,$and){
    $sql = "SELECT * from $tablename $ifwhere $where '$is' ";
    if ($result=mysqli_query($con,$sql))
    {
        $rowcount=mysqli_num_rows($result);
//        mysqli_free_result($result);
        if($rowcount>0){
            return 1;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
function updatedb($con,$tablename,$set,$ifwhere,$where,$is){
    $sql = "UPDATE $tablename SET $set $ifwhere $where $is";

    if ($con->query($sql) === TRUE) {
        return 1;

    }else{
        return 0;
    }
}
//$t=json_decode(selectdb($con,'journeys','','',''));
//$t2=json_decode(selectdb($con,'journeys','where','id=','2'));
//$t3=updatedb($con,'journeys','startLocation="11,11"','where','id=','2');
//echo $t[1]->id;
//echo $t2[0]->id;
?>