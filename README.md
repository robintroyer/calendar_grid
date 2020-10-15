# calendar

PHP Version: 7.4.7
SQL Version: 5.5.5-10.4.13-MariaDB

Der Kalendar kann:
- zwischen Monate wechseln und diese richtig ausgeben. (inklusive Schaltjahre etc.)
- den heutigen Tag hervorheben.
- Eintraege welche ueber das Formular in die Datenbank gespeichert wurden auslesen und entsprechend im Kalender eintragen. (Eintraege ueber einen laengeren Zeitraum werden pro Tag eingetragen, am Anfang die Startzeit, am Ende die Endzeit)
- bei Knopfdruck eine Detailsseite oeffnen, wo auch die Beschreibung des Termins ausgelesen werden kann.
- Termine in der Farbe ausgeben, die ihnen bei der Erstellung uebergeben wurde.
- zwischen einer Monats- und Listenansicht wechseln.
- in der Listenansicht nach entweder Datum oder Titel aufsteigend oder absteigend sortiert werden.
- Einträge im iCalendar Format ausgeben.
- den Monat eines Jahres anzeigen, welcher in der URL angegeben wurden.
- hochgeladene .ics Datein in die Datenbank speichern und ausgeben.
- Einträge auch in einer Jahres- bzw. Wochenansicht ausgeben lassen.
- bearbeitete Termine in der Datenbank speichern und ausgeben.


noch geplant:
- bessere Zuordnung der Klassen.
- Termine verstecken.
- evtl. Tagesansicht.

## Installation

1. `config-example.php` als `config.php` kopieren und entsprechend anpassen
2. Datenbankstruktur `termine.sql` einbauen
