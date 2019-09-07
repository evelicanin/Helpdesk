<?php



	//deklaracija varijabli
	$lijepi_niz = array();
	$lijepi_niz_error = array();
	$posted = $_POST['posted'];
	
	if($posted == 1){	
		$lijepi_niz['vrsta_zahtjeva'] = trim($_POST['vrsta_zahtjeva']);	
		$lijepi_niz['broj_zahtjeva_korisnika'] = trim($_POST['broj_zahtjeva_korisnika']);
		$lijepi_niz['tip_zahtjeva'] = trim($_POST['tip_zahtjeva']); 
		$lijepi_niz['modul'] = trim($_POST['modul']); 
		$lijepi_niz['prioritet'] = trim($_POST['prioritet']); 	
		$lijepi_niz['preferirani_rok'] = trim($_POST['preferirani_rok']);
		$lijepi_niz['naslov_zahtjeva'] = addslashes(trim($_POST['naslov_zahtjeva']));
		$lijepi_niz['opis_zahtjeva'] = addslashes(trim($_POST['opis_zahtjeva']));		
	}
	
	
	if($posted == '1' && $lijepi_niz['naslov_zahtjeva'] != '' && $lijepi_niz['modul'] != '' && $lijepi_niz['tip_zahtjeva'] != '' && $lijepi_niz['opis_zahtjeva'] != ''){
	

		$preferirani_rok = make_my_time($lijepi_niz['preferirani_rok']);	
		$podnosilac_zahtjeva = korisnik_sifra($_SESSION['username']);			
        
		$trenutni_datum = daj_datum();
		
		$sql_odg_osoba = "SELECT korisnik FROM korisnici_moduli WHERE modul = ".$lijepi_niz['modul'];
	    $query_odg_osoba = mysql_query($sql_odg_osoba) or die( "Ne valja sql_odg_osoba = $sql_odg_osoba ".mysql_error());
	    $red_odg_osoba = mysql_fetch_object($query_odg_osoba);
		$odg_osoba = $red_odg_osoba -> korisnik;
		
		$sql_insert_zahtjev = " INSERT INTO zahtjevi (podnosilac_zahtjeva, vrsta_zahtjeva, broj_zahtjeva_korisnika, tip_zahtjeva,  modul, prioritet, preferirani_rok, naslov_zahtjeva, opis_zahtjeva, datum_zahtjeva, serviser) ";
		$sql_insert_zahtjev .= " VALUES ( $podnosilac_zahtjeva,'".$lijepi_niz['vrsta_zahtjeva']."','".$lijepi_niz['broj_zahtjeva_korisnika']."', '".$lijepi_niz['tip_zahtjeva']."',".$lijepi_niz['modul'].", '".$lijepi_niz['prioritet']."', '".$preferirani_rok."', '".$lijepi_niz['naslov_zahtjeva']."', '".$lijepi_niz['opis_zahtjeva']."', '".$trenutni_datum."', '".$odg_osoba."')";
		$query_insert_zahtjev = mysql_query($sql_insert_zahtjev) or die("Ne valja sql_insert_zahtjev $sql_insert_zahtjev ".mysql_error());			
        
		
		
		//salji email
		$sql_podnosilac = "SELECT id_zahtjev FROM zahtjevi WHERE podnosilac_zahtjeva = ".$podnosilac_zahtjeva." ORDER BY id_zahtjev DESC";
	    $query_podnosilac = mysql_query($sql_podnosilac) or die( "Ne valja sql_podnosilac = $sql_podnosilac ".mysql_error());
	    $red_podnosilac = mysql_fetch_object($query_podnosilac);
		$zahtjev = $red_podnosilac -> id_zahtjev;
	    $rez = posalji_email($zahtjev, $odg_osoba_nova, $pr);
		
		?>
        <script language="JavaScript">
		<!--
		function refresh()
		{
		window.location.href = unescape(window.location.pathname)+'?s=zahtjev_ok';
		}
		setTimeout( "refresh()", 500 ); // 1000 milisekundi
		//-->
		</script>

		<?		
		
		
	}else{	
	
		if($posted == 1 && ($lijepi_niz['naslov_zahtjeva'] == '' || $lijepi_niz['opis_zahtjeva'] == '' || $lijepi_niz['modul'] == '' || $lijepi_niz['tip_zahtjeva'] == '')){			
			if($lijepi_niz['naslov_zahtjeva'] == '' )
				$lijepi_niz_error['naslov_zahtjeva'] = 'Molim Vas unesite obavezno polje <b>Naslov zahtjeva</b>';
			elseif ($lijepi_niz['opis_zahtjeva'] == '' )
				$lijepi_niz_error['opis_zahtjeva'] = 'Molim Vas unesite obavezno polje <br><b>Opis zahtjeva</b>';
			elseif ($lijepi_niz['modul'] == '' )
				$lijepi_niz_error['modul'] = 'Molim Vas unesite obavezno polje <b>Modul</b>';
			elseif ($lijepi_niz['tip_zahtjeva'] == '' )
				$lijepi_niz_error['tip_zahtjeva'] = 'Molim Vas unesite obavezno polje <br><b>Tip zahtjeva</b>';
			
		}
	
	//pomocni niz prioritet
	$prioriteti = array();
	$prioriteti[0][0] = 'NISKI';
	$prioriteti[1][0] = 'SREDNJI';
	$prioriteti[2][0] = 'VISOKI';
	$prioriteti[3][0] = 'URGENTNI';
	
	
	//pomocni niz vrsta_zahtjeva
	$vrsta_zahtjeva = array();
	$vrsta_zahtjeva[0][0] = 'ASW';
	$vrsta_zahtjeva[0][1] = 'Aplikativni softver';
	
	$vrsta_zahtjeva[1][0] = 'SSW';
	$vrsta_zahtjeva[1][1] = 'Sistemski softver';
	
	$vrsta_zahtjeva[2][0] = 'HW';
	$vrsta_zahtjeva[2][1] = 'Hardware';




	// pomocni niz tipova zahtjeva za formu
	$tipovi_zahtjeva = array();
	$i=0;
	$sql_tipovi_zahtjeva = "SELECT id_tip_zahtjeva, tip_zahtjeva FROM tipovi_zahtjeva ORDER BY id_tip_zahtjeva";
	$query_tipovi_zahtjeva = mysql_query($sql_tipovi_zahtjeva) or die( "Ne valja sql_tipovi_zahtjeva = $sql_tipovi_zahtjeva ".mysql_error());

	while($red_tip_zahtjeva = mysql_fetch_object($query_tipovi_zahtjeva)){

		$tipovi_zahtjeva[$i][0] = $red_tip_zahtjeva->id_tip_zahtjeva;
		$tipovi_zahtjeva[$i][1] = $red_tip_zahtjeva->tip_zahtjeva;
		$i++;
	}


	// pomocni niz nacina realizacije za formu
	$nacini_realizacije = array();
	$i=0;
	$sql_nacini_realizacije = "SELECT id_nacin_realizacije, nacin_realizacije FROM nacini_realizacije ORDER BY id_nacin_realizacije";
	$query_nacini_realizacije = mysql_query($sql_nacini_realizacije) or die( "Ne valja sql_nacini_realizacije = $sql_nacini_realizacije 

".mysql_error());

	while($red_nacin_realizacije = mysql_fetch_object($query_nacini_realizacije)){

		$nacini_realizacije[$i][0] = $red_nacin_realizacije->id_nacin_realizacije;
		$nacini_realizacije[$i][1] = $red_nacin_realizacije->nacin_realizacije;
		$i++;
	}



	// pomocni niz korisnici za formu
	$korisnici = array();
	$i=0;
	$sql_korisnici = "SELECT id_korisnik, ime, prezime FROM korisnici ORDER BY id_korisnik";
	$query_korisnici = mysql_query($sql_korisnici) or die( "Ne valja sql_korisnici = $sql_korisnici ".mysql_error());

	while($red_korisnik = mysql_fetch_object($query_korisnici)){

		$korisnici[$i][0] = $red_korisnik->id_korisnik;
		$korisnici[$i][1] = $red_korisnik->ime." ".$red_korisnik->prezime;
		$i++;
	}
	


	// pomocni niz  za formu
	$kolacije_izvrsenja = array();
	$i=0;
	$sql_kolacije_izvrsenja = "SELECT id_lokacija_izvrsenja, lokacija_izvrsenja FROM lokacije_izvrsenja ORDER BY id_lokacija_izvrsenja";
	$query_kolacije_izvrsenja = mysql_query($sql_kolacije_izvrsenja) or die( "Ne valja sql_kolacije_izvrsenja = $sql_kolacije_izvrsenja 

".mysql_error());

	while($red_lokacija_izvrsenja = mysql_fetch_object($query_kolacije_izvrsenja)){

		$lokacije_izvrsenja[$i][0] = $red_lokacija_izvrsenja->id_lokacija_izvrsenja;
		$lokacije_izvrsenja[$i][1] = $red_lokacija_izvrsenja->lokacija_izvrsenja;
		$i++;
	}



	// pomocni niz moduli za formu
	$moduli = array();
	$i=0;
	$grupa_korisnika = korisnik_grupa($_SESSION['username']);
	$sql_moduli = "SELECT a.id_modul, a.modul FROM moduli a, grupa_modul b WHERE a.id_modul = b.id_modul AND b.id_grupa = $grupa_korisnika ORDER BY modul";
	$query_moduli = mysql_query($sql_moduli) or die( "Ne valja sql_moduli = $sql_moduli ".mysql_error());

	while($red_modul = mysql_fetch_object($query_moduli)){

		$moduli[$i][0] = $red_modul->id_modul;
		$moduli[$i][1] = $red_modul->modul;
		$i++;
	}

	
	?>
    
	<form  action='index.php?s=unos_zahtjeva' method="post" name="insert" onsubmit="javascript:document.insert.submit_btn.disabled=true">	
		<input type="hidden" name ="posted" value=1>	
		
		<label for="password">Vrsta zahtjeva : </label>		
			<SELECT name="vrsta_zahtjeva" id="vrsta_zahtjeva"  >
			
				<?php 
				foreach($vrsta_zahtjeva AS $vrsta_zahtjeva){
					$selected = '';
					if($posted == 1){
						if($vrsta_zahtjeva[0] == $lijepi_niz['vrsta_zahtjeva'])
							$selected = 'SELECTED';
					}						
					echo "<OPTION VALUE='".$vrsta_zahtjeva[0]."' ".$selected."> ".$vrsta_zahtjeva[1]."";												
				}
			
				?>			
		    </SELECT>	

	
		<div class="clear"></div>		
		
		
		
		<?php
			$value = 'value = ""';
			if($posted == 1){
				$value = "value = '".$lijepi_niz['broj_zahtjeva_korisnika']."'";				
			}			
		?>
		<label for="password">Broj zahtjeva :</label>
		    <input type="text" name="broj_zahtjeva_korisnika" id="broj_zahtjeva_korisnika" class="text-input"  <?php echo $value;  ?> >
		<div class="clear"></div>		
			
			
			
		<label for="password">Tip zahtjeva :</label>
			<SELECT name="tip_zahtjeva" id="tip_zahtjeva">
				<OPTION SELECTED VALUE=''>
				<?php 
				foreach($tipovi_zahtjeva AS $tip_zahtjeva){
					$selected = '';
					if($posted == 1){
						if($tip_zahtjeva[0] == $lijepi_niz['tip_zahtjeva'])
							$selected = 'SELECTED';								
						}					
				echo "<OPTION VALUE='".$tip_zahtjeva[0]."' ".$selected."> ".$tip_zahtjeva[1]."";
				}				
				?>
			</SELECT>
		<div class="clear"></div>		
	
	
	
		<label for="password">Modul :</label>			
			<SELECT name="modul" id="modul">
			<OPTION SELECTED VALUE=''>
				<?php foreach($moduli AS $modul){
							$selected = '';
							if($posted == 1){
								if($modul[0] == $lijepi_niz['modul'])
									$selected = 'SELECTED';								
							}					
					echo "<OPTION VALUE='".$modul[0]."' ".$selected."> ".$modul[1]."";
				}	
				?>
			</SELECT>
		<div class="clear"></div>

		
		
		<label for="password">Prioritet :</label>
			<SELECT name="prioritet" id="prioritet">
				<?php foreach($prioriteti AS $prioritet){
					$selected = '';
					if($posted == 1){
						if($prioritet[0] == $lijepi_niz['prioritet'])
							$selected = 'SELECTED';							
					}					
				echo "<OPTION VALUE='".$prioritet[0]."' ".$selected."> ".$prioritet[0]."";
				}
				?>
			</SELECT>
		<div class="clear"></div>
		
		
		
		
		<?php
			$value = 'value = ""';
			if($posted == 1){
				$value = 'value = "'.$lijepi_niz['preferirani_rok'].'"';
			}			
		?>
		<label for="password">Preferirani rok :</label>
		    <input type = "text" name="preferirani_rok" id="preferirani_rok" class="date_input" <?php echo $value;  ?>>

		<div class="clear"></div>
		
					
		<label for="password">Naslov zahtjeva :<font color='red'>*</font></label>
		    <input type = "text" name="naslov_zahtjeva" id="naslov_zahtjeva">
	
		<div class="clear"></div>

		
		
		
		<?php		
			if($posted == 1){
				$value = 'value = "'.$lijepi_niz['opis_zahtjeva'].'"';
			}			
		?>			
		<label for="password">Opis zahtjeva :<font color='red'>*</font></label>
			
		    <textarea rows="15" cols="100" name="opis_zahtjeva" id="opis_zahtjeva" ><? echo $lijepi_niz['opis_zahtjeva']; ?></textarea> 
		

		<div class="clear"></div>
	
		
		

		<label for="password">&nbsp;</label>
		    <input type="submit" class="button" name="insert_button" value="Dodaj zahtjev" id="submit_btn"  style="margin-right:135px" /> 
		<div class="clear"></div>	 
		
	</form>
	<?php 
	   if(isset($lijepi_niz_error['naslov_zahtjeva']))
	     echo "<label20 ><br><br><br><br><br><br>".$lijepi_niz_error['naslov_zahtjeva']."</label20>"; 	
	   if(isset($lijepi_niz_error['opis_zahtjeva']))
	     echo "<label20 ><br><br><br><br><br><br>".$lijepi_niz_error['opis_zahtjeva']."</label20>"; 
	   if(isset($lijepi_niz_error['modul']))
	     echo "<label20 ><br><br><br><br><br><br>".$lijepi_niz_error['modul']."</label20>"; 
	   if(isset($lijepi_niz_error['tip_zahtjeva']))
	     echo "<label20 ><br><br><br><br><br>".$lijepi_niz_error['tip_zahtjeva']."</label20>"; 
	?>
<?php } ?>	
		
		
		
	

