<? 
$sql_korisnik = "SELECT tip_odgovorne_osobe FROM korisnici WHERE username = '".$_SESSION['username']."'";
$query_korisnik = mysql_query($sql_korisnik) or die( "Greska: sql_korisnik = $sql_korisnik ".mysql_error());
$red_korisnik = mysql_fetch_object($query_korisnik);

if ($red_korisnik->tip_odgovorne_osobe == 1)
  include ("inc/prikaz_zahtjeva_serviser.php");
elseif ($red_korisnik->tip_odgovorne_osobe == 2)
  include ("inc/prikaz_zahtjeva_ping_administrator.php"); 
elseif ($red_korisnik->tip_odgovorne_osobe == 3)
  include ("inc/prikaz_zahtjeva_administrator_grupe.php"); 
elseif ($red_korisnik->tip_odgovorne_osobe == 4)
  include ("inc/prikaz_zahtjeva_korisnik_grupe.php"); 
  

  ?>