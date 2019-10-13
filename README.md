# contact-form
### Author: Herman Adema (http://ademafoto.nl/)
WonderCMS plugin for adding a contact form to a WonderCMS website


### Put the code below in your theme.php to display the contact form on all pages.
 - **Important: make sure to change your.email@example.com to your actual email**

```
<?php
	global $contact_form_email;
	$contact_form_email = "your.email@example.com";
?>

<div class="container">
	<div class="col-xs-12 col-md-6 col-md-offset-3">
		<div id="contactform" class="grayFont">
       		  	<?php contact_form(); ?>
		</div>
	</div>
</div>
```
Save the changed theme.php and that's it!


### How to display form on a specific page
1. Change 'the name of the page' in the code below and add it to your theme.php (to show it on that specific page)
2. Make sure 'the name of the page' exists.

```
<?php if (wCMS::$currentPage == 'the name of the page'): ?>
	<?php
		global $contact_form_email;
		$contact_form_email = "your.email@example.com";
	?>

	<div class="container marginTop20">
		<div class="col-xs-12 col-md-6 col-md-offset-3">
			<div id="contactform" class="grayFont" style="height: 265px;">
         		  	<?php contact_form(); ?>
			</div>
		</div>
	</div>
<?php endif ?>
```
