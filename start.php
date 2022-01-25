<?php
// Verifico che il server sia MSI
if (!file_exists("server.json")) {
  die("[MSI] > Il server non è stato installato con MSI!\nGuida: https://fcosma.it/MSI/server.json_notfound");
}

// Ok, è un server MSI
$server = json_decode(file_get_contents("server.json"));

//recupero l'ultima versione
$latest = json_decode(file_get_contents("https://serverjars.com/api/fetchLatest/$server->type"));

// Verifico che le versioni coincidino
if ($latest->response->version !== $server->version) {
  echo "[MSI]#[Warning] > Il server non è all'ultima versione!\n";
  echo "[MSI]#[Warning][Info] > Versione attuale: $server->version - Ultima versione: $latest->response->version";
  echo "[MSI]#[ChoiseHeader] > Cosa desideri fare?";
  echo "[MSI]#[Choise] > [1] Aggiorna il server adesso\n";
  echo "[MSI]#[Choise] > [2] Non aggiornare il server adesso\n";
  echo "[MSI]#[Choise] > [n / c / 3] Non aggiornare mai questo server\n";
  $old = "$type-$version.jar"
  $r = readline();
  if ($r == "1") {
    unlink($old);
    echo "[MSI]#[DownloadManager] > Scaricando l'ultima versione da serverjars.com";
    shell.exec("wget https://serverjars.com/api/fetchJar/$server->type");
    $txt = file_get_contents("start.sh");
    $h = fopen("start.sh", "w+");
    fwrite($h, str_replace($old, $latest->response->file, $txt));
    fclose($h);
    shell.exec("start.sh");
  } elseif ($r == "2") {
    echo "[MSI]#[AutoUpdater] > Ok, avvio il server";
    shell.exec("start.sh");
  } else {
    echo "[MSI]#[AutoUpdater] > Ok, provvedo ad ingorare il server. Per riattivare gli aggiornamenti, elimina il file .noup nella cartella del server!";
    $h = fopen(".noup", "w+");
    fwrite($h, "[MSI]#[AutoUpdater][TextPlus] >> Per riattivare gli aggiornamenti, elimina questo file!");
    fclose($h);
    shell.exec("start.sh");
  }
} else {
  echo "[MSI]#[AutoUpdater] > Il server è già all'ultima versione. Avvio in corso...";
  shell.exec("start.sh");
}
    
