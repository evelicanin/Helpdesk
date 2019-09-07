<?php
//
$timeo_start = microtime(true);
ini_set("memory_limit","128M");
//

require("database.php");

$sql_lista_zahtjeva = "SELECT grupe_korisnika.grupa_korisnika grupa_korisnika, opis_zahtjeva, id_zahtjev, broj_zahtjeva_korisnika, naslov_zahtjeva, datum_zahtjeva, moduli.modul modul, statusi.status status, prioritet, concat(korisnici.prezime,' ',korisnici.ime) txt_podnosilac_zahtjeva" 
. " FROM zahtjevi, korisnici, grupe_korisnika, moduli, statusi"
. " WHERE zahtjevi.podnosilac_zahtjeva = korisnici.id_korisnik"
. " AND korisnici.id_grupa_korisnika = grupe_korisnika.id_grupa_korisnika"
. " AND zahtjevi.modul = moduli.id_modul"
. " AND korisnici.id_grupa_korisnika = 7"
. " AND zahtjevi.status = statusi.id_status"
. " ORDER BY broj_zahtjeva_korisnika";
$query_lista_zahtjeva = mysql_query($sql_lista_zahtjeva) or die( "Greska sql_lista_zahtjeva = $sql_lista_zahtjeva ".mysql_error());







$html = '
<table class="bpmTopnTailC"><thead>
<tr class="headerrow">
<td>Broj zahtjeva</td>
<td>Naslov zahtjeva</td>
<td>Opis zahtjeva</td>
<td>Status zahtjeva</td>
</tr>
</thead><tbody>
';


while($red_lista_zahtjeva = mysql_fetch_object($query_lista_zahtjeva)){

if ($i % 2){
$html .= '
<tr class="oddrow">
<td>'.$red_lista_zahtjeva->broj_zahtjeva_korisnika.'</td>
<td>'.$red_lista_zahtjeva->naslov_zahtjeva.'</td>
<td>'.$red_lista_zahtjeva->opis_zahtjeva.'</td>
<td>'.$red_lista_zahtjeva->status.'</td>
</tr>';
}else{
$html .= '
<tr class="evenrow">
<td>'.$red_lista_zahtjeva->broj_zahtjeva_korisnika.'</td>
<td>'.$red_lista_zahtjeva->naslov_zahtjeva.'</td>
<td>'.$red_lista_zahtjeva->opis_zahtjeva.'</td>
<td>'.$red_lista_zahtjeva->status.'</td>
</tr>';
}

$i++;
}

$html .= '
</tbody></table>';


//==============================================================
//==============================================================
//==============================================================

include("mpdf50/mpdf.php");

$mpdf=new mPDF('utf-8', 'A4-L','','',10,10,30,10); 

$mpdf->SetWatermarkImage('images/logo.png', 1, '', array(5,5));
$mpdf->showWatermarkImage = true;

$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);

$mpdf->Output('izvjestaj.pdf','D');
exit;

//==============================================================
//==============================================================
//==============================================================


?>
