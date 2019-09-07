<?php
function make_my_time($time){	

    $niz = explode(".", $time);		
	return $niz[2]."-".$niz[1]."-".$niz[0];
	
}
		
function korisnik_sifra($podnosilac_zahtjeva){
		
    $sql_user = " SELECT id_korisnik FROM korisnici WHERE username = '".$podnosilac_zahtjeva."'";
	$query_user = mysql_query($sql_user) or die("Ne valja query sql_user $sql_user ".mysql_error());
	if(mysql_num_rows($query_user) > 0 ){
		$red_user = mysql_fetch_object($query_user);
		$podnosilac_zahtjeva = $red_user->id_korisnik;	
	}else{
		$podnosilac_zahtjeva = 0;
	}
	return $podnosilac_zahtjeva;
		
}

function korisnik_naziv($usernamek){
		
    $sql_user = " SELECT concat(prezime, ' ', ime) naziv FROM korisnici WHERE id_korisnik = '".$usernamek."'";
	$query_user = mysql_query($sql_user) or die("Ne valja query sql_user $sql_user ".mysql_error());
	if(mysql_num_rows($query_user) > 0 ){
		$red_user = mysql_fetch_object($query_user);
		$naziv_korisnika = $red_user->naziv;	
	}else{
		$naziv_korisnika = 0;
	}
	return $naziv_korisnika;
		
}

function korisnik_grupa($username_kor){
		
    $sql_user = " SELECT id_grupa_korisnika FROM korisnici WHERE username = '".$username_kor."'";
	$query_user = mysql_query($sql_user) or die("Ne valja query sql_user $sql_user ".mysql_error());
	if(mysql_num_rows($query_user) > 0 ){
		$red_user = mysql_fetch_object($query_user);
		$grupa_korisnika = $red_user->id_grupa_korisnika;	
	}else{
		$grupa_korisnika = 0;
	}
	return $grupa_korisnika;
		
}

function tip_zahtjeva_naziv($id){
		
    $sql_user = " SELECT tip_zahtjeva FROM tipovi_zahtjeva WHERE id_tip_zahtjeva = '".$id."'";
	$query_user = mysql_query($sql_user) or die("Ne valja query sql_user $sql_user ".mysql_error());
	if(mysql_num_rows($query_user) > 0 ){
		$red_user = mysql_fetch_object($query_user);
		$naziv = $red_user->tip_zahtjeva;	
	}else{
		$naziv = 0;
	}
	return $naziv;
		
}

function status_naziv($id){
		
    $sql_user = " SELECT status FROM statusi WHERE id_status = '".$id."'";
	$query_user = mysql_query($sql_user) or die("Ne valja query sql_user $sql_user ".mysql_error());
	if(mysql_num_rows($query_user) > 0 ){
		$red_user = mysql_fetch_object($query_user);
		$naziv = $red_user->status;	
	}else{
		$naziv = 0;
	}
	return $naziv;
		
}

function realizacija_naziv($id){
		
    $sql_user = " SELECT nacin_realizacije FROM nacini_realizacije WHERE id_nacin_realizacije = '".$id."'";
	$query_user = mysql_query($sql_user) or die("Ne valja query sql_user $sql_user ".mysql_error());
	if(mysql_num_rows($query_user) > 0 ){
		$red_user = mysql_fetch_object($query_user);
		$naziv = $red_user->nacin_realizacije;	
	}else{
		$naziv = 0;
	}
	return $naziv;
		
}

function modul_naziv($id){
		
    $sql_user = " SELECT modul FROM moduli WHERE id_modul = '".$id."'";
	$query_user = mysql_query($sql_user) or die("Ne valja query sql_user $sql_user ".mysql_error());
	if(mysql_num_rows($query_user) > 0 ){
		$red_user = mysql_fetch_object($query_user);
		$naziv = $red_user->modul;	
	}else{
		$naziv = 0;
	}
	return $naziv;
		
}

function lokacija_naziv($id){
		
    $sql_user = " SELECT lokacija_izvrsenja FROM lokacije_izvrsenja WHERE id_lokacija_izvrsenja = '".$id."'";
	$query_user = mysql_query($sql_user) or die("Ne valja query sql_user $sql_user ".mysql_error());
	if(mysql_num_rows($query_user) > 0 ){
		$red_user = mysql_fetch_object($query_user);
		$naziv = $red_user->lokacija_izvrsenja;	
	}else{
		$naziv = 0;
	}
	return $naziv;
		
}

