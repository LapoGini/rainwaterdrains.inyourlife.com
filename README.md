<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Sul progetto Zanetti

- ## **Da implementare in app**

  - [ ] JSON caditoia da salvare in scheda SD con:
    - ID == TIMESTAMP_USER_ID_LATITUDINE_LONGITUDINE
    - ID_SD == da scrivere in SCHEDA SD solo se non presente
    - TOLLERANZA da recuperare dal GPS
    (per renderlo univoco si può ageire sul timestamp di inserimento più user)
    - INVIATO (conterrà l'ID della caditoia sul server inviato in risposta alla chiamata di scrittura sul server della caditoia stessa)
  - [ ] il file della foto avrà nome TIMESTAMP_USER_ID_LATITUDINE_LONGITUDINE.jpg
  - [ ] rimarrà tutto salvato su SD fino a che il server non dirà cosa cancellare
    - le chiamate da fare sono DELETE_CADITOIE con parametro ID_SD per ricevere da server tutte le caditoie da cancellare su quella determinata scheda SD, la risposta sarà una stringa di ID (id cadiotie presenti sul server) separati da virgola. Se per quella SD non c'è niente da cancellare si risponde con stringa vuota.
    - la chiamata da parte dell'app per confermare l'avvenuta cancellazione è DELETE_CADITOIE_ID con parametro l'ID della caditoia cancellata. Questo serve al server per aggiungere nel campo DELETED su server la data di avvenuta cancellazione altrimenti il campo è NULL
  - [ ] non dovrà essere più presente in app la voce "Altro" su "Stati caditoia" e su "Pozzetti"
  - [ ] la label "Ubicazione" è da cambiare in CIVICO/UBICAZIONE
  - [ ] aggiungere mappa con posizione e indicazione del valore subito dopo aver scattato la foto o subito dopo aver rilevato la posizione, se la mappa non è disponibile perchè offline, visualizzare solo la tolleranza.
  - [ ] Chiedere conferma per procedere (Procedere? Si/No) dopo aver mostrato la tolleranza, nel caso di rispoata negativa rifare la foto e/o riprendere posizione dal GPS.

- ## **Da implementare in Backend**

  - [ ] Aggiungere alla tabella Items (Caditoie) 2 campi (boolean)"cancellabile" e (dataora nullable)"delated"
  - [ ] Trovare tutte le attuali rotte per le API e confrontarle con quelle attualmente utilizzate sull'attuale APP
  - [ ] Aggiungere filtri su pagina caditoie per:
    - CLIENTE -> prevede la generazione di un file diverso a seconda del cliente
    - COMUNE -> Consequenziale al CLIENTE selezionato, ogni cliente ha i suoi comuni legati
    - STRADE -> Consequenziale al COMUNE selezionato.
    - TIPI TAG con relativi TAGS legati come checkbox o selezione multipla (Recapito (Fognatura Bianca, Fognatura Nera, Fognatura Mista), Tipo pozzetto (Caditoia, Bocca di Lupo, Griglia), Stato(Funzionante, Rotta, Bloccata, Cemento, Radicim Non Scaricam Fondo Rotto, Macchina sopra))
    - DATA PULIZIA (between)
    - OEPRATORE PULIZA
    - NOTE
  - [ ] aggiungere pulsante in caditoie "crea zip" per scaricare tutte le foto del filtro effettuato
  - [ ] controllare campi da modificare e no
  - [ ] seleziona tutti o singola riga o singolo filtro per rendere DELETABLE le righe selezionate sulla SD del telefono
  - [ ] controllare permessi su CLIENTI e USER
  - [ ] implementazione server side di DataTable
  - [ ] creare file a seconda del CLIENTE SELEZIONATO sulla base delle specifiche presenti al file [Esportazione](esportazione.xlsx)

- ## [SPECIFICHE DA CONTRATTO](contratto.pdf)
