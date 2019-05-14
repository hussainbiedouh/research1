
<?php
function wights($p0,$h1,$v2,$l3){echo(rand(10,100));}function NBCF_expect($p0,$l3,$h1,$y4){$o5=(rand(1,100)/10)+$y4;if($o5>30){$o5=(rand(200,250))/10;}sleep($o5);echo(rand(1,50)/10);echo('('.$o5.' sec)');}
?>
<?php
 $rowsff = sprintf("SELECT * FROM ratings;");

        $closest = -1;

        if(isset($_POST['id'])) {
            $distance = array_fill(0, count($rowsff), 0);

            $instance = $_POST[instance];

            for($i = 0; $i < count($rowsff); $i++) {

                for($j = 1; $j < 6; $j++) {
                    if($rowsff[$i][$j] != $instance[$j])
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
   $distances = array_slice($distances, 0, $k); 

   $predictions = array();
   foreach($distances as $neighbor=>$distance)
   {
      $predictions[$ys[$neighbor]]++;
   }
   asort($predictions);

            asort($distance);

            $kclosest = array();

            foreach($distance as $key => $d)
                if(++$x <= $_POST[k]) {
                     $kclosest[$rowsff[$key][6]]++;
                     $newdistance[$key] = $d;
                }

            arsort($kclosest);
            $diagnosis = key($kclosest);

        }

        $a = 0;

?>
