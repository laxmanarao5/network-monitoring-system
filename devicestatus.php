 <?php 
include("config.php");
 if(isset($_POST['call_network_api']))
 {
  ?>

    <!-- <form action="./checkDeviceStatus.php" method="POST">
      <button type="submit" name="network_device_fetching" hidden><button>
    </form> -->
<?php
$response = file_get_contents("http://localhost:4000/refresh");

$macs_from_api = explode (",", $response);


$query="UPDATE `devices` SET `status`='down' WHERE 1";
$result=mysqli_query($con,$query);

//get macs of all ips
$query="SELECT * FROM devices";
$result=mysqli_query($con,$query);
// print_r($macs_from_api);


    while($temp=$result->fetch_assoc())
    {
      foreach($macs_from_api as $mac)
        {
          if($temp["mac"]==$mac)
          {
            $query="UPDATE `devices` SET `status`='up' WHERE `mac`='$mac'";
            $result1=mysqli_query($con,$query);
          }
          //$query="SELECT mac FROM devices";
          //$result=mysqli_query($con,$query);
          //echo $mac." ";
        }
    }
    echo '<script type="text/JavaScript"> 
    window.location.href = ""
     </script>'
;
    
 }
//  else{

$query="SELECT * FROM devices";
$result=mysqli_query($con,$query);
  ?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <!-- <title>Responsive Sidebar Menu</title> -->
    <link rel="stylesheet" href="dash.css">
    <link rel="stylesheet" href="table.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  </head>
  <body >

    
    <div id="content" style="position: relative; top: 00px; right: 00px;">
  <table style="position: relative; left: 100px;" class="responstable">

  <tr>
        <form action="./devicestatus.php" method="POST">
          <button type="submit" name="call_network_api" style="position: relative; left: 15px; cursor: pointer;" >refresh</button>
        </form>

    <th style=" width: 90px;">location</th>
    <th style=" width: 90px;">MAC</th>
    <th style=" width: 90px;">Status</th>

    
  </tr>
      <?php
        while($temp=$result->fetch_assoc())
        { 
        ?>  
  <tr>
    <td style=" width: 00px;"><?php echo $temp['location'] ?></td>
    <td style=" width: 00px;"><?php echo $temp['mac'] ?></td>
    <td style=" width: 0px;"><?php echo $temp['status'] ?></td>

  </tr>
        <?php
        }
      ?>
      </table>
    </div>
    </div>
  </div>
        
</body>
</html>
<?php
?>