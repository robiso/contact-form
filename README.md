# Contact form plugin for WonderCMS
### Author: Herman Adema (http://ademafoto.nl/)

## Description
WonderCMS plugin for adding a contact form to


## Preview
![Plugin preview](/preview.jpg)


# Instructions

## 1. Install plugin
1. Login to your WonderCMS website.
2. Click "Settings" and click "Plugins".
3. Find plugin in the list and click "install".
4. Plugin will be automatically activated.

## 2. In the installed plugin directory, open "config" (via FTP or SSH)
 - ** Change your.email@example.com to your actual email**
 - ** Change "home" to page where you want the contact form displayed.

```
emailAddress=your.email@example.com
page=contact

```
Save changes to config file.


## 3. Put the code below in your custom theme.php to display the contact on the page specified in the config file

```
<?php echo contact_form(); ?>
```
Save the changed theme.php.


### Additional notes
If you're not using a custom theme, create a copy of your current theme and add the contact_form() inside the copied theme. This is to avoid a new theme update overriding your contact form.
