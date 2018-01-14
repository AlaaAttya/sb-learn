<?php
require_once("../php/main.php");
if (!$user) {
	header("Location: ../login.php");
}
$theme = $user['theme'];

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$query_group = "SELECT * FROM `group` ORDER BY id ASC";
$group = mysql_query($query_group, $edu) or die(mysql_error());
$row_group = mysql_fetch_assoc($group);
$totalRows_group = mysql_num_rows($group);

$nowgroupid = $_GET['group'];
$checkgroup = mysql_query("SELECT * FROM `group` WHERE `id` = '$nowgroupid'");
if (mysql_num_rows($checkgroup) == 1) {
	$data_checkgroup = mysql_fetch_array($checkgroup);
	$nowgroup = $data_checkgroup['name'];
	$checklesson = mysql_query("SELECT * FROM `lesson` WHERE `group` = '$nowgroupid'");
	$numlesson_thisgroup = mysql_num_rows($checklesson);
	$totalcard = 0;
	while ($data_checklesson = mysql_fetch_array($checklesson)) {
		$thislesson = mysql_query("SELECT * FROM `card` WHERE `lesson` = '{$data_checklesson['id']}'");
		$num_thislesson = mysql_num_rows($thislesson);
		$totalcard += $num_thislesson;
	}
	$lessonlist = mysql_query("SELECT * FROM `lesson` WHERE `group` = '$nowgroupid' ORDER BY `id` ASC");
}
else {
	header("Location: recallcard.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>มุ่งสู่ห้อง K/Q ม.5 - Recall Card - Group</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
<link href="../css/main_<?php echo $theme; ?>.css" rel="stylesheet" type="text/css" />
<link href="../css/loggedin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script>
function preload() {
	var gidnow = <?php echo $data_checkgroup['id']; ?>;
	$('#gid_tab_'+gidnow).show();
}
</script>
</head>

<body onload="preload()">
<div id="loading">Loading...</div>
<table border="0" cellspacing="0" cellpadding="0" id="all">
  <tr>
    <td width="50" valign="middle">
	  <div id="menu" class="icon"><img src="../images/menu.png" height="50" width="50" /></div>
	</td>
	<td>
	  <div id="topbar">
	    <div class="icon home"><img src="../images/home.png" height="50" width="50" /></div><div id="wide"><img src="../images/rctitle.png" height="50" width="150" /></div><div class="icon profile"><img src="../images/profile.png" height="50" width="50" /></div><div class="icon logout"><img src="../images/logout.png" height="50" width="50" /></div>
	  </div>
	</td>
  </tr>
  <tr>
    <td>
	  <div id="sidebar">
	    <div class="icon back"><img src="../images/back.png" height="50" width="50" /></div>
	    <div class="icon add"><img src="../images/add.png" height="50" width="50" /></div>
	    <div class="icon manage"><img src="../images/gear.png" height="50" width="50" /></div>
		<div class="high"></div>
	    <div class="icon info"><img src="../images/info.png" height="50" width="50" /></div>
	  </div>
	</td>
    <td>
	  <div id="main">
	    <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td width="50%">
			  <div id="mainleft">
			    <div class="groupmenu highlight" group="<?php echo $nowgroupid; ?>"><?php echo $nowgroup; ?></div>
				<?php while ($data_lessonlist = mysql_fetch_array($lessonlist)) { ?>
				<div class="lessonlist" lesson="<?php echo $data_lessonlist['id']; ?>" page="lesson"><?php echo $data_lessonlist['name']; ?></div>
				<?php } ?>
			  </div>
			</td>
		    <td width="50%">
			  <div id="mainright">
			    <div class="title">กลุ่ม: <?php echo $nowgroup; ?></div>
				<div class="textbox">
				  <p>จำนวน <?php echo $numlesson_thisgroup; ?> แบบฝึกหัด</p>
                  <p>จำนวน: <?php echo $totalcard; ?> ข้อ</p>
				</div>
				<div class="groupbutton"><img class="button" src="../images/theme/<?php echo $theme; ?>/rctable.png" width="150" height="150" onclick="window.open('wordlist.php?type=group&amp;group=<?php echo $_GET['group']; ?>','recallcardlesson','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=800px,height=500px')" /><img class="button" src="../images/theme/<?php echo $theme; ?>/rccard.png" width="150" height="150" onclick="window.open('cardlist.php?type=group&amp;group=<?php echo $_GET['group']; ?>','recallcardlesson','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=800px,height=500px')" /><?php if ($totalcard > 1) { ?><img class="button" src="../images/theme/<?php echo $theme; ?>/rcrand.png" width="150" height="150" onclick="window.open('randomcard.php?type=group&amp;group=<?php echo $_GET['group']; ?>','recallcardlesson','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=800px,height=500px')" /><?php } ?></div>
			  </div>
			</td>
		  </tr>
		</table>
	  </div>
	</td>
  </tr>
</table>
<div id="hidsidebar">
  <div class="inhid back">Back</div>
  <div class="inhid add">New lesson</div>
  <div class="inhid manage">Manage</div>
  <div class="high"></div>
  <div class="inhid info">Info</div>
</div>
<script type="text/javascript" src="../js/main.js"></script>
<script type="text/javascript" src="../js/rc_lesson.js"></script>
</body>
</html>
<?php
mysql_free_result($group);
?>
