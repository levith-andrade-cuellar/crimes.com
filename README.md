# crimes.com

**Contributors:** `Jenesis Blancaflor`, `Nabira Ahmad`, and `Levith Andrade Cuellar`

## Description
"crimes.com" is a fictional crimes database we developed for our Introduction to Databases class. The project involved designing the database schema, programming it using SQL and making it accessible to others via a secure graphical user interface. We summarize our work more technically in this short [report](/report.pdf).

This is a copy of our original, working repository which you can access [here](https://github.com/jb7801/crimes_database). 

## How It Works

### Login and Registration
- **Users:** There are two types of users, criminals and officers. Their type of account dictates what content they can view and/or modify.
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
- **Delete:** Officers may delete appeal entries.

## How to Access
For the time being our crimes.com database is hosted locally and will not run properly on your machine. If you'd like to see a demo please contact any of the contributors.

## Preview
These are a series of screenshots of the platform in action.

### Login Page ###
Access the database with a username and password.

<img src="/preview/login.png" alt="Login" width="400"/>

### Search Page ###
Search the database like you'd search Google.

<img src="/preview/search.png" alt="Search" width="400"/>

### Criminal Profile ###

**Landing**

Provides you an overview of the criminal.

<img src="/preview/criminal-profile-landing.png" alt="Criminal Profile Landing" width="400"/>

**Information**

Provides more detailed information. 

<img src="/preview/criminal-profile-info.png" alt="Criminal Profile Info" width="400"/>

### Officer Profile ###

<img src="/preview/officer-profile-landing.png" alt="Officer Profile Landing" width="400"/>

### Editing ###

**Insert**

Insert a new criminal record.

<img src="/preview/insert.png" alt="Insert" width="400"/>

**Update**

Update an existing criminal record.

<img src="/preview/update.png" alt="Update" width="400"/>

**Delete**

Delete an appeal.

<img src="/preview/delete.png" alt="Delete" width="400"/>
