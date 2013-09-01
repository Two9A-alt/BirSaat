Pagination Exercise: Imran Nazar
================================

This project implements a simple view on the BBC's Latest News JSON feed, as
a Web site with one feed item visible, and a navigation bar to assist in
viewing the other items in the feed.

The implementation is based loosely on Zend Framework's interpretation of MVC,
with views and templates separated and an isolated web-root. The framework is
referred to throughout as "BirSaat".

Installation notes
------------------

A new virtual host should be set up in the target HTTP server's configuration,
directed to the "www" subdirectory of this project. Examples for Apache and
Lighttpd follow.

Apache:

    <VirtualHost *:80>
      ServerName imrannazar.staging
      DocumentRoot /project_root/www
      RewriteEngine on
      RewriteCond %{REQUEST_FILENAME} !-f
      RewriteCond %{REQUEST_FILENAME} !-d
      RewriteRule ^/([\w/]*) /index.php?path=$1 [QSA]
      <Directory "/project_root/www">
        Order deny, allow
        Allow from all
      </Directory>
    </VirtualHost>

Lighttpd (assuming a symlink from /project_root/www to
/var/www/imrannazar.staging):

    server.modules = (
        "mod_simple_vhost"
    )
    $HTTP["host"] == "imrannazar.staging" {
            url.rewrite-if-not-file = (
                    "^/([\w/]*)(?:\?(.*))?" => "/index.php?path=$1&$2"
            )
    }

Testing notes
-------------

Unit tests have been provided for the framework dispatcher, news feed
representation and paginator; these can be executed as follows.

    cd /project_root/tests
    phpunit .

