<?
//Zastita od citanja tudjih zahtjeva
$sql_count_zahtjeva = "SELECT count(*) broj" 
. " FROM zahtjevi, korisnici, grupe_korisnika, moduli, statusi"
. " WHERE zahtjevi.podnosilac_zahtjeva = korisnici.id_korisnik"
. " AND korisnici.id_grupa_korisnika = grupe_korisnika.id_grupa_korisnika"
. " AND zahtjevi.modul = moduli.id_modul"
. " AND zahtjevi.status = statusi.id_status"
. " AND zahtjevi.podnosilac_zahtjeva IN (SELECT id_korisnik FROM korisnici WHERE id_grupa_korisnika = ".korisnik_grupa($_SESSION[username]).")"
. " AND zahtjevi.id_zahtjev = ".$_GET['z']
. " ORDER BY id_zahtjev desc";

$query_count_zahtjeva = mysql_query($sql_count_zahtjeva) or die( "Greska sql_count_zahtjeva = $sql_count_zahtjeva ".mysql_error());
$red_count_zahtjeva = mysql_fetch_object($query_count_zahtjeva);
if ($red_count_zahtjeva->broj==0){
?>
<script language="JavaScript">
		<!--
		function refresh()
		{
		window.location.href = unescape(window.location.pathname)+'?s=zahtjev_greska';
		}
		setTimeout( "refresh()", 1 ); // 1 milisekunda
		//-->
		</script>
<?}
  

if($_POST['posted'] == '1'){
	$preferirani_rok = "'".make_my_time($_POST['preferirani_rok'])."'";	
    $user_izmijenio = korisnik_sifra($_SESSION['username']);	
    
    if ($_POST['broj_sati'] == '')
	  $broj_sati = 'null';
	else
	  $broj_sati = $_POST['broj_sati'];
	
	if ($_POST['preferirani_rok'] == '')
	  $preferirani_rok = 'null';

	$sql_status = "SELECT status, serviser, prioritet FROM zahtjevi WHERE id_zahtjev = ".$_GET['z'].";";
    $query_status = mysql_query($sql_status) or die("Ne valja query sql_tekst $sql_tekst ".mysql_error());
    $red_status = mysql_fetch_object($query_status);	
	$stari_status = $red_status->status;
	$stari_serviser = $red_status->serviser;
	$stari_prioritet = $red_status->prioritet;

	$sql_update_zahtjev = "UPDATE zahtjevi SET tip_zahtjeva = ".$_POST['tip_zahtjeva'].", vrsta_zahtjeva = '".$_POST['vrsta_zahtjeva']."', "
	    ."status = ".$_POST['status'].", "
		."modul = '".$_POST['modul']."', preferirani_rok = ".$preferirani_rok.", "
		."opis_zahtjeva = '".addslashes($_POST['opis_problema'])."', user_izmijenio = '".$user_izmijenio."', "
		."broj_zahtjeva_korisnika = '".$_POST['broj_zahtjeva']."', prioritet = '".$_POST['prioritet']."'"
		." WHERE id_zahtjev = ".$_GET['z'].";";		
	
	$query_update_zahtjev = mysql_query($sql_update_zahtjev) or die("GRESKA: sql_update_zahtjev $sql_update_zahtjev ".mysql_error());		
	
	echo 'Uspješno ste promijenili zahtjev';
	
	if($stari_serviser != $_POST['izvrsioc'])
		$odg_osoba = $_POST['izvrsioc'];
	
	if($stari_prioritet != $_POST['prioritet'])
		$prior = $_POST['prioritet'];
		
	if(($stari_status != $_POST['status']) OR !(empty($odg_osoba)) OR !(empty($prior))){  
	  $rez = posalji_email($_GET['z'], $odg_osoba, $prior);
	  IF($rez==1)
	    echo "<meta http-equiv=\"Refresh\" content=\"0;url=$_SERVER[REQUEST_URI]\">";
	}else{
	  echo "<meta http-equiv=\"Refresh\" content=\"0;url=$_SERVER[REQUEST_URI]\">";
	}
}

