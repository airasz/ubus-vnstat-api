<?php
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: application/json; charset=utf-8");

echo "{";
$cnt = 0;
$bdata = false;
$count = 0;
$squery = "";
if (isset($_GET["network"])) {
    $bdata = true;
    $dt = $_GET["network"];
    if ($dt === "device") {
        $query = shell_exec("ubus call network.device status");
        echo '"network":{"status": true, "data":[';
        if (empty($query)) {
            echo '], "error":"query error. Check if ubus is installed on your system."},';
        } else {
            echo $query;
            echo '], "error": null},';
        }
    } elseif ($dt === "hosts") {
        $query = shell_exec("ubus call luci-rpc getHostHints");
        echo '"network":{"status": true, "data":[';
        if (empty($query)) {
            echo '], "error":"query error. Check if ubus is installed on your system."},';
        } else {
            $data = json_decode($query, true);
            //echo "{";
            foreach ($data as $key => $value) {
                // echo $key; // return val0|
                // echo "<br/>";
                // to get val0 inner content

                // echo $data[$key]['ipaddrs'][0].PHP_EOL;
                // echo $data[$key]['name'].PHP_EOL;
                if ($data[$key]['name'] != null) {
                    $count++;
                    if ($count > 1) {
                        echo ',';
                    }
                    echo '{';
                    echo '"name":"' . $data[$key]['name'] . '",';
                    echo '"ipv4":"' . $data[$key]['ipaddrs'][0] . '"}';
                }
                // echo '"encryption":"' . $data[$key][$keyb][$keyc]["config"]["encryption"] . '"}';

                foreach ($value as $item) {
                    // if ($item == "name") {
                    //     $count++;
                    //     if ($count > 1) {
                    //         echo ',"' . $list . '"'; //EDP.lanEDP.lan,esp32-arduino.lanesp32-arduino.lan,DESKTOP-F5BK09I.lanDESKTOP-F5BK09I.lan
                    //     } else {
                    //         echo '"' . $list . '"';
                    //     }
                    // }
                }
            }
            echo '], "error": null}';
        }
    } elseif ($dt === "iface") {
        $query = shell_exec("ubus call luci-rpc getNetworkDevices");
        echo '"network":{"status": true, "data":[';
        if (empty($query)) {
            echo '], "error":"query error. Check if ubus is installed on your system."},';
        } else {
            $data = json_decode($query, true);
            //echo "{";
            foreach ($data as $key => $value) {


                $txb = $data[$key]["stats"]["tx_bytes"];
                $rxb = $data[$key]["stats"]["rx_bytes"];

                if ($txb !== 0 & $rxb !== 0) {
                    //only not zero traffict shown
                    $count++;
                    if ($count > 1) {
                        echo ',{ "name" : "' . $key . '",'; // return val0
                    } else {
                        echo '{ "name" : "' . $key . '",'; // return val0
                    }

                    if ($txb > 1000000000) {
                        $txb = round($txb / 1000000000, 2);
                        echo '"tx":"' . $txb . ' GB",';
                    } elseif ($txb > 1000000 && $txb < 1000000000) {
                        $txb = round($txb / 1000000);
                        echo '"tx":"' . $txb . 'MB",';
                    } elseif ($txb > 1000 && $txb < 1000000) {
                        $txb = round($txb / 1000);
                        echo '"tx":"' . $txb . 'KB",';
                    } else {
                        echo '"tx":"' . $txb . ' B",';
                    }

                    if ($rxb > 1000000000) {
                        $rxb = round($rxb / 1000000000, 2);
                        echo '"rx":"' . $rxb . ' GB"';
                    } elseif ($rxb > 1000000 && $rxb < 1000000000) {
                        $rxb = round($rxb / 1000000);
                        echo '"rx":"' . $rxb . 'MB"';
                    } elseif ($rxb > 1000 && $rxb < 1000000) {
                        $rxb = round($rxb / 1000);
                        echo '"rx":"' . $rxb . 'KB"';
                    } else {
                        echo '"rx":"' . $rxb . ' B"';
                    }
                    echo "}";
                }
            } //end for
            echo '], "error": null}';
        }
    } elseif ($dt === "radio") {
        $query = shell_exec("ubus call luci-rpc getWirelessDevices");
        echo '"network":{"status": true, "data":[';
        if (empty($query)) {
            echo '], "error":"query error. Check if ubus is installed on your system."},';
        } else {
            $data = json_decode($query, true);
            $count = 0;
            //echo "{";
            // echo '>' . [$data]["radio0"]["iwinfo"]["hardware"]["name"];
            foreach ($data as $key => $value) {
                // echo $key . ' '; //radio 0, radio1
                foreach ($value as $keyb => $value2) {
                    // echo $keyb . ' ';
                    if ($keyb === "interfaces") {

                        foreach ($value2 as $keyc => $value3) {
                            $count++;
                            // echo '_' . $keyc;
                            // echo '_' . [$data][$key][$keyb][$keyc];
                            if ($count > 1) {
                                echo ',';
                            }
                            echo '{';
                            echo '"ssid":"' . $data[$key][$keyb][$keyc]["config"]["ssid"] . '",';
                            //echo '"key":"' . $data[$key][$keyb][$keyc]["config"]["key"] . '",'; // show bare key
                            $pass = $data[$key][$keyb][$keyc]["config"]["key"];

                            //    $pass='air46664';

                            for ($i = 0; $i < strlen($pass) - 3; $i++) {
                                $pass[$i] = '*';
                            }

                            echo '"key":"' . $pass . '",'; // show half sensored key 
                            echo '"mode":"' . $data[$key][$keyb][$keyc]["config"]["mode"] . '",';
                            echo '"encryption":"' . $data[$key][$keyb][$keyc]["config"]["encryption"] . '"}';
                        }
                    }
                    // $wencript = $data[$keyb]["interfaces"][0]["encryption"];
                    // $wssid = $data[$keyb]["interfaces"][0]["ssid"];
                }
            }
            echo '], "error": null}';
        }
    } elseif ($dt === "boardjson") {
        $query = shell_exec("ubus call luci-rpc getBoardJSON");
        echo '"network":{"status": true, "data":[';
        if (empty($query)) {
            echo '], "error":"query error"},';
        } else {
            echo $query;

            echo '], "error": null}';
        }
    } else {
        $query = shell_exec("ubus call network.interface.$dt status");
        echo '"network":{"status": true, "data":[';
        if (empty($query)) {
            echo '], "error":"interface not found. Check if ubus is installed on your system."}';
        } else {
            echo $query;
            echo '], "error": null}';
        }
    }
} else {
    // echo '"network":{"status": false, "data":[ ], "error":"no data"},';
    $cnt++;
}

