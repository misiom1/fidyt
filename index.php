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
if (!isset($_SESSION['login']))
{
    echo 'Niezalogowany [<a href="?login">Zaloguj</a>]<br>';
}
else
{
    echo 'Zalogowany jako '.$_SESSION['login'].' [<a href="?logout">Wyloguj</a>]<br>';
}
if (empty($_SERVER['QUERY_STRING']))
{
    if (!isset($_SESSION['login']))
    {
        ?>
        <a href="?showall">Pokaz wszystkie</a>
        <?
    }
    else 
	{
        if  ($_SESSION['ranga']==4 )
        {
            ?>
            <a href="?dodkatform">Dodaj kategorie</a><br>
            <a href="?doduserform">Dodaj uzytkownika</a><br>
            <?
        }
        if ($_SESSION['ranga']>=2)
        {
            ?>
            <a href="?dodzadform">Dodaj zadanie</a><br>
            <?
        }
        ?>
        <a href="?showall">Pokaz wszystkie</a>
        <?
	}                
}
elseif(isset($_GET['doduserform']) && $_SESSION['ranga']==4)
{
    $form->user('POST', '?doduser');
}
elseif(isset($_GET['doduser']) && $_SESSION['ranga']==4)
{
    $sql->user_add($_POST['idgrupa'], $_POST['imie'], $_POST['nazwisko'], $_POST['pseudonim'], $_POST['email'], $_POST['haslo'], $_POST['opis'], $_POST['ban_data'], $_POST['ban_ile_dni']);
    header("Location: index.php");
}
elseif(isset($_GET['dodkatform']) && $_SESSION['ranga']==4)
{
    $form->kategoria('POST', '?dodkat');
}
elseif(isset($_GET['dodkat']) && $_SESSION['ranga']==4)
{
    $sql->kategoria_add($_POST['idnadkat'], $_POST['nazwa'], $_POST['nazwaSkrocona'], $_POST['opis'], $_POST['usun'], $_POST['ukryj'], $_POST['kolejnosc_sort']);
    header("Location: index.php");
}
elseif(isset($_GET['dodzadform']) && $_SESSION['ranga']>1)
{
    $form->zadanie('POST', '?dodzad');
}
elseif(isset($_GET['dodzad']) && $_SESSION['ranga']>1)
{
    $sql->zadanie_add($_POST['tresc'], $_POST['rozwiazanie'], $_POST['poz_trudnosci'], $_POST['kat'], $_POST['ukryj'], $_POST['usun'], $_POST['id_os_aut']);
    header("Location: index.php");
}
elseif(isset($_GET['zgloszenieform']) && $_SESSION['ranga']>=1)
{
    $form->zgloszenieForm('POST', '?zgloszenie', $_GET['zgloszenieform']);
}
elseif(isset($_GET['zgloszenie']) && $_SESSION['ranga']>1)
{
    $sql->zgloszenie($_POST['tresc'], $_POST['imie'], $_POST['email'], $_POST['idzadania']);
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
elseif(isset($_GET['showuser']) && $_SESSION['ranga']==4)
{
    $sql->showuser($_GET['showuser']);
}
elseif(isset($_GET['showzad']))
{
    $sql->showzad($_GET['showzad']);
	$form->komentarzForm('POST', '?showzad='.$_GET['showzad'].'&koment');

	$sql->showkoment($_GET['showzad']);
	if(isset($_GET['koment']))
	{
		$sql->koment($_GET['showzad'], $_SESSION['id'], $_POST['komentarz']);
		header('Location: ?showzad='.$_GET['showzad']);
	}
}
elseif(isset($_GET['editkatform']) && $_SESSION['ranga']==4)
{
    $form->kategoria('POST', '?editkat', $_GET['editkatform']);
}
elseif(isset($_GET['editkat']) && $_SESSION['ranga']==4)
{
    $sql->editkat($_POST['idkat'], $_POST['idnadkat'], $_POST['nazwa'], $_POST['nazwaSkrocona'], $_POST['opis'], $_POST['usun'], $_POST['ukryj'], $_POST['kolejnosc_sort']);
    header("Location: ?showall");
}
elseif(isset($_GET['edituserform']) && $_SESSION['ranga']==4)
{
    $form->user('POST', '?edituser', $_GET['edituserform']);
}
elseif(isset($_GET['edituser']) && $_SESSION['ranga']==4)
{
    $sql->edituser($_POST['idos'],$_POST['idgrupa'], $_POST['imie'], $_POST['nazwisko'], $_POST['pseudonim'], $_POST['email'], $_POST['haslo'], $_POST['opis'], $_POST['data_zalozenia'], $_POST['ban_data'], $_POST['ban_ile_dni']);
    header("Location: ?showall");
}
elseif(isset($_GET['deluser']) && $_SESSION['ranga']==4)
{
    $sql->deleteuser($_GET['deluser']);
    header("Location: ?showall");
}
elseif(isset($_GET['editzadform']) && $_SESSION['ranga']>1)
{
    $form->zadanie('POST', '?editzad', $_GET['editzadform']);
}
elseif(isset($_GET['editzad']) && $_SESSION['ranga']>1)
{
    $sql->editzad($_POST['zadid'], $_POST['tresc'], $_POST['rozwiazanie'], $_POST['poz_trudnosci'], $_POST['kat'], $_POST['ukryj'], $_POST['usun'], $_POST['id_os_aut']);
    header("Location: ?showall");
}
elseif(isset($_GET['login']))
{
    $form->loginForm('POST', '?loginpost');
    if (isset($_GET['warn']))
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

