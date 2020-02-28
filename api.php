<?php
// ini_set('display_error',1);
// ini_set('display_startup_error',1);
// error_reporting(E_ALL);
$action = 'network'; //$_GET['action'];

$network_list = [];

if($action == "network"){

	$a =  shell_exec("ip -o addr show | awk '/\<inet\>/ {print $2, $3, $4}'");
	$device_list = preg_split('/[\r\n]+/', $a);
	
	$i = 1;
	//print_r($device_list);
	for($i = 1;$i<count($device_list)-2;$i++){
		//echo $i."\n";
		//echo $device_list[$i]."\n";
		$info = explode(" ",$device_list[$i]);
		$ip_split = explode(".",$info[2]);
		$device_ip = $ip_split[0].".".$ip_split[1].".".$ip_split[2].".0/24";
		//echo $device_ip."\n";
		$network_list[$device_ip] = [];
		$a =  shell_exec("nmap -sn ".$device_ip);
		$connected_ip = preg_split('/[\r\n]+/', $a);
		//print_r($connected_ip);
		//echo "\n";
		for($j=2;$j<count($connected_ip)-2;$j+=2){
			//
			$k = str_replace("Nmap scan report for ","",$connected_ip[$j]);
			//$b = explode(" ",$connected_ip[$i]);
			//echo $k."\n";
			array_push($network_list[$device_ip],$k);
		}

	}

	echo json_encode($network_list);

}

?>

