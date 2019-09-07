<?  
if($_GET['k']){
    $sql_delete = "DELETE FROM korisnici WHERE id_korisnik=".$_GET['k'];
	$query_delete = mysql_query($sql_delete) or die( "Greska sql_delete = $sql_delete ".mysql_error());
}

  $sql_korisnici = "SELECT a.id_korisnik, a.tip_odgovorne_osobe, a.id_grupa_korisnika, concat(a.prezime, ' ', a.ime) ime, a.username, b.grupa_korisnika" 
. " FROM korisnici a, grupe_korisnika b"
. " WHERE a.id_grupa_korisnika = b.id_grupa_korisnika"
. " ORDER BY b.grupa_korisnika, a.tip_odgovorne_osobe, ime";

$query_korisnici = mysql_query($sql_korisnici) or die( "Greska sql_korisnici = $sql_korisnici ".mysql_error());

?>

<form action="<?php echo $_SERVER[REQUEST_URI];?>" method="post">
  <fieldset>
    <legend>Korisnici</legend>
    <table id="gradient-style" summary="Meeting Results">

     <thead>
      <tr>
            <th scope="col">Tip korisnika</th>
            <th scope="col">Grupa korisnika</th>
            <th scope="col">Prezime i ime</th>
            <th scope="col">Username</th>
            <th scope="col">Promijeni</th>
            <th scope="col">Izbriši</th>
      </tr>
    </thead>
    <tbody>
	<?
	  while($red_korisnici = mysql_fetch_object($query_korisnici)){   
        switch($red_korisnici->tip_odgovorne_osobe){
            case 1:
            $tip_korisnika = "Izvršilac";
            break;
            
            case 2:
            $tip_korisnika = "Administrator PING";
            break;

            case 3:
            $tip_korisnika = "Administrator grupe";
            break;

            case 4:
            $tip_korisnika = "Korisnik grupe";
            break;
        }			
        echo "<tr>";
		echo "<td>$tip_korisnika</td>";
        echo "<td>$red_korisnici->grupa_korisnika</td>";
        echo "<td>$red_korisnici->ime</td>";
		echo "<td>$red_korisnici->username</td>";

        echo "<td><a href=?s=unos_korisnika&k=$red_korisnici->id_korisnik><img src=images/edit.png></a></td>";
        echo "<td><a href=?s=korisnici&k=$red_korisnici->id_korisnik><img src=images/delete.png></a></td>";
        echo "</tr>";
	} ?>
      </tbody>
   </table>    
    <br />
    <input type="button" 
           style="margin: -40px 25px 10px 227px;" 
           class="button_2" 
           name="commit" 
		   onClick="parent.location='?s=unos_korisnika'"
           value="Dodaj korisnika"/>
		  
  </fieldset>

  </form>


