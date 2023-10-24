<?php
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
//header("Content-Type: application/json; charset=utf-8");
header("Content-Type: text/plain; charset=utf-8");

$squery = "";

if (isset($_GET["zerotier"])) {
        $dt = $_GET["zerotier"];
        if ($dt === "on") {
                shell_exec(
                        "uci set zerotier.sample_config.enabled='1' &uci commit zerotier & /etc/init.d/zerotier restart"
                );
                //sleep(1);
                usleep(100000);
                $query = shell_exec("uci get zerotier.sample_config.enabled ");
                echo "cmd executed\n";

                echo "result: " . $query;
        } elseif ($dt === "off") {
                shell_exec(
                        "uci set zerotier.sample_config.enabled='0' &uci commit zerotier & /etc/init.d/zerotier restart"
                );
                //sleep(1);
                usleep(100000);
                $query = shell_exec("uci get zerotier.sample_config.enabled ");
                echo "cmd executed\n";
                echo "result: " . $query;
        } else {
                echo "command unknow";
        }
}
if (isset($_GET["offline"])) {
        $dt = $_GET["offline"];
        if ($dt === "on") {
                shell_exec(
                        "uci set wireless.wifinet3.disabled='0' && uci commit wireless && /etc/init.d/wireless restart"
                );
                //sleep(1);
                usleep(100000);
                $query =
                        shell_exec("uci get wireless.wifinet3.ssid") .
                        " disabled= " .
                        shell_exec("uci get wireless.wifinet3.disabled");
                echo "cmd executed\n";

                echo "result: " . $query;
        } elseif ($dt === "off") {
                shell_exec(
                        "uci set wireless.wifinet3.disabled='1' && uci commit wireless && /etc/init.d/wireless restart"
                );
                //sleep(1);
                usleep(100000);
                $query =
                        shell_exec("uci get wireless.wifinet3.ssid") .
                        " disabled= " .
                        shell_exec("uci get wireless.wifinet3.disabled");
                echo "cmd executed\n";
                echo "result: " . $query;
        } elseif ($dt === "status") {
                usleep(100000);
                $query =
                        shell_exec("uci get wireless.wifinet3.ssid") .
                        " disabled= " .
                        shell_exec("uci get wireless.wifinet3.disabled");
                echo "cmd executed\n";
                echo "result: " . $query;
        } else {
                usleep(100000);
                echo "command unknow";
        }
}
