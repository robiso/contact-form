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

## 2. In the installed plugin directory, open "config"
 - ** Change your.email@example.com to your actual email**
 - ** Change "home" to page where you want the contact form displayed.

```
emailAddress=your.email@example.com
page=contact

```
Save changes to config file.


## 3. Put the code below in your custom theme.php to display the contact on the page specified in the config file
 - **Important: make sure to change your.email@example.com to your actual email**

```

<div class="container">
	<div class="col-xs-12 col-md-6 col-md-offset-3">
		<div id="contactform" class="grayFont">
       		  	<?php echo contact_form(); ?>
		</div>
	</div>
</div>
```
Save the changed theme.php.