if (isset($_GET["system"])) {
    $dt = $_GET["system"];
    if ($bdata) {
        echo ",";
    }
    $bdata = true;
    echo '"system":{"status": true, "data":[';
    $query = shell_exec("ubus call system $dt");
    if (empty($query)) {
        echo '], "error":"parameter not found"}';
    } else {
        echo $query;

        echo '], "error": null}';
    }
} else {
    // echo '"system":{"status": false, "data":[ ], "error":"no data"},';
    $cnt++;
}
if (isset($_GET["dns"])) {
    $dt = $_GET["dns"];
    if ($bdata) {
        echo ",";
    }
    $bdata = true;
    if ($dt === "current") {
        echo '"system":{"status": true, "data":[';
        // shell_exec("/etc/init.d/adblock $dt gen");
        // sleep(3);
        $query = shell_exec("/etc/init.d/adblock $dt json");
        if (empty($query)) {
            echo '], "error":"query error. Check if ubus is installed on your system."},';
        } else {
            $data = json_decode($query, true);
            //echo "{";
            foreach ($data as $key => $value) {
                if ($key === "requests") {

                    foreach ($value as $keyb => $value2) {
                        // echo $keyb.': ';
                        $count++;
                        if ($count > 1) {
                            echo ',';
                        }
                        echo '{';
                        echo '"client":"' . $data[$key][$keyb]['client'] . '"';
                        echo ',';
                        echo '"domain":"' . $data[$key][$keyb]['domain'] . '"}';
                    }
                }
            }
            echo '], "error": null}';
        }
    } elseif ($dt === "domain") {
        echo '"system":{"status": true, "data":[';
        // shell_exec("/etc/init.d/adblock $dt gen");
        // sleep(3);
        $query = shell_exec("/etc/init.d/adblock report json");
        if (empty($query)) {
            echo '], "error":"query error. Check if addblock is installed on your system."},';
        } else {
            $data = json_decode($query, true);
            //echo "{";
            foreach ($data as $key => $value) {
                if ($key === "top_domains") {

                    foreach ($value as $keyb => $value2) {
                        // echo $keyb.': ';
                        $count++;
                        if ($count > 1) {
                            echo ',';
                        }
                        echo '{';
                        echo '"count":"' . $data[$key][$keyb]['count'] . '"';
                        echo ',';
                        echo '"address":"' . $data[$key][$keyb]['address'] . '"}';
                    }
                }
            }
            echo '], "error": null}';
        }
    } elseif ($dt === "blocked") {
        echo '"system":{"status": true, "data":[';
        // shell_exec("/etc/init.d/adblock $dt gen");
        // sleep(3);
        $query = shell_exec("/etc/init.d/adblock report json");
        if (empty($query)) {
            echo '], "error":"query error. Check if addblock is installed on your system."},';
        } else {
            $data = json_decode($query, true);
            //echo "{";
            foreach ($data as $key => $value) {
                if ($key === "top_blocked") {

                    foreach ($value as $keyb => $value2) {
                        // echo $keyb.': ';
                        $count++;
                        if ($count > 1) {
                            echo ',';
                        }
                        echo '{';
                        echo '"count":"' . $data[$key][$keyb]['count'] . '"';
                        echo ',';
                        echo '"address":"' . $data[$key][$keyb]['address'] . '"}';
                    }
                }
            }
            echo '], "error": null}';
        }
    }
} else {
    // echo '"system":{"status": false, "data":[ ], "error":"no data"},';
    $cnt++;
}

