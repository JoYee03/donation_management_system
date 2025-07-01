# Donation Management System

## Description

 The main purpose of this project is to develop Donor Management System which helps to raise funds online. 
 Even if any food item remains in any function people can send request to system. 
 The employees can check all kinds of reports after the login.
 This provide and support efficient and effective fundraising activities. 
 This project will help to find donors easily.

(This project is a **university assignment** and may contain flaws or incomplete features. The code is provided for educational purposes and is not intended for production use.)


---

## How to Run the Website (Local Setup)

### Prerequisites:
- [XAMPP](https://www.apachefriends.org/index.html) installed on your system

### Steps:

1. **Download the project folder**
    - Extract the ZIP file after downloading.

3. **Move the project to XAMPP**:
   - Locate the `htdocs` folder inside your XAMPP installation directory.
   - Copy the entire project folder (e.g., `donation_management`) into the `htdocs` folder.

4. **Start XAMPP services**:
   - Open the XAMPP Control Panel.
   - Start both **Apache** and **MySQL**.

5. **Set up the database**:
   - Open your browser and go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   - Create a new database named `donation_management`.
   - Import the provided SQL file (`donation_management_sql.sql`) into this database.

6. **Access the website**:
   - In your browser, go to [http://localhost/donation_management](http://localhost/donation_management)

The website should now be up and running on your local machine.

---

## Notes

- Ensure the SQL file is imported **before** using the website to avoid errors.
- If you encounter any issues, check the database name matches exactly with the one used in your PHP configuration files
