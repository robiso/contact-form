# Contact form plugin for WonderCMS
### Author: Herman Adema (http://ademafoto.nl/)

## Description
Plugin for adding a contact form to a WonderCMS website.

## Preview
![Plugin preview](/preview.jpg)


# Instructions
Before editing any files, create a copy of your current theme and set it as default. Do all the changes in the copied theme. **This will help you avoid a theme update overriding your contact form.**

## 1. Install plugin
1. Login to your WonderCMS website.
2. Click "Settings" and click "Plugins".
3. Find plugin in the list and click "install".
4. Plugin will be automatically activated.
5. Open Settings - Security and enter your email address and specify on which page you want to display the contact form on.

## 2. Put the code below in your custom theme.php to display the contact on the page specified in the config file

```
<?php echo contact_form(); ?>
```
Save changes to theme.php.

## 3. (Optional) Using Google reCaptcha v2
1. Setup your reCaptcha v2 (I'm not a robot) API keys: https://www.google.com/recaptcha/admin/create
2. Enter your API keys in Settings - Security.
3. Your form will now use reCaptcha.
