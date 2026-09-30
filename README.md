# Innovatief Klimaat

## Studentgegevens
- **Studentnaam:** Jeremy Meijer
- **Studentnummer:** 97124269

---

## Projectgegevens

### Naam van het project
**Innovatief Klimaat**

### Beschrijving van het project
Innovatief Klimaat is een interactieve vragenlijst-applicatie gebaseerd op de 10 dimensies van een innovatief klimaat (volgens de theorie van Göran Ekvall), met 5 vragen per dimensie. Docententeams van het Deltion College — elk vallend onder een categorie — kunnen de vragenlijst via een teamcode invullen en krijgen inzicht in hun resultaten per dimensie, vergeleken met het gemiddelde van hun categorie en het totaal van alle teams.

Het project bestaat uit twee onderdelen die in aparte sprints worden ontwikkeld:
- **Front-end:** een React/TypeScript-applicatie (Vite, Tailwind CSS) waarin gebruikers de vragenlijst invullen en de uitkomsten visueel bekijken.
- **Back-end:** een REST-API in PHP met het Symfony-framework, gekoppeld aan een MySQL-database. Deze beheert categorieën, teams, dimensies en vragen, slaat antwoorden anoniem op en berekent resultaten per persoon, per categorie en in totaal — steeds gefilterd per dimensie.

Omdat het project langer dan één week duurt, beschrijft dit README.md het project als geheel. Per sprint schrijf ik een apart README<num>.md bestand (bijv. README1.md) met de specifieke doelen voor die week.

### Reden(en) waarom ik dit project wil maken
Ik voer dit project uit voor een echte opdrachtgever. Het is voor mij een mooie kans om zowel mijn front-end als back-end vaardigheden te ontwikkelen: ik heb al ervaring opgedaan met React, en wil nu leren hoe je een eigen back-end (Symfony) en database bouwt en deze koppelt aan een bestaande front-end.

### Randvoorwaarden

| Onderdeel | Randvoorwaarde / Implementatie |
| :--- | :--- |
| **AVG** | Privacy staat voorop. Antwoorden worden volledig anoniem opgeslagen: alleen de teamcode en de gegeven scores worden geregistreerd. Namen, e-mailadressen of IP-adressen van individuele invullers worden nooit opgeslagen. |
| **Beveiliging** | De back-end gebruikt Doctrine ORM, dat standaard beschermt tegen SQL-injectie via voorbereide statements. Gevoelige instellingen staan in een `.env`-bestand dat niet in de publieke repository komt. |
| **Copyright** | Alle gebruikte media, stijlen en logo's zijn eigendom van of goedgekeurd door het Deltion College. |
| **Licenties** | Front-end: React, TypeScript, Vite, Tailwind CSS (MIT-licentie). Back-end: Symfony (PHP) en MySQL/MariaDB, beide open-source. |
| **Techniek** | Front-end in React/TypeScript; back-end in PHP 8+ met Symfony en een MySQL-database. Communicatie verloopt via JSON over HTTP. |
| **Wettelijke impact** | Geen directe wettelijke impact. Data wordt uitsluitend intern gebruikt voor analyse. |
| **Maatschappelijke impact** | Door het innovatieklimaat binnen Deltion inzichtelijk te maken, draagt de tool bij aan een betere leer- en werkomgeving. |

### Begin- en einddatum van het project
- **Begindatum:** 25 augustus 2026
- **Einddatum:** afhankelijk van het aantal benodigde sprints; wordt na elke sprint met de docent bepaald.

---

## Leerdoelen
Over het hele project wil ik de volgende doelen behalen:
- Een front-end applicatie bouwen en beheren met React, TypeScript en Tailwind CSS.
- Een back-end en database bouwen met Symfony en MySQL, inclusief een datamodel met categorieën, teams, dimensies en vragen.
- Resultaten kunnen berekenen en filteren op verschillende niveaus (per persoon, per categorie, totaal).
- Front-end en back-end aan elkaar koppelen via een REST-API met JSON.
- Veilig en AVG-conform omgaan met gebruikersdata.

> *"The only way to learn a new programming language is by writing programs in it."*
> — **Dennis Ritchie**

---

## Kerntaken / Werkprocessen (Crebo 25998)
Over het hele project werk ik aan de volgende kwalificatiedossier-werkprocessen:

- **B1-K1-W2 – Maakt een technisch ontwerp voor software:** het ontwerpen van de databasestructuur en de API-opzet voor de back-end.
- **B1-K1-W3 – Realiseert (onderdelen van) software:** het bouwen van zowel de front-end als de back-end, inclusief de koppeling tussen beide.
- **B1-K1-W4 – Test software:** het testen van de losse onderdelen (API en front-end) en de complete applicatie.

Per sprint werk ik dit verder uit in het bijbehorende README<num>.md bestand.
