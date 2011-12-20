<!DOCTYPE HTML SYSTEM>
<html>
<head>
<link rel="stylesheet" href="plik.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
</head>
<body>
<?
session_start();
require("class.inc.php");
$form = new Form();
$sql = new SQL();
if(!isset($_SESSION['login']))
{
	echo 'Niezalogowany [<a href="?login">Zaloguj</a>]<br>';
}
else
{
	echo 'Zalogowany jako '.$_SESSION['login'].' [<a href="?logout">Wyloguj</a>]<br>';
}
if(empty($_SERVER['QUERY_STRING']))
{
if(!isset($_SESSION['login']))
{
?>
<a href="?showall">Pokaz wszystkie</a>
<?
}
else{
if  ($_SESSION['ranga']==4 )
{
?>

<a href="?dodkatform">Dodaj kategorie</a><br>
<?
}
if($_SESSION['ranga']>=2)
{
?>
<a href="?dodzadform">Dodaj zadanie</a><br>
<?
}
?>
<a href="?showall">Pokaz wszystkie</a>
<?
}}
elseif(isset($_GET['dodkatform']))
{
$form->kategoria('POST', '?dodkat');
}
elseif(isset($_GET['dodkat']))
{
$sql->kategoria_add($_POST['idnadkat'], $_POST['nazwa'], $_POST['nazwaSkrocona'], $_POST['opis'], $_POST['usun'], $_POST['ukryj'], $_POST['kolejnosc_sort']);
header("Location: index.php");
}
elseif(isset($_GET['dodzadform']))
{
$form->zadanie('POST', '?dodzad');
}
elseif(isset($_GET['dodzad']))
{
$sql->zadanie_add($_POST['tresc'], $_POST['rozwiazanie'], $_POST['poz_trudnosci'], $_POST['kat'], $_POST['ukryj'], $_POST['usun']);
header("Location: index.php");
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
elseif(isset($_GET['login']))
{
$form->loginForm('POST', '?loginpost');
if(isset($_GET['warn']))
{
	echo $_GET['warn'];
}
}
elseif(isset($_GET['loginpost']))
{
$sql->login($_POST['login'], $_POST['haslo']);
}
elseif(isset($_GET['logout']))
{
$sql->logout();
}
?>
</body>
</html>
