# simpleFileManager

A demonstration of the PHP code required to implement a simple file manager, including basic password protection.  

Features:

* image file upload
* file view
* file delete
* download zip file of all uploaded files
* session handling and timeout

Read more about the rationale behind the need for the file manager [on my blog](http://www.spokenlikeageek.com/2015/07/20/a-very-simple-file-manager-in-php/)

NOTE: this code is designed as an example of the features of PHP file handling and is not intended to be a fully robust solution.

## Installation

To get up and running:

* Copy the files to your server
* create a folder somewhere to upload the files to
* change the permissions so that the web server has access, for example on centos:

    sudo chown apache:apache /link/to/file

* open login.php and change the password
* open index.php and set the $dir and $webdir to the correct locations.

You are good to go.

## Usage

Use at our own risk!

## License

Do what you want with it. It is provided “as is” with no warranties whatsoever.