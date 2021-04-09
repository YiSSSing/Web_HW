Message board example, 2019

this website is test by xampp, phpMyAdmin , MariaDB



MySQL required :

   1.database 
   
                      user_account  (id , email , eaccount , password , first_name , last_name , gender , comment , nickname , picture )
    php_partice --->  user_files    (id , owner , name , size , download_link , upload_time )
                      user_posts    (id , owner , board , post_time , content , reply , replies , good , say_good )

   2.the phpMyAdmin account is bounded


Setting of php.ini required :

   1.file_upload = On

   2.upload_max_filesize = 15M

   3.max_file_uploads = 30

   
   
Test (if you use xampp) :

   1.make a new directory homework_project or "name" whatever you like in xampp/htdocs

   2.copy all files and directory into this folder

   3.construct a database "php_partice" which contains three table, each must contain terms which mention before, NOTE that this program      
     has set phpMyAdmin account as (root,Yisinglabuse), if your account and password is differ from this, any your operations will not be

     store to database, and some error message may throws out 

   4.open xampp and open apache and mySQL

   5.open a browser and get URL : localhost/name  , which name is the directory name you set at step 1 
