# PhoneBook

## Description

This is a simple phone book application that allows you to add, edit, delete and search for contacts.  
It's written in Symfony 6.3.8 and React 18.  
The database used is PostgreSQL 15.5 and you can acces it through adminer at http://localhost:8080/ with the following
credentials :  

| System | Server |   Username    |   Password    | Database |
| :----: | :----: |:-------------:|:-------------:| :------: |
| PostgreSQL | database | _your username_ | _your password_ | phonebook |


## Installation

To install the application you need to have docker and docker-compose installed on your machine.
After you have cloned the repository, you need to install Symfony, then install the composer and npm dependencies:

```bash
composer install
```

```bash
npm install
```

## Usage

To start the application you need to run the following command:

```bash
symfony server:start
```

Then you can access the application at http://localhost:8000/

To launch the database you need to run the following command (it alo starts the adminer):

```bash
docker-compose up --d
```

Finally, to launch the frontend you need to run the following command:

```bash
npm run watch
```