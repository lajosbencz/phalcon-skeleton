
@echo on

SET root_path=%~dp0
SET "php_path=%root_path%/application/console/console.php"

php %php_path% %*
