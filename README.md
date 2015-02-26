# README #

This README would normally document whatever steps are necessary to get your application up and running.

**[CMU-IMC website](http://cmu-imc.med.cmu.ac.th/)** is written to perform information to visitors and to collect participants' information

1. **Static web pages** - (c) Faculty of Medicine, CMU
2. **Registration System** - (c) Sinkanok Labs, Sinkanok Groups
    1. Participants-side, User-side, or Front-end
    2. Staff-side, Admin-side or Back-end

### Pre-installed Configuration (in config.inc.php) ###

The config file is /reg/config.inc.php. All configs are stored in PHP format. The config variables are under `const // Config variables` and their names are **UPPERCASE**.

```
#!PHP
<?php
require_once 'class.MyConfig.php';
class Config extends MyConfig{
	const // Config variables
		DB_USER="root", DB_PW="[password]",
		DB_NAME="imc", DB_HOST='localhost',
		UPLOAD_FOLDER='images',
		....
		INFO_SHIRT_SIZE="SS\nS\nM\nL\nXL\nXXL"
	;

        ....
        // Methods of Class
        ....
}
?>
```


### Installation ###

1. Create MySQL database and tables for Reg System by SQL in /sql/imc-db-table.sql. If you want to create the tables only, execute SQL in /sql/imc-table.sql.
2. Config the Reg system before uploading (see *Pre-installed Configuration*)
3. Upload all directories to hosting. **EXCEPT** /sql/ and /Template/
4. Log in with DB_USER, DB_PW in config.inc.php, this account is called *'Root'*.
5. Go to Menu **Admin Task > System Configuration** and Edit the configuration
6. Go to Menu **Admin Task > Edit administrator** and Add new administrators who can 'Edit the admin' and 'Config the System'
7. After you add admins who have permissions in the previous step, Root account is automatically deactivated.

### Post-installed Configuration ###

1. Go to Menu **Admin Task > System Configuration**
2. The config is divided in each tabs. Click the tabs to see them.
    * Edit the configuration and **Save** or **Cancel**
    * Click **Reset Config** to reset all configurations
    * Click **Reset Database & Upload directory** to delete all participants' information

### How to Edit the System ###

* The templates is located at /Template/
    1. **IMC_Main.dwt** - the main template for whole page in the System
    2. **IMC_reg.dwt.php** - the template of front-end, it inherits (create and under control) from IMC_Main.dwt. Moreover, it contains PHP script `<?php ... ?> <? ... ?>`.
    3. **IMC_admin.dwt.php** - the back-end template, it also inherits from IMC_Main.dwt and contains PHP script.
* We require PHP programming skill (in Object-oriented programming) to edit the PHP script!
* All filenames of back-end pages (except home.php, home.scr.php) begin with admin.
* All classes in this System use for connect DB, [session](http://php.net/manual/en/intro.session.php) processing, or other functions.
* Learn more about [SKAjax and Modified Sinkanok Architecture here](http://labs.sinkanok.com/)

### Copyright ###

* The static page - copyright 2015 by [Faculty of Medicine](http://www.med.cmu.ac.th/), [Chiang Mai University](http://cmu.ac.th/)
* The Registration System - copyright 2015 by [Sinkanok Labs](http://labs.sinkanok.com), [Sinkanok Groups](http://sinkanok.com)

**All right reserve**