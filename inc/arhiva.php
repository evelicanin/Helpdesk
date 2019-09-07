
<script language="JavaScript">
<!--
function refresh()
{
window.location.href = unescape(window.location.pathname)+'?s=lista_zahtjeva';
}
setTimeout( "refresh()", 1000*60*5 ); // 1000 milisekundi
//-->
</script>
<?


  if ($tip_korisnika == 1){
  //vidi samo zahtjeve dodijeljenje njemu
  $sql_lista_zahtjeva = "SELECT grupe_korisnika.grupa_korisnika grupa_korisnika, id_zahtjev, broj_zahtjeva_korisnika, naslov_zahtjeva, datum_zahtjeva, moduli.modul modul, statusi.status status, prioritet, concat(korisnici.prezime,' ',korisnici.ime) txt_podnosilac_zahtjeva" 
. " FROM zahtjevi, korisnici, grupe_korisnika, moduli, statusi"
. " WHERE zahtjevi.podnosilac_zahtjeva = korisnici.id_korisnik"
. " AND korisnici.id_grupa_korisnika = grupe_korisnika.id_grupa_korisnika"
. " AND zahtjevi.modul = moduli.id_modul"
. " AND zahtjevi.status = statusi.id_status"
. " AND zahtjevi.serviser = ".korisnik_sifra($_SESSION['username'])
. " AND zahtjevi.status in (7,8,9)"
. " ORDER BY id_zahtjev desc";
}
elseif ($tip_korisnika == 2){
  //vidi sve zahtjeve
  $sql_lista_zahtjeva = "SELECT grupe_korisnika.grupa_korisnika grupa_korisnika, id_zahtjev, broj_zahtjeva_korisnika, naslov_zahtjeva, "
. " datum_zahtjeva, moduli.modul modul, statusi.status status, prioritet, concat(korisnici.prezime,' ',korisnici.ime) txt_podnosilac_zahtjeva, "
. " tipovi_zahtjeva.tip_zahtjeva, concat(korisnici2.prezime,' ',korisnici2.ime) txt_izvrsilac_zahtjeva" 
. " FROM zahtjevi "
. " LEFT JOIN korisnici ON"
. "     zahtjevi.podnosilac_zahtjeva = korisnici.id_korisnik"
. " LEFT JOIN grupe_korisnika ON"
. " 	korisnici.id_grupa_korisnika = grupe_korisnika.id_grupa_korisnika"
. " LEFT JOIN moduli ON"
. " 	zahtjevi.modul = moduli.id_modul"
. " LEFT JOIN statusi ON"
. " 	zahtjevi.status = statusi.id_status"
. " LEFT JOIN tipovi_zahtjeva ON"
. " 	zahtjevi.tip_zahtjeva = tipovi_zahtjeva.id_tip_zahtjeva"
. " LEFT JOIN korisnici korisnici2 ON"
. " 	zahtjevi.serviser = korisnici2.id_korisnik"
. " WHERE (zahtjevi.modul in (SELECT id_modul FROM admin_modul WHERE id_admin = ".korisnik_sifra($_SESSION['username']).") OR zahtjevi.serviser = ".korisnik_sifra($_SESSION['username']).")"
. " AND zahtjevi.status in (7,8,9)"
. " ORDER BY id_zahtjev desc";
}
elseif ($tip_korisnika == 3){
  //vidi zahtjeve kreirane unutar svoje grupe
  $sql_lista_zahtjeva = "SELECT grupe_korisnika.grupa_korisnika grupa_korisnika, id_zahtjev, broj_zahtjeva_korisnika, naslov_zahtjeva, datum_zahtjeva, moduli.modul modul, statusi.status status, prioritet, concat(korisnici.prezime,' ',korisnici.ime) txt_podnosilac_zahtjeva" 
. " FROM zahtjevi, korisnici, grupe_korisnika, moduli, statusi"
. " WHERE zahtjevi.podnosilac_zahtjeva = korisnici.id_korisnik"
. " AND korisnici.id_grupa_korisnika = grupe_korisnika.id_grupa_korisnika"
. " AND zahtjevi.modul = moduli.id_modul"
. " AND zahtjevi.status = statusi.id_status"
. " AND zahtjevi.podnosilac_zahtjeva IN (SELECT id_korisnik FROM korisnici WHERE id_grupa_korisnika = ".korisnik_grupa($_SESSION[username]).")"
. " AND zahtjevi.status in (7,8,9)"
. " ORDER BY id_zahtjev desc";
}
elseif ($tip_korisnika == 4){
  
  $sql_lista_zahtjeva = "SELECT grupe_korisnika.grupa_korisnika grupa_korisnika, id_zahtjev, broj_zahtjeva_korisnika, naslov_zahtjeva, datum_zahtjeva, moduli.modul modul, statusi.status status, prioritet, concat(korisnici.prezime,' ',korisnici.ime) txt_podnosilac_zahtjeva" 
. " FROM zahtjevi, korisnici, grupe_korisnika, moduli, statusi"
. " WHERE zahtjevi.podnosilac_zahtjeva = korisnici.id_korisnik"
. " AND korisnici.id_grupa_korisnika = grupe_korisnika.id_grupa_korisnika"
. " AND zahtjevi.modul = moduli.id_modul"
. " AND zahtjevi.status = statusi.id_status"
. " AND zahtjevi.podnosilac_zahtjeva = ".korisnik_sifra($_SESSION[username])
. " AND zahtjevi.status in (7,8,9)"
. " ORDER BY id_zahtjev desc";
}

  $query_lista_zahtjeva = mysql_query($sql_lista_zahtjeva) or die( "Greska sql_lista_zahtjeva = $sql_lista_zahtjeva ".mysql_error());