function checkPassword($password1, $password2){

	$minLength = 5; // Minimum length
	$maxLength = 15; // Maximum length
	$vrati_niz = array();
	
	
	if ($password1 == '' || $password2 == '') {
		$vrati_niz[0] = 'Molim Vas unesite lozinku dva puta.'; 
		$vrati_niz[1] = 0; 
	}

	if (strlen($password1) < $minLength || strlen($password1) > $maxLength) {
		$vrati_niz[0] = 'Vaša lozinka mora sadržavati najmanje '.$minLength.' i najviše '.$maxLength.' karaktera. Molim Vas probajte ponovo.'; 
		$vrati_niz[1] = 0; 		
	}

	else {
		if ($password1 != $password2) {
			$vrati_niz[0] = 'Lozinke koje ste unijeli se ne podudaraju, molim Vas unesite ponovo.'; 
			$vrati_niz[1] = 0; 			
		}	
		else {
			$vrati_niz[0] = ''; 
			$vrati_niz[1] = 1; 			
		}
	}
		
		return $vrati_niz;
}

function daj_tip_osobe(){
  $sql_korisnik = "SELECT concat(prezime, ' ', ime) ime, tip_odgovorne_osobe FROM korisnici WHERE username = '".$_SESSION['username']."'";
  $query_korisnik = mysql_query($sql_korisnik) or die( "Greska: sql_korisnik = $sql_korisnik ".mysql_error());
  $red_korisnik = mysql_fetch_object($query_korisnik);
  
  $tip_korisnika = $red_korisnik->tip_odgovorne_osobe;
  
  return $tip_korisnika;

}


function daj_datum(){
		
    $sql_user = " SELECT convert_tz(CURRENT_TIMESTAMP,'+00:00','+07:00') datum";
	$query_user = mysql_query($sql_user) or die("Ne valja query sql_user $sql_user ".mysql_error());
	if(mysql_num_rows($query_user) > 0 ){
		$red_user = mysql_fetch_object($query_user);
		$naziv = $red_user->datum;	
	}else{
		$naziv = 0;
	}
	return $naziv;
		
}

