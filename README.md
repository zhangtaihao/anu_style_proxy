ANU Style Proxy
===============

The ANU Style Proxy is an application used to set up a separate style server as
a proxy for the central Webstyle server. It is built on the Symfony Standard
Edition (http://symfony.com).


Requirements
------------

To install this package, the following requirements must be met:

* Web server (Apache 2+ or Nginx)
* PHP 5.3.4 (5.3.8 or higher is recommended, but 5.3.16 is not supported)
* The PHP fileinfo extension

Additionally, PHP should be configured with `allow_url_fopen = On` (php.ini).

Finally, to check system configuration, execute the `check.php` script from the
command line:

    php app/check.php

Reported errors and warnings in mandatory requirements should be fixed before
continuing. Optional recommendations may be ignored, although the following
should be installed for performance:

* PHP-XML extension
* PHP accelerator (e.g. APC)


Setting up
----------

Once the application is deployed in a directory, a web server should be set up
to serve the application `web/` folder on a URL. Furthermore, all nonexistent
paths should be rewritten to `web/app.php` for the application to serve.

Example Apache configuration (assuming the application is deployed in
`/var/anu_style_proxy`):

```
<VirtualHost *:80>
    ServerName styleproxy.example.com
    DocumentRoot /var/anu_style_proxy/web

    <Directory "/var/anu_style_proxy/web">
        <IfModule mod_rewrite.c>
            RewriteEngine On

            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ app.php [QSA,L]
        </IfModule>
    </Directory>
</VirtualHost>
```

Note that the above configuration does not cover serving the proxy over HTTPS.

An example on how to set up Nginx can be found at:

    http://wiki.nginx.org/Symfony


Configuration
-------------

The application configuration directory is `app/config`. To begin configuring
the application, duplicate `parameters.yml.dist` to `parameters.yml` and read
the description on each parameter.

The application requires that the `web/mirror` directory be writable by the web
server. The directory is used to store locally cached assets (if the
`process_resources` parameter is set to true). To bypass PHP altogether for
these static assets (and for a major performance boost), configure the web
server to rewrite asset paths into the mirror directory. For example:

```
    RewriteCond %{DOCUMENT_ROOT}/mirror%{REQUEST_URI} -f
    RewriteRule ^(.*)$ mirror/$1 [L]
```

To use these with the previous example setup, place these lines just after
`RewriteEngine On`.

Finally, the `app/cache` and `app/logs` directories must be writable for the
application to work.

For more information on how to resolve common issues encountered setting up
Symfony applications, please visit:

    http://symfony.com/doc/current/book/installation.html
