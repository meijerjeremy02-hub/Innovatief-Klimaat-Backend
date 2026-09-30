# Innovatief Klimaat — Sprint 1

## Studentgegevens
- **Studentnaam:** Jeremy Meijer
- **Studentnummer:** 97124269

---

## Projectgegevens

### Naam van het project
**Innovatief Klimaat** — Sprint 1: Symfony back-end bouwen en live zetten

### Beschrijving van het project
Deze eerste sprint bouw ik de volledige back-end: een REST-API in PHP met het Symfony-framework, gekoppeld aan een MySQL-database. Het doel is een **werkende, live versie** vóór woensdagochtend 30 september 09:00 uur — niet per se foutloos of volledig afgewerkt, maar functioneel en bereikbaar via een publieke URL. Verfijning (nette foutafhandeling, edge cases, front-end koppeling) volgt in een latere sprint.

**Datamodel:**
- **Category** — een categorie waar docententeams onder vallen.
- **Team** — een docententeam met een unieke code, hoort bij één categorie.
- **Dimension** — de 10 dimensies van Ekvall.
- **Question** — 5 vragen per dimensie (50 in totaal), elk met een score van 1 t/m 5 als antwoord.
- **Response** — één anonieme invulling, gekoppeld aan een team via de teamcode.
- **Answer** — het antwoord (score 1-5) op één vraag binnen één response. Omdat elk antwoord via de vraag aan een dimensie hangt, is elk resultaat altijd filterbaar per dimensie.

**API Endpoints:**

| Methode | Endpoint | Beschrijving |
| :--- | :--- | :--- |
| `POST` / `DELETE` | `/api/categories`, `/api/categories/{id}` | Categorie toevoegen/verwijderen |
| `POST` / `DELETE` | `/api/teams`, `/api/teams/{id}` | Team + code toevoegen/verwijderen |
| `POST` / `DELETE` | `/api/dimensions`, `/api/dimensions/{id}` | Dimensie toevoegen/verwijderen |
| `POST` / `DELETE` | `/api/questions`, `/api/questions/{id}` | Vraag toevoegen/verwijderen |
| `GET` | `/api/questions` | Alle vragen, gegroepeerd per dimensie |
| `POST` | `/api/submit` | Antwoorden anoniem opslaan, gekoppeld aan een teamcode |
| `GET` | `/api/results/person/{response_id}` | Gemiddelde per dimensie voor één invulling |
| `GET` | `/api/results/category/{category_id}` | Gemiddelde per dimensie over alle teams binnen die categorie |
| `GET` | `/api/results/total` | Gemiddelde per dimensie over alle invullingen |

---

### Reden(en) waarom ik deze sprint zo invul
Ik heb nog geen ervaring met back-end ontwikkeling in PHP en wil deze sprint gebruiken om in korte tijd een compleet werkend datamodel en API op te zetten met Symfony, inclusief het live zetten ervan. Ik kies er bewust voor om snelheid en werkende functionaliteit voorop te zetten boven perfectie; kleine foutjes of ontbrekende validatie neem ik voor lief zolang de kernfunctionaliteit (data toevoegen/verwijderen, invullen, resultaten per persoon/categorie/totaal) werkt.

---

### Randvoorwaarden

| Onderdeel | Randvoorwaarde / Implementatie |
| :--- | :--- |
| **AVG** | Responses zijn volledig anoniem: alleen de teamcode en de scores worden opgeslagen. Er worden geen namen, e-mailadressen of IP-adressen van individuele invullers vastgelegd. |
| **Beveiliging** | Doctrine ORM voorkomt SQL-injectie via voorbereide statements. Gevoelige instellingen staan in een `.env`-bestand dat niet in de publieke repository komt. Uitgebreide inputvalidatie is deze sprint bewust minimaal, om binnen de tijd te blijven. |
| **Copyright** | Alle gebruikte media en logo's zijn eigendom van of goedgekeurd door het Deltion College. |
| **Licenties** | Back-end: Symfony (PHP) en MySQL/MariaDB, beide open-source. |
| **Techniek** | Back-end in PHP 8+ met Symfony, gedraaid op een hostingomgeving (bijv. Railway of Render) met een MySQL-database. Communicatie via JSON over HTTP. Koppeling met de front-end is nog geen onderdeel van deze sprint. |
| **Wettelijke impact** | Geen directe wettelijke impact. Data wordt uitsluitend intern gebruikt voor analyse. |
| **Maatschappelijke impact** | Door het innovatieklimaat per team en categorie inzichtelijk te maken, helpt deze tool bij het verbeteren van de leer- en werkomgeving binnen Deltion. |

---

### Begin- en einddatum van deze sprint
- **Begindatum:** 24 september 2026
- **Einddatum:** 30 september 2026, live en werkend voor 09:00 uur (volgende week woensdagochtend)

---

## Leerdoelen
Gedurende deze sprint wil ik de volgende concrete doelen behalen:
1. Een Symfony-project opzetten met Doctrine entities en migrations voor een datamodel met meerdere gerelateerde tabellen (category, team, dimension, question, response, answer).
2. CRUD-endpoints bouwen om categorieën, teams, dimensies en vragen toe te voegen en te verwijderen.
3. Een submit-endpoint bouwen dat antwoorden anoniem opslaat, gekoppeld aan een teamcode.
4. Resultaten kunnen berekenen en filteren op drie niveaus: per persoon, per categorie en het totaal — steeds gegroepeerd per dimensie.
5. De applicatie snel en pragmatisch live zetten op een hostingomgeving, met de focus op werkende functionaliteit boven perfectie.

> *"Done is better than perfect."*
> — **Sheryl Sandberg**

---

## Kerntaken / Werkprocessen (Crebo 25998)
In deze sprint werk ik aan de volgende kwalificatiedossier-werkprocessen:

- **B1-K1-W2 – Maakt een technisch ontwerp voor software:** het ontwerpen van het datamodel (category, team, dimension, question, response, answer) en de opzet van de CRUD- en resultaten-endpoints.
- **B1-K1-W3 – Realiseert (onderdelen van) software:** het bouwen van de Symfony back-end: entities, migrations, CRUD-endpoints, het submit-endpoint en de resultaten-endpoints op de drie niveaus.
- **B1-K1-W4 – Test software:** het handmatig testen van de endpoints met Postman op de belangrijkste happy-flow-scenario's, zodat de live versie aantoonbaar werkt.
