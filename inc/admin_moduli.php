<?
if($_GET['korisnik']&&$_GET['m']&&$_GET['akcija']=='delete'){
    $sql_delete = "DELETE FROM admin_modul WHERE id_admin=".$_GET['korisnik']." AND id_modul=".$_GET['m'];
	$query_delete = mysql_query($sql_delete) or die( "Greska sql_delete = $sql_delete ".mysql_error());
	$redirekcija = $_SERVER[SCRIPT_NAME]."?s=admin_moduli&korisnik=".$_GET['korisnik'];
	echo "<meta http-equiv=\"Refresh\" content=\"0;url=$redirekcija\">";
}
if($_POST['posted'] == '1'){
	$sql_insert_modul = "INSERT INTO admin_modul (id_admin, id_modul) VALUES (".$_POST['izvrsioc'].", ".$_POST['modul'].");";
    $query_insert_modul = mysql_query($sql_insert_modul) or die("GRESKA: sql_insert_modul $sql_insert_modul ".mysql_error());
    echo "<meta http-equiv=\"Refresh\" content=\"0;url=$_SERVER[REQUEST_URI]\">";
}

// pomocni niz izvrsilaca za formu
	$izvrsioci = array();
	$i=0;
	$sql_izvrsioci = "SELECT id_korisnik, prezime, ime FROM korisnici WHERE tip_odgovorne_osobe = 2 ORDER BY prezime, ime";
	$query_izvrsioci = mysql_query($sql_izvrsioci) or die( "Greska: sql_izvrsioci = $sql_izvrsioci ".mysql_error());

	while($red_izvrsioci = mysql_fetch_object($query_izvrsioci)){

		$izvrsioci[$i][0] = $red_izvrsioci->id_korisnik;
		$izvrsioci[$i][1] = $red_izvrsioci->prezime." ".$red_izvrsioci->ime;
		$i++;
	}	

// pomocni niz modula za formu
if (isset($_GET['korisnik'])){
	$moduli = array();
	$i=0;
	$sql_moduli = "SELECT id_modul, modul FROM moduli 
		WHERE id_modul not in (SELECT id_modul FROM admin_modul WHERE id_admin = ".$_GET['korisnik'].") ORDER BY id_modul";
	$query_moduli = mysql_query($sql_moduli) or die( "Greska: sql_moduli = $sql_moduli ".mysql_error());

	while($red_moduli = mysql_fetch_object($query_moduli)){

		$moduli[$i][0] = $red_moduli->id_modul;
		$moduli[$i][1] = $red_moduli->modul;
		$i++;
	}	
}    	
?>

	 	

<form  action="<?php echo $_SERVER[REQUEST_URI];?>" method="post" name="insert">
<input type="hidden" name ="posted" value=1>
 	
 	<fieldset>
		
		<legend>Dodjela modula administratorima</legend>			
			
			
			<label for="password">Administrator : </label>
			<SELECT name="izvrsioc" id="izvrsioc" onchange="window.location='index.php?s=admin_moduli&korisnik='+this.value">
			  <OPTION SELECTED VALUE=''>
				<?php foreach($izvrsioci AS $izvrsioc){			
				if ($_GET['korisnik'] == $izvrsioc[0])					
				  echo "<OPTION SELECTED VALUE='".$izvrsioc[0]."'> ".$izvrsioc[1]."";
				else
				  echo "<OPTION VALUE='".$izvrsioc[0]."'> ".$izvrsioc[1]."";
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
			
			
			</br>
						
			<label for="password">&nbsp;</label>
			 <div id="buttonpos">
			       <input type="submit" style="margin: -20px 0 0 287px;" class="button2" name="commit" value="Pridruži modul"/>	
			</div>
			
			<div class="clear"></div>
		</form>				
	</fieldset>
	<br><br>	
	<fieldset>
		
		<legend>Pridruzeni moduli</legend>
<?php
$korisnik = $_GET["korisnik"];
IF ($korisnik != ''){
$i=0;
$sql_moduli2 = "SELECT a.id_modul id_modul, b.modul modul FROM admin_modul a, moduli b WHERE a.id_modul = b.id_modul AND a.id_admin = ".$korisnik." ORDER BY modul";
$query_moduli2 = mysql_query($sql_moduli2) or die( "Greska: sql_moduli2 = $sql_moduli2 ".mysql_error());

while($red_moduli2 = mysql_fetch_object($query_moduli2)){
    echo "<label for=password style=text-align:left;>$red_moduli2->modul</label>";
	echo "<label for=password style=text-align:left;><a href=?s=admin_moduli&akcija=delete&m=$red_moduli2->id_modul&korisnik=$korisnik><img src=images/delete.png></a></label>";	
	echo "<div class=clear></div>";	
    $i++;
}	
}
?>

<br><br>

	</fieldset>
    

