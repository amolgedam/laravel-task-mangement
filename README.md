I have share zip file with including database in DB folder for reference also we create migration files to create database without import .sql file.


1. Database Setup
    -> Open PhpMyadmin from Hosting Cpanel.
    -> create database and assign user for that database with given previleges like CREATE, INSERT, READ, UPDATE, DELETE etc.
    -> If we want fresh record then we used migration:-
        1. Go Console from Cpanel.
        2. go to project folder and run below command to create tables automatically.
                php artisan migrate
    -> else we can import .sql file from PhpMyadmin import menu.   
    
2. Project Setup.
    -> Open File Manager from Hosting Cpanel.
    -> open domain folder of project.
    -> unzip compress file.
    -> update .env file with database creadentials
    -> run composer install command to install php library
    -> run npm install command to install node packages

##########################################################################################
Drag and Drop funtionality check in drag-drop.html