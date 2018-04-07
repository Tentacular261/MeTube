# make fixperms.sh publicly non-accessable
chmod -v 700 fixperms.sh | grep -v "retained"

# make php source and .gitignore publicly non-accessable
find . -type f \( -name \*.php -or -name .gitignore \) -exec chmod -v 600 '{}' + | grep -v "retained"

# make css and js source and .htaccess publicly readable
find . -type f \( -name \*.css -or -name \*.js -or -name .htaccess \) -exec chmod -v 604 '{}' + | grep -v "retained"