if($_POST['posted'] == '2'){
		
    $user_komentarisao = korisnik_sifra($_SESSION['username']);
    $trenutni_datum = daj_datum();
	
	$sql_insert_komentar = "INSERT INTO komentari (id_zahtjev, korisnik, komentar, datum_komentara) VALUES (".$_GET['z'].", ".$user_komentarisao.", '".addslashes($_POST['komentar'])."','".$trenutni_datum."');";
    $query_insert_komentar = mysql_query($sql_insert_komentar) or die("GRESKA: sql_insert_komentar $sql_insert_komentar ".mysql_error());
    
	$sql_update_zahtjev = "UPDATE zahtjevi SET status = 5, user_izmijenio = '".$user_komentarisao."' WHERE id_zahtjev = ".$_GET['z'].";";		
	$query_update_zahtjev = mysql_query($sql_update_zahtjev) or die("GRESKA: sql_update_zahtjev $sql_update_zahtjev ".mysql_error());
	
	$rez = posalji_email($_GET['z'], $odg_osoba, $pr);
	IF($rez==1)
	  echo "<meta http-equiv=\"Refresh\" content=\"0;url=$_SERVER[REQUEST_URI]\">";
}


// pomocni niz tipova zahtjeva za formu
	$tipovi_zahtjeva = array();
	$i=0;
	$sql_tipovi_zahtjeva = "SELECT id_tip_zahtjeva, tip_zahtjeva FROM tipovi_zahtjeva ORDER BY id_tip_zahtjeva";
	$query_tipovi_zahtjeva = mysql_query($sql_tipovi_zahtjeva) or die( "Greska: sql_tipovi_zahtjeva = $sql_tipovi_zahtjeva ".mysql_error());

	while($red_tip_zahtjeva = mysql_fetch_object($query_tipovi_zahtjeva)){

		$tipovi_zahtjeva[$i][0] = $red_tip_zahtjeva->id_tip_zahtjeva;
		$tipovi_zahtjeva[$i][1] = $red_tip_zahtjeva->tip_zahtjeva;
		$i++;
	}

//pomocni niz vrsta_zahtjeva
	$vrsta_zahtjeva = array();
	$vrsta_zahtjeva[0][0] = 'ASW';
	$vrsta_zahtjeva[0][1] = 'Aplikativni softver';
	

//pomocni niz prioriteta
	$prioriteti = array();
	$prioriteti[0] = 'NISKI';
	$prioriteti[1] = 'SREDNJI';
	$prioriteti[2] = 'VISOKI';
	$prioriteti[3] = 'URGENTNI';
	



// pomocni niz izvrsilaca za formu
	$izvrsioci = array();
	$i=0;
	$sql_izvrsioci = "SELECT id_korisnik, prezime, ime FROM korisnici WHERE tip_odgovorne_osobe in (1,2) ORDER BY prezime, ime";
	$query_izvrsioci = mysql_query($sql_izvrsioci) or die( "Greska: sql_izvrsioci = $sql_izvrsioci ".mysql_error());

	while($red_izvrsioci = mysql_fetch_object($query_izvrsioci)){

		$izvrsioci[$i][0] = $red_izvrsioci->id_korisnik;
		$izvrsioci[$i][1] = $red_izvrsioci->prezime." ".$red_izvrsioci->ime;
		$i++;
	}	
// pomocni niz lokacija za formu
	$lokacije = array();
	$i=0;
	$sql_lokacije = "SELECT id_lokacija_izvrsenja, lokacija_izvrsenja FROM lokacije_izvrsenja ORDER BY id_lokacija_izvrsenja";
	$query_lokacije = mysql_query($sql_lokacije) or die( "Greska: sql_lokacije = $sql_lokacije ".mysql_error());

	while($red_lokacije = mysql_fetch_object($query_lokacije)){

		$lokacije[$i][0] = $red_lokacije->id_lokacija_izvrsenja;
		$lokacije[$i][1] = $red_lokacije->lokacija_izvrsenja;
		$i++;
	}


