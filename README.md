A very basic image labelling tool with zoom support.

##### Setup
  * Create mysql database move config.php.dist to config.php and add DB info
  * Make sure mysql is *not* in ONLY_FULL_GROUP_BY mode
  * Add your labels to index.php
  * Use import.php to import your images

To view labels in browser, use get parameters `user={user}&image_id={id}`.

##### TODO:
  * Auth
  * Touchscreen support for tablets
