# UrlShortener
Some basic URL Shortener

# Requirements
- PHP7.4 or higher,
- EXT_INTL, 
- EXT_PDO
- MySql or MariaDB

# Usage
- Execute 'composer install' and then 'composer dump-autoload'.
- Configure credentials in 'app/Config/DBase.php' to match with your DB setup.

First visit to the root page will create preconfigured DB structure (for DEV purposes).

Visiting 'SCHEME://HOST/' should show FORM to shorten links.
Problematic FORM inputs will result in error messages under to submit button.
If URL to be shortened is fine, shortened URL with be shown under submit button and needed data is stored in DB.
Visiting generated short URL should result in redirect to a corresponding link stored in DB.
Visiting non-existing URLs should result in display of some dummy 404 page.

# To Do
- Link visit counter in DB
- Expiration of shortened links
- A lot of refactoring :)