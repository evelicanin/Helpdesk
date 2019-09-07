<?php 


	//deklaracija varijabli	
	$lijepi_niz_error = array();
	$provjeri_password = array();
	$posted = $_POST['posted'];


if($posted == 1 && $_POST['password'] != '' && $_POST['password2'] != '' && $_POST['password_old'] != ''){
    $provjeri_password = checkPassword($_POST['password'], $_POST['password2']);
	if(!$provjeri_password[1]){		
	  $lijepi_niz_error['provjera_pass'] = $provjeri_password[0];			
	  $posted = 0;
	}else{
	   $sql_stari_pass = "SELECT password FROM korisnici WHERE id_korisnik = ".korisnik_sifra($_SESSION['username']).";";
       $query_stari_pass = mysql_query($sql_stari_pass) or die("Ne valja query sql_stari_pass $sql_stari_pass ".mysql_error());
       $red_stari_pass = mysql_fetch_object($query_stari_pass);	
	   $stari_stari_pass = $red_stari_pass->password;
	   
	    if($stari_stari_pass == md5($_POST['password_old'])){
			$novi_pass = md5($_POST['password']);
			$sql_update_zahtjev = "UPDATE korisnici SET password = '".$novi_pass."'"
				." WHERE id_korisnik = ".korisnik_sifra($_SESSION['username']).";";		
			$query_update_zahtjev = mysql_query($sql_update_zahtjev) or die("GRESKA: sql_update_zahtjev $sql_update_zahtjev ".mysql_error());
		?>
		<script language="JavaScript">
		<!--
		function refresh()
		{
		window.location.href = 'logout.php';
		}
		setTimeout( "refresh()", 500 ); // 1000 milisekundi
		//-->
		</script>
		<?
		}
		else{
		    $lijepi_niz_error['password_old'] = 'Stari password nije ispravan!';
		}
	}	

}
elseif($posted == 1 && ($_POST['password'] == '' || $_POST['password2'] == '' || $_POST['password_old'] == '')){
	$lijepi_niz_error['unos'] = 'Niste popunili sva obavezna polja!';
}
?>
	
	    <form  action='index.php?s=change_pass' method="post" >
	    <input type="hidden" name ="posted" value=1>
		
		<label for="password">Stara Lozinka :<font color='red'>*</font></label>
		    <input type="password" name="password_old" id="password_old" class="text-input">

		<div class="clear"></div>
		
		<label for="password">Nova Lozinka :<font color='red'>*</font></label>
		    <input type="password" name="password" id="password" class="text-input">

		<div class="clear"></div>

	
		<label for="password">Potvrditi Lozinku :<font color='red'>*</font></label>
		    <input type="password" name="password2" id="password2" class="text-input">

		<div class="clear"></div>		

		<label for="password">&nbsp;</label>
		    <input type="submit" class="button2" name="insert_button" value="Promijeni lozinku" id="submit_btn"  style="margin-right:135px" /> 
		<div class="clear"></div>	
		
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		

	</form>
	
	<div id="error_text">
<?php
if(isset($lijepi_niz_error['password2']))    
    echo "<label20 ><br><br><br><br><br><br>".$lijepi_niz_error['password2']."</label20>";  
elseif(isset($lijepi_niz_error['password']))
	echo "<label20 ><br><br><br><br><br><br>".$lijepi_niz_error['password']."</label20>"; 
elseif(isset($lijepi_niz_error['password_old']))
	echo "<label20 ><br><br><br><br><br><br>".$lijepi_niz_error['password_old']."</label20>"; 
elseif(isset($lijepi_niz_error['provjera_pass']))
	echo "<label20 ><br><br><br><br><br>".$lijepi_niz_error['provjera_pass']."</label20>"; 
elseif(isset($lijepi_niz_error['unos']))
	echo "<label20 ><br><br><br><br><br>".$lijepi_niz_error['unos']."</label20>";



		
	?>	
		
	</div>	
		
	


	
	