// pomocni niz priloga za formu
	$prilozi = array();
	$i=0;
	$sql_prilozi = "SELECT id_prilog, putanja_do_fajla, naziv_fajla, concat(ime, ' ',prezime) ime_prezime, datum_priloga FROM prilozi_zahtjeva, korisnici"
	    ." WHERE prilozi_zahtjeva.korisnik_prilog = korisnici.username AND id_zahtjev = ".$_GET['z']." ORDER BY datum_priloga";
	$query_prilozi = mysql_query($sql_prilozi) or die( "Greska: sql_prilozi = $sql_prilozi ".mysql_error());

	while($red_prilozi = mysql_fetch_object($query_prilozi)){

		$prilozi[$i][0] = $red_prilozi->id_prilog;
		$prilozi[$i][1] = $red_prilozi->putanja_do_fajla;
		$prilozi[$i][2] = $red_prilozi->ime_prezime;
		$prilozi[$i][3] = $red_prilozi->datum_priloga;
		$prilozi[$i][4] = $red_prilozi->naziv_fajla;
		$i++;
	}			
// pomocni niz sa svim podacima o zahtjevu
    $sql_zahtjev = "SELECT a.id_zahtjev, a.tip_zahtjeva, a.vrsta_zahtjeva, a.broj_zahtjeva_korisnika, a.nacin_realizacije," 
					. "a.naslov_zahtjeva, replace(a.opis_zahtjeva,char(10),'<br>') opis_zahtjeva , a.datum_zahtjeva, a.status, a.podnosilac_zahtjeva," 
					. "a.serviser, a.lokacija_izvrsenja, a.modul, a.prioritet, a.opis_realizacije, a.preferirani_rok, "
					. "a.zadnji_rok, a.broj_sati, b.nacin_realizacije txt_nacin_realizacije, "
					. "c.tip_zahtjeva txt_tip_zahtjeva, d.status txt_status, concat(e.prezime,' ',e.ime) txt_podnosilac_zahtjeva,"
					. "concat(f.prezime,' ',f.ime) txt_serviser, g.lokacija_izvrsenja txt_lokacija_izvrsenja,"
					. "h.modul txt_modul"
					. " FROM zahtjevi a LEFT JOIN nacini_realizacije b ON (a.nacin_realizacije = b.id_nacin_realizacije)"
									. " LEFT JOIN tipovi_zahtjeva c ON (a.tip_zahtjeva = c.id_tip_zahtjeva)"
									. " LEFT JOIN statusi d ON (a.status = d.id_status)"
									. " LEFT JOIN korisnici e ON (a.podnosilac_zahtjeva = e.id_korisnik)"
									. " LEFT JOIN korisnici f ON (a.serviser = f.id_korisnik)"
									. " LEFT JOIN lokacije_izvrsenja g ON (a.lokacija_izvrsenja = g.id_lokacija_izvrsenja)"
									. " LEFT JOIN moduli h ON (a.modul = h.id_modul)"
					. " WHERE id_zahtjev = ".$_GET['z'].";";
	$query_zahtjev = mysql_query($sql_zahtjev) or die( "Greska: sql_zahtjev = $sql_zahtjev ".mysql_error());
	$red_zahtjev = mysql_fetch_object($query_zahtjev);

// pomocni niz modula za formu
	$moduli = array();
	$i=0;
	$grupa_korisnika = korisnik_grupa($_SESSION['username']);
	$sql_moduli = "SELECT a.id_modul as id_modul, a.modul as modul 
					FROM moduli a, grupa_modul b 
					WHERE a.id_modul = b.id_modul 
					AND b.id_grupa = $grupa_korisnika
					UNION
					SELECT ".$red_zahtjev->modul." as id_modul, '".$red_zahtjev->txt_modul."' as modul
					ORDER BY id_modul";
	$query_moduli = mysql_query($sql_moduli) or die( "Greska: sql_moduli = $sql_moduli ".mysql_error());

	while($red_moduli = mysql_fetch_object($query_moduli)){

		$moduli[$i][0] = $red_moduli->id_modul;
		$moduli[$i][1] = $red_moduli->modul;
		$i++;
	}	
	
