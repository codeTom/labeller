A very basic image labelling tool with zoom support.

##### Setup
  * Create mysql database move config.php.dist to config.php and add DB info
  * Run db.sql to create tables
  * Add your labels to index.php
  * Use import.php to import your images

To view labels in browser, use get parameters `user={user}&image_id={id}`.
To export all data go to api.php?key={KEY}&data , where {KEY} is the key set
in config.php (keep this safe).

##### TODO:
  * Auth
  * Touchscreen support for tablets
