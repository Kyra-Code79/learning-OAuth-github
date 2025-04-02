# GitHub OAuth Learning Project

This project demonstrates how to integrate GitHub OAuth for authenticating users and accessing their GitHub data, such as repositories, following, and followers. It uses a `.env` file to securely store the `ClientID` and `ClientSecret` for GitHub OAuth.
This project is open-source and available under the MIT License.

### How to Use:

- This **README** outlines the setup process, explaining how to set up OAuth credentials in a `.env` file and use the provided files (login, dashboard, callback, logout).
- It explains the project structure, how to run it locally, and important considerations for deploying or using the project securely.

### Tips for Customization:

- Replace the placeholders for **Client ID** and **Client Secret** with the actual values from your GitHub OAuth application.
- Make sure to adjust the **redirect URI** and set up a proper production environment if you are deploying it live.

## Prerequisites

Before you begin, you need the following:

- **PHP** installed on your system (version 7.4 or higher recommended).
- **Composer** for managing dependencies.
- A **GitHub OAuth Application** with the following credentials:
  - **Client ID**
  - **Client Secret**

## Setting Up the Project

### 1. Clone the repository

Clone the repository to your local machine:

```bash
git clone https://github.com/your-username/your-repository-name.git
cd your-repository-name
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure the .env file
```bash
GITHUB_CLIENT_ID=your-client-id-here
GITHUB_CLIENT_SECRET=your-client-secret-here
```

### 4. Run the project locally
1. Start your local PHP server:
```bash
php -S localhost:8000
```
or
Open Xampp/Laragon, Start Localhost server

2. Open your browser and go to:
```bash
http://localhost:8000/login.php
```
3. Click the "Login with GitHub" button to authenticate.
4. After authentication, you will be redirected to the dashboard, where you can see your GitHub data (repos, following, and followers).
5. You can log out by clicking the "Logout" button on the dashboard, which will destroy the session and token.

### 5. IMPORTANT NOTES
This project is for learning purposes. Make sure to never commit your .env file with real ClientID and ClientSecret to a public repository.
The .env file should be added to .gitignore to prevent it from being pushed to GitHub.

Example .gitignore
```.gitingore
.env
```
