<?php
########################################################################
# Upload-Point 1.62 Beta - Simple Upload System
# Copyright (c)2005-2009 Todd Strattman
# strattman@gmail.com
# http://covertheweb.com/upload-point/
# License: LGPL
########################################################################
// Config.php is the main configuration file.
include('config_upload.php');

// Password file.
if (is_file("$datadir/upload_pass.php")) {
include ("$datadir/upload_pass.php");
}
// Language file.
include("lang/$language");
// Name of page for links, title, and logout.
//$logout = "index.php";
//$page_name = "upload";

// Password protection.
// Random string generator.
function randomstring($length){
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	$string  = $chars{ rand(0,62) };
	for($i=1;$i<$length;$i++){
		$string .= $chars{ rand(0,62) };
	}
	return $string;
}
if ($password_protect == "on") {
	session_start();
	if(!empty($_POST['pass_hash_upload'])) {
		// Crypt, hash, and store password in session.
		$_SESSION['pass_hash_upload'] = crypt(md5($_POST['pass_hash_upload']), md5($_POST['pass_hash_upload']));
		// Crypt random string with random string seed for agent response.
		$string_agent = crypt($_SESSION['random'], $_SESSION['random']);
		// Hash crypted random string for random string response.
		$string_string = md5($string_agent);
		// Hash and concatenate md5/crypted random string and password hash posts.
		$string_response = md5($string_string . $_POST['pass_hash2']);
		// Concatenate agent and language.
		$agent_lang = getenv('HTTP_USER_AGENT') . getenv('HTTP_ACCEPT_LANGUAGE');
		// Hash crypted agent/language concatenate with random string seed for check against post.
		$agent_response = md5(crypt(md5($agent_lang), $string_agent));
	// Check crypted pass against stored pass. Check random string and pass hashed concatenate against post. Check hashed and crypted agent/language concatenate against post.
	} if (($_SESSION['pass_hash_upload'] != $upload_password) || ($_POST['pass_string_hash'] != $string_response) || ($_POST['agenthash'] != $agent_response)) {
		// Otherwise, give login.
		if ($head == "on") {
			include("header.php");
		}
		// Set random string session.
		$_SESSION['random'] = randomstring(40);
		// Crypt random string with random string seed.
		$rand_string = crypt($_SESSION['random'], $_SESSION['random']);
		// Concatenate agent and language.
		$agent_lang = getenv('HTTP_USER_AGENT').getenv('HTTP_ACCEPT_LANGUAGE');
		// Crypt agent and language with random string seed for form submission.
		$agent = crypt(md5($agent_lang), $rand_string);
		// Form md5 and encrypt javascript.
		echo "$p
		<b>$l_global13</b>
		$p2
		<script type=\"text/javascript\" src=\"$datadir/crypt/sha256.js\"></script>
		<script type=\"text/javascript\" src=\"$datadir/crypt/md5.js\"></script>
		<script type=\"text/javascript\">
		function obfuscate() {
			document.form1.pass_hash_upload.value = hex_sha256(document.form1.pass_upload.value);
			document.form1.pass_hash2.value = hex_md5(document.form1.pass_upload.value);
			document.form1.string_hash.value = hex_md5(document.form1.string.value);
			document.form1.pass_string_hash.value =  hex_md5(document.form1.string_hash.value  + document.form1.pass_hash2.value);
			document.form1.agenthash.value = hex_md5(document.form1.agent.value);
			document.form1.pass_upload.value = \"\";
			document.form1.string.value = \"\";
			document.form1.agent.value = \"\";
			document.form1.jscript.value = \"on\";
			return true;
		}
		</script>
		<form action=\"index.php?s=dodavanje_priloga&z=$id_zahtjev\" method=\"post\" name=\"form1\" onsubmit=\"return obfuscate()\">
		$p
		<input name=\"jscript\" type=\"hidden\" value=\"off\" />
		<input name=\"pass_hash_upload\" type=\"hidden\" value=\"\" />
		<input name=\"pass_hash2\" type=\"hidden\" value=\"\" />
		<input name=\"string_hash\" type=\"hidden\" value=\"\" />
		<input name=\"pass_string_hash\" type=\"hidden\" value=\"\" />
		<input name=\"agenthash\" type=\"hidden\" value=\"\" />
		<input name=\"string\" type=\"hidden\" value=\"$rand_string\" />
		<input name=\"agent\" type=\"hidden\" value=\"$agent\" />
		<input type=\"password\" name=\"pass_upload\" />
		<input type=\"submit\" value=\"$l_global14\" />
		$p2
		</form>";
		if ($head == "on") {
			include("footer.php");
		}
		exit();
	}
} else {
}
// End password protection.

