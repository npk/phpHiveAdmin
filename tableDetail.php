<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

if(!@$_GET['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	
	$transport = new TSocket(HOST, PORT);
	$protocol = new TBinaryProtocol($transport);
	$client = new ThriftHiveClient($protocol);
	
	$transport->open();

	//$client->execute('add jar '.$env['hive_jar']);
	$client->execute('use '.$_GET['database']);
	
	echo '<div class="container">';
	echo '<div class="span10">';

	if(!@$_GET['table'])
	{
		die ($lang['dieTableChoose']);
	}
	else
	{
		$sql = "desc formatted ".$_GET['table'];
		$etc = new Etc;
		
		$client->execute($sql);
		$array_desc_table = $client->fetchAll();
		
		echo "<a href=javascript:history.back()>".$lang['back']."</a><br><br>";
		
		#var_dump($array_desc_table);
		
		###############################################################################################################
		
		$array_desc_table_1 = $etc->GetTableDetail($array_desc_table, "1");
		
		#var_dump($array_desc_table);
		
		$i = 0;
		while ('' != @$array_desc_table_1[$i])
		{
			$array_desc = explode('	',$array_desc_table_1[$i]);
			$array_desc_desc['name'][$i] = trim($array_desc[0]);
			$array_desc_desc['type'][$i] = trim($array_desc[1]);
			$array_desc_desc['comment'][$i] = trim($array_desc[2]);
			$i++;
		}
		echo "<table class=\"table table-condensed table-bordered table-striped table-hover\">";
		echo "<tr class=info><td> ".$lang['columnName']." </td><td> ".$lang['columnType']." </td><td> ".$lang['comment']." </td></tr>";
		$i = 0;
		foreach ($array_desc_table_1 as $k => $v)
		{
			echo "<tr>\n";
			echo "<td>".$array_desc_desc['name'][$i]."</td>";
			echo "<td>".$array_desc_desc['type'][$i]."</td>";
			echo "<td>".$array_desc_desc['comment'][$i]."</td>";
			echo "</tr>";
			$i++;
		}
		echo "</table>";
		
		echo "<br>";
		
		#####################################################################################################
		
		$array_desc_table_2 = $etc->GetTableDetail($array_desc_table, "2");
		
		$i = 0;
		while ('' != @$array_desc_table_2[$i])
		{
			$array_desc = explode("	",$array_desc_table_2[$i]);
			$array_desc_desc['name'][$i] = trim($array_desc[0]);
			$array_desc_desc['type'][$i] = trim($array_desc[1]);
			$i++;
		}
		
		echo "<table class=\"table table-condensed table-bordered table-striped table-hover\">";
		echo "<tr class=info><td> ".$lang['detailedName']." </td><td> ".$lang['detailedName']." </td></tr>";
		$i = 0;
		foreach ($array_desc_table_2 as $k => $v)
		{
			echo "<tr>\n";
			if($array_desc_desc['name'][$i] == "Location:")
			{
				echo "<td>".$array_desc_desc['name'][$i]."</td>";
				$tmp = explode("/",$array_desc_desc['type'][$i]);
				for($j = 3; $j < count($tmp); $j++)
				{
					$str .="/".$tmp[$j];
				}
				echo "<td><a href=fileBrowser.php?path=".$str.">".$array_desc_desc['type'][$i]."</a></td>";
			}
			else
			{
				echo "<td>".$array_desc_desc['name'][$i]."</td>";
				echo "<td>".$array_desc_desc['type'][$i]."</td>";
			}
			echo "</tr>";
			$i++;
		}
		echo "</table>";
		echo "<br>"; 
		
		#####################################################################################################
		
		$array_desc_table_3 = $etc->GetTableDetail($array_desc_table, "3");
		
		$i = 0;
		while ('' != @$array_desc_table_3[$i])
		{
			$array_desc = explode("	",$array_desc_table_3[$i]);
			$array_desc_desc['name'][$i] = trim($array_desc[0]);
			$array_desc_desc['type'][$i] = trim($array_desc[1]);
			$i++;
		}
		
		echo "<table class=\"table table-condensed table-bordered table-striped table-hover\">";
		echo "<tr class=info><td> ".$lang['storageName']." </td><td> ".$lang['storageName']." </td></tr>";
		$i = 0;
		foreach ($array_desc_table_3 as $k => $v)
		{
			echo "<tr>\n";
			echo "<td>".$array_desc_desc['name'][$i]."</td>";
			echo "<td>".$array_desc_desc['type'][$i]."</td>";
			echo "</tr>";
			$i++;
		}
		echo "</table>";
		echo "<br>";
		
		#####################################################################################################
		
		$array_desc_table_4 = @$etc->GetTableDetail($array_desc_table, "4");
		
		if($array_desc_table_4[0] != "")
		{
			$i = 0;
			while ('' != @$array_desc_table_4[$i])
			{
				$array_desc = explode("	",$array_desc_table_4[$i]);
				$array_desc_desc['name'][$i] = trim($array_desc[0]);
				$array_desc_desc['type'][$i] = trim($array_desc[1]);
				$array_desc_desc['comment'][$i] = trim($array_desc[1]);
				$i++;
			}
		
			echo "<table class=\"table table-condensed table-bordered table-striped table-hover\">";
			echo "<tr class=info><td> ".$lang['partitionName']." </td><td> ".$lang['partitionName']." </td><td> ".$lang['comment']." </td></tr>";
			$i = 0;
			foreach ($array_desc_table_4 as $k => $v)
			{
				echo "<tr>\n";
				echo "<td>".$array_desc_desc['name'][$i]."</td>";
				echo "<td>".$array_desc_desc['type'][$i]."</td>";
				echo "<td>".$array_desc_desc['comment'][$i]."</td>";
				echo "</tr>";
				$i++;
			}
			echo "</table>";
		}
		echo "<br>";
		echo "<a href=javascript:history.back()>".$lang['back']."</a><br><br>";
		
	}
	echo "</div>";
	echo "</div>";
}
?>