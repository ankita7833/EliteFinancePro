# EliteFinancePro # 🛺 Elite Finance Pro
**Developed by:** Gemini, Ankita, and Sambrudhi

### 🚀 Project Motivation
In the Indian context, small cash transactions like **Chai (₹10)**, **Auto-rickshaws (₹20)**, and **Street Snacks** are often missed in digital trackers. Our goal was to build a system that makes logging these "micro-expenses" effortless while providing deep financial insights.

### ✨ Unique Features
* **Indi-Quick Taps:** One-click buttons to log common local expenses instantly.
* **Daily Burn Rate:** An automated calculation that monitors your spending velocity based on monthly income.
* **Health Progress Bar:** A visual representation of your wallet's retention percentage.
* **Indi-Theming:** Custom CSS engine for "Cyber Dark" and "Saffron Calm" light modes.

### 🛠️ Technical Implementation
* **Backend:** PHP 8 with session-based user tracking.
* **Database:** Relational MySQL for transaction history and categorization.
* **Visualization:** Chart.js integration for real-time Income vs. Expense bar analysis.
* **Security:** Use of `.gitignore` and `db.php.example` to ensure local server credentials are never exposed in a public repository.

### 📂 Setup Instructions
1.  **Database:** Create a database named `elite_finance` in PHPMyAdmin and import the `database.sql` file provided in this repository.
2.  **Configuration:** Rename `db.php.example` to `db.php` and update the database username/password to match your local XAMPP settings.
3.  **Run:** Place the folder in your `htdocs` directory and navigate to `localhost/folder_name` in your browser.