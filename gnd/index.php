<?php
$BASE = '../..';
$TITLE = 'GND';
include "$BASE/header.php";
?>

<p>The <b>Integrated Authority File (GND)</b> is an authority file for Persons,
Corporate bodies, Conferences and Events, Geographic Information, Topics and
Works. It is operated cooperatively mostly by German libraries. Its content can
be used under <a href="http://creativecommons.org/publicdomain/zero/1.0/">CC
1.0</a> in multiple formats. GND is being mapped to several other knowledge
organization systems.</p>

<h3>Mappings from GND</h3>

<p>
 See the <a href="../">concordance registry</a> for 
 <ul>
   <li>GND-DDC mappings (project CrissCross)</li>
   <li>GND-LCSH mappings (project macs)</li>
   <li><strike>GND-RAMEAU mappings (project macs)</strike></li>
 </ul>
 Additional mappings are counted daily:
</p>


<?php
$mappings = [];
foreach (file('count-gnd-mappings.csv') as $line) {
    list($date, $code, $count) = explode(',',rtrim($line));
    if ($count) {
        $mappings[$code] = ['date' => $date, count => $count];
    }
}

?>
<table class="table sortable table-hover tablesorter tablesorter-gbv">
  <thead>
    <tr>
      <th class="text-right">Mappings</th>
      <th>KOS</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($mappings as $code => $m) { ?>
    <tr>
      <td class="text-right">
        <a href="http://swb.bsz-bw.de/DB=2.104/CMD?ACT=SRCHA&IKT=2016&TRM=<?=$code?>"><?=$m['count']?></a>
      </td>
      <td><?=$code?></td>
      <td><?=$m['date']?></td>
    </tr>
<?php } ?>
  </tbody>
</table>

<h3>Mappings from other systems</h3>

<h4>Wikidata Mappings</h4>
<p>See <a href="../wikidata">Wikidata concordances</a> for mappings from Wikidata to GND, harvested daily.</p>

<h4>RVK Mappings</h4>
<p>See the <a href="../">Concordance registry</a> for concordances between RVK and GND.</p>

<?php
include "$BASE/footer.php";
