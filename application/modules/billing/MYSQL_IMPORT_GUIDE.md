# MySQL Database Import - Complete Guide for XAMPP

## Your Setup

- **XAMPP Installation:** D:\xampp
- **Database Name:** vhms_rashmi_amc_2025
- **MySQL User:** root
- **MySQL Password:** *(you have a password set)*
- **SQL File to Import:** `application/modules/billing/sql/CLEAN_SETUP.sql`

---

## Method 1: phpMyAdmin (Easiest - No Command Line)

### Step-by-Step

1. **Start XAMPP**
   - Open XAMPP Control Panel
   - Click **Start** next to Apache
   - Click **Start** next to MySQL

2. **Open phpMyAdmin**
   - Open browser
   - Go to: `http://localhost/phpmyadmin`
   - Login with:
     - **Username:** root
     - **Password:** *(your password)*

3. **Select Database**
   - Left panel: Click on `vhms_rashmi_amc_2025`

4. **Import SQL File**
   - Click **Import** tab (at top)
   - Under "File to import:", click **Choose File**
   - Navigate to: `D:\xampp\htdocs\ahms\application\modules\billing\sql\CLEAN_SETUP.sql`
   - Click **Open**
   - Click **Go** button (bottom right)

5. **Wait for Completion**
   - Should see: "22 tables have been created successfully."
   - Scroll down to verify no errors

6. **Verify Success**
   - Click **SQL** tab
   - Paste this query:
   ```sql
   SHOW TABLES LIKE 'billing%';
   ```
   - Click **Go**
   - Should show 22 tables

---

## Method 2: Windows PowerShell (Recommended for Command Line)

### Step-by-Step

1. **Open PowerShell**
   - Press: `Windows Key + X`
   - Click: **Windows PowerShell**

2. **Navigate to Project**
   ```powershell
   cd D:\xampp\htdocs\ahms
   ```

3. **Run Import Command**
   ```powershell
   Get-Content "application\modules\billing\sql\CLEAN_SETUP.sql" | mysql -u root -p your_password vhms_rashmi_amc_2025
   ```
   
   **Replace** `your_password` with your actual MySQL password
   
   Example:
   ```powershell
   Get-Content "application\modules\billing\sql\CLEAN_SETUP.sql" | mysql -u root -p "MyPassword123" vhms_rashmi_amc_2025
   ```

4. **Check for Errors**
   - If successful: No output or "Query OK" messages
   - If error: Will show error message

5. **Verify Success**
   ```powershell
   mysql -u root -p "MyPassword123" vhms_rashmi_amc_2025 -e "SHOW TABLES LIKE 'billing%';"
   ```
   
   Should list 22 tables

---

## Method 3: Windows Command Prompt (CMD)

### Step-by-Step

1. **Open Command Prompt**
   - Press: `Windows Key + R`
   - Type: `cmd`
   - Press: **Enter**

2. **Navigate to Project**
   ```cmd
   cd D:\xampp\htdocs\ahms
   ```

3. **Run Import Command**
   ```cmd
   mysql -u root -p your_password vhms_rashmi_amc_2025 < application\modules\billing\sql\CLEAN_SETUP.sql
   ```
   
   **Replace** `your_password` with your actual MySQL password
   
   Example:
   ```cmd
   mysql -u root -p "MyPassword123" vhms_rashmi_amc_2025 < application\modules\billing\sql\CLEAN_SETUP.sql
   ```

4. **Wait for Completion**
   - Command should complete silently (good sign!)
   - If you see errors, scroll up to read them

5. **Verify Success**
   ```cmd
   mysql -u root -p "MyPassword123" vhms_rashmi_amc_2025 -e "SHOW TABLES LIKE 'billing%';"
   ```

---

## Troubleshooting

### Problem 1: "Access denied for user 'root'"

**Cause:** Wrong password

**Solution:**
- Check what password you set for MySQL
- Make sure it's enclosed in quotes: `-p "password"`
- If no password, use: `-p ""` (empty quotes)

### Problem 2: "Can't find file"

**Cause:** Wrong path to SQL file

**Solution:**
- Make sure you're in: `D:\xampp\htdocs\ahms`
- Use exact path: `application\modules\billing\sql\CLEAN_SETUP.sql`
- Use backslashes (\\) not forward slashes

### Problem 3: "Duplicate entry" errors

**Cause:** Tables already exist

