<?
session_start(); 
date_default_timezone_set('Europe/Sarajevo');
require("database.php");
require("login.php");
require("inc/funkcije.php");
$s = $_GET['s']; 

if($s == '' || $s == 'login' || $s == 'pocetna') {
  $otvori = 'inc/pocetna.php';  
}
elseif($s == 'unos_zahtjeva') {
  $otvori = 'inc/unos_zahtjeva.php';
  $nav2 = "id=current";
}
elseif($s == 'zahtjev') {
  $otvori = 'inc/zahtjev.php';  
}
elseif($s == 'lista_zahtjeva') {
  $otvori = 'inc/lista_zahtjeva.php';
  $nav3 = "id=current";
}
elseif($s == 'logout') {
  $otvori = 'logout.php';
  $nav4 = "id=current";
}
elseif($s == 'prikaz_zahtjeva') {
  $otvori = 'inc/prikaz_zahtjeva.php'; 
  $nav3 = "id=current";  
}
elseif($s == 'dodavanje_priloga') {
  $otvori = 'inc/upload.php';  
}
elseif($s == 'korisnici') {
  $otvori = 'inc/korisnici.php';
  $nav5 = "id=current";
}
elseif($s == 'unos_korisnika') {
  $otvori = 'inc/unos_korisnika.php';
  $nav5 = "id=current";
}
elseif($s == 'korisnici_moduli') {
  $otvori = 'inc/korisnici_moduli.php';
  $nav6 = "id=current";
}
elseif($s == 'grupe_moduli') {
  $otvori = 'inc/grupe_moduli.php';
  $nav8 = "id=current";
}
elseif($s == 'admin_moduli') {
  $otvori = 'inc/admin_moduli.php';
  $nav9 = "id=current";
}
elseif($s == 'zahtjev_ok') {
  $otvori = 'inc/zahtjev_ok.php';
  $nav2 = "id=current";
}
elseif($s == 'arhiva') {
  $otvori = 'inc/arhiva.php';
  $nav1 = "id=current";
}
elseif($s == 'change_pass') {
  $otvori = 'inc/change_pass.php';
  $nav7 = "id=current";
}
elseif($s == 'zahtjev_greska') {
  $otvori = 'inc/zahtjev_greska.php';  
}

if($logged_in){

  $sql_korisnik = "SELECT concat(prezime, ' ', ime) ime, tip_odgovorne_osobe FROM korisnici WHERE username = '".$_SESSION['username']."'";
  $query_korisnik = mysql_query($sql_korisnik) or die( "Greska: sql_korisnik = $sql_korisnik ".mysql_error());
  $red_korisnik = mysql_fetch_object($query_korisnik);
  
  $tip_korisnika = $red_korisnik->tip_odgovorne_osobe;
  
  if ($red_korisnik->tip_odgovorne_osobe == 1){
    $tip_odgovorne_osobe = "Izvršilac";
	$slika = "serviser.png";}
  elseif ($red_korisnik->tip_odgovorne_osobe == 2){
    $tip_odgovorne_osobe = "Administrator PING-a";
	$slika = "adminp.png";}
  elseif ($red_korisnik->tip_odgovorne_osobe == 3){
    $tip_odgovorne_osobe = "Administrator grupe";
	$slika = "admin.png";}
  elseif ($red_korisnik->tip_odgovorne_osobe == 4){
    $tip_odgovorne_osobe = "Korisnik grupe";
	$slika = "user.png";}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!--
Copyright: Six Revisions
Designed and Coded By: Richard Carpenter
URL : www.hv-designs.co.uk | www.sixrevisions.com
-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PING doo - HELPDESK</title>
<link rel="shortcut icon" href="images/favicon.ico">

<link href="css/styles.css?v=2" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/date_input.css" />
<link rel="stylesheet" type="text/css" href="css/demo_table.css" />
<link rel="stylesheet" type="text/css" href="css/demo_page.css" />


<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.date_input.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="js/atooltip.min.jquery.js"></script>
<script src="js/contact.js" type="text/javascript"></script>
<script type="text/javascript">$($.date_input.initialize);</script>
<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				
				function trim(str) {
	str = str.replace(/^\s+/, '');
	for (var i = str.length - 1; i >= 0; i--) {
		if (/\S/.test(str.charAt(i))) {
			str = str.substring(0, i + 1);
			break;
		}
	}	
	return str;
}

jQuery.fn.dataTableExt.oSort['date-euro-asc'] = function(a, b) {
	if (trim(a) != '') {
		var frDatea = trim(a).split(' ');
		var frTimea = frDatea[1].split(':');
		var frDatea2 = frDatea[0].split('.');
		var x = (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1]) * 1;
		
	} else {
		var x = 10000000000000; // = l'an 1000 ...
	}

	if (trim(b) != '') {
		var frDateb = trim(b).split(' ');
		var frTimeb = frDateb[1].split(':');
		frDateb = frDateb[0].split('.');
		var y = (frDateb[2] + frDateb[1] + frDateb[0] + frTimeb[0] + frTimeb[1]) * 1;		                
	} else {
		var y = 10000000000000;		                
	}
	var z = ((x < y) ? -1 : ((x > y) ? 1 : 0));
	return z;
};

