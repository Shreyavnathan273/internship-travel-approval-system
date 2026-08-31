# Internship Travel Approval System

A web-based **Travel Request and Approval Management System** developed as part of my internship. The system allows employees to submit travel requests and enables different departments to review and process those requests through a structured approval workflow.

## Technologies Used

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP
* **Database:** MySQL
* **Development Environment:** XAMPP

## Main Features

* User registration and login
* Travel request submission
* Viewing and managing travel applications
* Multi-level approval workflow
* Role-based access for different departments
* Application status tracking
* MySQL database integration

## Approval Workflow

The travel request follows a structured approval process:

**HR → Manager → Finance → Admin**

Each stage reviews the application before it proceeds to the next level.

## Project Structure

```text
Internship Travel Approval System
│
├── Database/
│   └── login_db.sql
│
└── PHP_Project/
    ├── index.php
    ├── login.php
    ├── register.php
    ├── dashboard.php
    ├── form.php
    ├── applications.html
    ├── approvals.php
    ├── action.php
    ├── edit.php
    ├── view.php
    ├── success.php
    └── logout.php
```

## How to Run

1. Install and open **XAMPP**.

2. Start **Apache** and **MySQL**.

3. Place the project folder inside the XAMPP `htdocs` directory.

4. Open **phpMyAdmin**.

5. Create/import the database using:

   `Database/login_db.sql`

6. Update the database connection details in the PHP files if required.

7. Open the project in a browser using:

   `http://localhost/Internship_Project/PHP_Project/`

## Purpose

The purpose of this project is to provide a simple and organized digital system for managing employee travel requests and their approval process, reducing manual work and improving the tracking of applications.

## Author

**Shreya V Nathan**
**B.Tech Computer Science and Engineering (Data Science)**
**Adi Shankara Institute of Engineering and Technology**