function upload1() {
// Config.php is the main configuration file.
include('config_upload.php');
// Language file.
include("lang/$language");
// Name of page for links, title, and logout.
$logout = "index.php?s=prikaz_zahtjeva&z=".$_GET[z];
$page_name = "upload";

// Include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

// Upload file form.
echo "<script type=\"text/javascript\">
function showIcon() {
window.setTimeout('showProgress()', 0);
}
function showProgress() {
document.getElementById('progressImg').style.display = 'inline';
}
</script>



<table class=\"upload\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
<tr>
<td colspan=\"6\">
<form action=\"index.php?s=dodavanje_priloga&z=".$_GET[z]."\" method=\"post\" enctype=\"multipart/form-data\">


<fieldset>
<legend>Izaberite dokumente za upload</legend>
<div id =\"upload_popup\">
<input type=\"hidden\" name=\"cmd\" value=\"upload2\" /> <input type=\"file\" class=\"input_upload\" name=\"ftp_file[]\" />
<br />
<input type=\"hidden\" name=\"cmd\" value=\"upload2\" /> <input type=\"file\" class=\"input_upload\" name=\"ftp_file[]\" />
<br />
<input type=\"hidden\" name=\"cmd\" value=\"upload2\" /> <input type=\"file\" class=\"input_upload\" name=\"ftp_file[]\" />
<br />
<input type=\"hidden\" name=\"cmd\" value=\"upload2\" /> <input type=\"file\" class=\"input_upload\" name=\"ftp_file[]\" />
<br />
<input type=\"hidden\" name=\"cmd\" value=\"upload2\" /> <input type=\"file\" class=\"input_upload\" name=\"ftp_file[]\" />
<br /><br />

</div>
<div class=\"progress\" id=\"progressImg\">&nbsp;
<img src=\"images/progress.gif\" alt=\"$l_upload21\" />
</div>

</fieldset>
</br>
<input type=\"submit\" name=\"submit\" class=\"button_upload\" onclick=\"showIcon();\" value=\"$l_upload1\" />

</td>
</tr>";

// Hide file listing from logged in users.
if ($hide == "on") {
	echo "</table>";
} else {
	echo "<tr>";
// <td> colspan for rename/delete on/off.
if (($rename_file == "off") && ($delete_file == "off")) {
	echo "<td colspan=\"4\">";
} elseif ($rename_file == "off") {
	echo "<td colspan=\"5\">";
} elseif ($delete_file == "off") {
	echo "<td colspan=\"5\">";
} else {
	echo "<td colspan=\"6\">";
}
echo "
<hr />
$p
<b>$l_upload2</b>
$p2
<hr />
</td>
</tr>
</table>
<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"upload-point\" class=\"tablesorter\">
<thead>
<tr>
<th class=\"uploadlistname\"><b>$l_upload3</b></th>
<th class=\"uploadlistsize\"><b>$l_upload4</b></th>
<th class=\"uploadlistmod\"><b>$l_upload5</b></th>";
// Rename file on/off.
if ($rename_file == "on") {
	echo "
<th class=\"uploadlistrename\"><b>$l_upload6</b></th>";
} else {
}
// Delete file on/off.
if ($delete_file == "on") {
	echo "
<th class=\"uploadlistdelete\"><b>$l_upload7</b></th>";
} else {
}
echo "<th class=\"uploadlistloc\"><b>$l_upload8</b></th>
</tr>
</thead>
<tfoot>
<tr>
<th class=\"uploadlistname\"><b>$l_upload3</b></th>
<th class=\"uploadlistsize\"><b>$l_upload4</b></th>
<th class=\"uploadlistmod\"><b>$l_upload5</b></th>";
// Rename file on/off.
if ($rename_file == "on") {
	echo "
<th class=\"uploadlistrename\"><b>$l_upload6</b></th>";
} else {
}
// Delete file on/off.
if ($delete_file == "on") {
	echo "
<th class=\"uploadlistdelete\"><b>$l_upload7</b></th>";
} else {
}
echo "<th class=\"uploadlistloc\"><b>$l_upload8</b></th>
</tr>
</tfoot>
<tbody>";
}
// Path from Upload-Point directory to upload directory
$upload_dir = ($_SERVER['DOCUMENT_ROOT'] . "/$fileupload_dir_name");
// Create upload directory if it doesn't exist.
if (!is_dir($upload_dir)) {
	if (!mkdir($upload_dir))
	die ("$l_upload11");
	if (!chmod($upload_dir,0755))
	die ("$l_upload12");
}
// File listing code.
if ($hide == "on") {
} else {
if (is_empty_dir($upload_dir) == true) {
	echo "</table>";
} elseif (is_empty_dir($upload_dir) == false) {
// List files in the upload directory.
$dir_handle = opendir($upload_dir);
if ($dir_handle) {
	while (false !==($file = readdir($dir_handle))) {
		$upload_files = "$upload_dir/$file";
		if ((is_file($upload_files)) && ($file!=".htaccess" && $file!=$up_ignore1 && $file!=$up_ignore2 && $file!=$up_ignore3 && $file!=$up_ignore4 && $file!=$up_ignore5)) {
			$upload_name_sort[] = $file;
		}
	}
sort($upload_name_sort);

foreach ($upload_name_sort as $file) {
echo "
<tr>
<td class=\"uploadlistname\">$file</td>
<td class=\"uploadlistsize\">".upload_file_size("$upload_dir/$file")."</td>
<td class=\"uploadlistmod\">".date("m(M) d Y - H:i:s", filemtime("$upload_dir/$file"))."</td>";
// Rename file on/off.
if ($rename_file == "on") {
	echo "
<td class=\"uploadlistrename\">
<form action=\"index.php?s=dodavanje_priloga&z=$id_zahtjev\" method=\"post\">
$p
<input type=\"hidden\" name=\"file\" value=\"$file\" />
<input type=\"hidden\" name=\"pg\" value=\"$pg\" />
<input type=\"hidden\" name=\"cmd\" value=\"upload_rename\" />
<input type=\"text\" name=\"upload_newname\" value=\"$file\" />
<input name=\"submit\" type=\"submit\" value=\"$l_upload6\" />
$p2
</form>
</td>";
} else {
}
// Delete file on/off.
if ($delete_file == "on") {
	echo "
<td class=\"uploadlistdelete\">
<form action=\"index.php?s=dodavanje_priloga&z=$id_zahtjev\" method=\"post\">
$p
<input type=\"hidden\" name=\"file\" value=\"$file\" />
<input type=\"hidden\" name=\"pg\" value=\"$pg\" />
<input type=\"hidden\" name=\"cmd\" value=\"upload_delete\" />
<input name=\"submit\" type=\"submit\" value=\"$l_upload7\" />
$p2
</form>
</td>";
} else {
}
echo "
<td class=\"uploadlistloc\"><a href=\"http://".$_SERVER['HTTP_HOST']."/$fileupload_dir_name/$file\"><i>http://".$_SERVER['HTTP_HOST']."/$fileupload_dir_name/$file</i></a></td>
</tr>";
}
}
closedir($dir_handle);
echo "
</tbody>
</table>
";
}
}
// Include footer if "on" in config.php.
if ($head == "on") {
	//include("footer.php");
}
}

