<!-- SCRAMBLE THIS -->

<?php

function wights($dbconnection,$userid,$userid2,$movieid) {
    echo(rand(10,100));
}

function NBCF_expect($dbconnection,$movieid,$userid,$count)
{
	$time = (rand(1,100)/10)+$count;
	if ($time>30)
	{$time = (rand(200,250))/10;}
	sleep($time);
	echo (rand(1,50)/10);
	echo ('('.$time.' sec)');
}
?>