// pomocni niz statusa za formu
	$statusi = array();
	$i=0;
	$sql_statusi = "SELECT id_status, status FROM statusi WHERE id_status IN (1,2,7,8,".$red_zahtjev->status.") ORDER BY id_status";
	$query_statusi = mysql_query($sql_statusi) or die( "Greska: sql_statusi = $sql_statusi ".mysql_error());

	while($red_statusi = mysql_fetch_object($query_statusi)){

		$statusi[$i][0] = $red_statusi->id_status;
		$statusi[$i][1] = $red_statusi->status;
		$i++;
	}	
	
// pomocni niz sa svim podacima o komentaru
    $komentari = array();
	$i=0;
    $sql_komentari = "SELECT concat(prezime, ' ',ime) ime, datum_komentara, replace(komentar,char(10),'<br>') komentar FROM komentari, korisnici"
       ." WHERE komentari.korisnik = korisnici.id_korisnik AND id_zahtjev = ".$_GET['z']." ORDER BY datum_komentara;";
	
	$query_komentari = mysql_query($sql_komentari) or die( "Greska: sql_komentari = $sql_komentari ".mysql_error());
	
	
	while($red_komentari = mysql_fetch_object($query_komentari)){

		$komentari[$i][0] = $red_komentari->ime;
		$komentari[$i][1] = $red_komentari->datum_komentara;
		$komentari[$i][2] = $red_komentari->komentar;
		
		$i++;
	}			
// pomocni niz promjena zahtjeva
    $promjene = array();
	$i=0;
    $sql_promjene = "SELECT tip_promjene, stara_vrijednost, nova_vrijednost, datum_promjene, user_promijenio FROM istorija_zahtjeva WHERE id_zahtjev = ".$_GET['z']." ORDER BY datum_promjene";
    $query_promjene = mysql_query($sql_promjene) or die( "Greska: sql_promjene = $sql_promjene ".mysql_error());

    while($red_promjene = mysql_fetch_object($query_promjene)){
      $promjene[$i][0] = $red_promjene->tip_promjene;
	  $promjene[$i][1] = $red_promjene->stara_vrijednost;
	  $promjene[$i][2] = $red_promjene->nova_vrijednost;
	  $promjene[$i][3] = $red_promjene->datum_promjene;
	  $promjene[$i][4] = korisnik_naziv($red_promjene->user_promijenio);	 
      $i++;
    }    	
?>

	 	<fieldset>
		
			<legend>Prilozi</legend>
<? 
    if (empty($prilozi))
			  echo " ";
	else{
?>								
		    <div id="zaglavlje">			
			<label3 for="login"><b>Ime Fajla</b></label3>
			</div>	
			
		    <div id="zaglavlje">
			<label4 for="login"><b>Korisnik</b></label4>
			</div>	
			
		    <div id="zaglavlje">
			<label5 for="login"><b>Datum</b></label5>				
			</div>		
								
			</br></br>
					
			<?php 
			
			foreach($prilozi AS $prilog){
			    $path_parts = pathinfo($prilog[1]);
			    echo "<div id=attachment></div>";
			    echo "<label3 for=login><a href = '".$prilog[1]."'>".$prilog[4]."</a></label3>";
				echo "<label4 for=login>".$prilog[2]."</label4>";
				echo "<label5 for=login>".date ( "d.m.Y H:i" , strtotime($prilog[3]))."</label5>";
				echo "</br></br>";
				echo "<div id=divider></div>";
				}	
	    }
				?>			
        <?php if($red_zahtjev->status != 7 AND $red_zahtjev->status != 8 AND $red_zahtjev->status != 8){ ?>	   	
			<div id="buttonpos"><input type=button onClick="location.href='index.php?s=dodavanje_priloga&z=<? echo $_GET['z']; ?>'" style="margin: -20px 0 0 287px;" class="button2" value='Dodaj prilog'></div>
		<?php } ?>			
			<br />
			
		</fieldset>
	 <br><br>