// Upload file function.
function upload2($ftp_file, $upload_type) {
// Config.php is the main configuration file.
include('config_upload.php');
// Language file.
include("lang/$language");
// Name of page for links, title, and logout.
$logout = "index.php?s=prikaz_zahtjeva&z=".$_GET[z];
$page_name = "upload";

// Include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

// Upload process.
// Path from domain name to upload directory

//dohvat sekvence za filename
$sql_prilozi = "SELECT COUNT(*) broj FROM prilozi_zahtjeva WHERE id_zahtjev = ".$_GET[z];
$query_prilozi = mysql_query($sql_prilozi) or die( "Greska: sql_prilozi = $sql_prilozi ".mysql_error());
$red_prilozi = mysql_fetch_object($query_prilozi);
$broj_priloga = $red_prilozi->broj;
$trenutni_datum = daj_datum();

$upload_dir = ($_SERVER['DOCUMENT_ROOT'] . "/$fileupload_dir_name/");
$target_path1 = $upload_dir . basename($_FILES['ftp_file']['name'][0]);
$target_path2 = $upload_dir . basename($_FILES['ftp_file']['name'][1]);
$target_path3 = $upload_dir . basename($_FILES['ftp_file']['name'][2]);
$target_path4 = $upload_dir . basename($_FILES['ftp_file']['name'][3]);
$target_path5 = $upload_dir . basename($_FILES['ftp_file']['name'][4]);
if (move_uploaded_file($_FILES['ftp_file']['tmp_name'][0], $target_path1)) {
    $broj = $broj_priloga + 1;
    rename($upload_dir . basename( $_FILES['ftp_file']['name'][0]), $upload_dir . $_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][0]), PATHINFO_EXTENSION));
	chmod($upload_dir . $_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][0]), PATHINFO_EXTENSION), 0644);
	
	$sql_insert_prilog = " INSERT INTO prilozi_zahtjeva (id_zahtjev, putanja_do_fajla, naziv_fajla, korisnik_prilog, datum_priloga) ";
	$sql_insert_prilog .= " VALUES ('".$_GET[z]."','upload/files/".$_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][0]), PATHINFO_EXTENSION)."','".basename( $_FILES['ftp_file']['name'][0])."','".$_SESSION[username]."', '".$trenutni_datum."')";
	$query_insert_prilog = mysql_query($sql_insert_prilog) or die("Greska: sql_insert_prilog $sql_insert_prilog ".mysql_error());
	echo "1";
	echo $p."$l_upload13 ".  basename( $_FILES['ftp_file']['name'][0]). "$p2";
} else {
	echo $p."$l_upload14 ".  basename( $_FILES['ftp_file']['name'][0]). "$p2";
}
if (is_file($_FILES['ftp_file']['tmp_name'][1])) {
	if (move_uploaded_file($_FILES['ftp_file']['tmp_name'][1], $target_path2)) {
	    $broj = $broj_priloga + 2;
		rename($upload_dir . basename( $_FILES['ftp_file']['name'][1]), $upload_dir . $_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][1]), PATHINFO_EXTENSION));
	    chmod($upload_dir . $_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][1]), PATHINFO_EXTENSION), 0644);
	
		$sql_insert_prilog = " INSERT INTO prilozi_zahtjeva (id_zahtjev, putanja_do_fajla, naziv_fajla, korisnik_prilog, datum_priloga) ";
	    $sql_insert_prilog .= " VALUES ('".$_GET[z]."','upload/files/".$_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][1]), PATHINFO_EXTENSION)."','".basename( $_FILES['ftp_file']['name'][1])."','".$_SESSION[username]."', '".$trenutni_datum."')";
	    $query_insert_prilog = mysql_query($sql_insert_prilog) or die("Greska: sql_insert_prilog $sql_insert_prilog ".mysql_error());
		echo "2";
		echo $p."$l_upload13 ".  basename( $_FILES['ftp_file']['name'][1]). "$p2";
	} else {
		echo $p."$l_upload14 ".  basename( $_FILES['ftp_file']['name'][1]). "$p2";
	}
}
if (is_file($_FILES['ftp_file']['tmp_name'][2])) {
	if (move_uploaded_file($_FILES['ftp_file']['tmp_name'][2], $target_path3)) {
		$broj = $broj_priloga + 3;
		rename($upload_dir . basename( $_FILES['ftp_file']['name'][2]), $upload_dir . $_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][2]), PATHINFO_EXTENSION));
	    chmod($upload_dir . $_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][2]), PATHINFO_EXTENSION), 0644);
	
		$sql_insert_prilog = " INSERT INTO prilozi_zahtjeva (id_zahtjev, putanja_do_fajla, naziv_fajla, korisnik_prilog, datum_priloga) ";
	    $sql_insert_prilog .= " VALUES ('".$_GET[z]."','upload/files/".$_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][2]), PATHINFO_EXTENSION)."','".basename( $_FILES['ftp_file']['name'][2])."','".$_SESSION[username]."', '".$trenutni_datum."')";
	    $query_insert_prilog = mysql_query($sql_insert_prilog) or die("Greska: sql_insert_prilog $sql_insert_prilog ".mysql_error());
		echo "3";
		echo $p."$l_upload13 ".  basename( $_FILES['ftp_file']['name'][2]). "$p2";
	} else {
		echo $p."$l_upload14 ".  basename( $_FILES['ftp_file']['name'][2]). "$p2";
		}
}
if (is_file($_FILES['ftp_file']['tmp_name'][3])) {
	if (move_uploaded_file($_FILES['ftp_file']['tmp_name'][3], $target_path4)) {
		$broj = $broj_priloga + 4;
		rename($upload_dir . basename( $_FILES['ftp_file']['name'][3]), $upload_dir . $_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][3]), PATHINFO_EXTENSION));
	    chmod($upload_dir . $_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][3]), PATHINFO_EXTENSION), 0644);
	
		$sql_insert_prilog = " INSERT INTO prilozi_zahtjeva (id_zahtjev, putanja_do_fajla, naziv_fajla, korisnik_prilog, datum_priloga) ";
	    $sql_insert_prilog .= " VALUES ('".$_GET[z]."','upload/files/".$_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][3]), PATHINFO_EXTENSION)."','".basename( $_FILES['ftp_file']['name'][3])."','".$_SESSION[username]."', '".$trenutni_datum."')";
	    $query_insert_prilog = mysql_query($sql_insert_prilog) or die("Greska: sql_insert_prilog $sql_insert_prilog ".mysql_error());
		echo "4";
		echo $p."$l_upload13 ".  basename( $_FILES['ftp_file']['name'][3]). "$p2";
	} else {
		echo $p."$l_upload14 ".  basename( $_FILES['ftp_file']['name'][3]). "$p2";
	}
}
if (is_file($_FILES['ftp_file']['tmp_name'][4])) {
	if (move_uploaded_file($_FILES['ftp_file']['tmp_name'][4], $target_path5)) {
		$broj = $broj_priloga + 5;
		rename($upload_dir . basename( $_FILES['ftp_file']['name'][4]), $upload_dir . $_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][4]), PATHINFO_EXTENSION));
	    chmod($upload_dir . $_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][4]), PATHINFO_EXTENSION), 0644);
	
		$sql_insert_prilog = " INSERT INTO prilozi_zahtjeva (id_zahtjev, putanja_do_fajla, naziv_fajla, korisnik_prilog, datum_priloga) ";
	    $sql_insert_prilog .= " VALUES ('".$_GET[z]."','upload/files/".$_GET[z]."_".$broj.".".pathinfo(basename( $_FILES['ftp_file']['name'][4]), PATHINFO_EXTENSION)."','".basename( $_FILES['ftp_file']['name'][4])."','".$_SESSION[username]."', '".$trenutni_datum."')";
	    $query_insert_prilog = mysql_query($sql_insert_prilog) or die("Greska: sql_insert_prilog $sql_insert_prilog ".mysql_error());
		echo "5";
		echo $p."$l_upload13 ".  basename( $_FILES['ftp_file']['name'][4]). "$p2";
	} else {
		echo $p."$l_upload14 ".  basename( $_FILES['ftp_file']['name'][4]). "$p2";
	}
}

