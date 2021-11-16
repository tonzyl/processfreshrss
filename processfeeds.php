<html> 
 <head> <h3>My automagic linkposting</h3></head> 

 <body> 
<div width="400">
 <?php
 
if ($_POST) {
$replylabel = 1;
$favlabel = 2;
$bmarklabel = 3;

// eerst achterhalen hoeveel posts we moeten maken
$tellermax = $_POST['totaal'];

$teller3 = 1;

while ($teller3 <= $tellermax) {

//zet de labelnamen
$labelnumber = "labelnumber".$teller3;
$auteur = "auteur".$teller3;
$link = "link".$teller3;
$title = "title".$teller3;
$motivatie = "motivatie".$teller3;
$quote = "quote".$teller3;

//haal de velden voor deze posting op
$lab = $_POST[$labelnumber];
$aut = $_POST[$auteur];
$url = $_POST[$link];
$naam = $_POST[$title];
$naamstart = substr($naam, 0, 4);
$motiv = $_POST[$motivatie];
$cit = $_POST[$quote];

if ($naamstart == "http" ) {
	$naam = "a posting";
}

//bouw nu de svg etc op
if ($lab == $replylabel) {
	$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="19" height="19"><path d="M576 240c0 115-129 208-288 208-48.3 0-93.9-8.6-133.9-23.8-40.3 31.2-89.8 50.3-142.4 55.7-5.2.6-10.2-2.8-11.5-7.7-1.3-5 2.7-8.1 6.6-11.8 19.3-18.4 42.7-32.8 51.9-94.6C21.9 330.9 0 287.3 0 240 0 125.1 129 32 288 32s288 93.1 288 208z"/></svg>';
	$descriptor = ' <em>In reply to <a href="'.$url.'" class="p-name u-in-reply-to">'.$naam.'</a> by '.$aut.'</em>';
}

if ($lab == $favlabel) {
	$svg ='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="15" height="12"><path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"/></svg>';
	$descriptor = ' <em>Favorited <a class="u-favorite-of p-name" href="'.$url.'">'.$naam.'</a> by '.$aut.'</em>';
}

if ($lab == $bmarklabel) {
	$svg='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="12" height="17"><path d="M0 512V48C0 21.49 21.49 0 48 0h288c26.51 0 48 21.49 48 48v464L192 400 0 512z"/></svg>';
	$descriptor = ' <em>Bookmarked <a class="u-bookmark-of" href="'.$url.'">'.$naam.'</a> (by '.$aut.')</em>';
}

$respons = $svg.$descriptor."<br/><p>".$motiv."</p>";
if ($cit) {
	$respons = $respons."<br/><blockquote>".$cit."<br/><br/>".$aut."</blockquote>";
}
$finalrespons = htmlentities($respons); //zodat het kopieerbaar is naar WordPress

echo "<h1>Nieuwe posting ".$teller3."</h1><br/>".$finalrespons; // evt hier naar WP schrijven ooit

$teller3 = $teller3 +1;
}
//alle output van het form is verwerkt en afgebeeld
echo "<br/>All done, hoera. Verwijder de labels uit FreshRSS";
}



if (!$_POST) { 
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "dbname";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT ton_entry.link, ton_entry.author, ton_entry.title, ton_entrytag.id_tag, uncompress(ton_entry.content_bin) as content FROM ton_entry JOIN ton_entrytag ON ton_entry.id=ton_entrytag.id_entry";
$result = $conn->query($sql);
$tellermax = $result->num_rows;
$teller = 1;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
  	$author[$teller] = substr($row["author"],1);
  	$title[$teller] = $row["title"];
  	$link[$teller] = $row["link"];
  	$label[$teller] = $row["id_tag"];
  	$blob[$teller] = $row["content"];
    
    $teller = $teller + 1;
  }
} else {
  echo "0 results";
}
$conn->close();

# voor opbouwen straks moeten we alle variabelen meegeven in het form, plus het aantal
?>
<form name="input_form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">

<?php
echo "<input type='hidden' name='totaal' value='". $tellermax."'>";
$teller2 = 1;
while ($teller2 <= $tellermax) {


 
echo "<h1> Item nummer:". $teller2." titel: " . $title[$teller2]. " - author: " . $author[$teller2]. " - link " . $link[$teller2]. " - label:". $label[$teller2]. "</h1><br>". $blob[$teller2];

echo "<br/>nu als formulier:<br/>";
echo "<input type='number' name='labelnumber". $teller2 . "' value='". $label[$teller2]."'>";
echo "<input type='text' name='auteur". $teller2 . "' value='". $author[$teller2]."'>";
echo "<input type='text' name='link". $teller2 . "' value='". $link[$teller2]."'>";
echo "<input type='text' name='title". $teller2 . "' value='". $title[$teller2]."'><br/>";
echo "Mijn motivatie of antwoord:<br/><textarea cols='150' rows='10' name='motivatie". $teller2 . "'></textarea><br/><br/>";
echo "Te gebruiken quote uit artikel:<br/><textarea cols='150' rows='10' name='quote". $teller2 . "'></textarea>";


$teller2 = $teller2 + 1;	
}


?> 
 <br> <input type="submit" name="submitButton" value="Verwerk">
 </form> 
<?php
}
?>
</div>
 </body></html>