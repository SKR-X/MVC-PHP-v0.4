# MVC-PHP-v0.4
Небольшой MVC-фреймворк


# Правила для .htaccess

<FilesMatch "^\.ht">
Require all denied
</FilesMatch>

Options +FollowSymlinks
Options -Indexes

RewriteEngine On

RewriteCond %{REQUEST_URI} ^/?(css|fonts|img|js)(/.*)?$ [NC]
RewriteRule ^.*$ /app/content/styles/%1%2 [L]

RewriteCond %{REQUEST_URI} !^/?app/web/index\.php [NC]
RewriteCond %{REQUEST_URI} !^/?(css|fonts|img|js)(/.*)?$ [NC]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_URI} ^/?$ [OR]
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^.*$ /app/web/index.php [NC,L]