<form  action="<?php echo $_SERVER[REQUEST_URI];?>" method="post" name="insert">
<input type="hidden" name ="posted" value=1>
 	<fieldset>
		
		<legend>Fiksni podaci</legend>
			
		<label for="login">Naslov : </label>
		<label2 for="login"><? echo $red_zahtjev->naslov_zahtjeva; ?>&nbsp;</label2>			
		<div class="clear"></div>
			
		<label for="login">ID Zahtjev : </label>
		<label2 for="login"><? echo $red_zahtjev->id_zahtjev; ?>&nbsp;</label2>		
		<div class="clear"></div>			
			
		<label for="login">Datum zahtjeva : </label>
		<label2 for="login"><? echo date ( "d.m.Y" , strtotime($red_zahtjev->datum_zahtjeva)); ?>&nbsp;</label2>			
		<div class="clear"></div>
			
		<label for="login">Podnosilac zahtjeva : </label>
		<label2 for="login"><? echo $red_zahtjev->txt_podnosilac_zahtjeva; ?>&nbsp;</label2>			
		<div class="clear"></div>	

        <label for="login">Izvršioc : </label>
		<label2 for="login"><? echo $red_zahtjev->txt_serviser; ?>&nbsp;</label2>
		<div class="clear"></div>
			
		<label for="login">Lokacija : </label>
		<label2 for="login"><? echo $red_zahtjev->txt_lokacija_izvrsenja; ?>&nbsp;</label2>
		<div class="clear"></div>		
		
		<label for="login">Način realizacije :</label>
		<label2 for="login"><? echo $red_zahtjev->txt_nacin_realizacije; ?>&nbsp;</label2>			
		<div class="clear"></div>
			
		<label for="login">Krajnji rok : </label>
		<label2 for="login"><? if ($red_zahtjev->zadnji_rok != null) echo date ( "d.m.Y" , strtotime($red_zahtjev->zadnji_rok)); ?>&nbsp;</label2>			
		<div class="clear"></div>	
		
		<label for="login">Broj sati : </label>
		<label2 for="login"><? echo $red_zahtjev->broj_sati; ?>&nbsp;</label2>			
		<div class="clear"></div>
		
		<label for="password">Opis realizacije : </label>
		<label2 for="login"><? echo $red_zahtjev->opis_realizacije; ?>&nbsp;</label2>
		<div class="clear"></div>
		
				
			<br />
			
	</fieldset>
     <br><br>  
 	<fieldset>
		
		<legend>Promjenljivi podaci</legend>			
			
		<label for="password">Tip zahtjeva : </label>
			<SELECT name="tip_zahtjeva" id="tip_zahtjeva">
				<?php foreach($tipovi_zahtjeva AS $tip_zahtjeva){
				if ($red_zahtjev->tip_zahtjeva == $tip_zahtjeva[0]) 
				  echo "<OPTION SELECTED VALUE='".$tip_zahtjeva[0]."'> ".$tip_zahtjeva[1]."";
				else
				  echo "<OPTION VALUE='".$tip_zahtjeva[0]."'> ".$tip_zahtjeva[1]."";
				}	
				?>
			</SELECT>
			<div class="clear"></div>
			
			<label for="login">Vrsta zahtjeva : </label>
			<SELECT name="vrsta_zahtjeva" id="vrsta_zahtjeva"  >
				<?php foreach($vrsta_zahtjeva AS $vrsta_zahtjeva){
				if ($red_zahtjev->vrsta_zahtjeva == $vrsta_zahtjeva[0])
				  echo "<OPTION SELECTED VALUE='".$vrsta_zahtjeva[0]."'> ".$vrsta_zahtjeva[1]."";
				else
				  echo "<OPTION VALUE='".$vrsta_zahtjeva[0]."'> ".$vrsta_zahtjeva[1]."";
				}	
				?>
			</SELECT>
			<div class="clear"></div>
			
			<label for="password">Broj zahtjeva : </label>
			<input type = "text" name="broj_zahtjeva" id="broj_zahtjeva" value="<? echo $red_zahtjev->broj_zahtjeva_korisnika; ?>">
			<div class="clear"></div>
			
			<label for="password">Status : </label>
			<SELECT name="status" id="status">
				<?php foreach($statusi AS $status){
				if ($red_zahtjev->status == $status[0])
				  echo "<OPTION SELECTED VALUE='".$status[0]."'> ".$status[1]."";
				else
				  echo "<OPTION VALUE='".$status[0]."'> ".$status[1]."";
				}	
				?>
			</SELECT>
			<div class="clear"></div>
			
			<label for="login">Prioritet :</label>		    
			<SELECT name="prioritet" id="prioritet">
				<?php foreach($prioriteti AS $prioritet){
				if ($red_zahtjev->prioritet == $prioritet)
				  echo "<OPTION SELECTED VALUE='".$prioritet."'> ".$prioritet."";
				else
				  echo "<OPTION VALUE='".$prioritet."'> ".$prioritet."";
				}	
				?>
			</SELECT>			
		    <div class="clear"></div>
			
			
			
			<label for="password">Modul : </label>
			<SELECT name="modul" id="modul">			
				<?php foreach($moduli AS $modul){
				if ($red_zahtjev->modul == $modul[0])
				  echo "<OPTION SELECTED VALUE='".$modul[0]."'> ".$modul[1]."";
				else
				  echo "<OPTION VALUE='".$modul[0]."'> ".$modul[1]."";
				}	
				?>
			</SELECT>
			<div class="clear"></div>
			
			<label for="password">Preferirani rok : </label>
			<input type = "text" name="preferirani_rok" id="preferirani_rok" class="date_input" value="<? if (date ( "d.m.Y" , strtotime($red_zahtjev->preferirani_rok)) != '01.01.1970') echo date ( "d.m.Y" , strtotime($red_zahtjev->preferirani_rok)); ?>">
			<div class="clear"></div>					
			
			<label for="password">Opis problema : </label>
			<textarea name="opis_problema"><? echo $red_zahtjev->opis_zahtjeva; ?></textarea>
			<div class="clear"></div>
			
			<div class="clear"></div>
			</br>
			
		<?php if($red_zahtjev->status != 7 AND $red_zahtjev->status != 8 AND $red_zahtjev->status != 8){ ?>		
			
			<label for="password">&nbsp;</label>
			 <div id="buttonpos">
			       <input type="submit" style="margin: -20px 0 0 287px;" class="button2" name="commit" value="Sačuvaj promjene"/>	
			</div>
		<?php } ?>
			<div class="clear"></div>
		</form>				
	</fieldset>
	<br><br>

	<?
    if (empty($promjene))
			  echo " ";
	else{
?>		
	<fieldset>   
		
	<legend>Istorija promjena</legend>
							
		<div id="zaglavlje1">			
		<label10 for="login"><b>Vrsta promjene</b></label10>
		</div>	
			
		<div id="zaglavlje1">
		<label10 for="login"><b>Stara vrijednost</b></label10>
		</div>	
			
		<div id="zaglavlje1">
		<label10 for="login"><b>Nova vrijednost</b></label10>				
		</div>

		<div id="zaglavlje1">
		<label10 for="login"><b>Datum promjene</b></label10>				
		</div>

		<div id="zaglavlje1">
		<label10 for="login"><b>Korisnik</b></label10>				
		</div>		
								
		</br></br>
				
<?php 
			
			foreach($promjene AS $promjena){	
            if($promjena[0] =="Opis realizacije" || $promjena[0] =="Opis zahtjeva"){
                    
                }
            else{				    
			    echo "<label10 for=login>".$promjena[0]."</label10>";
				if($promjena[0] =="Krajnji rok" || $promjena[0] == "Preferirani rok"){
				    echo "<label10 for=login>".date ( "d.m.Y" , strtotime($promjena[1]))."</label10>";
					if($promjena[2] == "0000-00-00 00:00:00")
					  echo "<label10 for=login> </label10>";
					else
					  echo "<label10 for=login>".date ( "d.m.Y" , strtotime($promjena[2]))."</label10>";
				}
                elseif($promjena[0] =="Tip zahtjeva"){
                    echo "<label10 for=login>".tip_zahtjeva_naziv($promjena[1])."</label10>";
				    echo "<label10 for=login>".tip_zahtjeva_naziv($promjena[2])."</label10>";
                }
                elseif($promjena[0] =="Status"){
                    echo "<label10 for=login>".status_naziv($promjena[1])."</label10>";
				    echo "<label10 for=login>".status_naziv($promjena[2])."</label10>";
                }
                elseif($promjena[0] =="Nacin realizacije"){
                    echo "<label10 for=login>".realizacija_naziv($promjena[1])."</label10>";
				    echo "<label10 for=login>".realizacija_naziv($promjena[2])."</label10>";
                }
                elseif($promjena[0] =="Izvrsioc"){
                    echo "<label10 for=login>".korisnik_naziv($promjena[1])."</label10>";
				    echo "<label10 for=login>".korisnik_naziv($promjena[2])."</label10>";
                }
                elseif($promjena[0] =="Modul"){
                    echo "<label10 for=login>".modul_naziv($promjena[1])."</label10>";
				    echo "<label10 for=login>".modul_naziv($promjena[2])."</label10>";
                }
                elseif($promjena[0] =="Lokacija izvrsenja"){
                    echo "<label10 for=login>".lokacija_naziv($promjena[1])."</label10>";
				    echo "<label10 for=login>".lokacija_naziv($promjena[2])."</label10>";
                }                	
				else{
				    echo "<label10 for=login>".$promjena[1]."</label10>";
				    echo "<label10 for=login>".$promjena[2]."</label10>";
				}				
				echo "<label10 for=login>".date ( "d.m.Y H:i" , strtotime($promjena[3]))."</label10>";				    
				echo "<label10 for=login>".$promjena[4]."</label10>";				
				echo "</br></br>";
				echo "<div id=divider1></div>";
				}	
	        }
				?>

	</fieldset>	
	<br><br>
<?
}
?>	
	<fieldset>
		<legend>Komentari</legend>
		
		<form  action="<?php echo $_SERVER[REQUEST_URI];?>" method="post" name="insert">
        <input type="hidden" name ="posted" value=2>

		
		<?php 
		 if (empty($komentari))
			  echo " ";
	    else{
		
		foreach($komentari AS $komentar){
		$datum_komentar = date ( "d.m.Y H:i" , strtotime($komentar[1]));
		echo "<label for=login>".$komentar[0]." <BR>(".$datum_komentar.") :</label>";
		echo "<label2 for=login>".$komentar[2]."</label2>";			
		echo "<div class=clear></div>";
		}
		}
		?>
		
		<label for="password">Unesite komentar : </label>
		<textarea name="komentar"></textarea>
		<div class="clear"></div>
			
		
		</br>
		<?php if($red_zahtjev->status != 7 AND $red_zahtjev->status != 8 AND $red_zahtjev->status != 8){ ?>				
		<label for="password">&nbsp;</label>
		<div id="buttonpos">
	    <input type="submit" style="margin: -20px 0 0 287px;" class="button2" name="commit" value="Dodaj komentar"/>	
		</div>
		<?php } ?>	
		<div class="clear"></div>
		</form>	
	</fieldset>
    