function posalji_email($zahtjev, $odg_osoba, $prior){
  $sql_tekst = "SELECT a.naslov_zahtjeva, a.id_zahtjev, a.broj_zahtjeva_korisnika, a.status, a.prioritet, b.status txt_status, a.modul FROM zahtjevi a, statusi b"
	           ." WHERE a.status = b.id_status AND id_zahtjev = ".$zahtjev.";";
  $query_tekst = mysql_query($sql_tekst) or die("Ne valja query sql_tekst $sql_tekst ".mysql_error());
  $red_tekst = mysql_fetch_object($query_tekst);


    
	$tip_osobe = daj_tip_osobe();
	
	$sql_user = "SELECT DISTINCT email FROM korisnici WHERE id_korisnik IN("
		        ." SELECT * FROM("
	            ." SELECT podnosilac_zahtjeva korisnik FROM zahtjevi where id_zahtjev = ".$zahtjev
			    ." union"
			    ." SELECT id_korisnik korisnik FROM korisnici WHERE tip_odgovorne_osobe = 3 AND id_grupa_korisnika IN("
				  ." SELECT id_grupa_korisnika FROM zahtjevi, korisnici WHERE zahtjevi.podnosilac_zahtjeva = korisnici.id_korisnik"
                  ." AND id_zahtjev = ".$zahtjev.")"
				." union"
                ." SELECT id_korisnik korisnik FROM korisnici WHERE tip_odgovorne_osobe = 2 AND id_korisnik IN (SELECT id_admin FROM admin_modul WHERE id_modul = ".$red_tekst->modul.")"
				." union"
				." SELECT serviser FROM zahtjevi WHERE id_zahtjev =".$zahtjev.") tab1"
				." WHERE korisnik != ".korisnik_sifra($_SESSION['username'])
				.") AND email != ''";	
	
	$query_user = mysql_query($sql_user) or die("Ne valja query sql_user $sql_user ".mysql_error());
	$red_user = mysql_fetch_object($query_user);
	
	$sql_tekst = "SELECT a.naslov_zahtjeva, a.id_zahtjev, a.broj_zahtjeva_korisnika, a.status, b.status txt_status FROM zahtjevi a, statusi b"
	           ." WHERE a.status = b.id_status AND id_zahtjev = ".$zahtjev.";";
	$query_tekst = mysql_query($sql_tekst) or die("Ne valja query sql_tekst $sql_tekst ".mysql_error());
	$red_tekst = mysql_fetch_object($query_tekst);
	
	IF($red_tekst->status == 4 || $red_tekst->status == 5){	
	  $sql_kom = "SELECT komentar FROM komentari WHERE id_zahtjev = ".$zahtjev." ORDER BY id_komentar desc";	
	  $query_kom = mysql_query($sql_kom) or die("Ne valja query sql_kom $sql_kom ".mysql_error());
	  $red_kom = mysql_fetch_object($query_kom);
	  $komentar_zadnji = $red_kom -> komentar;		  
	}
	
	//Ako je doslo do promjene odgovorne osobe
	IF(!(empty($odg_osoba))){
		$sql_os = "SELECT ime, prezime FROM korisnici WHERE id_korisnik = ".$odg_osoba;	
	    $query_os = mysql_query($sql_os) or die("Ne valja query sql_kom $sql_os ".mysql_error());
	    $red_os = mysql_fetch_object($query_os);
	    $odg_osoba_txt = $red_os -> prezime." ".$red_os -> ime;
		$promjena_osobe = 1;
	}
	
	IF(!(empty($prior))){
		$priorit = 1;
	}
	
	$to = $red_user->email;		

	$i = 0;
	$cc = array();
	while($red_user = mysql_fetch_object($query_user)){
	    $cc[$i] = $red_user->email;			
        $i++;
    }		
	
	IF($promjena_osobe == 1 AND $priorit == 1)
		$subject = $red_tekst->id_zahtjev."-".$red_tekst -> naslov_zahtjeva.": Promjena odgovorne osobe i prioriteta";
	ELSEIF($promjena_osobe == 1)
		$subject = $red_tekst->id_zahtjev."-".$red_tekst -> naslov_zahtjeva.": Promjena odgovorne osobe";
	ELSEIF($priorit == 1)
		$subject = $red_tekst->id_zahtjev."-".$red_tekst -> naslov_zahtjeva.": Promjena prioriteta";
	ELSE
		$subject = $red_tekst->id_zahtjev."-".$red_tekst -> naslov_zahtjeva.": ".$red_tekst->txt_status;
	IF($promjena_osobe == 1 AND $priorit == 1){
		$message = "Broj zahtjeva korisnika: ".$red_tekst->broj_zahtjeva_korisnika."\n"
	         ."Broj PING naloga: ".$red_tekst->id_zahtjev."\n"
			 ."Naslov zahtjeva: ".$red_tekst->naslov_zahtjeva."\n\n"
			 ."Nova odgovorna osoba: ".$odg_osoba_txt."\n"
			 ."Novi prioritet: ".$prior."\n";
	}elseif($promjena_osobe == 1){
		$message = "Broj zahtjeva korisnika: ".$red_tekst->broj_zahtjeva_korisnika."\n"
	         ."Broj PING naloga: ".$red_tekst->id_zahtjev."\n"
			 ."Naslov zahtjeva: ".$red_tekst->naslov_zahtjeva."\n\n"
			 ."Nova odgovorna osoba: ".$odg_osoba_txt."\n";
	}elseif($priorit == 1){
		$message = "Broj zahtjeva korisnika: ".$red_tekst->broj_zahtjeva_korisnika."\n"
	         ."Broj PING naloga: ".$red_tekst->id_zahtjev."\n"
			 ."Naslov zahtjeva: ".$red_tekst->naslov_zahtjeva."\n\n"
			 ."Novi prioritet: ".$prior."\n";
	}else{
		$message = "Broj zahtjeva korisnika: ".$red_tekst->broj_zahtjeva_korisnika."\n"
	         ."Broj PING naloga: ".$red_tekst->id_zahtjev."\n"
			 ."Naslov zahtjeva: ".$red_tekst->naslov_zahtjeva."\n"
			 ."Novi status: ".$red_tekst->txt_status."\n\n"
			 .$komentar_zadnji;
	}

// To send HTML mail, the Content-type header must be set
/*$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
*/

$broj = count($cc);
// Additional headers
$headers = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";
$headers .= 'From: Ping HelpDesk <helpdesk@noreply.com>' . "\r\n";
$headers .= 'Cc: ';
$i=0;
while($i < $broj){
    if($i == $broj - 1)
	  $headers .= $cc[$i];
	else
	  $headers .= $cc[$i].', ';
	$i++;
}
$headers .= "\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion();

$message = $message."\n\n---------------------------------------------------------------------------------\nOvo je automatski poslan email "
                     ."iz aplikacije PING HelpDesk! NE ODGOVARATI na email\n---------------------------------------------------------------------------------";

//$dodatni_param = "-f noreply@ping.com>";

// Mail it
if(mail($to, $subject, $message, $headers)){  
  return 1;
}else{  
  echo " Email nije poslan!";
  return 0;
}
}	




?>