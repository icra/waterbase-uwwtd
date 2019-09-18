<?php
/*
  distance between two coordinates
  converted from javascript to php
  extracted from 'https://cdn.jsdelivr.net/npm/geodesy@2/latlon-spherical.js';
*/

function distance($lat1,$lon1, $lat2,$lon2){
  if(is_null($lat1)) return false;
  if(is_null($lon1)) return false;
  if(is_null($lat2)) return false;
  if(is_null($lon2)) return false;

  $R = 6371e3;//metres
  $φ1 = to_radians($lat1);
  $φ2 = to_radians($lat2);
  $Δφ = to_radians($lat2-$lat1);
  $Δλ = to_radians($lon2-$lon1);
  $a = sin($Δφ/2) * sin($Δφ/2) + cos($φ1) * cos($φ2) * sin($Δλ/2) * sin($Δλ/2);
  $c = 2*atan2(sqrt($a), sqrt(1-$a));
  $d = $R*$c/1000; //km
  return $d;
}
function to_radians($n){return $n*M_PI/180;}

//test: distance(40.6,2.0, 40.3,2.1); //34.415 km
?>
