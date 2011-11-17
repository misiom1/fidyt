<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
</head>
<body>
<?
require("class.inc.php");
$form = new Form();
$sql = new SQL();
if(empty($_SERVER['QUERY_STRING']))
{
?>
<a href="?dodkatform">Dodaj kategorie</a><br>
<a href="?dodzadform">Dodaj zadanie</a><br>
<a href="?showall">Pokaz wszystkie</a>
<?
}
elseif(isset($_GET['dodkatform']))
{
$form->kategoria('POST', '?dodkat');
}
elseif(isset($_GET['dodkat']))
{
$sql->kategoria_add($_POST['idnadkat'], $_POST['nazwa'], $_POST['nazwaSkrocona'], $_POST['opis'], $_POST['usun'], $_POST['ukryj'], $_POST['kolejnosc_sort']);
header("Location: ?showall");
}
elseif(isset($_GET['dodzadform']))
{
$form->zadanie('POST', '?dodzad');
}
elseif(isset($_GET['dodzad']))
{
$sql->zadanie_add($_POST['tresc'], $_POST['rozwiazanie'], $_POST['poz_trudnosci'], $_POST['kat'], $_POST['ukryj'], $_POST['usun']);
header("Location: ?showall");
}
elseif(isset($_GET['showall']))
{
$sql->show_all();
}
elseif(isset($_GET['showkat']))
{
$sql->showkat($_GET['showkat']);
}
elseif(isset($_GET['showzad']))
{
$sql->showzad($_GET['showzad']);
}
elseif(isset($_GET['editkatform']))
{
$form->kategoria('POST', '?editkat', $_GET['editkatform']);
}
elseif(isset($_GET['editkat']))
{
$sql->editkat($_POST['idkat'], $_POST['idnadkat'], $_POST['nazwa'], $_POST['nazwaSkrocona'], $_POST['opis'], $_POST['usun'], $_POST['ukryj'], $_POST['kolejnosc_sort']);
header("Location: ?showall");
}
elseif(isset($_GET['editzadform']))
{
$form->zadanie('POST', '?editzad', $_GET['editzadform']);
}
elseif(isset($_GET['editzad']))
{
$sql->editzad($_POST['zadid'], $_POST['tresc'], $_POST['rozwiazanie'], $_POST['poz_trudnosci'], $_POST['kat'], $_POST['ukryj'], $_POST['usun']);
header("Location: ?showall");
}
?>
</body>
</html>
