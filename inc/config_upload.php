<?php
########################################################################
# Upload-Point 1.62 Beta - Simple Upload System
# Copyright (c)2005-2009 Todd Strattman
# strattman@gmail.com
# http://covertheweb.com/upload-point/
# License: LGPL
########################################################################

// INITIAL SETTINGS //

// Language to use. (English=en.php .:. Deutsch=de.php .:. Espanol=es.php .:. Greek=gr.php .:. Nederlands=nl.php .:. Turkish=tr.php .:. 简体中文=zh-cn-utf8.php).
$language = "en.php";

// Site name and page title.
$page_title = "Dodavanje fajlova";

// PASSWORD PROTECTION SETTINGS //

// I STRONGLY recommend using the built in password protection, unless you are using SSL. I believe it is much more secure than .htaccess or most other password protection scripts. Options.php must be used for the password protection. The passwords cannot be set using the config.php file. Cookies must be enabled.
$password_protect = "off";

// FILE UPLOAD //
// Option to use basic file upload/delete.

// Whether or not the fileupload option is available. on or off.
$fileupload = "on";

// The file upload directory from the domain name. This directory will be automatically created. For instance, if you use "http://YOURDOMAIN.com/testing/files/", the file upload directory will equal: "testing/files"
$fileupload_dir_name = "apps/helpdesk/upload/files";

// Files to ignore(not list) in the upload directory. ".htaccess" is ignored by default.
$up_ignore1 = "";
$up_ignore2 = "";
$up_ignore3 = "";
$up_ignore4 = "";
$up_ignore5 = "";

// Hide file listing from logged in users. on or off.
$hide = "on";

// Rename File function. on or off.
$rename_file = "on";

// Delete File function. on or off.
$delete_file = "on";

// BASIC SETTINGS //

// Redirect speed for index.php. 1000 = 1 second
$edit_redirect = "500";

// Redirect speed for options.php. 1000 = 1 second
$admin_redirect = "500";

//---------------------------------------------------------------//
// You do not need to make changes below, unless you are changing the default directory names or structure.
//---------------------------------------------------------------//

// Whether or not to use the header/footer.
$head = "off";

// Script directory. For instance, if your Upload-Point installation is at "http://YOURDOMAIN.com/testing/upload", then "$textdir = testing/upload".
$textdir = "apps/helpdesk/inc";

// Data directory name (where the password files, created by the script, are stored). Do not change unless you manually change the "data" directory name.
$datadir = "data";

// Path from script directory to webpage directory. Do not change unless you have moved the script directory from the default (http://YOURDOMAIN.COM/upload).
$pagepath = "apps/helpdesk/inc";


// Html start tag. The following are only used for Upload-Point script pages.
$p = "<p>";
// Html end tag
$p2 = "</p>";

?>