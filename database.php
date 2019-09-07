<?

/**
 * Connect to the mysql database.
 */
$conn = mysql_connect("localhost", "shef_hd", "help#desk*2010") or die(mysql_error());
mysql_select_db('shef_helpdesk', $conn) or die(mysql_error());

?>