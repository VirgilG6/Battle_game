# Presentation of the software slamquiz
![alt text](https://github.com/VirgilG6/slamquiz/blob/develop/assets/screenshot_home.jpg)

## Installation
1. Create storage space on your computer:
```
cd temp
```

2. Clone the project using the following command:
```
git clone https://github.com/VirgilG6/slamquiz.git
```

3. Install composer (if you have not followed this link: https://getcomposer.org/download/): 
```
composer install
```

4. Copy the file .env and rename it to .env.local

5. Replace db_user, db_password and db_name with your data
```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
```

6. Start the server with the command:
```
php bin/console server:run
```

7. Open the index page on a browser:
```
localhost:8000
```

8. Existing account:
```
Login: User
Password: 123456

Login: Admin
Password: 123456

Login: Superadmin
Password: 123456

The password is the same because it was to test.
```
