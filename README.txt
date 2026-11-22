SMART INVENTORY - UPDATED LOCAL PROJECT

IMPORTANT: This update is designed to use your EXISTING smart_inventory database.
It does NOT import database.sql and does NOT delete existing products or sales.
On first PHP connection, config.php creates only the additional admin_users table if it does not exist.

INSTALL / UPDATE
1. Stop Apache in XAMPP.
2. Make a backup copy of your current SmartInventory folder.
3. Extract this ZIP. Copy the files INSIDE the SmartInventory folder into:
   C:\xampp_c\htdocs\SmartInventory
   Choose Replace files if Windows asks.
   Do NOT delete the existing database in phpMyAdmin and do NOT import database.sql again.
4. Start Apache and MySQL.
5. Open http://localhost/SmartInventory/
6. First visit: create your one admin account (full name, username, password).
   Password must be at least 8 characters. This setup screen appears only while no admin account exists.
7. Later visits show the login page. Use the same username/password each time.

FEATURES
- Same general dashboard layout and colour theme
- One admin login, logout, profile details and password change
- Add, edit, delete and search products
- Low-stock and expiry alerts
- Record sales; quantity is reduced transactionally
- Recent sales history
- Gemini AI suggestions (optional; set key in Admin Profile)
- Optional standalone Python Flask demand-estimate endpoint

GEMINI AI
1. Open Dashboard > click the admin profile area at bottom-left.
2. In Gemini AI settings, paste your own Gemini API key and Save changes.
3. Return to Dashboard > AI Insights > Generate insights.
4. PHP cURL must be enabled in the active XAMPP php.ini. If cURL error appears, enable extension=curl and restart Apache.
The key is stored locally in private/gemini_key.php; private/.htaccess blocks direct web access under Apache.
Never share your API key in chat or upload this project publicly with your real key included.

DATABASE
- Existing products and sales tables are reused.
- The only automatic schema addition is admin_users for login.
- Do not re-import database.sql during this update. The original SQL file is retained only as a reference for a fresh install.

SECURITY / LIMITATIONS
This is a local learning/demo project, not production-ready for public hosting.
Use a unique password and keep the project local. No public internet deployment without security review.
The Python endpoint is a simple moving-average baseline, not a trained or validated forecasting model.
Expiry alerts use entered expiry dates; they do not predict expiry.