if (isset($_GET["vnstat"])) {
    $dt = $_GET["vnstat"];
    if ($bdata) {
        echo ",";
    }
    $bdata = true;
    echo '"vnstat":{"status": true, "data":[';
    $query = shell_exec("vnstat --json -i $dt");
    if (empty($query)) {
        echo '], "error":"interface not found. Check if vnstat is installed on your system."}';
    } else {
        $squery = $query;
        if (strpos($squery, "Error") !== false) {
            echo str_replace(
                "toreplace",
                $dt,
                '"Error: Unable to read database \"/var/lib/vnstat/toreplace\": No such file or directory"'
            );
        } else {
            echo $query;
        }
        echo '], "error": null}';
    }
} else {
    // echo '"vnstat":{"status": false, "data":[ ], "error":"no data"},';
    $cnt++;
}

if (isset($_GET["netdata"])) {
    $dt = $_GET["netdata"];
    if ($bdata) {
        echo ",";
    }
    $showData = "0";
    if (isset($_GET["data"])) {
        $dt2 = $_GET["data"];
        if ($dt2 === "all") {
            $showData = "1";
        } else {
            $showData = "0";
        }
    }
    echo '"netdata":{"status": true, "data":[';
    netdataParse($dt, $showData);
} else {
    // echo '"netdata":{"status": false, "data":[ ], "error":"no data"}';
    $cnt++;
}
echo "}";
function netdataParse($param, $cond)
{
    if ($param === "info") {
        $getData = file_get_contents("http://127.0.0.1:19999/api/v1/info");
        echo $getData;
        echo '], "error": null}';
    } elseif ($param === "temp") {
        $rawDt = shell_exec(
            "cat /sys/class/thermal/thermal_zone0/temp | awk '{print $1}'"
        );
        $jsDt = "{ \"temp\": $rawDt } ], \"error\":\"null\"}";
        echo $jsDt;
    } else {
        $rawr = shell_exec(
            "curl http://127.0.0.1:19999/api/v1/data?chart=$param"
        );
        if ($cond === "1") {
            if ($rawr === "Chart is not found: $param") {
                echo '], "error":"parameter not found. Check if netdata is installed on your system."}';
            } else {
                $getData = file_get_contents(
                    "http://127.0.0.1:19999/api/v1/data?chart=$param"
                );
                echo $getData;
                echo '], "error": null}';
            }
        } else {
            if ($rawr === "Chart is not found: $param") {
                echo '], "error":"parameter not found. Check if netdata is installed on your system."}';
            } else {
                $getData = file_get_contents(
                    "http://127.0.0.1:19999/api/v1/data?chart=$param&after=-1"
                );
                echo $getData;
                echo '], "error": null}';
            }
        }
    }
}

//echo secondsToDHMS($uptime);

function secondsToDHMS($seconds)
{
    $s = (int) $seconds;
    return sprintf('%dd:%02dh:%02dm:%02ds', $s / 86400, $s / 3600 % 24, $s / 60 % 60, $s % 60);
}