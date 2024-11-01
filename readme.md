# Sumedia Urlify

Makes /wp-admin/ and /wp-login.php pathes configurable using mod_rewrite.

## Wordpress Plugin

With this plugin i try to support a way to change the admins URL and the login path.
More things could follow.

It will change 3 things:

- Write to .htaccess some Rules
- Define a constant in the wp-config.php
- Hook into the url fetching methods to substitute the new urls

So far in version 0.2.0 it seems to work properly.

## Dependency

This plugin depends on:

wp-cli/wp-config-transformer
sumedia-wordpress/base

## Troubleshooting 

If something breaks, you have to revert the changes of this plugin as
described in https://www.sumedia-howto.de/wordpress/wordpress-urls-rewrite-zum-schutz-sensibler-dateien-und-pfade/ (German)