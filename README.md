# README #

This README would normally document whatever steps are necessary to get your application up and running.

**CMU-IMC** website is written to perform information to visitors and to collect participants' information

1. Static web pages - (c) Faculty of Medicine, CMU
2. Registration System - (c) Sinkanok Labs, Sinkanok Groups
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

* Set-up
* Configuration
* Dependencies
* Database configuration
* How to run tests
* Deployment instructions

### Post-installed Configuration ###

* Writing tests
* Code review
* Other guidelines

### Who do I talk to? ###

* Repo owner or admin
* Other community or team contact