// Redirect to upload page.
if ($su == "on") {
	$upload_redirect = $admin_redirect;
} else {
	$upload_redirect = $edit_redirect;
}
echo "<script type=\"text/javascript\">
<!--
var URL   = \"$logout\"
var speed = $upload_redirect
function reload() {
location = URL
}
setTimeout(\"reload()\", speed);
//-->
</script>
$p
$l_upload15
$p2";

// Include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
}

// Function to delete files.
function upload_delete($file, $pg) {
// Config.php is the main configuration file.
include('config.php');
// Language file.
include("lang/$language");
// Name of page for links, title, and logout.
$logout = "index.php?s=prikaz_zahtjeva&z=".$_GET[z];
$page_name = "upload";

// Include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

// Path to file.
$upload_file_path = ($_SERVER['DOCUMENT_ROOT'] . "/$fileupload_dir_name/$file");
// Delete file
unlink($upload_file_path);
echo "$p<b>$file</b> $l_upload18$p2";

// Redirect to upload page.
if ($su == "on") {
	$upload_redirect = $admin_redirect;
} else {
	$upload_redirect = $edit_redirect;
}
echo "<script type=\"text/javascript\">
<!--
var URL   = \"$logout?pg=$pg\"
var speed = $upload_redirect
function reload() {
location = URL
}
setTimeout(\"reload()\", speed);
//-->
</script>
$p
$l_upload15
$p2";

// Include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
}

// Function to rename file.
function upload_rename($file, $upload_newname, $pg) {
// Config.php is the main configuration file.
include('config.php');
// Language file.
include("lang/$language");
// Name of page for links, title, and logout.
$logout = "index.php?s=prikaz_zahtjeva&z=".$_GET[z];
$page_name = "upload";

// Include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

// Path to file.
$upload_file_path = ($_SERVER['DOCUMENT_ROOT'] . "/$fileupload_dir_name/$file");
// Check is file exists and rename it.
if (file_exists($upload_file_path)) {
	rename ($upload_file_path, $_SERVER['DOCUMENT_ROOT'] . "/$fileupload_dir_name/$upload_newname") or die ("$l_upload19");
	echo "$p<b>$file</b> $l_upload20: <b>$upload_newname</b>.$p2";
}

// Redirect to upload page.
if ($su == "on") {
	$upload_redirect = $admin_redirect;
} else {
	$upload_redirect = $edit_redirect;
}
echo "<script type=\"text/javascript\">
<!--
var URL   = \"$logout?pg=$pg\"
var speed = $upload_redirect
function reload() {
location = URL
}
setTimeout(\"reload()\", speed);
//-->
</script>
$p
$l_upload15
$p2";

// Include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
}

// Errorless check if directory is empty.
function is_empty_dir($dir) {
if (is_dir($dir)) {
	$dl = opendir($dir);
	if ($dl) {
		while ($name = readdir($dl)) {
			if (!is_dir("$dir/$name")) {
				return false;
				break;
			}
		}
		closedir($dl);
	} return true;
} else return true;
}

// Show readable file size function.
function upload_file_size($file) {
$file_size = 0;
if (file_exists($file)) {
	$size = filesize($file);
	if ($size < 1024) {
		$file_size = $size.' Bytes';
	} elseif (($size >= 1024) && ($size < 1024000)) {
		$file_size = round($size/1024,2).' KB';
	} elseif ($size >= 1024000) {
		$file_size = round(($size/1024)/1024,2).' MB';
	}
}
return $file_size;
}

function logout (){
// Config.php is the main configuration file.
include('config.php');
// Language file.
include("lang/$language");
// Name of page for links, title, and logout.
$logout = "index.php?s=prikaz_zahtjeva&z=".$_GET[z];
$page_name = "upload";

// Include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}
session_destroy ();
session_unset ($_SESSION['pass_hash_upload']);
echo "<script type=\"text/javascript\">
<!--
var URL   = \"$logout\"
var speed = $edit_redirect
function reload() {
location = URL
}
setTimeout(\"reload()\", speed);
//-->
</script>";
echo "$p
$l_global10
$p2
$p
$l_global11
$p2";
// Include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
}

switch(@$_REQUEST['cmd']) {
	default:
	upload1();
	break;
	
case "upload2";
	upload2(@$_POST['ftp_file'], @$_POST['upload_type'], $_POST['submit']);
	break;

case "upload_delete";
	upload_delete($_POST['file'], $_POST['pg']);
	break;

case "upload_rename";
	upload_rename($_POST['file'], $_POST['upload_newname'], $_POST['pg']);
	break;

case "logout";
	logout();
	break;
}

?>