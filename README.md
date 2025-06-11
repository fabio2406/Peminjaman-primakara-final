# 🚀 Peminjaman primakara
## Tutorial Running Local

- Install Composer min version : 2.7.2
- Install PHP min version : 8.2
- Install Node 

## Tutorial Install Depedency

### 🔹 **Step 1: Clone the Repository**
```sh
git clone https://github.com/Prajwal100/Complete-Ecommerce-in-laravel-10.git
cd Complete-Ecommerce-in-laravel-10
```

### 🔹 **Step 2: Install Dependencies**
```sh
composer install
npm install
```

### 🔹 **Step 3: Environment Setup**
```sh
cp .env.example .env
php artisan key:generate
```
Update `.env` with database credentials.

### 🔹 **Step 4: Database Configuration**
```sh
php artisan migrate --seed
```


### 🔹 **Step 5: Run the Application**
```sh
php artisan serve
```
🔗 Open `http://localhost:8000`

### **Admin Login Credentials:**
📧 **Username:** `admin`  
🔑 **Password:** `password`
