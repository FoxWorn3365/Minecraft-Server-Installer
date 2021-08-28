<?php
$jar = glob("*.jar");
$ja = explode("-", $jar[0]);
$ojar = $ja[0];
$latest = json_decode(file_get_contents("https://serverjars.com/api/fetchLatest/$ojar"));

$v = $latest->response->version;



if ($ja[1] === $v) {
   echo "Starting server...";
   shell_exec("./start.sh");
} else {
   echo "\nLA TUA VERSIONE E' OBSOLETA!\n\nTua versione: $ja[1]\nUltima versione: $v";
   echo "\nAggiorno? [Y / N]";
   $r = readline();
if ($r == "Y") {
   echo "\nAggiornamento in corso...";
   echo "\nEliminazione del precedente Jar";
   unlink($jar[0]);
   echo "\nRecupero dell'ultimo jar in corso";
   shell_exec("wget https://serverjars.com/api/fetchJar/$ojar");
   shell_exec("mv $ojar $ojar-$v.jar");
   $sas = file_get_contents("start.sh");
   $sas = str_replace($ja[1], $v, $sas);
   $h = fopen("start.sh", "w+");
   fwrite($h, $sas);
   fclose($h);
   echo "\n\¡Aggiornamento completato!\n\nStatus:\nNuova versione: $v";
   shell_exec("./start.sh");
} else {
   shell_exec("./start.sh");
}
}
