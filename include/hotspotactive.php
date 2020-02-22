<?php
/**
 * Copyright (c) 2019. Arash Hatami
 */
session_start();
// hide all error
error_reporting(0);
if(!isset($_SESSION["mikhmon"])){
  header("Location:../admin.php?id=login");
}else{

// load session MikroTik
$session = $_GET['session'];

// load config
include('./config.php');
$iphost=explode('!',$data[$session][1])[1]; 
$userhost=explode('@|@',$data[$session][2])[1];
$passwdhost=explode('#|#',$data[$session][3])[1]; 


// routeros api
include_once('../lib/routeros_api.class.php');
include_once('../lib/formatbytesbites.php');
$API = new RouterosAPI();
$API->debug = false;
$API->connect( $iphost, $userhost, decrypt($passwdhost));

  $gethotspotactive = $API->comm("/ip/hotspot/active/print");
	$TotalReg = count($gethotspotactive);
	
	$counthotspotactive = $API->comm("/ip/hotspot/active/print", array(
	  "count-only" => "",));

}
?>
<div class="row">
<div id="reloadHotspotActive">
<div class="col-12">
	<div class="card">
		<div class="card-header">
    		<h3><i class="fa fa-wifi"></i> Hotspot Active <?php
  if($counthotspotactive < 2 ){echo "$counthotspotactive item";
  }elseif($counthotspotactive > 1){
  echo "$counthotspotactive items";};echo"</th>";
?>			</h3>
        </div>
         <div class="card-body overflow">	   
<table id="tFilter" class="table table-bordered table-hover text-nowrap">
  <thead>
  <tr>
    <th></th>
    <th>Server</th>
    <th>User</th>
    <th>Address</th>
    <th>Mac Address</th>
    <th>Uptime</th>
    <th>Bytes Out</th>
    <th>Login By</th>
    <th>Comment</th>
  </tr>
  </thead>
  <tbody>
<?php
	for ($i=0; $i<$TotalReg; $i++){
	$hotspotactive = $gethotspotactive[$i];
	$id = $hotspotactive['.id'];
	$server = $hotspotactive['server'];
	$user = $hotspotactive['user'];
	$address = $hotspotactive['address'];
	$mac = $hotspotactive['mac-address'];
	$uptime = formatDTM($hotspotactive['uptime']);
	$byteso = formatBytes($hotspotactive['bytes-out'], 2);
	$loginby = $hotspotactive['login-by'];
	$comment = $hotspotactive['comment'];
	
	echo "<tr>";
	echo "<td style='text-align:center;'><a  title='Remove ". $user . "' href='./app.php?remove-user-active=". $id . "&session=".$session."'><i class='fa fa-minus-square text-danger'></i></a></td>";
	echo "<td>" . $server . "</td>";
	echo "<td><a title='Open User " .$user. "' style='color:#f3f4f5;' href=./app.php?hotspot-user=" .$user. "&session=".$session."><i class='fa fa-edit'></i> " .$user."</a></td>";
	echo "<td>" . $address . "</td>";
	echo "<td>" . $mac . "</td>";
	echo "<td style='text-align:right;'>" . $uptime . "</td>";
	echo "<td style='text-align:right;'>" . $byteso . "</td>";
	echo "<td>" . $loginby . "</td>";
	echo "<td>" . $comment . "</td>";
	echo "</tr>";
	}
?>
  </tbody>
</table>
</div>
</div>
</div>
</div>
</div>
