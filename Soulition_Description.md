1) Created Artisan command with 2 arguments - I deleted the 'id' argument due to the fact that I couldn't find a way to implement the logic.

2) Copied the provided files to the storage folder - storage -> files 

3) I provided my solution in ImportData.php 

4) Could not find a way to insert the extracted data to the database. 

5) I am using XAMPP mysql. I have created my database(importdatapayarc) and configured the .env settings(successfully established connection with my DB)

6) I saw how the migrations work. I migrated the the initial project migration with the php artisan migrate command.

7) I have created my own migration  and configured it 

8) The part of the task I couldn't solve - I think we should have Model for each record type. I created them. 

    - If my logic is right: I do not know how to import column names dynamically and their values. 
    - I did not understand the part with the id and how it should prevent duplications. 