?>
<div id="container">
  <div id="demoo">
  <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
	  <tr>
		<th>Naziv Banke</th>
		<th>Broj zahtjeva</th>
		<th>Vaš broj</th>
		<th>Naslov zahtjeva</th>
		<th>Datum</th>
		<th>Modul</th>		
		<th>Prioritet</th>	
		<th>Status</th>
        <th>Podnosilac</th>	
		<th>Tip zahtjeva</th>
		<th>Izvršilac</th>		
	  </tr>
	</thead>
	<tbody>
	<?
	  while($red_lista_zahtjeva = mysql_fetch_object($query_lista_zahtjeva)){
        $timestamp = strtotime($red_lista_zahtjeva->datum_zahtjeva);
		$datum_zahtjeva = date ( "d.m.Y H:i" , $timestamp);
		
		if($tip_korisnika == 2){
			$tip_zahtjeva = $red_lista_zahtjeva->tip_zahtjeva;
			$izvrsilac = $red_lista_zahtjeva->txt_izvrsilac_zahtjeva;
		}else{
			$tip_zahtjeva = "";
			$izvrsilac = "";
		}
		
		echo "<tr class=gradeC>";
		echo "<td>$red_lista_zahtjeva->grupa_korisnika</td>";
		echo "<td width=10% class=sadrzaj>$red_lista_zahtjeva->id_zahtjev</td>";
		echo "<td width=10% class=sadrzaj>$red_lista_zahtjeva->broj_zahtjeva_korisnika</td>";
		echo "<td width=30% class=sadrzaj><a href = index.php?s=prikaz_zahtjeva&z=$red_lista_zahtjeva->id_zahtjev class=fixedTip title=\"Podnosilac: $red_lista_zahtjeva->txt_podnosilac_zahtjeva\">$red_lista_zahtjeva->naslov_zahtjeva</a></td>";
		echo "<td width=10% class=sadrzaj>$datum_zahtjeva</td>";
		echo "<td width=10% class=sadrzaj>$red_lista_zahtjeva->modul</td>";		
		echo "<td width=10% class=sadrzaj>$red_lista_zahtjeva->prioritet</td>";
		echo "<td width=20% class=sadrzaj>$red_lista_zahtjeva->status</td>";
		echo "<td width=20% class=sadrzaj>$red_lista_zahtjeva->txt_podnosilac_zahtjeva</td>";
		echo "<td width=20% class=sadrzaj>$tip_zahtjeva</td>";
		echo "<td width=20% class=sadrzaj>$izvrsilac</td>";
		echo "</tr>";
		
		$i++;
	  }
	  ?>
	</tbody>
  </table>
  </div>
</div>
