<?
require('config.inc.php');
try
{
	$db = new PDO(BAZA.':host=localhost;dbname='.DB, LOGIN, PASSWORD);
}
catch (PDOException $e)
{
	print "Błąd połączenia z bazą!: " . $e->getMessage() . "<br/>";
	die();
}
class Form{
	// $method=(GET|POST), $action - handler formularza, $idkat - id kategorii do edycji, jak dodajemy to pomijamy
    public function kategoria($method, $action, $idkat="0")
    {
		global $db;
        $sql = $db->query('SELECT id_kategoria, nazwa FROM kategoria');
        if ($idkat!=0)
        {
            $s = $db->query('SELECT * FROM kategoria WHERE id_kategoria=\''.$idkat.'\'');
            $up = $s -> fetch();
        }
        echo '<form method=\''.$method.'\' action=\''.$action.'\' name="kategoriaForm">';
        echo '<table>';
        echo '<tr><td>Id nadkategorii:</td><td>';
        echo '<select name="idnadkat"><option value="0"';
		// Jeżeli chcemy edytować kategorie ($idkat!=0) i w danej kategorii nie ma nadkategorii to wyswietlamy 0
        if ($idkat!=0 && $up['id_nadkategoria']==0) echo ' selected="selected"';
        echo '>0</option>';
        foreach($sql as $row)
        {
            if ($idkat!=0 && $up['id_kategoria']!=$row['id_kategoria'])
            {
                echo '<option value="'.$row['id_kategoria'].'"';
				// Jezeli edytujemy i znaleźliśmy nadkategorie to wyświetlamy ją domyślnie
                if ($idkat!=0 && $up['id_nadkategoria']==$row['id_kategoria']) echo ' selected="selected"';
                echo '>'.$row['id_kategoria'].' - '.$row['nazwa'].'</option>';
            }
        }
        echo '</select></td></tr>';
        echo '<tr><td>Nazwa:</td><td><input type=text name=nazwa';
		// Jeżeli edytujemy to wpisujemy wartości z bazy danych do inputów
        if ($idkat!=0) echo ' value="'.$up['nazwa'].'"';
        echo'></td></tr>';
        echo '<tr><td>Nazwa skrócona:</td><td><input type=text name=nazwaSkrocona';
        if ($idkat!=0) echo ' value="'.$up['nazwa_skrocona'].'"';
        echo '></td></tr>';
        echo '<tr><td>Opis:</td><td><textarea rows=5 cols=40 name=opis>';
        if ($idkat!=0) echo $up['opis'];
        echo '</textarea></td></tr>';
        echo '<tr><td>Usun:</td><td><select name="usun"><option value="0"';
        if ($idkat!=0 && $up['usun']==0) echo ' selected="selected"';
        echo '>0</option><option value="1"';
        if ($idkat!=0 && $up['usun']==1) echo ' selected="selected"';
        echo '>1</option></select></td></tr>';
        echo '<tr><td>Ukryj:</td><td><select name="ukryj"><option value="0"';
        if ($idkat!=0 && $up['ukryj']==0) echo ' selected="selected"';
        echo '>0</option><option value="1"';
        if ($idkat!=0 && $up['ukryj']==1) echo ' selected="selected"';
        echo '>1</option></select></td></tr>';
        echo '<tr><td>Kolejność sortowania:</td><td><select name="kolejnosc_sort"><option value="0"';
        if ($idkat!=0 && $up['kolejnosc_sortowania']==0) echo ' selected="selected"';
        echo '>0</option><option value="1"';
        if ($idkat!=0 && $up['kolejnosc_sortowania']==1) echo ' selected="selected"';
        echo '>1</option></select></td></tr>';
        echo '<tr><td colspan=2><input type=submit value=Submit></td></tr>';
        if ($idkat!=0) echo '<tr><td><input type="hidden" name="idkat" value="'.$idkat.'"></td></tr>';
        echo '</table></form>';
echo '<br>';
 echo '<a href="index.php"> Cofnij do index </a>';
        $sql->closeCursor();
    }
    public function zadanie($method, $action, $zadid="0")
    {
		global $db;
        $sql = $db->query('SELECT id_kategoria, nazwa FROM kategoria');
        if ($zadid!=0)
        {
            $s = $db->query('SELECT * FROM zadanie WHERE id_zadanie=\''.$zadid.'\'');
            $up = $s->fetch();
            $kat = $db->query('SELECT * FROM zadanie_kategoria WHERE id_zadanie=\''.$zadid.'\'');
            $array = $kat->fetchAll(PDO::FETCH_ASSOC);
        }
        ?>

<form name="ZadForm" method="<?=$method?>" action="<?=$action?>">
<table><tr><td>Treśc:</td><td><textarea name="tresc" rows="10" cols="40">
<? if ($zadid!=0) echo trim($up['tresc']); ?>
</textarea></td></tr>
<tr><td>Rozwiązanie:</td><td><textarea name="rozwiazanie" rows="10" cols="40">
<? if ($zadid!=0) echo trim($up['rozwiazanie']); ?>
</textarea></td></tr>
<tr><td>Poziom trudności:</td><td><input type="text" name="poz_trudnosci"
<? if ($zadid!=0) echo 'value="'.$up['poziom_trudnosci'].'"'; ?>
></td></tr>
<tr><td>Kategoria/e</td><td><select name="kat[]" multiple="multiple">
<?

                foreach($sql as $row)
        {
            $selected=0;
            if ($zadid!=0)
            {
                foreach($array as $x)
                {
                    if ($row['id_kategoria']==$x['id_kategoria']) { $selected=1; break; }
                }
            }
            ?>
<option value="<?=$row['id_kategoria']?>"<? if ($zadid!=0 && $selected==1) echo ' selected="selected"'; ?>><?=$row['nazwa']?></option>
<?
              }

              ?>
</select>
<tr><td>Ukryj:</td><td><select name="ukryj"><option value="0"<?if ($zadid!=0 && $up['ukryj']==0) echo ' selected="selected"'; ?>>0</option><option value="1"<?if ($zadid!=0 && $up['ukryj']==1) echo ' selected="selected"'; ?>>1</option></select></td></tr>
<tr><td>Usuń:</td><td><select name="usun"><option value="0"<?if ($zadid!=0 && $up['usun']==0) echo ' selected="selected"'; ?>>0</option><option value="1"<?if ($zadid!=0 && $up['usun']==1) echo ' selected="selected"'; ?>>1</option></select></td></tr>
<tr><td colspan="2"><input type="submit" value="Submit"></td></tr></table>
<? if ($zadid!=0) echo '<input type="hidden" name="zadid" value="'.$zadid.'">'; ?>
</form>
<br>
<a href="index.php"> Cofnij do index </a>
<?
        $sql->closeCursor();
        if ($zadid!=0)
        {
            $kat->closeCursor();
            $s->closeCursor();
        }
    }
	public function loginForm($method, $action)
	{
		echo '<table><form method=\''.$method.'\' action=\''.$action.'\'>';
		echo '<tr><td>Login:</td><td><input type="text" name="login"></td></tr>';
		echo '<tr><td>Hasło:</td><td><input type="password" name="haslo"></td></tr>';
		echo '<tr><td colspan="2"><input type="submit" value="submit"></td></tr></form></table>';
	}

}
class SQL{
    public function kategoria_add($idNadKat, $nazwa, $nazwa_skr, $opis, $usun, $ukryj, $kolejn_sort)
    {
		global $db;
        $db->exec('INSERT INTO kategoria(id_nadkategoria, nazwa, nazwa_skrocona, opis, usun, ukryj, kolejnosc_sortowania) VALUES(\''.$idNadKat.'\', \''.$nazwa.'\', \''.$nazwa_skr.'\', \''.$opis.'\', \''.$usun.'\', \''.$ukryj.'\', \''.$kolejn_sort.'\')') or die(print_r($db->errorInfo(), true));

    }
    public function zadanie_add($tresc, $rozwiazanie, $poz_trudnosci, $kat, $ukryj, $usun, $id_osoba_autor)
    {
		global $db;
        $date = date('Y-m-d');
        $db->exec('INSERT INTO zadanie(tresc, rozwiazanie, data_dodania, data_modyfikacji, poziom_trudnosci, usun, ukryj, id_osoba_autor) VALUES(\''.$tresc.'\', \''.$rozwiazanie.'\', \''.$date.'\', \''.$date.'\', \''.$poz_trudnosci.'\', \''.$usun.'\', \''.$ukryj.'\', \''.$id_osoba_autor.'\')') or die(print_r($db->errorInfo(), true));
        $sql = $db->query('SELECT id_zadanie FROM zadanie WHERE data_dodania=\''.$date.'\' AND tresc=\''.$tresc.'\'') or die(print_r($db->errorInfo(), true));
        $row = $sql -> fetch();

        for ($i=0;$i<count($kat);$i++)
        {
            $sq = $db->query('SELECT kolejnosc_sortowania FROM kategoria WHERE id_kategoria=\''.$kat[$i].'\'') or die(print_r($db->errorInfo(), true));
            $w = $sq -> fetch();
            $db->exec('INSERT INTO zadanie_kategoria VALUES(\''.$kat[$i].'\', \''.$row['id_zadanie'].'\', \''.$w['kolejnosc_sortowania'].'\')') or die(print_r($db->errorInfo(), true));
        }

    }
    public function show_all()
    {
		global $db;
        $sql = $db->query('SELECT id_kategoria, nazwa, usun, ukryj FROM kategoria') or die(print_r($db->errorInfo(), true));
        echo '<h2>KATEGORIE</h2>';
        foreach($sql as $row)
        {
if((isset($_SESSION['ranga']) && $_SESSION['ranga']<4) || !isset($_SESSION['ranga'])){
if($row['usun']==0 && $row['ukryj']==0){
            echo '<a href="?showkat='.$row['id_kategoria'].'">'.$row['nazwa'].'</a>';
            echo '<br>';
        }}
else
{echo '<a href="?showkat='.$row['id_kategoria'].'">'.$row['nazwa'].'</a> | <a href="?editkatform='.$row['id_kategoria'].'">Edytuj</a>';
            echo '<br>';}
}        
$sql->closeCursor();
        $sql = $db->query('SELECT id_zadanie, usun, ukryj,id_osoba_autor FROM zadanie') or die(print_r($db->errorInfo(), true));
        echo '<h2>ZADANIA</h2>';
        foreach($sql as $row)
        {
if((isset($_SESSION['ranga']) && $_SESSION['ranga']<4) || !isset($_SESSION['ranga'])){
if($row['usun']==0 && $row['ukryj']==0){
            echo '<a href="?showzad='.$row['id_zadanie'].'">Zadanie numer:'.$row['id_zadanie'].'</a> ';
if((isset($_SESSION['ranga']) && $_SESSION['ranga']==3) && (isset($_SESSION['id']) && $row['id_osoba_autor']==$_SESSION['id']))
{
echo '| <a href="?editzadform='.$row['id_zadanie'].'">Edytuj</a>';
   }         echo '<br>';
        }}else
{ echo '<a href="?showzad='.$row['id_zadanie'].'">Zadanie numer:'.$row['id_zadanie'].'</a> | <a href="?editzadform='.$row['id_zadanie'].'">Edytuj</a>';
            echo '<br>';
}        }
        $sql->closeCursor();
echo '<br>';
 echo '<a href="index.php"> Cofnij do index </a>';
    }
    public function showkat($id)
    {
		global $db;
        $sql = $db->query('SELECT * FROM kategoria WHERE id_kategoria = \''.$id.'\'') or die(print_r($db->errorInfo(), true));
        $row = $sql -> fetch();
 echo '<table>';
        echo '<tr><td>Id kategorii:</td><td>'.$row['id_kategoria'].'</td></tr>';
        echo '<tr><td>Id nadkategorii:</td><td>'.$row['id_nadkategoria'].'</td></tr>';
        echo '<tr><td>Nazwa:</td><td>'.$row['nazwa'].'</td></tr>';
        echo '<tr><td>Nazwa skrócona:</td><td>'.$row['nazwa_skrocona'].'</td></tr>';
        echo '<tr><td>Opis:</td><td>'.nl2br($row['opis']).'</td></tr>';
        echo '<tr><td>Usun:</td><td>'.$row['usun'].'</td></tr>';
        echo '<tr><td>Ukryj:</td><td>'.$row['ukryj'].'</td></tr>';
        echo '<tr><td>Kolejnosc sortowania:</td><td>'.$row['kolejnosc_sortowania'].'</td></tr>';
        echo '</table>';
        echo '<br><br>';
echo '<br>';
 echo '<a href="?showall"> Cofnij </a>';
        $sql->closeCursor();
    }
    public function showzad($id)
    {
		global $db;
		$sql = $db->query('SELECT * FROM zadanie WHERE id_zadanie = \''.$id.'\'') or die(print_r($db->errorInfo(), true));
        $row = $sql -> fetch();
        echo '<table>';
        echo '<tr><td>Id zadania:</td><td>'.$row['id_zadanie'].'</td></tr>';
        echo '<tr><td>Tresc:</td><td>'.nl2br($row['tresc']).'</td></tr>';
if(isset($_SESSION['ranga']))
{
        echo '<tr><td>Rozwiazanie:</td><td>'.nl2br($row['rozwiazanie']).'</td></tr>';
}
        echo '<tr><td>Data Dodania:</td><td>'.$row['data_dodania'].'</td></tr>';
        echo '<tr><td>Data Modyfikacji:</td><td>'.$row['data_modyfikacji'].'</td></tr>';
        echo '<tr><td>Poziom trudnosci:</td><td>'.$row['poziom_trudnosci'].'</td></tr>';
        echo '<tr><td>Usun:</td><td>'.$row['usun'].'</td></tr>';
        echo '<tr><td>Ukryj:</td><td>'.$row['ukryj'].'</td></tr>';
        echo '</table>';
        echo '<br><br>';
echo '<br>';
 echo '<a href="?showall"> Cofnij </a>';
        $sql->closeCursor();
    }
    public function editkat($idkat, $idNadKat, $nazwa, $nazwa_skr, $opis, $usun, $ukryj, $kolejn_sort)
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
        $db->exec('UPDATE kategoria SET id_nadkategoria=\''.$idNadKat.'\', nazwa=\''.$nazwa.'\', nazwa_skrocona=\''.$nazwa_skr.'\', opis=\''.$opis.'\', usun=\''.$usun.'\', ukryj=\''.$ukryj.'\', kolejnosc_sortowania=\''.$kolejn_sort.'\' WHERE id_kategoria=\''.$idkat.'\'') or die(print_r($db->errorInfo(), true));
    }
    public function editzad($zadid, $tresc, $rozwiazanie, $poz_trudnosci, $kat, $ukryj, $usun, $id_osoba_autor)
    {
		global $db;
        $kitkat = $db->query('SELECT id_kategoria FROM zadanie_kategoria WHERE id_zadanie=\''.$zadid.'\'');
        $array = $kitkat->fetchAll(PDO::FETCH_ASSOC);
		// przejeżdżamy pętlą po wszystkich kategoriach powiązanych z zadaniem
        for ($i=0;$i<count($kat);$i++)
        {
            $add=0;
            $licz=0;
			// sprawdzamy czy po edycji wszystkie powiązania kategorii i zadania są w tabeli zadanie_kategoria
            foreach($array as $x)
            {
                if ($x['id_kategoria']==$kat[$i]) { $add=0; $licz++; break; }
                $add=1;
                $licz++;
            }
            if ($licz==0) $add=1;
			// jeżeli któregoś nie ma to dodajemy
            if ($add==1)
            {
                $sq = $db->query('SELECT kolejnosc_sortowania FROM kategoria WHERE id_kategoria=\''.$kat[$i].'\'') or die(print_r($db->errorInfo(), true));
                $w = $sq -> fetch();
                $db->exec('INSERT INTO zadanie_kategoria VALUES(\''.$kat[$i].'\', \''.$zadid.'\', \''.$w['kolejnosc_sortowania'].'\')') or die(print_r($db->errorInfo(), true));
            }
        }
		// jedziemy pętlą po wszystkich rekordach powiązanych z zadaniem z zadanie_kategoria
        foreach($array as $x)
        {
            $del=0;
			//sprawdzamy czy po edycji usunięto któreś powiązanie kategorii i zadania w tabeli zadanie_kategoria
            for ($i=0;$i<count($kat);$i++)
            {
                if ($kat[$i]==$x['id_kategoria']) { $del=0; break; }
                $del=1;
            }
			// jak w tabeli jest usunięte powiązanie to je usuwamy
            if ($del==1)
            {
                $db->exec('DELETE FROM zadanie_kategoria WHERE id_kategoria=\''.$x['id_kategoria'].'\' AND id_zadanie=\''.$zadid.'\'') or die(print_r($db->errorInfo(), true));
            }
        }
        $date = date('Y-m-d');
		// finalnie edytujemy zadanie
        $db->query('UPDATE zadanie SET tresc=\''.$tresc.'\', rozwiazanie=\''.$rozwiazanie.'\', data_modyfikacji=\''.$date.'\', poziom_trudnosci=\''.$poz_trudnosci.'\', usun=\''.$usun.'\', ukryj=\''.$ukryj.'\', id_osoba_autor=\''.$id_osoba_autor.'\' WHERE id_zadanie=\''.$zadid.'\'') or die(print_r($db->errorInfo(), true));
    }
	public function login($login, $password)
	{
		global $db;
		$s = $db->query('SELECT COUNT(*) FROM konto WHERE pseudonim=\''.$login.'\' AND haslo=PASSWORD(\''.$password.'\')');
		$c = $s->fetch();
		if($c[0]==0) header("Location: ?login&warn=Zły login lub hasło!");
		else if($c[0]>=1)
		{
			$s = $db->query('SELECT id_osoba, id_grupa FROM konto WHERE pseudonim=\''.$login.'\'') or die(print_r($db->errorInfo(), true));
			$e = $s->fetch();
			$_SESSION['login']=$login;
			$_SESSION['id']=$e['id_osoba'];
			$_SESSION['ranga']=$e['id_grupa'];
			header("Location: ?");
		}
	}
	public function logout()
	{
		session_unset();
		session_destroy();
		header("Location: ?");
	}
}
?>
