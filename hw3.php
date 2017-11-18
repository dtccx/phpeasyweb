
<form method ="post" action="showresu.php">
	<label for="cname"> Costomer Name: </label>
	<input type="text" id="cname" name="cname" /><br/>
    <label for="kword"> Kew Word:      </label>
    <input type="text" id="kword" name="kword" /><br/>
    <label for="pnum"> Number of People:</label>
    <input type="text" id="pnum" name="pnum" /><br/>
    <label for="date"> Date and Time:   Year:</label>
    <input type="text" id="year" name="year" />Month:<input type="text" id="month" name="month" />Day:<input type="text" id="day" name="day" />Hour:<input type="text" id="hour" name="hour" /><br/>
    <input type="submit" name="act" value="Search">
</form>

<?php
$keyword = $_POST['kword'];
$pnum=$_POST['pnum'];
$year=$_POST['year'];
$month=$_POST['month'];
$day=$_POST['day'];
$hour=$_POST['hour'];


?>
