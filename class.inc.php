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
			print "B³±d po³±czenia z baz±!: " . $e->getMessage() . "<br/>";
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
			print "B³±d po³±czenia z baz±!: " . $e->getMessage() . "<br/>";
    		die();
		}
		$db->exec('INSERT INTO kategoria(id_nadkategoria, nazwa, nazwa_skrocona, opis, usun, ukryj, kolejnosc_sortowania) VALUES(\''.$idNadKat.'\', \''.$nazwa.'\', \''.$nazwa_skr.'\', \''.$opis.'\', \''.$usun.'\', \''.$ukryj.'\', \''.$kolejn_sort.'\')') or die(print_r($db->errorInfo(), true));
	}
}

?>