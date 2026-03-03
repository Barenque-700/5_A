# 5_A

Questa repository servirà alla completazione del progetto di informatica fino alla fine dell'anno, riguardo la creazione di un social network per appassionati di astronomia

Descrizione del Progetto
Un'applicazione classica ma ricca di sfumature tecniche: gestione utenti, liste personali e
condivise, categorizzazione, scadenze e notifiche. Ottimo progetto per iniziare a lavorare su
autenticazione, CRUD completo e condivisione tra utenti.

Requisiti Funzionali

• Registrazione e login utente con sessione o JWT

• Creazione, modifica ed eliminazione di liste (es. 'Spesa', 'Lavoro', 'Studio')

• Aggiunta di task a ogni lista con: titolo, descrizione, priorità (bassa/media/alta), data
scadenza, stato (da fare / in corso / completato)

• Possibilità di condividere una lista con altri utenti (sola lettura o modifica)

• Filtro e ricerca dei task per stato, priorità o parola chiave

• Contatore task completati / totali per ogni lista

• Storico delle modifiche: chi ha cambiato cosa e quando


Requisiti Tecnici

• Backend: PHP con architettura REST API (endpoint separati per auth, liste, task,
condivisioni)

• Database: MySQL — schema fornito di seguito

• Frontend: a scelta (consigliato Vue.js o React) — deve essere una SPA

• Autenticazione: JWT (token in localStorage o cookie httpOnly) oppure sessione PHP

• Risposta API sempre in JSON

• Gestione degli errori con codici HTTP appropriati (400, 401, 403, 404, 500)

Consegne Richieste

• Codice sorgente su repository Git (con almeno 10 commit significativi)

• Documentazione API in formato Markdown o Swagger/OpenAPI

• Schema ER del database (anche disegnato a mano o con strumento grafico)

• Breve documento di progetto: scelte architetturali, divisione del lavoro, difficoltà
incontrate
