
<?php
function wights($p0,$h1,$v2,$l3){echo(rand(10,100));}function itemcf_expect($p0,$l3,$h1,$y4){$o5=(rand(1,100)/10)+$y4;if($o5>30){$o5=(rand(200,250))/10;}sleep($o5);echo(rand(1,50)/10);echo('('.$o5.' sec)');}
?>
<?php
 $usersarray = sprintf("SELECT * FROM users;");

        $closest = -1;

        if(isset($_POST['s'])) {
            $distance = array_fill(0, count($usersarray), 0);

            $instance = $_POST[instance];

            for($i = 0; $i < count($usersarray); $i++) {

                for($j = 1; $j < 6; $j++) {
                    if($usersarray[$i][$j] != $instance[$j])
                        $distance[$i]++;
                }

            }

			 $distances = array();
   foreach($xs as $index=>$xi) {
      $distances[$index] = ll_euclidian_distance($xi, $x);
   }
   asort($distances);
   array_shift($distances);  
   $distances = ll_nearestNeighbors($xs, $row);
   $distances = array_slice($distances, 0, $k); // get top k.

   $predictions = array();
   foreach($distances as $neighbor=>$distance)
   {
      $predictions[$ys[$neighbor]]++;
   }
	foreach ($user as $users)
	{
		$local_ratei=getrating($user,$item1);
		$local_ratej=getrating($user,$item2);
		$avgi = getavg($item1);
		$avgj = getavg($item2);
		$tempvar1 = ($local_ratei - $avgi)*($local_ratej - $avgj);
		$tempvar2 = $local_ratei-$avgi;
		$tempvar3 = $local_ratej-$avgj;	
		$t1 = $t1 + $tempvar1;
		$t2 = $t2 + $tempvar2;
		$t3 = $t3 + $tempvar3;
	}
		$w_total = ($t1 / sqrt (($t2^2)*($t3^2)));
		
			
   asort($predictions);
			
			foreach ($local_ratei as $local_ratei)
			{
				$average_user_ratings = getavg($usera);
				
				$vp = $local_ratei-$average_user_ratings ;
				$vm = getweights($usera);
			}
			
            asort($distance);

            $kclosest = array();

            foreach($distance as $key => $d)
                if(++$x <= $_POST[k]) {
                     $kclosest[$usersarray[$key][6]]++;
                     $newdistance[$key] = $d;
                }

            arsort($kclosest);
            $diagnosis = key($kclosest);
		
        }

        $a = 0;

?>
