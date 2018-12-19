<?php
session_start();

if (!isset($_SESSION['name']))
{
    header("Location:index.php");
    //sends referring page as get to login page for correct redirection afterwards
}
?>
<!DOCTYPE html>
<html>
<head>

    <title>Fixtures</title>
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script>
  $(function() {
    $("#navigation").load("navbar.php");
    });
</script>
</head>
<body>
<div id="navigation"></div>
<div class="container-fluid" style="margin-top:10px">
    <form action="Addfixtures.php" method="POST">
    <div class="form-group" style="width:30%">
        Home Team:<select class="form-control" name="HomeID">
        <?php
        include_once ("connect.php");
        $stmt = $conn->prepare("SELECT teams.Teamid, schools.Schoolname, teams.Division, teams.SchoolID FROM teams 
        INNER JOIN schools ON teams.SchoolID = schools.SchoolID" );
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo("<option value=".$row["Teamid"].'>'.$row["Schoolname"].$row["Division"]."</option>");
        }
        //$conn=null;
        ?>

        </select>
        <br>
        Away Team:<select class="form-control" name="AwayID">
        <?php
        include_once ("connect.php");
        $stmt = $conn->prepare("SELECT Teams.Teamid, Schools.Schoolname, Teams.Division, Teams.SchoolID FROM Teams 
        INNER JOIN Schools ON Teams.SchoolID = Schools.SchoolID" );
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo("<option value=".$row["Teamid"].'>'.$row["Schoolname"].$row["Division"]."</option>");
        }
        //$conn=null;
        ?>

    </select>
    <br>
    Date <input class="form-control" type='date' id='datepicker' name='fixtdate' size='9' value=''>
    <br>


    <input class="btn btn-primary mb-2" type="submit" value="Add Fixture">
</form>
<br>
<h3>Current Fixtures</h3>
<p>Played in Red</p>
<?php
//include_once ("connect.php");
$today = date("d-m-Y"); 
$stmt = $conn->prepare("SELECT FixtureID,HomeID, AwayID, fixtdate, 
awsc.Schoolname as AWS, hsch.Schoolname as HS, 
home.Division, away.Division FROM Fixtures 
INNER JOIN Teams as home ON (Fixtures.HomeID = home.teamID) 
INNER JOIN  Teams as away ON (Fixtures.AwayID=away.TeamID) 
INNER JOIN Schools as awsc ON away.SchoolID=awsc.SchoolID 
INNER JOIN Schools as hsch ON home.SchoolID=hsch.SchoolID 
ORDER BY fixtdate DESC" );
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
   if(date("d/m/y",(strtotime($row["fixtdate"])))>$today){
    echo("<p style='color:red'>".$row["HS"]." ".$row["Division"]." v ".$row["AWS"].$row["Division"]." - ".date("d/m/y",(strtotime($row["fixtdate"])))."</p>");
   }else{
    //make into table at some point
    echo("<p>".$row["HS"]." ".$row["Division"]." v ".$row["AWS"].$row["Division"]." - ".date("d/m/y",(strtotime($row["fixtdate"])))."</p>");
   }
}
$conn=null;
?>
</div>
</body>
</html>