jQuery.fn.dataTableExt.oSort['date-euro-desc'] = function(a, b) {
	if (trim(a) != '') {
		var frDatea = trim(a).split(' ');
		var frTimea = frDatea[1].split(':');
		var frDatea2 = frDatea[0].split('.');
		var x = (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1]) * 1;		                
	} else {
		var x = 10000000000000;		                
	}

	if (trim(b) != '') {
		var frDateb = trim(b).split(' ');
		var frTimeb = frDateb[1].split(':');
		frDateb = frDateb[0].split('.');
		var y = (frDateb[2] + frDateb[1] + frDateb[0] + frTimeb[0] + frTimeb[1]) * 1;		                
	} else {
		var y = 10000000000000;		                
	}		            
	var z = ((x < y) ? 1 : ((x > y) ? -1 : 0));		            
	return z;
}; 
				
				
				
				oTable = $('#example').dataTable({
					"fnDrawCallback": function ( oSettings ) {
						if ( oSettings.aiDisplay.length == 0 )
						{
							return;
						}
						
						var nTrs = $('tbody tr', oSettings.nTable);
						var iColspan = nTrs[0].getElementsByTagName('td').length;
						var sLastGroup = "";
						for ( var i=0 ; i<nTrs.length ; i++ )
						{
							var iDisplayIndex = oSettings._iDisplayStart + i;
							var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
							if ( sGroup != sLastGroup )
							{
								var nGroup = document.createElement( 'tr' );
								var nCell = document.createElement( 'td' );
								nCell.colSpan = iColspan;
								nCell.className = "group";
								nCell.innerHTML = sGroup;
								nGroup.appendChild( nCell );
								nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
								sLastGroup = sGroup;
							}
						}
					},					
					"aoColumnDefs": [
						{ "bVisible": false, "aTargets": [ 0 ] },
						{ "bVisible": false, "aTargets": [ 8 ] },
						{ "bVisible": false, "aTargets": [ 9 ] },
						{ "bVisible": false, "aTargets": [ 10 ] },
						{ "sType": "date-euro", "aTargets": [ 4 ] }
					],
					
					
					
					"aaSortingFixed": [[ 0, 'asc' ]],
					"aaSorting": [[ 1, 'desc' ]],
					"sDom": 'lfr<"giveHeight"t>ip'
				});
			} );
		</script>		
<script type="text/javascript"> 
			$(function(){ 
				
				$('a.fixedTip').aToolTip({
		    		fixed: true
				});

				$('a').aToolTip({          
        fixed: true,                       // Set true to activate fixed position  
        inSpeed: 400,                       // Speed tooltip fades in  
        outSpeed: 100,                      // Speed tooltip fades out         
        xOffset: -10,                         // x Position  
        yOffset: 0                          // y position  
    });  
				
			}); 
		</script> 

</head>
<body>

<div id="container">

 <div id="header">
    <a href="index.php?s=pocetna"><img src='images/logo.png' border="0"></a>
	<div id="info">	
	    <img src="images/<? echo $slika; ?>">
	   <p><span><? echo $red_korisnik->ime."</span>,<br> prijavljeni ste kao:<br>".$tip_odgovorne_osobe; ?></p>
	</div>
 </div>

<? if ($red_korisnik->tip_odgovorne_osobe == 2){ ?>
  <div id="navigation_admin">
  <ul class="nav-links_admin">   
   <li <? echo $nav2."_admin"; ?>><a href="?s=unos_zahtjeva">Unos zahtjeva</a></li>
   <li <? echo $nav3."_admin"; ?>><a href="index.php?s=lista_zahtjeva">Lista zahtjeva</a></li>
   <li <? echo $nav1."_admin"; ?>><a href="?s=arhiva">Arhiva</a></li>   
<? if ($red_korisnik->tip_odgovorne_osobe == 2){ ?>   
   <li <? echo $nav5."_admin"; ?>><a href="index.php?s=korisnici">Korisnici</a></li> 
   <li <? echo $nav6."_admin"; ?>><a href="index.php?s=korisnici_moduli">Korisnik-modul</a></li> 
   <li <? echo $nav8."_admin"; ?>><a href="index.php?s=grupe_moduli">Grupa-modul</a></li> 
   <li <? echo $nav9."_admin"; ?>><a href="index.php?s=admin_moduli">Admin-modul</a></li> 
<? } ?>
   <li <? echo $nav4."_admin"; ?>><a href="logout.php">Odjavi se</a></li>
  </ul>
 </div>
 
<?php }else{ ?>

 
 <div id="navigation">
  <ul class="nav-links">   
   <li <? echo $nav2; ?>><a href="?s=unos_zahtjeva">Unos zahtjeva</a></li>
   <li <? echo $nav3; ?>><a href="index.php?s=lista_zahtjeva">Lista zahtjeva</a></li>
   <li <? echo $nav1; ?>><a href="?s=arhiva">Arhiva</a></li>   
<? if ($red_korisnik->tip_odgovorne_osobe == 2){ ?>   
   <li <? echo $nav5; ?>><a href="index.php?s=korisnici">Korisnici</a></li> 
   <li <? echo $nav6; ?>><a href="index.php?s=korisnici_moduli">Korisnik modul</a></li> 
<? } ?>
<? if ($red_korisnik->tip_odgovorne_osobe != 2){ ?> 
   <li <? echo $nav7; ?>><a href="index.php?s=change_pass">Promijeni lozinku</a></li>
<? } ?>
   <li <? echo $nav4; ?>><a href="logout.php">Odjavi se</a></li>
  </ul>
 </div>
<? } ?>
 
 <div id="content-area">

  <? include $otvori; 
  mysql_close($conn);
  ?>
 
 </div>

 <div id="footer">
  <p>Copyright &copy;
   
 | Design &amp; Coded By PING</p>
 </div>
</div>

</body>
</html>

<?
}
else header( 'Location: main.php' ) ;
?>
