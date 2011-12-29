<?
require('config.inc.php');
require_once('class.phpmailer.php');
try
{
	$db = new PDO(BAZA.':host=localhost;dbname='.DB, LOGIN, PASSWORD);
}
catch (PDOException $e)
{
	print "Blad polaczenia z baza!: " . $e->getMessage() . "<br/>";
	die();
}
class Form{
	// $method=(GET|POST), $action - handler formularza, $idkat - id kategorii do edycji, jak dodajemy to pomijamy
	public function kategoria($method, $action, $idkat="0")
	{
		global $db;
		if ($idkat!=0)
		{
			$s = $db->query('SELECT * FROM kategoria WHERE id_kategoria=\''.$idkat.'\'');
			$up = $s -> fetch();
		}
		echo '<form method=\''.$method.'\' action=\''.$action.'\' name="kategoriaForm">';
		echo '<table>';
		echo '<tr><td>Id nadkategorii:</td><td>';
		echo '<select name="idnadkat"><option value="0"';
		// Jezeli chcemy edytowac kategorie ($idkat!=0) i w danej kategorii nie ma nadkategorii to wyswietlamy 0
		if ($idkat!=0 && $up['id_nadkategoria']==0) echo ' selected="selected"';
		echo '>0</option>';
		$sql = $db->query('SELECT id_kategoria, nazwa FROM kategoria');
		foreach($sql as $row)
		{
			if ($idkat!=0 && $up['id_kategoria']!=$row['id_kategoria'])
			{
				echo '<option value="'.$row['id_kategoria'].'"';
				// Jezeli edytujemy i znalezlismy nadkategorie to wyswietlamy ja domyslnie
				if ($up['id_nadkategoria']==$row['id_kategoria']) echo ' selected="selected"';
				echo '>'.$row['id_kategoria'].' - '.$row['nazwa'].'</option>';
			}
			else
			{
				echo '<option value="'.$row['id_kategoria'].'">'.$row['id_kategoria'].' - '.$row['nazwa'].'</option>';
			}
		}
		echo '</select></td></tr>';
		echo '<tr><td>Nazwa:</td><td><input type=text name=nazwa';
		// Jezeli edytujemy to wpisujemy wartosci z bazy danych do inputow
		if ($idkat!=0) echo ' value="'.$up['nazwa'].'"';
		echo'></td></tr>';
		echo '<tr><td>Nazwa skrocona:</td><td><input type=text name=nazwaSkrocona';
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
		echo '<tr><td>Kolejnosc sortowania:</td><td><select name="kolejnosc_sort"><option value="0"';
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
	public function user($method, $action, $idos="0")
	{
		global $db;
		$sql = $db -> query('SELECT * FROM grupa');
		if ($idos!=0)
		{
			$s = $db->query('SELECT * FROM konto WHERE id_osoba=\''.$idos.'\'');
			$up = $s -> fetch();
		}
		echo '<form method=\''.$method.'\' action=\''.$action.'\' name="userForm">';
		echo '<table>';
		echo '<tr><td>Id grupy:</td><td>';
		echo '<select name="idgrupa">';
		foreach($sql as $row)
		{
			if ($idos!=0)
			{
				echo '<option value="'.$row['id_grupa'].'"';
				// Jezeli edytujemy i znalezlismy nadkategorie to wyswietlamy ja domyslnie
				if ($up['id_grupa']==$row['id_grupa']) echo ' selected="selected"';
				echo '>'.$row['id_grupa'].' - '.$row['nazwa'].'</option>';
			}
			else
			{
				echo '<option value="'.$row['id_grupa'].'">'.$row['id_grupa'].' - '.$row['nazwa'].'</option>';
			}
		}
		echo '</select></td></tr>';
		echo '<tr><td>Imie:</td><td><input type=text name=imie';
		// Jezeli edytujemy to wpisujemy wartosci z bazy danych do inputow
		if ($idos!=0) echo ' value="'.$up['imie'].'"';
		echo'></td></tr>';
		echo '<tr><td>Nazwisko:</td><td><input type=text name=nazwisko';
		if ($idos!=0) echo ' value="'.$up['nazwisko'].'"';
		echo '></td></tr>';
		echo '<tr><td>Pseudonim:</td><td><input type=text name=pseudonim';
		if ($idos!=0) echo ' value="'.$up['pseudonim'].'"';
		echo '></td></tr>';
		echo '<tr><td>Email:</td><td><input type=text name=email';
		if ($idos!=0) echo ' value="'.$up['email'].'"';
		echo '></td></tr>';
		echo '<tr><td>Haslo:</td><td><input type=password name=haslo';
		echo '></td></tr>';
		echo '<tr><td>Opis:</td><td><textarea rows=5 cols=40 name=opis>';
		if ($idos!=0) echo $up['opis'];
		echo '</textarea></td></tr>';
		echo '<tr><td>Data Zalozenia:</td><td><input type=date name=data_zalozenia';
		if ($idos!=0) echo ' value="'.$up['data_zalozenia'].'"';
		echo '></td></tr>';
		echo '<tr><td>Ban Data:</td><td><input type=date name=ban_data';
		if ($idos!=0) echo ' value="'.$up['ban_data'].'"';
		echo '> YYYY-MM-DD</td></tr>';
		echo '<tr><td>Ban Ile Dni:</td><td><input type=int name=ban_ile_dni';
		if ($idos!=0) echo ' value="'.$up['ban_ile_dni'].'"';
		echo '></td></tr>';
		echo '<tr><td colspan=2><input type=submit value=Submit></td></tr>';
		if ($idos!=0) echo '<tr><td><input type="hidden" name="idos" value="'.$idos.'"></td></tr>';
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
			if($up['id_osoba_autor']!=$_SESSION['id'] && $_SESSION['ranga']<=2)
				die("Nie masz uprawnieñ!");
			$kat = $db->query('SELECT * FROM zadanie_kategoria WHERE id_zadanie=\''.$zadid.'\'');
			$array = $kat->fetchAll(PDO::FETCH_ASSOC);
		}
		?>

		<form name="ZadForm" method="<?=$method?>" action="<?=$action?>">
		<table><tr><td>Tresc:</td><td><textarea name="tresc" rows="10" cols="40"><? if ($zadid!=0) echo nl2br(trim($up['tresc'])); ?></textarea></td></tr>
		<tr><td>Rozwiazanie:</td><td><textarea name="rozwiazanie" rows="10" cols="40"><? if ($zadid!=0) echo trim($up['rozwiazanie']); ?></textarea></td></tr>
		<tr><td>Poziom trudnosci:</td><td><input type="text" name="poz_trudnosci"
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
		<?
		if($_SESSION['ranga']>=4)
		{
		?>
		<tr><td>Ukryj:</td><td><select name="ukryj"><option value="0"<?if ($zadid!=0 && $up['ukryj']==0) echo ' selected="selected"'; ?>>0</option><option value="1"<?if ($zadid!=0 && $up['ukryj']==1) echo ' selected="selected"'; ?>>1</option></select></td></tr>
		<tr><td>Usun:</td><td><select name="usun"><option value="0"<?if ($zadid!=0 && $up['usun']==0) echo ' selected="selected"'; ?>>0</option><option value="1"<?if ($zadid!=0 && $up['usun']==1) echo ' selected="selected"'; ?>>1</option></select></td></tr>
		<tr><td>ID autora:</td><td><input type="text" name="id_os_aut" value="<?= ($zadid!=0)?$up['id_osoba_autor']:$_SESSION['id'] ?>"></td></tr>
		<?
		}
		else
		{
			echo '<input type="hidden" name="ukryj" value="0">';
			echo '<input type="hidden" name="usun" value="0">';
			echo '<input type="hidden" name="id_os_aut" value="'.$_SESSION['id'].'">';
		}
		?>
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
		echo '<tr><td>Haslo:</td><td><input type="password" name="haslo"></td></tr>';
		echo '<tr><td colspan="2"><input type="submit" value="submit"></td></tr></form></table>';
	}
	public function komentarzForm($method, $action)
	{
		echo '<form method=\''.$method.'\' action=\''.$action.'\'>';
		echo '<table><tr><td>Komentarz:</td><td><textarea rows="10" cols="40" name="komentarz"></textarea></td></tr>';
		echo '<tr><td colspan="2"><input type="submit" name="submit" value="Submit"></td></tr></table>';
		echo '</form>';
	}

public function zgloszenieForm($method, $action, $idzadania)
{
echo '<table>';
echo '<tr>';
echo '<form method=\''.$method.'\' action=\''.$action.'\'>';
echo '<tr>';
echo '<td> Id zglaszanego zadania</td>';
echo '<td><input type="text" name=idzadania value='.$idzadania.' /></td>';
echo '<tr>';
echo '<td>Tre¶æ zg³aszanej uwagi/b³êdu</td>';
echo '<td><textarea name=tresc style= rows=5 cols=40></textarea></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Imiê, nazwisko lub nick</td>';
echo '<td><input type=text name=imie style=width: 250px></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Adres e-mail</td>';
echo '<td><input type=text name=email style=width: 250px></td>';
echo '</tr>';
echo '<tr>';
echo '<td>&nbsp;</td>';
echo '<td><input type= submit name= submit  value=Wy¶lij >&nbsp';
echo '<input type=reset value=Wyczy¶æ></td></form>';
echo '</tr>';
echo '</table>';
} 

}
class SQL{
public function zgloszenie($tresc, $imie, $email, $id_zad){
$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

$mail->IsSMTP(); // telling the class to use SMTP

try {
//  $mail->Host       = "mail.yourdomain.com"; // SMTP server
  $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
  $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
  $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
  $mail->Username   = "username@gmail.com";  // GMAIL username
  $mail->Password   = "password";            // GMAIL password
  $mail->AddAddress('adinorhaste@gmail.com', 'Administrator');
  $mail->SetFrom($email, $imie);
  $mail->Subject = 'Zgloszenie bledu do zadania o numerze id:'.$id_zad;
$mail->Body = $tresc;
  $mail->Send();
  echo "Wiadomo¶æ zosta³a wys³ana</p>\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
}
	public function kategoria_add($idNadKat, $nazwa, $nazwa_skr, $opis, $usun, $ukryj, $kolejn_sort)
	{
		global $db;
		$s = $db->prepare('INSERT INTO
		kategoria(id_nadkategoria, nazwa, nazwa_skrocona, opis, usun, ukryj, kolejnosc_sortowania)
		VALUES(
		:id_nadkat,
		:nazwa,
		:nazwa_skr,
		:opis,
		:usun,
		:ukryj,
		:kolejn_sort)');
		$s->bindValue(':id_nadkat', $idNadKat, PDO::PARAM_STR);
		$s->bindValue(':nazwa', $nazwa, PDO::PARAM_STR);
		$s->bindValue(':nazwa_skr', $nazwa_skr, PDO::PARAM_STR);
		$s->bindValue(':opis', $opis, PDO::PARAM_STR);
		$s->bindValue(':usun', $usun, PDO::PARAM_INT);
		$s->bindValue(':ukryj', $ukryj, PDO::PARAM_INT);
		$s->bindValue(':kolejn_sort', $kolejn_sort, PDO::PARAM_INT);
		$s->execute() or die(print_r($db->errorInfo(), true));

	}
	public function zadanie_add($tresc, $rozwiazanie, $poz_trudnosci, $kat, $ukryj, $usun, $id_osoba_autor)
	{
		global $db;
		$date = date('Y-m-d');
		$s = $db->prepare('INSERT INTO zadanie(tresc, rozwiazanie, data_dodania, data_modyfikacji, poziom_trudnosci, usun, ukryj, id_osoba_autor)
		VALUES(
		:tresc,
		:rozwiazanie,
		:date,
		:date,
		:poz_trudnosci,
		:usun,
		:ukryj,
		:id_os_aut)') or die(print_r($db->errorInfo(), true));
		$s->bindValue(':tresc', trim($tresc), PDO::PARAM_STR);
		$s->bindValue(':rozwiazanie', trim($rozwiazanie), PDO::PARAM_STR);
		$s->bindValue(':date', $date, PDO::PARAM_STR);
		$s->bindValue(':poz_trudnosci', $poz_trudnosci, PDO::PARAM_STR);
		$s->bindValue(':usun', $usun, PDO::PARAM_STR);
		$s->bindValue(':ukryj', $ukryj, PDO::PARAM_STR);
		$s->bindValue(':id_os_aut', $id_osoba_autor, PDO::PARAM_STR);
		$s->execute() or die(print_r($db->errorInfo(), true));
		$sql = $db->prepare('SELECT id_zadanie FROM zadanie WHERE data_dodania=:date AND tresc=:tresc');
		$sql->bindValue(':date', $date, PDO::PARAM_STR);
		$sql->bindValue(':tresc', $tresc, PDO::PARAM_STR);
		$sql->execute() or die(print_r($db->errorInfo(), true));
		$row = $sql -> fetch();

		for ($i=0;$i<count($kat);$i++)
		{
			$sq = $db->prepare('SELECT kolejnosc_sortowania FROM kategoria WHERE id_kategoria=:kat');
			$sq->bindValue(':kat', $kat[$i], PDO::PARAM_INT);
			$sq->execute() or die(print_r($db->errorInfo(), true));
			$w = $sq -> fetch();
			$x = $db->prepare('INSERT INTO zadanie_kategoria VALUES(
				:kat,
				:id_zad,
				:kolejn_sort)');
			$x->bindValue(':kat', $kat[$i], PDO::PARAM_INT);
			$x->bindValue(':id_zad', $row['id_zadanie'], PDO::PARAM_INT);
			$x->bindValue(':kolejn_sort', $w['kolejnosc_sortowania'], PDO::PARAM_INT);
			$x->execute() or die(print_r($db->errorInfo(), true));
		}

	}
	public function user_add($idGrupa, $imie, $nazwisko, $pseudonim, $email, $haslo, $opis, $ban_data, $ban_ile_dni)
	{
		global $db;
		$s=$db->prepare('INSERT INTO 
			konto(id_grupa, imie, nazwisko, pseudonim, email, haslo, opis, data_zalozenia, ban_data, ban_ile_dni) 
			VALUES(
			:idgrupa,
			:imie,
			:nazwisko,
			:pseudonim,
			:email,
			PASSWORD(:haslo),
			:opis,
			NOW(),
			:ban_data,
			:ban_ile_dni)') or die(print_r($db->errorInfo(), true));
		$s->bindValue(':idgrupa', $idGrupa, PDO::PARAM_INT);
		$s->bindValue(':imie', $imie, PDO::PARAM_STR);
		$s->bindValue(':nazwisko', $nazwisko, PDO::PARAM_STR);
		$s->bindValue(':pseudonim', $pseudonim, PDO::PARAM_STR);
		$s->bindValue(':email', $email, PDO::PARAM_STR);
		$s->bindValue(':haslo', $haslo, PDO::PARAM_STR);
		$s->bindValue(':opis', $opis, PDO::PARAM_STR);
		$s->bindValue(':ban_data', $ban_data, PDO::PARAM_STR);
		$s->bindValue(':ban_ile_dni', $ban_ile_dni, PDO::PARAM_STR);
		$s->execute() or die(print_r($db->errorInfo(), true));
	}

	public function show_all()
	{
		global $db;
		$sql = $db->query('SELECT id_kategoria, nazwa, usun, ukryj FROM kategoria') or die(print_r($db->errorInfo(), true));
		echo '<h2>KATEGORIE</h2>';
		foreach($sql as $row)
		{
			if((isset($_SESSION['ranga']) && $_SESSION['ranga']<4) || !isset($_SESSION['ranga']))
			{
				if($row['usun']==0 && $row['ukryj']==0)
				{
					echo '<a href="?showkat='.$row['id_kategoria'].'">'.$row['nazwa'].'</a>';
					echo '<br>';
				}
			}
			else if($_SESSION['ranga']==4)
			{
				echo '<a href="?showkat='.$row['id_kategoria'].'">'.$row['nazwa'].'</a> | <a href="?editkatform='.$row['id_kategoria'].'">Edytuj</a>';
				echo '<br>';
			}
		}        
		$sql->closeCursor();
		$sql = $db->query('SELECT id_zadanie, usun, ukryj,id_osoba_autor FROM zadanie') or die(print_r($db->errorInfo(), true));
		echo '<h2>ZADANIA</h2>';
		foreach($sql as $row)
		{
			if((isset($_SESSION['ranga']) && $_SESSION['ranga']<3) || !isset($_SESSION['ranga']))
			{
				if($row['usun']==0 && $row['ukryj']==0)
				{
					echo '<a href="?showzad='.$row['id_zadanie'].'">Zadanie numer:'.$row['id_zadanie'].'</a> ';
					if((isset($_SESSION['ranga']) && $_SESSION['ranga']==2) && (isset($_SESSION['id']) && $row['id_osoba_autor']==$_SESSION['id']))
					{
						echo '| <a href="?editzadform='.$row['id_zadanie'].'">Edytuj</a>';
					} 
					echo '<br>';
				}
			}
			else if($_SESSION['ranga']>=3 || $_SESSION['ranga']==$row['id_osoba_autor'])
			{ 
				echo '<a href="?showzad='.$row['id_zadanie'].'">Zadanie numer:'.$row['id_zadanie'].'</a> | <a href="?editzadform='.$row['id_zadanie'].'">Edytuj</a> | <a href="?zgloszenieform='.$row['id_zadanie'].'">Zg³o¶ uwagê/b³±d</a>';
				echo '<br>';
			}        
		}
		$sql->closeCursor();
		if($_SESSION['ranga']==4)
		{
			echo '<br>';
			$sql = $db->query('SELECT id_osoba, pseudonim FROM konto') or die(print_r($db->errorInfo(), true));
			echo '<h2>UZYTKOWNICY</h2>';
			foreach($sql as $row)
			{
				if(isset($_SESSION['ranga']) && $_SESSION['ranga']==4)
				{
					echo '<a href="?showuser='.$row['id_osoba'].'">Uzytkownik:'.$row['pseudonim'].'</a> ';
					echo '| <a href="?edituserform='.$row['id_osoba'].'">Edytuj</a>';
					echo '| <a href="?deluser='.$row['id_osoba'].'">Usun</a>';
				} 
				echo '<br>';
			}        
		}
		echo '<br>';
		echo '<a href="index.php"> Cofnij do index </a>';
	}
	public function showkat($id)
	{
		global $db;
		$sql = $db->prepare('SELECT * FROM kategoria WHERE id_kategoria = :id');
		$sql -> bindValue(':id', $id, PDO::PARAM_INT);
		$sql -> execute() or die(print_r($db->errorInfo(), true));
		$row = $sql -> fetch();
		echo '<table>';
		echo '<tr><td>Id kategorii:</td><td>'.$row['id_kategoria'].'</td></tr>';
		echo '<tr><td>Id nadkategorii:</td><td>'.$row['id_nadkategoria'].'</td></tr>';
		echo '<tr><td>Nazwa:</td><td>'.$row['nazwa'].'</td></tr>';
		echo '<tr><td>Nazwa skrocona:</td><td>'.$row['nazwa_skrocona'].'</td></tr>';
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
	public function showuser($id)
	{
		global $db;
		$sql = $db->prepare('SELECT * FROM konto WHERE id_osoba = :id') or die(print_r($db->errorInfo(), true));
		$sql -> bindValue(':id', $id, PDO::PARAM_INT);
		$sql -> execute() or die(print_r($db->errorInfo(), true));
		$row = $sql -> fetch();
		echo '<table>';
		echo '<tr><td>Id osoby:</td><td>'.$row['id_osoba'].'</td></tr>';
		echo '<tr><td>Id grupy:</td><td>'.$row['id_grupa'].'</td></tr>';
		echo '<tr><td>Imie:</td><td>'.$row['imie'].'</td></tr>';
		echo '<tr><td>Nazwisko:</td><td>'.$row['nazwisko'].'</td></tr>';
		echo '<tr><td>Pseudonim:</td><td>'.$row['pseudonim'].'</td></tr>';
		echo '<tr><td>Email:</td><td>'.$row['email'].'</td></tr>';
		echo '<tr><td>Haslo:</td><td>'.$row['haslo'].'</td></tr>';
		echo '<tr><td>Opis:</td><td>'.nl2br($row['opis']).'</td></tr>';
		echo '<tr><td>Data Zalozenia:</td><td>'.$row['data_zalozenia'].'</td></tr>';
		echo '<tr><td>Ban Data:</td><td>'.$row['ban_data'].'</td></tr>';
		echo '<tr><td>Ban Ile Dni:</td><td>'.$row['ban_ile_dni'].'</td></tr>';
		echo '</table>';
		echo '<br><br>';
		echo '<br>';
		echo '<a href="?showall"> Cofnij </a>';
		$sql->closeCursor();
	}
	public function showzad($id)
	{
		global $db;
		$sql = $db->prepare('SELECT * FROM zadanie WHERE id_zadanie = :id');
		$sql -> bindValue(':id', $id, PDO::PARAM_INT);
		$sql -> execute() or die(print_r($db->errorInfo(), true));
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
		//echo '<tr><td>Usun:</td><td>'.$row['usun'].'</td></tr>';
		//echo '<tr><td>Ukryj:</td><td>'.$row['ukryj'].'</td></tr>';
		echo '</table>';
		echo '<br><br>';
		echo '<br>';
		echo '<a href="?showall"> Cofnij </a>';
		$sql->closeCursor();
	}
	public function editkat($idkat, $idNadKat, $nazwa, $nazwa_skr, $opis, $usun, $ukryj, $kolejn_sort)
	{
		global $db;
		$s = $db->prepare('UPDATE kategoria SET 
			id_nadkategoria=:id_nadkat,
			nazwa=:nazwa,
			nazwa_skrocona=:nazwa_skr,
			opis=:opis,
			usun=:usun,
			ukryj=:ukryj,
			kolejnosc_sortowania=:kolejn_sort
			WHERE id_kategoria=:idkat');
		$s->bindValue(':id_nadkat', $idNadKat, PDO::PARAM_STR);
		$s->bindValue(':nazwa', $nazwa, PDO::PARAM_STR);
		$s->bindValue(':nazwa_skr', $nazwa_skr, PDO::PARAM_STR);
		$s->bindValue(':opis', $opis, PDO::PARAM_STR);
		$s->bindValue(':usun', $usun, PDO::PARAM_INT);
		$s->bindValue(':ukryj', $ukryj, PDO::PARAM_INT);
		$s->bindValue(':kolejn_sort', $kolejn_sort, PDO::PARAM_INT);
		$s->bindValue(':idkat', $idkat, PDO::PARAM_INT);
		$s->execute() or die(print_r($db->errorInfo(), true));
	}

	public function edituser($idos,$idGrupa, $imie, $nazwisko, $pseudonim, $email, $haslo, $opis, $data_zalozenia, $ban_data, $ban_ile_dni)
	{
		global $db;
		$s = $db->prepare('UPDATE konto SET
			id_grupa=:idgrupa,
			imie=:imie,
			nazwisko=:nazwisko,
			pseudonim=:pseudonim,
			email=:email,
			opis=:opis,
			data_zalozenia=:data_zalozenia,
			ban_data=:ban_data,
			ban_ile_dni=:ban_ile_dni
			WHERE id_osoba=:id_osoba');
		$s->bindValue(':idgrupa', $idGrupa, PDO::PARAM_INT);
		$s->bindValue(':imie', $imie, PDO::PARAM_STR);
		$s->bindValue(':nazwisko', $nazwisko, PDO::PARAM_STR);
		$s->bindValue(':pseudonim', $pseudonim, PDO::PARAM_STR);
		$s->bindValue(':email', $email, PDO::PARAM_STR);
		$s->bindValue(':opis', $opis, PDO::PARAM_STR);
		$s->bindValue(':data_zalozenia', $data_zalozenia, PDO::PARAM_STR);
		$s->bindValue(':ban_data', $ban_data, PDO::PARAM_STR);
		$s->bindValue(':ban_ile_dni', $ban_ile_dni, PDO::PARAM_STR);
		$s->bindValue(':id_osoba', $idos, PDO::PARAM_INT);
		$s->execute() or die(print_r($db->errorInfo(), true));
		if(!empty($haslo))
		{
			$s = $db->prepare('UPDATE konto SET haslo=PASSWORD(:haslo) WHERE id_osoba=:id_osoba');
			$s->bindValue(':haslo', $haslo, PDO::PARAM_STR);
			$s->bindValue(':id_osoba', $idos, PDO::PARAM_INT);
			$s->execute() or die(print_r($db->errorInfo(), true));
		}
	}
	public function editzad($zadid, $tresc, $rozwiazanie, $poz_trudnosci, $kat, $ukryj, $usun, $id_osoba_autor)
	{
		global $db;
		if($id_osoba_autor!=$_SESSION['id'] || $_SESSION['ranga']<=1)
			die("Nie masz uprawnieñ!");
		$kitkat = $db->prepare('SELECT id_kategoria FROM zadanie_kategoria WHERE id_zadanie=:zadid');
		$kitkat->bindValue(':zadid', $zadid, PDO::PARAM_INT);
		$kitkat->execute() or die(print_r($db->errorInfo(), true));
		$array = $kitkat->fetchAll(PDO::FETCH_ASSOC);
		// przejezdzamy petla po wszystkich kategoriach powiazanych z zadaniem
		for ($i=0;$i<count($kat);$i++)
		{
			$add=0;
			$licz=0;
			// sprawdzamy czy po edycji wszystkie powiazania kategorii i zadania sa w tabeli zadanie_kategoria
			foreach($array as $x)
			{
				if ($x['id_kategoria']==$kat[$i]) { $add=0; $licz++; break; }
				$add=1;
				$licz++;
			}
			if ($licz==0) $add=1;
			// jezeli ktoregos nie ma to dodajemy
			if ($add==1)
			{
				$sq = $db->prepare('SELECT kolejnosc_sortowania FROM kategoria WHERE id_kategoria=:kat');
				$sq->bindValue(':kat', $kat[$i], PDO::PARAM_INT);
				$sq->execute() or die(print_r($db->errorInfo(), true));
				$w = $sq -> fetch();
				$s = $db->prepare('INSERT INTO zadanie_kategoria
					VALUES(:kat,
					:zadid,
					:sort)') or die(print_r($db->errorInfo(), true));
				$s->bindValue(':kat', $kat[$i], PDO::PARAM_INT);
				$s->bindValue(':zadid', $zadid, PDO::PARAM_INT);
				$s->bindValue(':sort', $w['kolejnosc_sortowania'], PDO::PARAM_INT);
				$s->execute() or die(print_r($db->errorInfo(), true));
			}
		}
		// jedziemy petla po wszystkich rekordach powiazanych z zadaniem z zadanie_kategoria
		foreach($array as $x)
		{
			$del=0;
			//sprawdzamy czy po edycji usunieto ktores powiazanie kategorii i zadania w tabeli zadanie_kategoria
			for ($i=0;$i<count($kat);$i++)
			{
				if ($kat[$i]==$x['id_kategoria']) { $del=0; break; }
				$del=1;
			}
			// jak w tabeli jest usuniete powiazanie to je usuwamy
			if ($del==1)
			{
				$s = $db->prepare('DELETE FROM zadanie_kategoria
						WHERE id_kategoria=:id_kategoria
						AND id_zadanie=:zadid');
				$s->bindValue(':id_kategoria', $x['id_kategoria'], PDO::PARAM_INT);
				$s->bindValue(':zadid', $zadid, PDO::PARAM_INT);
				$s->execute() or die(print_r($db->errorInfo(), true));
			}
		}
		$date = date('Y-m-d');
		// finalnie edytujemy zadanie
		$l = $db->prepare('UPDATE zadanie SET 
			tresc=:tresc,
			rozwiazanie=:rozwiazanie,
			data_modyfikacji=:date,
			poziom_trudnosci=:poz_trudnosci,
			usun=:usun,
			ukryj=:ukryj,
			id_osoba_autor=:id_os_aut
			WHERE id_zadanie=:zadid');
		$l->bindValue(':tresc', trim($tresc), PDO::PARAM_STR);
		$l->bindValue(':rozwiazanie', trim($rozwiazanie), PDO::PARAM_STR);
		$l->bindValue(':date', $date, PDO::PARAM_STR);
		$l->bindValue(':poz_trudnosci', $poz_trudnosci, PDO::PARAM_STR);
		$l->bindValue(':usun', $usun, PDO::PARAM_STR);
		$l->bindValue(':ukryj', $ukryj, PDO::PARAM_STR);
		$l->bindValue(':id_os_aut', $id_osoba_autor, PDO::PARAM_STR);
		$l->bindValue(':zadid', $zadid, PDO::PARAM_STR);
		$l->execute() or die(print_r($db->errorInfo(), true));
	}
	public function deleteuser($idkat)
	{
		global $db;
		$sql = $db->query('SELECT id_osoba_autor, usun  FROM zadanie');
		foreach($sql as $row)
		{
			if ($idkat!=0 && $idkat == $row['id_osoba_autor'])
			{
				$usun=1;
				$s->prepare('UPDATE zadanie SET usun=:usun WHERE id_osoba_autor=:id_os_aut');
				$s -> bindValue(':usun', $usun, PDO::PARAM_INT);
				$s -> bindValue(':id_os_aut', $row['id_osoba_autor'], PDO::PARAM_STR);
				$s -> execute() or die(print_r($db->errorInfo(), true));
			}}
		$s=$db->prepare('DELETE FROM konto WHERE id_osoba=:idkat');
		$s->bindValue(':idkat', $idkat, PDO::PARAM_INT);
		$s -> execute() or die(print_r($db->errorInfo(), true));

	}
	public function login($login, $password)
	{
		global $db;
		$s = $db -> prepare('SELECT COUNT(*) FROM konto WHERE pseudonim=:login AND haslo=PASSWORD(:haslo)');
		$s -> bindValue(':login', $login, PDO::PARAM_STR);
		$s -> bindValue(':haslo', $password, PDO::PARAM_STR);
		$s -> execute() or die(print_r($db->errorInfo(), true));
		$c = $s->fetch();
		if($c[0]==0) header("Location: ?login&warn=Zly login lub haslo!");
		else if($c[0]>=1)
		{
			$s = $db -> prepare('SELECT id_osoba, id_grupa FROM konto WHERE pseudonim=:login');
			$s -> bindValue(':login', $login, PDO::PARAM_STR);
			$s -> execute() or die(print_r($db->errorInfo(), true));
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
	public function koment($id_zadanie, $id_osoba, $komentarz)
	{
		global $db;
		$s = $db -> prepare('INSERT INTO
		komentarz(id_zadanie, id_osoba, komentarz, data_komentarz, ukryj, usun, czy_przeczytany)
		VALUES(
		:id_zadanie,
		:id_osoba,
		:komentarz,
		NOW(),
		0,
		0,
		0)');
		$s->bindValue(':id_zadanie', $id_zadanie, PDO::PARAM_INT);
		$s->bindValue(':id_osoba', $id_osoba, PDO::PARAM_INT);
		$s->bindValue(':komentarz', $komentarz, PDO::PARAM_STR);
		$s -> execute() or die(print_r($db->errorInfo(), true));
	}
	public function showkoment($id_zadanie)
	{
		global $db;
		$s = $db -> prepare('SELECT * FROM komentarz WHERE id_zadanie=:id_zadanie ORDER BY data_komentarz DESC');
		$s->bindValue(':id_zadanie', $id_zadanie, PDO::PARAM_STR);
		$s -> execute() or die(print_r($db->errorInfo(), true));
		echo '<table width="500">';
		foreach($s as $row)
		{
			$x = $db->prepare('SELECT pseudonim FROM konto WHERE id_osoba=:id_osoba');
			$x->bindValue(':id_osoba', $row['id_osoba'], PDO::PARAM_INT);
			$x -> execute() or die(print_r($db->errorInfo(), true));
			$f = $x->fetch();
			echo '<tr><td>'.$row['komentarz'].'<br>Autor: '.$f['pseudonim'].', data: '.$row['data_komentarz'].'</td></tr>';
		}
		echo '</table>';
	}
}
?>
