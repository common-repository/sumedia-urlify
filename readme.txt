=== Sumedia Urlify ===
Contributors: sumediawebdesign
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=E6825AJ567QMA&source=url
Tags: security, admin url, login url, rewrite
Requires at least: 5.3
Tested up to: 5.4
Stable tag: 5.4
Requires PHP: 5.6.0
License: GPL-3.0-or-later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==

Makes /wp-admin/ and /wp-login.php pathes configurable using mod_rewrite.

This plugin support a way to change the admins URL and the login path.

It will change 3 things:

* Write to .htaccess some Rules
* Define a constant in the wp-config.php
* Hook into the url fetching methods to substitute the new urls

So far in version 0.2.0 it seems to work properly.

= Dependencies =

This plugin depends on:

wp-cli/wp-config-transformer

= Troubleshooting =

If something breaks, you have to revert the changes of this plugin as
described in https://www.sumedia-howto.de/wordpress/wordpress-urls-rewrite-zum-schutz-sensibler-dateien-und-pfade/ (German)

= Changelog =
See changelog.txt