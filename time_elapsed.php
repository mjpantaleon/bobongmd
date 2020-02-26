<?php

function time_elapsed($secs)
{
	$bit	= array(
					
			' year'		=> $secs / 31556926 % 12,
			' week'		=> $secs / 604800 % 52,
			' day'		=> $secs / 86400 % 7,
			' hour'		=> $secs / 3600 % 24,
			' minute'	=> $secs / 60 % 60,
			' second'	=> $secs % 60
			
			);
	
	foreach($bit as $k => $v)
	{
		if($v > 1) $ret[] = $v . $k . 's';
		if($v == 1) $ret[] = $v . $k;
	}
	
	array_splice($ret, count($ret)-1, 0, 'and');
	$ret[] = 'ago.';
	
	return join(' ', $ret);
}


//$now	= time();
//$past	= strtotime('2013-05-21 11:55:03');

//echo "time elapsed: ".time_elapsed($now-$past)."\n";



?>