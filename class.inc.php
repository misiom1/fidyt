<?
require('config.inc.php');
class Form{
public $db;
public function kategoria($method, $action)
{
try
{
$db = new PDO(BAZA.':host=localhost;dbname='.DB, LOGIN, PASSWORD);
}
catch (PDOException $e)
{
print "Błąd poł±czenia z bazą!: " . $e->getMessage() . "<br/>";
     die();
}
$sql = $db->query('SELECT id_kategoria, nazwa FROM kategoria');

echo '<form method=\''.$method.'\' action=\''.$action.'\' name=kategoriaForm>';
echo '<table>';
echo '<tr><td>Id nadkategorii:</td><td>';
echo '<select name="idnadkat"><option value="0">0</option>';
foreach($sql as $row)
{
echo '<option value="'.$row['id_kategoria'].'">'.$row['id_kategoria'].' - '.$row['nazwa'].'</option>';
}
$sql->closeCursor();
echo '</select></td></tr>';
echo '<tr><td>Nazwa:</td><td><input type=text name=nazwa></td></tr>';
echo '<tr><td>Nazwa skrócona:</td><td><input type=text name=nazwaSkrocona></td></tr>';
echo '<tr><td>Opis:</td><td><textarea rows=5 cols=40 name=opis></textarea></td></tr>';
echo '<tr><td>Usun:</td><td><select name="usun"><option value=0>0</option><option value=1>1</option></select></td></tr>';
echo '<tr><td>Ukryj:</td><td><select name="ukryj"><option value=0>0</option><option value=1>1</option></select></td></tr>';
echo '<tr><td>Kolejnosc sortowania:</td><td><select name="kolejnosc_sort"><option value=0>0</option><option value=1>1</option></select></td></tr>';
echo '<tr><td colspan=2><input type=submit value=Submit></td></tr>';
echo '</table>';
}
public function zadanie($method, $action)
{
try
{
$db = new PDO(BAZA.':host=localhost;dbname='.DB, LOGIN, PASSWORD);
}
catch (PDOException $e)
{
print "Bł±d poł±czenia z baz±!: " . $e->getMessage() . "<br/>";
     die();
}
$sql = $db->query('SELECT id_kategoria, nazwa FROM kategoria');
?>
<form name="dodzadanie" method="<?=$method?>" action="<?=$action?>">
<table><tr><td>Treśc:</td><td><textarea name="tresc" rows="10" cols="40"></textarea></td></tr>
<tr><td>Rozwiązanie:</td><td><textarea name="rozwiazanie" rows="10" cols="40"></textarea></td></tr>
<tr><td>Poziom trudności:</td><td><input type="text" name="poz_trudnosci"></td></tr>
<tr><td>Kategoria/e</td><td><select name="kat[]" multiple="multiple">
<?
foreach($sql as $row)
{
?>
<option value="<?=$row['id_kategoria']?>"><?=$row['nazwa']?></option>
<?
}
$sql->closeCursor();
?>
</select>
<tr><td>Ukryj:</td><td><select name="ukryj"><option value=0>0</option><option value=1>1</option></select></td></tr>
<tr><td>Usuń:</td><td><select name="usun"><option value=0>0</option><option value=1>1</option></select></td></tr>
<tr><td colspan="2"><input type="submit" value="Submit"></td></tr>
</form>
<?
}

}
class SQL{
public function checkDbEngine($dbname, $user, $password)
{
if(function_exists('sqlsrv_connect') && sqlsrv_connect('localhost', $user, $password)) return 'mssql';
elseif(function_exists('mssql_connect') && mssql_connect('localhost', $user, $password)) return 'mssql';
elseif(mysql_connect('localhost', $user, $password)) return 'mysql';
}
public function kategoria_add($idNadKat, $nazwa, $nazwa_skr, $opis, $usun, $ukryj, $kolejn_sort)
{
try
{
$db = new PDO(BAZA.':host=localhost;dbname='.DB, LOGIN, PASSWORD);
}
catch (PDOException $e)
{
print "Błąd połączenia z bazą!: " . $e->getMessage() . "<br/>";
     die();
}
$db->exec('INSERT INTO kategoria(id_nadkategoria, nazwa, nazwa_skrocona, opis, usun, ukryj, kolejnosc_sortowania) VALUES(\''.$idNadKat.'\', \''.$nazwa.'\', \''.$nazwa_skr.'\', \''.$opis.'\', \''.$usun.'\', \''.$ukryj.'\', \''.$kolejn_sort.'\')') or die(print_r($db->errorInfo(), true));
}
public function zadanie_add($tresc, $rozwiazanie, $poz_trudnosci, $kat, $ukryj, $usun)
{
try
{
$db = new PDO(BAZA.':host=localhost;dbname='.DB, LOGIN, PASSWORD);
}
catch (PDOException $e)
{
print "Błąd połączenia z bazą!: " . $e->getMessage() . "<br/>";
     die();
}
$date = date('Y-m-d');
$db->exec('INSERT INTO zadanie(tresc, rozwiazanie, data_dodania, data_modyfikacji, poziom_trudnosci, usun, ukryj) VALUES(\''.$tresc.'\', \''.$rozwiazanie.'\', \''.$date.'\', \''.$date.'\', \''.$poz_trudnosci.'\', \''.$usun.'\', \''.$ukryj.'\')') or die(print_r($db->errorInfo(), true));
$sql = $db->query('SELECT id_zadanie FROM zadanie WHERE data_dodania=\''.$date.'\' AND tresc=\''.$tresc.'\'') or die(print_r($db->errorInfo(), true));
$row = $sql -> fetch();

for($i=0;$i<count($kat);$i++)
{
$sq = $db->query('SELECT kolejnosc_sortowania FROM kategoria WHERE id_kategoria=\''.$kat[$i].'\'') or die(print_r($db->errorInfo(), true));
$w = $sq -> fetch();
$db->exec('INSERT INTO zadanie_kategoria VALUES(\''.$kat[$i].'\', \''.$row['id_zadanie'].'\', \''.$w['kolejnosc_sortowania'].'\')') or die(print_r($db->errorInfo(), true));
}
}
public function show_all()
{
try
{
$db = new PDO(BAZA.':host=localhost;dbname='.DB, LOGIN, PASSWORD);
}
catch (PDOException $e)
{
print "Błąd połączenia z bazą!: " . $e->getMessage() . "<br/>";
     die();
}
$sql = $db->query('SELECT id_kategoria, nazwa FROM kategoria');
echo '<h2>KATEGORIE</h2>';
foreach($sql as $row)
{
echo '<a href="?showkat='.$row['id_kategoria'].'">'.$row['nazwa'].'</a>';
echo '<br>';
}
$sql->closeCursor();
}
public function showkat($id)
{
try
{
$db = new PDO(BAZA.':host=localhost;dbname='.DB, LOGIN, PASSWORD);
}
catch (PDOException $e)
{
print "Błąd połączenia z bazą!: " . $e->getMessage() . "<br/>";
     die();
}
$sql = $db->query('SELECT id_kategoria, id_nadkategoria, nazwa, nazwa_skrocona, opis, usun, ukryj, kolejnosc_sortowania FROM kategoria WHERE id_kategoria = \''.$id.'\'') or die(print_r($db->errorInfo(), true));

echo '<table>';
echo '<tr><td>Id kategorii:</td><td>'.$sql['id_kategoria'].'</td></tr>';
echo '<tr><td>Id nadkategorii:</td><td>'.$sql['id_nadkategoria'].'</td></tr>';
echo '<tr><td>Nazwa:</td><td>'.$sql['nazwa'].'</td></tr>';
echo '<tr><td>Nazwa skrócona:</td><td>'.$sql['nazwa_skrocona'].'</td></tr>';
echo '<tr><td>Opis:</td><td><'.$sql['opis'].'</td></tr>';
echo '<tr><td>Usun:</td><td>'.$sql['usun'].'</td></tr>';
echo '<tr><td>Ukryj:</td><td>'.$sql['ukryj'].'</td></tr>';
echo '<tr><td>Kolejnosc sortowania:</td><td><'.$sql['kolejnosc_sortowania'].'</td></tr>';
echo '</table>';
echo '<br><br>';

$sql->closeCursor();
}
}
?>
