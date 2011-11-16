<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2">
<title></title>
</head>
<body>
<?
include("class.inc.php");
$form = new Form();
$sql = new SQL();
if(empty($_SERVER['QUERY_STRING']))
{	
	$form->kategoria('POST', '?dodkat');
	try
	{
		$db = new PDO(BAZA.':host=localhost;dbname='.DB, LOGIN, PASSWORD);
	}
	catch (PDOException $e)
	{
		print "Błąd połączenia z bazą!: " . $e->getMessage() . "<br/>";
	   	die();
	}
	$wynik = $db->exec($s);
	echo $wynik;
}
elseif(isset($_GET['dodkat']))
{
	$sql->kategoria_add($_POST['idnadkat'], $_POST['nazwa'], $_POST['nazwaSkrocona'], $_POST['opis'], $_POST['usun'], $_POST['ukryj'], $_POST['kolejn_sort']);
	
}
?>
</body>
</html>
