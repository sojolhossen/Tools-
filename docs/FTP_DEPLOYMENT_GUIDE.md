# AmarHoster FTP Auto-Deployment Guide — ToolZeno.site

**Workflow File:** `.github/workflows/deploy.yml`  
**Deploy Method:** FTP / FTPS Auto-Sync via GitHub Actions  
**Target Host:** AmarHoster cPanel Shared Hosting  
**Status:** Ready to use  

---

## ১. কীভাবে কাজ করে? (How it works)

1. আপনি যখনই `main` ব্রাঞ্চে কোনো কোড `push` করবেন বা ম্যানুয়ালি `Run workflow` দেবেন:
2. GitHub Actions-এর ক্লাউড সার্ভার স্বয়ংক্রিয়ভাবে:
   - PHP 8.2 ও Node.js 20 সেটআপ করবে।
   - সব Composer প্যাকেজ ইনস্টল করবে।
   - সব NPM প্যাকেজ ইনস্টল করে `npm run build` দিয়ে Vite CSS/JS কম্পাইল করবে।
   - আপনার সব টেস্ট রান করে নিশ্চিত করবে কোনো ত্রুটি নেই।
   - শুধুমাত্র প্রয়োজনীয় ফাইলগুলো (`vendor/`, `public/build/`, `app/`, ইত্যাদি) একটি রেডি-টু-রান প্যাকেজে তৈরি করবে।
3. এরপর সেই রেডি-টু-রান প্যাকেজটি **FTP/FTPS**-এর মাধ্যমে সরাসরি আপনার AmarHoster হোস্টিংয়ে আপলোড করে দেবে!
4. হোস্টিংয়ে থাকা আপনার আসল `.env` ফাইল এবং ব্যবহারকারীর আপলোড করা ফাইল (`storage/app/public`) কখনো ডিলিট বা ওভাররাইট হবে না।

---

## ২. GitHub Repository Secrets সেটআপ (মাত্র ৩টি আবশ্যক সিক্রেট)

আপনার GitHub রিপোজিটরিতে যান:  
**Settings > Secrets and variables > Actions > New repository secret**

নিচের সিক্রেটগুলো যোগ করুন:

| Secret Name | Required? | Example Value | বিবরণ |
| :--- | :---: | :--- | :--- |
| `FTP_SERVER` | **Yes** | `ftp.toolzeno.site` বা আপনার সার্ভার IP (যেমন: `198.51.100.25`) | AmarHoster FTP হোস্টনেম বা সার্ভার আইপি। |
| `FTP_USERNAME` | **Yes** | `cpaneluser` বা `deploy@toolzeno.site` | cPanel ইউজারনেম অথবা তৈরি করা FTP অ্যাকাউন্টের ইউজারনেম। |
| `FTP_PASSWORD` | **Yes** | `YourStrongPassword` | cPanel অথবা FTP অ্যাকাউন্টের পাসওয়ার্ড। |
| `FTP_PORT` | *Optional* | `21` | ডিফল্ট `21`। না দিলেও স্বয়ংক্রিয়ভাবে ২১ ধরবে। |
| `FTP_SERVER_DIR` | *Optional* | `public_html/` বা `./` | হোস্টিংয়ের যে ফোল্ডারে ফাইল যাবে। (নিচে ৩নং পয়েন্ট দেখুন) |
| `FTP_PROTOCOL` | *Optional* | `ftp` বা `ftps` | ডিফল্ট `ftp`। FTPS চাইলে `ftps` দিতে পারেন। |

---

## ৩. AmarHoster cPanel ডিরেক্টরি সেটআপ (Important!)

হোস্টিংয়ে ফাইল আপলোডের ক্ষেত্রে AmarHoster cPanel-এ ২টি সাধারণ উপায় আছে:

### পদ্ধতি ১: সাধারণ cPanel সেটআপ (`public_html/`)
যদি আপনার মূল ডোমেইন `toolzeno.site` সরাসরি `public_html` ফোল্ডারকে রুট হিসেবে ধরে:
- GitHub Secret-এ `FTP_SERVER_DIR` দিন: `public_html/`
- অথবা আপনার FTP অ্যাকাউন্টের ডিরেক্টরি যদি সরাসরি `public_html` করা থাকে, তবে `FTP_SERVER_DIR` ফাঁকা রাখতে পারেন (ডিফল্ট `./`)।

> [!NOTE]
> Laravel প্রজেক্টের রুট ফাইল সাধারণত `public_html`-এ থাকলে, `public_html/.htaccess` ফাইলের মাধ্যমে রিকোয়েস্ট `public/` ফোল্ডারে রিডাইরেক্ট হয়, যা আপনার প্রজেক্টের রুট ডিরেক্টরিতে ইতোমধ্যে তৈরি করা আছে।

### পদ্ধতি ২: সিকিউর সেটআপ (Recommended for Laravel)
1. cPanel File Manager-এ গিয়ে রুট ফোল্ডারে `toolzeno` নামে একটি ফোল্ডার বানান: `/home/username/toolzeno`
2. GitHub Secret-এ `FTP_SERVER_DIR` দিন: `toolzeno/`
3. এরপর cPanel Terminal বা Symlink দিয়ে `public_html -> toolzeno/public` লিংক করুন।

---

## ৪. সার্ভারে প্রোডাকশন `.env` তৈরি

FTP ডিপ্লয়মেন্ট আপনার সার্ভারের `.env` ওভাররাইট করবে না। তাই সার্ভারে একবারের জন্য `.env` ফাইল তৈরি করে ডাটাবেজ তথ্য দিন:

```dotenv
APP_NAME=ToolZeno
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://toolzeno.site

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_cpanel_db
DB_USERNAME=your_cpanel_user
DB_PASSWORD=your_db_password
```

---

## ৫. ডিপ্লয়মেন্ট কীভাবে চালাবেন?

### অটোমেটিক ডিপ্লয়:
আপনি যখনই কোড `git push origin main` করবেন, GitHub Actions সাথে সাথে অটো বিল্ড ও ডিপ্লয় শুরু করবে।

### ম্যানুয়াল টেস্ট ডিপ্লয় (Dry-run):
1. GitHub-এ গিয়ে **Actions > Deploy ToolZeno via FTP** সিলেক্ট করুন।
2. **Run workflow** বাটনে ক্লিক করুন।
3. **dry_run** চেকবক্সে টিক দিলে কোনো ফাইল পরিবর্তন না করেই টেস্ট করে দেখাবে কানেকশন ঠিক আছে কিনা।
