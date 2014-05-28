PunArchive
==========
PunArchive is a read-only version of PunBB 1.2. When supplied with a
pre-existing PunBB 1.2 database, it lets users access topics and posts
through the web. Users cannot post or log in.

Installation
------------
Copy all of the files in the `upload` folder to a web server running PHP
4.1.0 or later. Make sure that a PunBB 1.2 database exists and can be
accessed. PunArchive supports MySQL, PostgreSQL and SQLite. PunArchive
only needs `SELECT` permission, unless the search function is enabled.
In that case, `INSERT` and `DELETE` permissions are also required.
(`DELETE` permission is also recommended if there are any expired bans
that need to be deleted, but this is not a requirement.)

The last step is to create a `config.php` file in the directory you
uploaded all the files to. If you already have a PunBB 1.2 forum, you
can copy it from there, but you may want to remove the entries relating
to cookies since they are not needed. You can also create a new one
based on the `example_config.php` file in the extras folder.

PunBB 1.2 languages and styles are compatible with PunArchive. Mods may
or may not be compatible. Administration plugins are not compatible
since there is no administration interface.

History
-------
PunBB 1.2 stopped being officially maintained sometime after the release
of version 1.2.23. More information about the official versions can be
found at <http://punbb.informer.com/>. Later, an unofficial version
called PunBB+ was released at <http://www.punres.net/>. PunArchive is
based on version 1.2.50 from there.
