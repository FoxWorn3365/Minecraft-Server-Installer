<?php
$latest = file_get_contents("https://s1.fcosma.it/API/MSI/version?t=NOJSON");

if (empty($latest)) {
   $latest = "null";
}

echo "\n==========";
echo "\nMinecraft Server Installer";
echo "\n==========";
echo "\n\nMade by: FoxWorn3365\nVersion: 0.7\nLatest version: $latest";

echo "\n\nATTENZIONE: Non eseguire mai questo file dalla directory dove vuoi installare il server!";

echo "\n\nPer iniziare l'installazione digita start, invece per aggiornare digita update ";
$do = readline();
if ($do === "update") {
   echo "Scarico l'aggiornamento da API.fcosma.it/MSI/latest";
   shell_exec("wget https://s1.fcosma.it/API/MSI/latest");
   shell.exec("mv latest MSI.php");
   echo "Operazione completata! File salvato in " . __DIR__ . "MSI.php";
exit;
}
echo "\nBenvenuto nel processo di installazione del server Minecraft!\nDi seguito verranno elencati tutti i software per tutti i server!";
$r = json_decode(file_get_contents("https://serverjars.com/api/fetchTypes/"));



echo "\n------\nServer Bedrock\n";
foreach ($r->response->bedrock as $e) {
  echo "$e\n";
}

echo "\n------\nServer Java (Vanilla)\n";
foreach ($r->response->vanilla as $e) {
  echo "$e\n";
}

echo "\n------\nServer Java (Plugin)\n";
foreach ($r->response->servers as $e) {
   echo "$e\n";
}

echo "\n------\nServer Java (Moddati)\n";
foreach ($r->response->modded as $e) {
   echo "$e\n";
}

echo "\n------\nServer Proxy\n";
foreach ($r->response->proxies as $e) {
   echo "$e\n";
}

echo "\n\nOra inserisci il tipo di server che vuoi installare: ";
$server = readline();

if (empty($server)) {
   die("Risposta vuota, abortisco...\n");
}
echo "\nTipo selezionato: $server";
echo "\n----------\nEcco le versioni che ho trovato per il tipo da te specificato:\n\n";
$v = json_decode(file_get_contents("https://serverjars.com/api/fetchAll/$server/"));

foreach ($v->response as $a) {
   echo "$a->version\n";
}

echo "\n\nSeleziona la versione che desideri installare: ";
$version = readline();
if (empty($version)) {
   die("Risposta vuota, abortisco...");
}

echo "\nVersione selezionata: $version";
echo "\n----------\nSeleziona l'estensione del file 'start' per il server:\n";
echo "[1] start.sh --> Linux";
echo "\n\nInserisci il numero dell'estensione scelta: ";
$ext = readline();
echo "\n>>IMPOSTAZIONI JAVA<<\n\n";
echo "Minecraft Server Installer installerà la seguente versione di Java:";
if ($version === "1.17.1" || $version === "1.17") {
    echo "Java 16";
    $jar = 16;
} else {
    echo "Java 8";
    $jar = 8;
}
echo "\n\nInserisci la memoria massima di java per il server (Xmx) [IMMETTI PURE L'UNITA' DI MISURA (M / G)]: ";
$max = readline();
echo "Ora inserisci la memoria minima (Xms) [IMMETTI PURE L'UNITA' DI MISURA (M / G)]: ";
$min = readline();
echo "Usare la GUI? (Se sei su un VPS quindi senza interfaccia grafica usa No): [Si / No] ";
$nogui = readline();
if ($nogui != "Si" && $nogui != "No") {
    echo "Hai inserito una risposta errata! Abortisco...";
    exit;
}

if ($nogui === "Si") {
   $gui = "";
} else {
   $gui = " nogui";
}

echo "\nAccetti l'EULA di Minecraft? [Si / No] ";
$eula = readline();
if ($eula != "Si" && $eula != "No") {
    echo "Hai inserito una risposta errata! Abortisco...";
    exit;
}

echo "\nInserisci la directory ove installare il server: ";
$dir = readline();

if (empty($dir) || $dir == "/") {
   echo "Non posso eliminare la directory base! Abortisco...";
   exit;
} else {
   if (is_dir($dir)) {
   shell_exec("rm -rf $dir");
   mkdir($dir, 0777);
   } else {
   mkdir($dir, 0777);
   }
}

echo "\n\nSto installando il server, ci potrebbe volere un po!";

echo "Pronto per l'installazione!";

echo "\n[=-----] Creazione della directory";

sleep(0.5);

echo "\n[==----] Creazione del file eula.txt";

$s = "eula.txt";
$h = fopen("$dir$s", "w+");
fwrite($h, "eula=true");
fclose($h);

echo "\n[===---] Recupero del file .jar del server";

echo "\nFetching jar file from https://serverjars.com/api/fetchJar/$server/$version";
shell_exec("wget https://serverjars.com/api/fetchJar/$server/$version");
shell_exec("mv $version $server-$version.jar");
shell_exec("mv $server-$version.jar $dir");

echo "\n[====--] Creazione del file di start";

$b = "start.sh";
$h = fopen("$dir$b", "w+");
fwrite($h, "java -Xmx$max -Xms$min -jar $server-$version.jar$gui");
fclose($h);

echo "\n[=====-] Impostando Java";

$java = shell_exec("java -version 2>&1");

$javawa = explode('"', $java);
$javaa = $javawa[1];

echo $javaa;

if (strpos($javaa, $jar) !== false) {
  echo "\nLa versione di Java installata e' gia' quella richiesta in $javaa";
} else {
//Verifichiamo la versione sia già installata
$vs = shell_exec("ls /usr/lib/jvm/");
if (stripos($vs, $jar) !== true) {
   echo "Non hai installata la versione richiesta di Java (Java$jar)!\nVai su https://fcosma.it/GitHub/MSI/help?error=java&do=install&v=$jar";
} else {
shell_exec('sudo update-java-alternatives -s $(sudo update-java-alternatives -l | grep  ' . $jar . ' | cut -d " " -f1)');

$java2 = shell_exec("java -version 2>&1");

$java2 = explode('"', $java2);
$java24 = $java2[1];

if (stripos($java24, $jar) === false) {
  die("Java non si e' sistemato secondo il programma! Usa update-alternatives --config java e seleziona Java$jar");
}
}
}

echo "[======] Creazione dell'Auto-Updater";

$hs = "start.php";

shell_exec("wget https://s1.fcosma.it/API/MSI/start");
$suad = "start.php";
shell_exec("mv start $dir$suad");

echo "\n\nServer installato con successo!";
echo "\nFaccio partire il server? [Si / No]";
$rep = readline();

if ($rep == "No") {
   echo "\nOk, abortisco!\nGrazie per avermi utilizzato <3\n\n";
   exit;
} else {
  echo "\n\nStarto il server Minecraft seguendo le tue direttive...";
  shell_exec("java -Xmx$max -Xms$min -jar$dir$server-version.jar$gui");
}

