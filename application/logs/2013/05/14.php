<?php defined('SYSPATH') or die('No direct script access.'); ?>

2013-05-14 20:16:25 --- ERROR: Database_Exception [ 2 ]: mysql_connect(): Can't connect to MySQL server on 'sql3.pcextreme.nl' (4) ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
2013-05-14 20:16:25 --- STRACE: Database_Exception [ 2 ]: mysql_connect(): Can't connect to MySQL server on 'sql3.pcextreme.nl' (4) ~ MODPATH/database/classes/kohana/database/mysql.php [ 67 ]
--
#0 /var/www/vdevos/fusic/modules/database/classes/kohana/database/mysql.php(171): Kohana_Database_MySQL->connect()
#1 /var/www/vdevos/fusic/modules/database/classes/kohana/database/mysql.php(360): Kohana_Database_MySQL->query(1, 'SHOW FULL COLUM...', false)
#2 /var/www/vdevos/fusic/modules/orm/classes/kohana/orm.php(1504): Kohana_Database_MySQL->list_columns('playlists')
#3 /var/www/vdevos/fusic/modules/orm/classes/kohana/orm.php(392): Kohana_ORM->list_columns(true)
#4 /var/www/vdevos/fusic/modules/orm/classes/kohana/orm.php(337): Kohana_ORM->reload_columns()
#5 /var/www/vdevos/fusic/modules/orm/classes/kohana/orm.php(246): Kohana_ORM->_initialize()
#6 /var/www/vdevos/fusic/modules/orm/classes/kohana/orm.php(37): Kohana_ORM->__construct(NULL)
#7 /var/www/vdevos/fusic/application/classes/controller/home.php(41): Kohana_ORM::factory('playlist')
#8 [internal function]: Controller_Home->action_index()
#9 /var/www/vdevos/fusic/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Home))
#10 /var/www/vdevos/fusic/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#11 /var/www/vdevos/fusic/system/classes/kohana/request.php(1138): Kohana_Request_Client->execute(Object(Request))
#12 /var/www/vdevos/fusic/index.php(109): Kohana_Request->execute()
#13 {main}
2013-05-14 20:16:25 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1126 ]
2013-05-14 20:16:25 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1126 ]
--
#0 /var/www/vdevos/fusic/index.php(109): Kohana_Request->execute()
#1 {main}
2013-05-14 20:16:26 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1126 ]
2013-05-14 20:16:26 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: favicon.ico ~ SYSPATH/classes/kohana/request.php [ 1126 ]
--
#0 /var/www/vdevos/fusic/index.php(109): Kohana_Request->execute()
#1 {main}
2013-05-14 20:23:45 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: assets/css/pages/playlists.cover.css ~ SYSPATH/classes/kohana/request.php [ 1126 ]
2013-05-14 20:23:45 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: assets/css/pages/playlists.cover.css ~ SYSPATH/classes/kohana/request.php [ 1126 ]
--
#0 /var/www/vdevos/fusic/index.php(109): Kohana_Request->execute()
#1 {main}
2013-05-14 20:25:49 --- ERROR: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: assets/css/pages/playlists.cover.css ~ SYSPATH/classes/kohana/request.php [ 1126 ]
2013-05-14 20:25:49 --- STRACE: HTTP_Exception_404 [ 404 ]: Unable to find a route to match the URI: assets/css/pages/playlists.cover.css ~ SYSPATH/classes/kohana/request.php [ 1126 ]
--
#0 /var/www/vdevos/fusic/index.php(109): Kohana_Request->execute()
#1 {main}