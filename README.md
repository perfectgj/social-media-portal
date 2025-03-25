# 📌 Social Media Portal - Laravel Project

## 📖 Project Overview
This is a **social media portal** built with **Laravel** where users can:
- Register & Login
- Send Friend Requests & Accept/Reject them
- Receive Email & In-App Notifications for Friend Requests
- Remove Friends
- Search Users & See Friend Status Dynamically
- Edit & Update Profiles
- Upload Profile Pictures
- Paginate Friends List

## 🛠️ Tech Stack Used
- **Laravel 12.3.0** (Latest Version)
- **Bootstrap 5** (Frontend Styling)
- **MySQL / PostgreSQL** (Database)
- **Laravel Queue Jobs** (For Email Notifications)
- **AJAX & JavaScript** (For Search & Notifications)
- **Blade Templates** (For Views)

## 🚀 Installation & Setup
### **1️⃣ Clone the Repository**
```bash
git clone https://github.com/perfectgj/social-media-portal.git
cd social-media-portal
```

### **2️⃣ Install Dependencies**
```bash
composer install
npm install
```

### **3️⃣ Configure `.env` File**
Copy `.env.example` to `.env`:
```bash
cp .env.example .env
```

Edit `.env` and set up the following:
```env
APP_NAME=SocialMediaPortal
APP_URL=http://localhost:8000

DB_CONNECTION=mysql # Change if using PostgreSQL
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=social_media_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="Social Media Portal"
```

### **4️⃣ Generate Application Key**
```bash
php artisan key:generate
```

### **5️⃣ Set Up the Database**
```bash
php artisan migrate
```

### **6️⃣ Set Up Storage for Profile Pictures**
```bash
php artisan storage:link
```

### **7️⃣ Run Laravel Queue Jobs for Email Notifications**
```bash
php artisan queue:work
```

### **8️⃣ Start the Server**
```bash
php artisan serve
```

Now, visit `http://localhost:8000/` 🎉

## 🔐 Authentication
- **Register** at `/register`
- **Login** at `/login`
- **Logout** at `/logout`

## 👥 Friend System
- **Send Friend Requests** from the dashboard
- **Accept/Reject Friend Requests** from the notifications dropdown
- **Remove Friends** from the Friends List
- **Receive Email Notifications** when a request is sent
- **Resend Friend Requests if Rejected**

## 📬 Notifications
- **Dropdown in Navbar** for friend requests
- **AJAX-based Accept/Reject System**
- **Laravel Queue Jobs for Email Alerts**

## 🔍 Search & Pagination
- **Search Users Dynamically** (Only when typing 3+ characters)
- **Show Friend Status Dynamically** (`Add Friend`, `Request Sent`, `Already Friends`)
- **Paginated Friends List** (5 per page)

## 📝 Profile Management
- **Edit Full Name, DOB, Bio, Location, Interests, Password**
- **Upload Profile Picture**
- **Password Change via Checkbox** (Only updates if checked)

## 🎯 Future Enhancements
✅ **Real-Time Friend Requests (WebSockets & Laravel Echo)**
✅ **User Status (Online/Offline)**
✅ **Messaging System**

## 👨‍💻 Author
- **Developer:** Girish Jadeja
- **Contact:** girishljadeja@gmail.com

---
### 🎉 Now you're ready to launch the **Social Media Portal**! 🚀