**Solution:**
- CLEAN_SETUP.sql drops tables first, so this shouldn't happen
- If it does, manually delete tables in phpMyAdmin:
  1. In phpMyAdmin, select all `billing_*` tables
  2. Click **Drop**
  3. Try import again

### Problem 4: Command not found

**Cause:** MySQL not in PATH

**Solution for PowerShell:**
```powershell
$env:Path += ";D:\xampp\mysql\bin"
mysql -u root -p "your_password" vhms_rashmi_amc_2025 < "application\modules\billing\sql\CLEAN_SETUP.sql"
```

**Solution for Command Prompt:**
```cmd
set PATH=%PATH%;D:\xampp\mysql\bin
mysql -u root -p "your_password" vhms_rashmi_amc_2025 < application\modules\billing\sql\CLEAN_SETUP.sql
```

---

## Quick Password Reference

### If You Don't Remember Your Password

**Option 1: Check XAMPP Config**
- Open: `D:\xampp\mysql\data\mysql\user.MYD`
- OR check phpMyAdmin settings

**Option 2: Reset Password**
1. Stop MySQL in XAMPP Control Panel
2. Open Command Prompt
3. Run:
   ```cmd
   cd D:\xampp\mysql\bin
   mysqld --skip-grant-tables
   ```
4. In another Command Prompt:
   ```cmd
   mysql -u root
   FLUSH PRIVILEGES;
   ALTER USER 'root'@'localhost' IDENTIFIED BY 'new_password';
   EXIT;
   ```
5. Restart MySQL normally

**Option 3: Use phpMyAdmin First**
- Go to `http://localhost/phpmyadmin`
- Click **User accounts**
- Click **Edit privileges** for root
- Set a new password you'll remember

---

## Step-by-Step Visual Guide

### Using phpMyAdmin (Easiest)

```
1. Start XAMPP
   ↓
2. http://localhost/phpmyadmin
   ↓
3. Login (root / password)
   ↓
4. Select database "vhms_rashmi_amc_2025"
   ↓
5. Click "Import" tab
   ↓
6. Click "Choose File"
   ↓
7. Select: D:\xampp\htdocs\ahms\application\modules\billing\sql\CLEAN_SETUP.sql
   ↓
8. Click "Go"
   ↓
9. Wait for success message
   ↓
10. Verify: Run "SHOW TABLES LIKE 'billing%';"
    ✅ Should see 22 tables
```

---

## Verification Checklist

After import, verify by running these in phpMyAdmin (SQL tab):

- [ ] **22 tables created:**
  ```sql
  SHOW TABLES LIKE 'billing%';
  ```
  (Should show exactly 22 tables)

- [ ] **7 payment methods:**
  ```sql
  SELECT COUNT(*) FROM billing_payment_methods;
  ```
  (Should show: 7)

- [ ] **8 service categories:**
  ```sql
  SELECT COUNT(*) FROM billing_service_categories;
  ```
  (Should show: 8)

- [ ] **3 tax rates:**
  ```sql
  SELECT COUNT(*) FROM billing_tax_configurations;
  ```
  (Should show: 3)

- [ ] **9 configurations:**
  ```sql
  SELECT COUNT(*) FROM billing_configurations;
  ```
  (Should show: 9)

---

## What Gets Created

| Item | Count | Type |
|------|-------|------|
| Tables | 22 | Database tables |
| Payment Methods | 7 | Default data |
| Service Categories | 8 | Default data |
| Tax Rates | 3 | Default data |
| Config Settings | 9 | Default data |
| Foreign Keys | 3 | Constraints |
| Indexes | 15+ | Performance |

---

## Next Steps After Import

1. ✅ **Database Setup** (you are here)
2. ⏳ **Access Module** → Go to: `http://localhost/ahms/billing`
3. ⏳ **Add Data** → Create insurance companies, services, etc.
4. ⏳ **Test Workflow** → Create first invoice
5. ⏳ **Train Staff** → Teach how to use module

---

## Support

- **phpMyAdmin:** `http://localhost/phpmyadmin`
- **XAMPP Control Panel:** Check MySQL status
- **Error messages:** Copy exact text and search in troubleshooting section
- **MySQL Manual:** `https://dev.mysql.com/doc/`

---

**Status:** Ready for Database Import ✅

**Recommended Method:** phpMyAdmin (Method 1) - Easiest and no password in command line
