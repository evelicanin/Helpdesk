<?php
########################################################################
# Upload-Point 1.62 Beta - Simple Upload System
# Copyright (c)2005-2009 Todd Strattman
# strattman@gmail.com
# http://covertheweb.com/upload-point/
# License: LGPL
########################################################################

// TO ADD: $l_opt26hijk - $l_opt33a


// Header.php
$l_head3 = "Options";
$l_head5 = "File Upload";
$l_head6 = "PHP Info";
$l_head7 = "Custom php.ini";

// Options.php
$l_opt8 = "Please feel free to customize your <b>Upload-Point</b> installation below.";
$l_opt10 = "You can revisit this page at any time to make changes and your settings will be remembered.";
$l_opt11 = "Site name and page title.";
$l_opt18 = "Password Protection";
$l_opt19 = "Option to use the built-in password protection.";
$l_opt22 = "The administrator password for <b>Options </b>(options.php)";
$l_opt24 = "The password for <b>File Upload</b> (index.php).";
$l_opt25 = "File Upload";
$l_opt26 = "Whether or not the \"File upload\" option is available. The \"File Upload\" directory will be created if it does not exist.";
$l_opt26a = "Create File Upload directory index file.";
$l_opt26b = "The option to create an index file that will either 1) lists the files in a slightly more pleasing manner than the default server listing or 2) makes the directory non-viewable and redirects the browser to the domain name. If there is an existing index file, the script will not overwrite it.";
$l_opt26c = "Non-viewable or viewable index file.";
$l_opt26d = "Delete the file upload directory index.";
$l_opt26e = "WARNING!!! This will delete any existing index file in the \"File Upload\" directory.";
$l_opt26f = "The number of files to list per page.";
$l_opt26g = "Option to hide file listing from logged in user.";
$l_opt26h = "File Upload :: Index Files";
$l_opt26i = "File Upload :: User Restrictions";
$l_opt26j = "Rename File function.";
$l_opt26k = "Delete File function.";
$l_opt28 = "The file upload directory from the domain name.";
$l_opt28a = " This directory will be automatically created. For instance, if you use \"http://YOURDOMAIN.com/testing/files/\", the file upload directory will equal: \"testing/files\".";
$l_opt29 = "Files to ignore(not list) in the upload directory. \".htaccess\" is ignored by default.";
$l_opt33 = "Custom file upload size settings</b> (php.ini)";
$l_opt33a = "File Upload :: PHP Upload Size";
$l_opt34 = "Most servers allow a user to create a custom <b>php.ini</b> file to override default php.ini settings. This is useful if you want to increase the file size upload limits. The custom <b>php.ini</b> file contains the following lines:<br /><b>file_uploads</b> = On<br /><b>post_max_size</b> = *M<br /><b>upload_max_filesize</b> = *M<br /><b>register_globals</b> = On/Off<br /><b>error_log = error_log</b><br /><b>error_reporting</b> = 2039<br /><b>log_errors</b> = On<br />It is recommended to compare the new <b>\"PHP Info\"</b> results with the original <b>\"PHP Info\"</b> results and make any neccesary additions to match the orginal php.ini.$p2$p Note!!! This may not work on all servers!!!";
$l_opt35 = "Create \"php.ini\"";
$l_opt42 = "Basic Settings";
$l_opt43 = "Redirect speed for index.php. 1000 = 1 second";
$l_opt44 = "Redirect speed for options.php. 1000 = 1 second";
$l_opt104 = "Do Not Change Below (unless you know what you are doing)";
$l_opt105 = "<b>Header</b> Whether or not to use the header/footer.";
$l_opt106 = "<b>Script Directory</b> For instance, if your Upload-Point installation is at \"http://YOURDOMAIN.com/testing/upload\", then \"<b>Script Directory</b> = testing/upload\".";
$l_opt109 = "<b>Data Directory</b> Where the password files, created by the script, are stored. Do not change unless you manually change the \"data\" directory name or location.";
$l_opt110 = "<b>Script Path</b> From script directory to webpage directory.";
$l_opt111 = "<b>Html start tag</b> These are only used for Upload-Point script pages.";
$l_opt112 = "<b>Html end tag</b>";
$l_opt113 = "Edit your configuration";
$l_opt114 = "Your configuration was <b><i>succesfully</i></b> saved.";
$l_opt116 = "Language/Sprache/Lengua/Γλώσσες/Taal/Dil/语言";
$l_opt117 = "English";
$l_opt118 = "Deutsch";
$l_opt119 = "Espa&ntilde;ol";
$l_opt119a = "Nederlands";
$l_opt119b = "简体中文";
$l_opt119c = "Türkçe";
$l_opt119d = "ελληνικα";
$l_opt121 ="has been created and the permissions have been set to";
$l_opt123 ="There was a problem and <b>$fileupload_dir_name</b> was not created.";
$l_opt128 = "reset only";
$l_opt133 = "Viewable";
$l_opt134 = "Non-Viewable";

// Setup.php
$l_set17 = "Maximum file upload size. For instance: 55M";
$l_set18 = "Register Globals, on/off";
$l_set19 = "Create";
$l_set20 = "<b>PHP.INI</b> created!!!";
$l_set21 = "Automatically closing...";
$l_set22 = "<b>THERE WAS A PROBLEM!!!</b> php.ini was not created!!!";

// Upload.php
$l_upload1 = "Dodaj priloge";
$l_upload2 = "My Files";
$l_upload3 = "Name";
$l_upload4 = "Size";
$l_upload5 = "Modified";
$l_upload6 = "Rename";
$l_upload7 = "Delete";
$l_upload8 = "Location";
$l_upload11 = "upload files directory doesn't exist and creation failed!!!";
$l_upload12 = "change permission to 755 failed!!!";
$l_upload13 = "Successfully uploaded";
$l_upload14 = "There was a problem uploading";
$l_upload15 = "Automatically redirecting to the <a href=\"index.php\">Upload-Page</a>";
$l_upload18 = "was <b>succesfully</b> deleted.";
$l_upload19 = "Could not rename file";
$l_upload20 = "was <b>succesfully</b> renamed to";
$l_upload21 = "Uploading, please wait...";

// Global
$l_global5 = "Cancel";
$l_global7 = "On";
$l_global8 = "Off";
$l_global10 = "Successfully Logged Out";
$l_global11 = "Redirecting...";
$l_global12 = "Logout";
$l_global13 = "Enter Password";
$l_global14 = "Login";
$l_global17 = "Yes";
$l_global18 = "No";
?>