# How to Run eCMS Locally

This guide will help you set up and run the eCMS project on your local Windows machine.

## Prerequisites

Ensure you have the following installed (which you seem to have):
- **PHP** (v8.0+ recommended)
- **Composer** (Dependency Manager for PHP)
- **Node.js & NPM** (For building frontend assets)

## Step-by-Step Setup

### 1. Fix PowerShell Execution Policy (One-time)
If you see errors running `npm` or `vite`, it's likely due to PowerShell's execution policy. Run this command in **PowerShell as Administrator**:
```powershell
Set-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy RemoteSigned
```
*Type `Y` and press Enter if prompted.*

### 2. Install Backend Dependencies
Open your terminal in the project folder and run:
```bash
composer install
```
*This installs all PHP libraries required by Laravel.*

### 3. Environment Configuration
2. **Setup .env file**:
   Check if you have a `.env` file. If not, copy the example:
   ```bash
   cp .env.example .env
   ```
   *Or manually copy-paste `.env.example` content into a new file named `.env`.*

3. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

4. **Database Setup**:
   - Open your `.env` file.
   - Find `DB_DATABASE=masterecommerce` and change it to:
     ```ini
     DB_DATABASE=eiconcom_bd
     ```
   - **Create the Database**:
     - Open [phpMyAdmin](http://localhost/phpmyadmin) (or your preferred database tool).
     - Create a NEW database named `eiconcom_bd`.
   - **Import the Data**:
     - Click on the `eiconcom_bd` database you just created.
     - Click "Import" tab.
     - Click "Choose File" and select `eiconcom_bd (3).sql` from the project folder.
     - Click "Go" (or "Import") at the bottom.
     - *Note: Since you are importing a full SQL file, you do NOT need to run `php artisan migrate` or `db:seed`.*

### 4. Install Frontend Dependencies
```bash
npm install
```

### 5. Run the Application
You will need **two** terminal windows running simultaneously.

**Terminal 1 (Backend Server):**
```bash
php artisan serve
```
*This typically runs the site at [http://127.0.0.1:8000](http://127.0.0.1:8000)*

**Terminal 2 (Frontend Assets):**
```bash
npm run dev
```
*This compiles your CSS/JS and watches for changes (Vite).*

## Summary of Commands
```bash
# First time setup
composer install
cp .env.example .env
php artisan key:generate
# (Configure .env database settings now)
php artisan migrate
npm install

# Daily running (Two terminals)
php artisan serve
npm run dev
```
