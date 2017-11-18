<?php
/**
 * Created by PhpStorm.
 * User: ccx
 * Date: 11/15/17
 * Time: 10:46 AM
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '5211180');
define('DB_NAME', 'hw3');
$dbcon = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($dbcon->connect_error) {
    die("Connect fail " . $conn->connect_error);
}
echo "Search successfully". "<br>";

$cname=$_POST['cname'];
$keyword = $_POST['kword'];
$pnum=$_POST['pnum'];
$year=$_POST['year'];
$month=$_POST['month'];
$day=$_POST['day'];
$hour=$_POST['hour'];
$btime=$year."-".$month."-" .$day." ".$hour.":00:00";
//echo $keyword."<br>";
if($pnum==null){
    $pnum=0;
}
//echo $pnum."<br>";

if($month>12||$month<0||$day<0||$day>30||$hour<0||$hour>24){
    echo "The date is wrong!"."<br>";
    return;
}

if($year==null||$month==null||$day==null||$hour==null){
    $sql="SELECT DISTINCT rid, rname, raddress,description,capacity FROM restaurant NATURAL JOIN booking WHERE (rname like '%{$keyword}%' or description like '%{$keyword}%')and rid not in(select rid from booking NATURAL JOIN restaurant as a where $pnum>capacity)";
    //echo $sql."<br>";
}
else{

    $sql = "SELECT DISTINCT rid, rname, raddress,description,capacity FROM restaurant NATURAL JOIN booking WHERE (rname like '%{$keyword}%' or description like '%{$keyword}%')
and rid not in(select rid from booking NATURAL JOIN restaurant as a where $pnum>capacity-(select coalesce(SUM(quantity),0) from booking where a.rid=rid and YEAR(btime)=$year and MONTH(btime)=$month and DAY(btime)=$day and HOUR(btime)=$hour))";
    //echo $sql."<br>";
}


$result = mysqli_query($dbcon,$sql);

echo "<table border=1>";     //使用表格格式化数据
echo "<tr><td>RID</td><td>Name</td><td>Address</td><td>Description</td><td>Capacity</td><td>BookButton</td></tr>";
if ($result->num_rows > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo  "<td>". $row["rid"]."</td>". "<td>". $row["rname"]."</td>". "<td>". $row["raddress"]. "</td>"."<td>" . $row["description"]. "</td>"."<td>" . $row["capacity"]."</td>";
        //echo "<input type='button' value='Status Update'>";

        echo "<td>"."<form method='post' action=''>".
        "<input type='submit' name='action' value='Book'/>".
        "<input type='hidden' name='id' value=\"{$row["rid"]}\" />".
            "<input type='hidden' name='pnum' value='{$pnum}' />".
            "<input type='hidden' name='cname' value='{$cname}' />".
            "<input type='hidden' name='btime' value='{$btime}' />".
        "</form>".
        "</td>";

        //echo "<td>"."<button id='button' value=\"{$row["rid"]}\" onclick='btnfunction(id)'>Book</input>"."</td>";
        echo "</tr>";
    }
}
else {
    echo "There is no available restaurant~ Thank you";
}

if ($_POST['action'] && $_POST['id']) {
    if ($_POST['action'] == 'Book') {
        $bkrid=$_POST['id'];
        //echo $bkrid."<br>";
        $bknum=$_POST['pnum'];
        //echo $bknum;
        $bkname=$_POST['cname'];
        //echo $cname;
        $bktime=$_POST['btime'];
        //echo $bktime."<br>";
        $sqlcid="select cid from customer where cname='$bkname'";
        //echo $sqlcid;
        $resultid=mysqli_query($dbcon,$sqlcid);
        $rowcid = mysqli_fetch_array($resultid);
        $bkcid = $rowcid[0];
        if($bkcid==null){
            echo "The user does not exist"."<br>";
            return;
        }
        //echo $bkcid;

        $insertsql="INSERT INTO booking (cid, rid, btime, quantity) VALUES ('$bkcid', '$bkrid','$bktime','$bknum')";
        if ($dbcon->query($insertsql) === TRUE) {
            echo "You have booked successfully, new record created successfully"."<br>";
        } else {
            echo "Error: UNKNOWN ACTION" . $insertsql . "<br>" . $dbcon->error;
        }
    }
}

?>

<!--<script>-->
<!--//    function btnfunction(id) {-->
<!--//        $.ajax({-->
<!--//            type: "POST",-->
<!--//            url: "book.php",-->
<!--//            data: {-->
<!--//                rid, $(this).val(),-->
<!--//            },-->
<!--//            success: function(result) {-->
<!--//                alert('ok');-->
<!--//            },-->
<!--//            error: function(result) {-->
<!--//                alert('error');-->
<!--//            }-->
<!--//        });-->
<!--//    }-->
<!---->
<!--$(document).ready(function() {-->
<!--    $("#button").click(function(e) {-->
<!--        e.preventDefault();-->
<!--        $.ajax({-->
<!--            type: "POST",-->
<!--            url: "book.php",-->
<!--            data: {-->
<!--                id: $("#button").val(),-->
<!--            },-->
<!--            success: function(result) {-->
<!--                alert('ok');-->
<!--            },-->
<!--            error: function(result) {-->
<!--                alert('error');-->
<!--            }-->
<!--        });-->
<!--    });-->
<!--</script>-->