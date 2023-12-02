# PhoneBook

## Description

This is a simple phone book application that enables you to perform actions such as adding, editing, deleting, and
searching for contacts.  
It is built using Symfony 6.3.8 and React 18.  
The application employs PostgreSQL 15.5 as its database, which can be accessed through Adminer
at [http://localhost:8080/](http://localhost:8080/) using the following credentials:

|   System   |  Server  |    Username     |    Password     | Database  |
|:----------:|:--------:|:---------------:|:---------------:|:---------:|
| PostgreSQL | database | _your username_ | _your password_ | phonebook |

## Installation

To set up the application, ensure that Docker and Docker Compose are installed on your machine.  
After cloning the repository, proceed to install Symfony, followed by the Composer and NPM dependencies:

```bash
composer install
```

```bash
npm install
```

## Usage

To initiate the application, use the following command:

```bash
symfony server:start
```

You can then access the application at http://localhost:8000/

To launch the database, run the subsequent command (which also starts Adminer):

```bash
docker-compose up --d
```

Finally, to start the frontend, execute the following command:

```bash
npm run watch
```
