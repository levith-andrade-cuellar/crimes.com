# crimes.com

**Contributors:** `Jenesis Blancaflor`, `Nabira Ahmad`, and `Levith Andrade Cuellar`

## Description
"crimes.com" is a fictional crimes database we developed for our Introduction to Databases class. The project involved designing the database schema, programming it using SQL and making it accessible to others via a secure graphical user interface. 

## How It Works

### Login and Registration
- **Users:** There are two types of users, criminals and officers may register to the site. Their type of account dictates what content they can view and/or modify.
  - **Officers:** May view, insert, update and delete on the entire database. We assume they are in charge of keeping the database up to date and have the legal permissions to do so.
  - **Criminals:** May only view criminal and crime data. We assume that they are entitled to their own information.

  Permissions are granted and enforced by using SQL users and some front-end protections using HTML.

- **Login Details:** The backend hashes user passwords for secure storage. 

### Search
- **Search Bar:** A search prompt, inspired by Google's, allows the user to search for criminal, crime, officer and probation officer profiles. The search bar enforces filtering by providing the user a series of search options depending on their level of access.

### Profiles
- **Profile:** A successful search result is a profile that details information about a criminal, crime, officer or probation officer. A criminal profile, for instance, includes biographical information about the criminal in addition to details about the crimes they've committed.

### Editing
Some editing functionality is available to users of type officer and can be accessed under any officer profile.
- **Insert:** Officers may insert new criminals to the database.
- **Update:** Officers may update a criminal's information.
- **Delete:** officers may delete appeal entries.
