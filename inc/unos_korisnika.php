<?php 


	//deklaracija varijabli
	$lijepi_niz = array();
	$lijepi_niz_error = array();
	$provjeri_password = array();
	$posted = $_POST['posted'];
	
	
	if($posted == 1 || $checkPass){
	
		$lijepi_niz['grupa_korisnika'] = trim($_POST['grupa_korisnika']);	
		$lijepi_niz['grupa_privilegija'] = trim($_POST['grupa_privilegija']);
		$lijepi_niz['prezime'] = trim($_POST['prezime']); 
		$lijepi_niz['ime'] = trim($_POST['ime']); 
		$lijepi_niz['username'] = trim($_POST['username']); 	
		$lijepi_niz['password'] = trim($_POST['password']);		
		$lijepi_niz['password2'] = trim($_POST['password2']);
		$lijepi_niz['telefon'] = trim($_POST['telefon']);
		$lijepi_niz['email'] = trim($_POST['email']);		
		$lijepi_niz['mobitel'] = trim($_POST['mobitel']);		
		$lijepi_niz['tip_odgovorne_osobe'] = trim($_POST['tip_odgovorne_osobe']);

		
		$provjeri_password = checkPassword($lijepi_niz['password'], $lijepi_niz['password2']);
		if(!$provjeri_password[1]){		
			$checkPass = !$provjeri_password[1];
			//echo $provjeri_password[0]."<br/><br/>";
            $lijepi_niz_error['provjera_pass'] = $provjeri_password[0];			
			$posted = 0;
		}
			
	}
		

	
	if($posted == '1' && $lijepi_niz['prezime'] != '' && $lijepi_niz['ime'] != '' && $lijepi_niz['username'] != '' && $lijepi_niz['password'] != ''){
	
			
		$sql_insert_korisnik = " INSERT INTO korisnici (id_grupa_korisnika, id_grupa_privilegija, prezime, ime,  username, `password`, telefon, email, mobitel, tip_odgovorne_osobe) ";
		$sql_insert_korisnik .= " VALUES ( ".$lijepi_niz['grupa_korisnika'].", ".$lijepi_niz['grupa_privilegija'].", '".$lijepi_niz['prezime']."', '".$lijepi_niz['ime']."',  '".$lijepi_niz['username']."', md5('".$lijepi_niz['password']."'), '".$lijepi_niz['telefon']."', '".$lijepi_niz['email']."', '".$lijepi_niz['mobitel']."',  ".$lijepi_niz['tip_odgovorne_osobe'].") ";
		$query_insert_zahtjev = mysql_query($sql_insert_korisnik) or die("Ne valja sql_insert_korisnik $sql_insert_korisnik ".mysql_error());			

		echo 'Uspješno ste unijeli korisnika ';	
	
		
	}else{	
	
		if($posted == 1 && ($lijepi_niz['prezime'] == '' || $lijepi_niz['ime'] == '' || $lijepi_niz['username'] == '' || $lijepi_niz['password'] == ''))
			if($lijepi_niz['prezime'] == '' ){
				$lijepi_niz_error['prezime'] = 'Molim Vas unesite obavezno polje Prezime.';
			}if($lijepi_niz['prezime'] != '' && $lijepi_niz['ime'] == ''){		
				$lijepi_niz_error['ime'] = 'Molim Vas unesite obavezno polje Ime.';
			}if($lijepi_niz['prezime'] != '' && $lijepi_niz['ime'] != '' && $lijepi_niz['username'] == '' ){
				$lijepi_niz_error['username'] = 'Molim Vas unesite obavezno polje Username.';
			}if($lijepi_niz['prezime'] != '' && $lijepi_niz['ime'] != '' && $lijepi_niz['username'] == '' && $lijepi_niz['password'] ){
				$lijepi_niz_error['password'] = 'Molim Vas unesite obavezno polje Password.';
			}if($lijepi_niz['prezime'] != '' && $lijepi_niz['ime'] != '' && $lijepi_niz['username'] == '' && $lijepi_niz['password'] && $lijepi_niz['password2'] == ''  ){
				$lijepi_niz_error['password2'] = 'Molim Vas unesite obavezno polje Password2.';
			}
			
	
	
	
	//pomocni tipovi odgovornih osoba
	$odgovorne_osobe = array();
	$odgovorne_osobe[0][0] = '1';
	$odgovorne_osobe[0][1] = 'Serviser';
	$odgovorne_osobe[1][0] = '2';
	$odgovorne_osobe[1][1] = 'Administrator PING';
	$odgovorne_osobe[2][0] = '3';
	$odgovorne_osobe[2][1] = 'Administrator Grupe';
	$odgovorne_osobe[3][0] = '4';
	$odgovorne_osobe[3][1] = 'Korisnik Grupe';
	

	// pomocni niz grupe korisnika za formu
	$grupe_korisnika = array();
	$i=0;
	$sql_grupe_korisnika = "SELECT id_grupa_korisnika, grupa_korisnika FROM grupe_korisnika ORDER BY id_grupa_korisnika";
	$query_grupe_korisnika = mysql_query($sql_grupe_korisnika) or die( "Ne valja sql_grupe_korisnika = $sql_grupe_korisnika ".mysql_error());

	while($red_grupa_korisnika = mysql_fetch_object($query_grupe_korisnika)){

		$grupe_korisnika[$i][0] = $red_grupa_korisnika->id_grupa_korisnika;
		$grupe_korisnika[$i][1] = $red_grupa_korisnika->grupa_korisnika;
		$i++;
	}
	
	
	// pomocni niz grupe privilegija za formu
	$grupe_privilegija = array();
	$i=0;
	$sql_grupe_privilegija = "SELECT id_grupa_privilegija, grupa_privilegija FROM grupe_privilegija ORDER BY id_grupa_privilegija";
	$query_grupe_privilegija = mysql_query($sql_grupe_privilegija) or die( "Ne valja sql_grupe_privilegija = $sql_grupe_privilegija ".mysql_error());

	while($red_grupa_privilegija = mysql_fetch_object($query_grupe_privilegija)){

		$grupe_privilegija[$i][0] = $red_grupa_privilegija->id_grupa_privilegija;
		$grupe_privilegija[$i][1] = $red_grupa_privilegija->grupa_privilegija;
		$i++;
	}

//ukoliko se radi o editovanju

if($posted == 2 && $_POST['password'] == ''){
    $sql_update_zahtjev = "UPDATE korisnici SET id_grupa_korisnika = ".$_POST['grupa_korisnika'].", id_grupa_privilegija = ".$_POST['grupa_privilegija'].","
	    ." prezime = '".$_POST['prezime']."', ime = '".$_POST['ime']."', username = '".$_POST['username']."', telefon = '".$_POST['telefon']."',"
		." email = '".$_POST['email']."', mobitel = '".$_POST['mobitel']."', tip_odgovorne_osobe = ".$_POST['tip_odgovorne_osobe']
	    ." WHERE id_korisnik = ".$_POST['k'].";";		
	$query_update_zahtjev = mysql_query($sql_update_zahtjev) or die("GRESKA: sql_update_zahtjev $sql_update_zahtjev ".mysql_error());
}elseif($posted == 2 && $_POST['password'] != ''){
    $provjeri_password = checkPassword($_POST['password'], $_POST['password2']);
	if(!$provjeri_password[1]){		
	  $lijepi_niz_error['provjera_pass'] = $provjeri_password[0];			
	  $posted = 0;
	}else{
	   $novi_pass = md5($_POST['password']);
	   $sql_update_zahtjev = "UPDATE korisnici SET id_grupa_korisnika = ".$_POST['grupa_korisnika'].", id_grupa_privilegija = ".$_POST['grupa_privilegija'].","
	    ." prezime = '".$_POST['prezime']."', ime = '".$_POST['ime']."', username = '".$_POST['username']."', telefon = '".$_POST['telefon']."',"
		." email = '".$_POST['email']."', mobitel = '".$_POST['mobitel']."', tip_odgovorne_osobe = '".$_POST['tip_odgovorne_osobe']."', password = '".$novi_pass."'"
	    ." WHERE id_korisnik = ".$_POST['k'].";";		
	  $query_update_zahtjev = mysql_query($sql_update_zahtjev) or die("GRESKA: sql_update_zahtjev $sql_update_zahtjev ".mysql_error());
	}
	

}
   

if(isset($_GET['k'])){
  $sql_korisnik = "SELECT id_grupa_korisnika, id_grupa_privilegija, prezime, ime, username, telefon, email, mobitel, tip_odgovorne_osobe FROM korisnici WHERE id_korisnik = ".$_GET['k'];
  $query_korisnik = mysql_query($sql_korisnik) or die( "Greska: sql_korisnik = $sql_korisnik ".mysql_error());
  $red_korisnik = mysql_fetch_object($query_korisnik);

}
	
?>
	



	<? if(isset($_GET['k'])){ ?>
	    <form  action='index.php?s=unos_korisnika&k=<? echo $_GET['k']; ?>' method="post" >
	    <input type="hidden" name ="posted" value=2>
		<input type="hidden" name ="k" value=<? echo $_GET['k']; ?>>
	<? }else{ ?>
	    <form  action='index.php?s=unos_korisnika' method="post" >
	    <input type="hidden" name ="posted" value=1>
	<? } ?>
	
		
		<label for="password">Grupa korisnika : </label>		
			<SELECT name="grupa_korisnika" id="grupa_korisnika"  >
			
				<?php 
				foreach($grupe_korisnika AS $grupa_korisnika){
				  if ($red_korisnik->id_grupa_korisnika == $grupa_korisnika[0])
					echo "<OPTION SELECTED VALUE='".$grupa_korisnika[0]."'> ".$grupa_korisnika[1]."";
				  else
				    echo "<OPTION VALUE='".$grupa_korisnika[0]."'> ".$grupa_korisnika[1]."";												
				}
			
				?>			
		    </SELECT>		
		<div class="clear"></div>	



		<label for="password">Grupa privilegija : </label>		
			<SELECT name="grupa_privilegija" id="grupa_privilegija"  >
			
				<?php 
				foreach($grupe_privilegija AS $grupa_privilegija){
				  if ($red_korisnik->id_grupa_privilegija == $grupa_privilegija[0])
					echo "<OPTION SELECTED VALUE='".$grupa_privilegija[0]."'> ".$grupa_privilegija[1]."";
				  else
				    echo "<OPTION VALUE='".$grupa_privilegija[0]."'> ".$grupa_privilegija[1]."";												
				}
			
				?>			
		    </SELECT>		
		<div class="clear"></div>
		
		
		
		<?php
			$value = 'value = ""';
			if($posted == 1 || $checkPass){
				$value = 'value = "'.$lijepi_niz['prezime'].'"';
			}
            elseif(isset($_GET['k']))	
                $value = 'value = "'.$red_korisnik->prezime.'"';	
				
		?>
		<label for="password">Prezime :<font color='red'>*</font></label>
		    <input type="text" name="prezime" id="prezime" class="text-input"  <?php echo $value;  ?> >

		<div class="clear"></div>	

		
		<?php
			$value = 'value = ""';
			if($posted == 1 || $checkPass){
				$value = 'value = "'.$lijepi_niz['ime'].'"';
			}
            elseif(isset($_GET['k']))
                $value = 'value = "'.$red_korisnik -> ime.'"';			
		?>
		<label for="password">Ime :<font color='red'>*</font></label>
		    <input type="text" name="ime" id="ime" class="text-input"  <?php echo $value;  ?> >

		<div class="clear"></div>


		<?php
			$value = 'value = ""';
			if($posted == 1 || $checkPass){
				$value = 'value = "'.$lijepi_niz['username'].'"';
			}
            elseif(isset($_GET['k']))
                $value = 'value = "'.$red_korisnik -> username.'"';			
		?>
		<label for="password">Korisničko ime :<font color='red'>*</font></label>
		    <input type="text" name="username" id="username" class="text-input"  <?php echo $value;  ?> >

		<div class="clear"></div>	
		
		
		<?php
			$value = 'value = ""';
			if($posted == 1 || $checkPass){
				$value = 'value = "'.$lijepi_niz['password'].'"';
			}			
		?>
		<label for="password">Lozinka :<font color='red'>*</font></label>
		    <input type="text" name="password" id="password" class="text-input"  <?php echo $value;  ?> >

		<div class="clear"></div>



		<?php
			$value = 'value = ""';
			if($posted == 1 || $checkPass){
				$value = 'value = "'.$lijepi_niz['password2'].'"';
			}			
		?>
		<label for="password">Potvrditi Lozinku :<font color='red'>*</font></label>
		    <input type="text" name="password2" id="password2" class="text-input"  <?php echo $value;  ?> >

		<div class="clear"></div>

		
		
		
		<?php
			$value = 'value = ""';
			if($posted == 1 || $checkPass){
				$value = 'value = "'.$lijepi_niz['telefon'].'"';
			}	
            elseif(isset($_GET['k']))
                $value = 'value = "'.$red_korisnik -> telefon.'"';			
		?>
		<label for="password">Telefon :</label>
		    <input type="text" name="telefon" id="telefon" class="text-input"  <?php echo $value;  ?> >
		<div class="clear"></div>	

		
		<?php
			$value = 'value = ""';
			if($posted == 1 || $checkPass){
				$value = 'value = "'.$lijepi_niz['email'].'"';
			}
            elseif(isset($_GET['k']))
                $value = 'value = "'.$red_korisnik -> email.'"';			
		?>
		<label for="password">E-mail :</label>
		    <input type="text" name="email" id="email" class="text-input"  <?php echo $value;  ?> >
		<div class="clear"></div>	
	

		<?php
			$value = 'value = ""';
			if($posted == 1 || $checkPass){
				$value = 'value = "'.$lijepi_niz['mobitel'].'"';
			}	
            elseif(isset($_GET['k']))
                $value = 'value = "'.$red_korisnik -> mobitel.'"';			
		?>
		<label for="password">Mobitel :</label>
		    <input type="text" name="mobitel" id="mobitel" class="text-input"  <?php echo $value;  ?> >
		<div class="clear"></div>	


			
			
		<label for="password">Tip odgovorne osobe</label>
			<SELECT name="tip_odgovorne_osobe" id="tip_odgovorne_osobe">
				<?php foreach($odgovorne_osobe AS $odgovorna_osoba){
					if ($red_korisnik->tip_odgovorne_osobe == $odgovorna_osoba[0])
					echo "<OPTION SELECTED VALUE='".$odgovorna_osoba[0]."'> ".$odgovorna_osoba[1]."";
				  else
				    echo "<OPTION VALUE='".$odgovorna_osoba[0]."'> ".$odgovorna_osoba[1]."";
					}	
				?>
			</SELECT>
		<div class="clear"></div>		
	
	

	
		
		

		<label for="password">&nbsp;</label>
		    <input type="submit" class="button2" name="insert_button" value="Dodaj korisnika" id="submit_btn"  style="margin-right:135px" /> 
		<div class="clear"></div>	
		
	
		

	</form>
	
	<div id="error_text">
<?php
if(isset($lijepi_niz_error['password2']))    
    echo "<label20 ><br><br><br><br><br><br>".$lijepi_niz_error['password2']."</label20>"; 
elseif(isset($lijepi_niz_error['prezime']))  
 	echo "<label20 ><br><br><br><br><br><br>".$lijepi_niz_error['prezime']."</label20>"; 
elseif(isset($lijepi_niz_error['ime'])) 
	echo "<label20 ><p><br><br><br><br><br><br>".$lijepi_niz_error['ime']."</p></label20>"; 
elseif(isset($lijepi_niz_error['username']))
	echo "<label20 ><br><br><br><br><br><br>".$lijepi_niz_error['username']."</label20>"; 
elseif(isset($lijepi_niz_error['password']))
	echo "<label20 ><br><br><br><br><br><br>".$lijepi_niz_error['password']."</label20>"; 
elseif(isset($lijepi_niz_error['provjera_pass']))
	echo "<label20 ><br><br><br><br><br>".$lijepi_niz_error['provjera_pass']."</label20>"; 


} 	
		
	?>	
		
	</div>	
		
	


	
	
