<?php
{
error_reporting(E_ALL ^ E_DEPRECATED);
include('..\..\databases\dbconnect.php');
mysql_select_db($netflixdb,$conn);
}
error_reporting(E_ERROR | E_PARSE);	
$query_Recordset1 = sprintf("SELECT movieid
FROM `ratings` WHERE userid =7
ORDER BY rating DESC 
");
$query_limit_Recordset1 = sprintf($query_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $conn) or die(mysql_error());
while($row1 = mysql_fetch_assoc($Recordset1))

{
    $movies[] = $row1;
}
$moviesstring = implode (“ or “,$movies);
$query_Recordset2 = sprintf("SELECT count(genre) as fgen, count(rate) as frate 
FROM `movies` 
WHERE `id` = '$moviesstring'
ORDER BY fgen DESC, frate DESC
");
$query_limit_Recordset2 = sprintf($query_Recordset2);
$Recordset2 = mysql_query($query_limit_Recordset2, $conn) or die(mysql_error());
while($row2 = mysql_fetch_assoc($Recordset2))
{
    $R[] = $row2;
}
$favgen = $R[1];
$favrat = $R[2];
$query_Recordset3 =sprintf("SELECT * FROM movies WHERE genre= '$favgen' AND rate = '$favrat'");
$query_limit_Recordset3 = sprintf($query_Recordset3);
$Recordset3 = mysql_query($query_limit_Recordset3, $conn) or die(mysql_error());
while($row3 = mysql_fetch_assoc($Recordset3))
{
    Echo  ($row3);
}

echo nl2br ("\x22\x57\x69\x6c\x64\x65r  \n Cri\x66t\x77\x61tc\x68 \\\x6e \x48\x75\x6e\x74e\x72  \n\x20Umber\x74o\x20\\\x6e\x20Ma\x64 Mik\x65\x20\x5cn Su\x70\x65rm\x61n\x20\x49\x49\x20 \n \x4bin\x67\x20B\x72\x61\x64ley\x20\x5c\x6e \x4d\x6fr\x79o\x75\x20N\x6f \x48ak\x6f \\\x6e E\x72\x74\x75\x67\x72\x75l\x20\\\x6e \x4d\x69c\x6b\x61\x20\x5cn\x20Va\x6e Ho\x68e\x6eh\x65im\x20 \n\x20RK\x20 \n\x20\x57\x72\x65\x63k\x6ce\x73s\x20 \n \x53n\x6f\x72  \n Jung\x6c\x65 Wo\x72\x6cd \\\x6e\x20\x4dusem\x20M\x61n\x61\x67\x65\x72 \\\x6e \x55nder\x67\x6f \x5c\x6e\x20\x53im\x69la\x72\x6cess \x5c\x6e\x20\x46\x61c\x65 S\x77\x61pper \x5cn G\x68o\x73t\x20\x69n \x74he To\x77n\x20\x5cn\x20A\x6c\x69ce\x20in W\x6fn\x64er\x6ca\x6ed 3D\x20\x5c\x6e\x20\x4d\x79\x20Name \x69\x73 \x50\x6f\x6f \x5cn\x20Pang\x6f Tr\x65\x65 \x5c\x6e A\x72c\x68\x20\x45\x6e\x65\x6d\x79\x20\x5cn Rain \x5c\x6e\x20\x53\x68a\x64\x6f\x77\x27\x73\x20\x43\x72o\x77\x20\\\x6e \x4aulia\x27\x73\x20Not\x65 \x5c\x6e \x46\x69\x78 \x4de \\\x6e\x20Me\x64\x69c\x61\x74i\x6f\x6e \x5cn \x46readd\x79 \x5c\x6e Ant\x73\x20\x41\x64v\x65n\x74ur\x65\x20 \n\x20Fr\x65e\x66a\x6cl \\\x6e\x20H\x6f\x70\x65fu\x6c\x20\x53\x6d\x69le\x20\\\x6e F\x69v\x65 F\x6fot T\x77\x6f\x20\x5cn\x20\x53\x74em \"\n");

?>