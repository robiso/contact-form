<?php
/**
 * Contact form plugin for WonderCMS.
 *
 * It allows to add and manage additional contents on a page.
 *
 * @author Thijs Ferket www.ferket.net
 * @forked and adapted by Herman Adema  
 * @forked by Jeremy Czajkowski
 * @forked by Robert Isoski @robiso
 * @version 3.5.3
 */

global $Wcms;

if (defined('VERSION') && !defined('version')) {
    define('version', VERSION);
    defined('version') OR die('Direct access is not allowed.');
}

// Function to create a default config file if it doesn't exist
function createDefaultConfigFile($configFile) {
    $defaultConfig = <<<EOD
[general]
page=home
emailAddress=YOUR.EMAIL@EXAMPLE.COM
reCaptchaSiteKey=YOUR_RECAPTCHA_SITE_KEY
reCaptchaSecretKey=YOUR_RECAPTCHA_SECRET_KEY
language=en_US
EOD;

    // Write the default configuration to the config file
    if (file_put_contents($configFile, $defaultConfig) === false) {
        die("Error: Unable to create the config file. Please check directory permissions.");
    }
}

// Path to the config file
$configFile = __DIR__ . '/config';

// Check if the config file exists
if (!file_exists($configFile)) {
    // Create the config file with default values
    createDefaultConfigFile($configFile);
}

// Load the configuration
$configuration = parse_ini_file($configFile);
if ($configuration === false) {
    die("Error: Unable to read the config file. Please check its format and permissions.");
}

// Ensure default values are populated in the database.js file
function populateDefaultValues() {
    global $Wcms;

    // Default values
    $defaultEmail = 'YOUR.EMAIL@EXAMPLE.COM';
    $defaultSiteKey = 'YOUR_RECAPTCHA_SITE_KEY';
    $defaultSecretKey = 'YOUR_RECAPTCHA_SECRET_KEY';
    $defaultPage = 'home';
    $defaultLanguage = 'en_US'; // Default language

    // Check and populate contactFormEmail
    $email = $Wcms->get('config', 'contactFormEmail');
    if (empty($email) || is_object($email)) {
        $Wcms->set('config', 'contactFormEmail', $defaultEmail);
    }

    // Check and populate contactFormReCaptchaSiteKey
    $siteKey = $Wcms->get('config', 'contactFormReCaptchaSiteKey');
    if (empty($siteKey) || is_object($siteKey)) {
        $Wcms->set('config', 'contactFormReCaptchaSiteKey', $defaultSiteKey);
    }

    // Check and populate contactFormReCaptchaSecretKey
    $secretKey = $Wcms->get('config', 'contactFormReCaptchaSecretKey');
    if (empty($secretKey) || is_object($secretKey)) {
        $Wcms->set('config', 'contactFormReCaptchaSecretKey', $defaultSecretKey);
    }

    // Check and populate contactFormPage
    $page = $Wcms->get('config', 'contactFormPage');
    if (empty($page) || is_object($page)) {
        $Wcms->set('config', 'contactFormPage', $defaultPage);
    }

    // Check and populate contactFormLanguage
    $language = $Wcms->get('config', 'contactFormLanguage');
    if (empty($language) || is_object($language)) {
        $Wcms->set('config', 'contactFormLanguage', $defaultLanguage);
    }
}

// Populate default values on plugin initialization
populateDefaultValues();

// Define constants
define('CONTACT_FORM_PAGE', $Wcms->get('config', 'contactFormPage') ?? $configuration['page'] ?? 'home');
define('CONTACT_FORM_EMAIL', $Wcms->get('config', 'contactFormEmail') ?? 'YOUR.EMAIL@EXAMPLE.COM');
define('CONTACT_FORM_RECAPTCHA_SITE_KEY', $Wcms->get('config', 'contactFormReCaptchaSiteKey') ?? 'YOUR_RECAPTCHA_SITE_KEY');
define('CONTACT_FORM_RECAPTCHA_SECRET_KEY', $Wcms->get('config', 'contactFormReCaptchaSecretKey') ?? 'YOUR_RECAPTCHA_SECRET_KEY');
define('CONTACT_FORM_LANG', $configuration['language']);


// Add listener for CSS
$Wcms->addListener('css', 'contactfCSS');

function contactfCSS($args) {
    global $Wcms;

    $script = '<link rel="stylesheet" href="'.$Wcms->url("plugins/contact-form/css/style.css").'" type="text/css">';
    $args[0] .= $script;
    return $args;
}

// Add listener for admin settings
$Wcms->addListener('settings', 'contactfSettings');

function contactfSettings($args) {
    global $Wcms;

    // Get the current email address from the database
    $email = $Wcms->get('config', 'contactFormEmail');

    // If the email address is not set, use the default value (only once during initialization)
    if (empty($email) || $email === 'YOUR.EMAIL@EXAMPLE.COM') {
        $email = 'YOUR.EMAIL@EXAMPLE.COM'; // Default value
        $Wcms->set('config', 'contactFormEmail', $email); // Save the default value to the database
    }

    // Get the current reCAPTCHA site key from the database
    $reCaptchaSiteKey = $Wcms->get('config', 'contactFormReCaptchaSiteKey');

    // If the reCAPTCHA site key is not set, use the default value (only once during initialization)
    if (empty($reCaptchaSiteKey) || $reCaptchaSiteKey === 'YOUR_RECAPTCHA_SITE_KEY') {
        $reCaptchaSiteKey = 'YOUR_RECAPTCHA_SITE_KEY'; // Default value
        $Wcms->set('config', 'contactFormReCaptchaSiteKey', $reCaptchaSiteKey); // Save the default value to the database
    }

    // Get the current reCAPTCHA secret key from the database
    $reCaptchaSecretKey = $Wcms->get('config', 'contactFormReCaptchaSecretKey');

    // If the reCAPTCHA secret key is not set, use the default value (only once during initialization)
    if (empty($reCaptchaSecretKey) || $reCaptchaSecretKey === 'YOUR_RECAPTCHA_SECRET_KEY') {
        $reCaptchaSecretKey = 'YOUR_RECAPTCHA_SECRET_KEY'; // Default value
        $Wcms->set('config', 'contactFormReCaptchaSecretKey', $reCaptchaSecretKey); // Save the default value to the database
    }

    // Get the current page from the database
    $page = $Wcms->get('config', 'contactFormPage');

    // If the page is not set, use the default value (only once during initialization)
    if (empty($page) || $page === 'home') {
        $page = 'home'; // Default value
        $Wcms->set('config', 'contactFormPage', $page); // Save the default value to the database
    }

    // Get the current language from the database
    $language = $Wcms->get('config', 'contactFormLanguage');

    // If the language is not set, use the default value (only once during initialization)
    if (empty($language) || $language === 'en_US') {
        $language = 'en_US'; // Default value
        $Wcms->set('config', 'contactFormLanguage', $language); // Save the default value to the database
    }

    // Get all available language files
    $languagesDir = __DIR__ . '/languages';
    $languageFiles = glob($languagesDir . '/*.ini');
    $languageOptions = [];

    foreach ($languageFiles as $file) {
        $languageCode = basename($file, '.ini'); // Extract language code from file name
        $languageOptions[] = $languageCode; // Add to the list of available languages
    }

    // Generate the language dropdown options
    $languageDropdown = '';
    foreach ($languageOptions as $option) {
        $selected = ($option === $language) ? 'selected' : ''; // Mark the current language as selected
        $languageDropdown .= "<option value='$option' $selected>$option</option>";
    }

    // Add a new field in the Security section of the WonderCMS settings modal
    $settingsForm = <<<EOD
<p class="subTitle">Contact form - email</p>
<div class="change">
    <div data-target="config" id="contactFormEmail" class="editText">$email</div>
</div>
<p class="subTitle">Contact form - reCAPTCHA Site Key (optional)</p>
<div class="change">
    <div data-target="config" id="contactFormReCaptchaSiteKey" class="editText">$reCaptchaSiteKey</div>
</div>
<p class="subTitle">Contact form - reCAPTCHA Secret Key (optional)</p>
<div class="change">
    <div data-target="config" id="contactFormReCaptchaSecretKey" class="editText">$reCaptchaSecretKey</div>
</div>
<p class="subTitle">Contact form - page where form is displayed</p>
<div class="change">
    <div data-target="config" id="contactFormPage" class="editText">$page</div>
</div>
<p class="subTitle">Contact form - language</p>
<div class="change">
    <select data-target="config" id="contactFormLanguage" class="wform-control">
        $languageDropdown
    </select>
</div>
EOD;

    // Ensure $args[0] is a string
    if (is_array($args) && isset($args[0])) {
        $modalContent = $args[0];
    } else {
        $modalContent = '';
    }

    // If $modalContent is an object, convert it to a string
    if (is_object($modalContent)) {
        $modalContent = $modalContent->content ?? ''; // Access the 'content' property of the object
    }

    // Find the position of the Password section
    $passwordSectionStart = '<p class="subTitle">Password</p>';
    $passwordSectionStartPos = strpos($modalContent, $passwordSectionStart);

    if ($passwordSectionStartPos !== false) {
        // Insert the email and reCAPTCHA fields before the Password section
        $modalContent = substr_replace($modalContent, $settingsForm, $passwordSectionStartPos, 0);
    } else {
        // Fallback: Append to the end of the modal (if Password section is not found)
        $modalContent .= $settingsForm;
    }

    // Update the $args array with the modified modal content
    $args[0] = $modalContent;

    return $args;
}

// Add listener for JavaScript
$Wcms->addListener('js', 'contactfJS');

function contactfJS($args) {
    global $Wcms;

    // Only include the reCAPTCHA script if valid keys are provided
    if ($Wcms->get('config', 'contactFormReCaptchaSiteKey') !== 'YOUR_RECAPTCHA_SITE_KEY') {
        $script = <<<EOD
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
EOD;
        $args[0] .= $script;
    }

    // Add JavaScript to handle the language dropdown
    $script = <<<EOD
<script>
document.addEventListener('DOMContentLoaded', function() {
    const languageDropdown = document.getElementById('contactFormLanguage');
    if (languageDropdown) {
        languageDropdown.addEventListener('change', function() {
            const value = this.value;
            const id = this.id;
            const target = this.getAttribute('data-target');
            const token = document.querySelector('input[name="token"]').value;

            // Create a form and submit it to save the selected value
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.location.href;

            const fieldnameInput = document.createElement('input');
            fieldnameInput.type = 'hidden';
            fieldnameInput.name = 'fieldname';
            fieldnameInput.value = id;

            const contentInput = document.createElement('input');
            contentInput.type = 'hidden';
            contentInput.name = 'content';
            contentInput.value = value;

            const targetInput = document.createElement('input');
            targetInput.type = 'hidden';
            targetInput.name = 'target';
            targetInput.value = target;

            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = 'token';
            tokenInput.value = token;

            form.appendChild(fieldnameInput);
            form.appendChild(contentInput);
            form.appendChild(targetInput);
            form.appendChild(tokenInput);

            document.body.appendChild(form);
            form.submit();
        });
    }
});
</script>
EOD;
    $args[0] .= $script;

    return $args;
}

function contactfCONTENT() {
    global $Wcms;

    $emailadr = CONTACT_FORM_EMAIL;

    // Get the selected language from the database
    $language = $Wcms->get('config', 'contactFormLanguage') ?? 'en_US';

    // Load the correct language file
    $languageFile = __DIR__ . '/languages/' . $language . '.ini';
    if (file_exists($languageFile)) {
        $i18n = parse_ini_file($languageFile);
    } else {
        // Fallback to English if the selected language file doesn't exist
        $i18n = parse_ini_file(__DIR__ . '/languages/en_US.ini');
    }

    // Fallback for missing language keys
    if (!isset($i18n['recaptcha_empty'])) {
        $i18n['recaptcha_empty'] = 'Please complete the reCAPTCHA.';
    }
    if (!isset($i18n['recaptcha_invalid'])) {
        $i18n['recaptcha_invalid'] = 'Invalid reCAPTCHA. Please try again.';
    }

    // Config
    $cfg['email'] = $emailadr;         // Webmaster email
    $cfg['text'] = TRUE;               // If an error occurs, make text red   ( TRUE is on, FALSE is off )
    $cfg['input'] = TRUE;              // If an error occurs, make border red ( TRUE is on, FALSE is off )
    $cfg['HTML'] = FALSE;              // HTML email ( TRUE is on, FALSE is off )
    $cfg['reCaptchaSiteKey'] = $Wcms->get('config', 'contactFormReCaptchaSiteKey'); // reCAPTCHA site key

    // Email validator
    function checkmail($email) {
        if (preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)) {
            return TRUE;
        }
        return FALSE;
    }

    $formulier = TRUE;
    $final_content = '';

    if (isset($_POST['submitForm']) && ($_SERVER['REQUEST_METHOD'] == "POST")) {
        $aFout = array();

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $subject = trim($_POST['subject']);
        $message = trim($_POST['message']);

        if (empty($name) || (strlen($name) < 3) || preg_match("/([<>])/i", $name)) {
            $aFout[] = $i18n['name_empty'];
            unset($name);
            $fout['text']['name'] = TRUE;
            $fout['input']['name'] = TRUE;
        }
        if (empty($email)) {
            $aFout[] = $i18n['email_empty'];
            unset($email);
            $fout['text']['email'] = TRUE;
            $fout['input']['email'] = TRUE;
        } elseif (checkmail($email) == 0) {
            $aFout[] = $i18n['email_invalid'];
            unset($email);
            $fout['text']['email'] = TRUE;
            $fout['input']['email'] = TRUE;
        }
        if (empty($subject)) {
            $aFout[] = $i18n['subject_empty'];
            unset($subject);
            $fout['text']['subject'] = TRUE;
            $fout['input']['subject'] = TRUE;
        }
        if (empty($message)) {
            $aFout[] = $i18n['message_empty'];
            unset($message);
            $fout['text']['message'] = TRUE;
            $fout['input']['message'] = TRUE;
        }

        // Validate reCAPTCHA v2
        if (!empty($cfg['reCaptchaSiteKey']) && $cfg['reCaptchaSiteKey'] !== 'YOUR_RECAPTCHA_SITE_KEY') {
            if (empty($_POST['g-recaptcha-response'])) {
                $aFout[] = $i18n['recaptcha_empty'];
            } else {
                $recaptchaResponse = $_POST['g-recaptcha-response'];
                $recaptchaSecret = $Wcms->get('config', 'contactFormReCaptchaSecretKey');
                $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify";

                // Use cURL to verify the reCAPTCHA response
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $recaptchaUrl);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'secret' => $recaptchaSecret,
                    'response' => $recaptchaResponse,
                ]));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                $recaptchaResult = json_decode($response);

                if (!$recaptchaResult->success) {
                    $aFout[] = $i18n['recaptcha_invalid'];
                }
            }
        }

        if (!$cfg['text']) {
            unset($fout['text']);
        }
        if (!$cfg['input']) {
            unset($fout['input']);
        }
        if (empty($aFout)) {
            $formulier = FALSE;

            // Use the admin email as the From address
            $fromEmail = $cfg['email']; // Admin email from the plugin configuration
            $fromName = "Website Title"; // Replace with your website name

            // Improved email headers
            $headers = "From: " . $fromName . " <" . $fromEmail . ">\r\n";
            $headers .= "Reply-To: \"" . $name . "\" <" . $email . ">\r\n";
            $headers .= "Return-Path: <" . $fromEmail . ">\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            // Send the email
            if (mail($cfg['email'], $i18n['subject_prefix'] . " " . $subject, $message, $headers)) {
                echo "<h4 class='text-center'<br /><br />" . $i18n['result_sent'] . "</h4>";
            } else {
                echo "<h4 class='text-center'><br /><br />" . $i18n['result_failed'] . "</h4>";
            }
        }
    }

    if ($formulier) {
        $final_content .= "<div id='message'><p class='message'>" . ($_SESSION['SubmitMessage'] ?? '') . "</p></div>";
        unset($_SESSION['SubmitMessage']);

        if ($aFout ?? false) {
            $final_content .= '<div id="errors">' . implode('<br>', $aFout) . '</div>';
        }
        $final_content .= "<div id='containerform'>";

        $final_content .= "<form method='post' action=''>";
        $final_content .= "<p>";
        $final_content .= "<div class='form-group'><input type='text' placeholder='$i18n[name]' id='name' name='name' maxlength='30'";
        if (isset($fout['input']['name'])) { $final_content .= "class='fout'"; } $final_content .= "value='";
        if (!empty($name)) { $final_content .= stripslashes($name); } $final_content .= "' /></div>";

        $final_content .= "<div class='form-group'><input type='text' placeholder='$i18n[email]' id='email' name='email' maxlength='255'";
        if (isset($fout['input']['email'])) { $final_content .= "class='fout'"; } $final_content .= "value='";
        if (!empty($email)) { $final_content .= stripslashes($email); } $final_content .= "' /></div>";

        $final_content .= "<div class='form-group'><input type='text' placeholder='$i18n[subject]' id='subject' name='subject' maxlength='40'";
        if (isset($fout['input']['subject'])) { $final_content .= "class='fout'"; } $final_content .= "value='";
        if (!empty($subject)) { $final_content .= stripslashes($subject); } $final_content .= "' /></div>";

        $final_content .= "<div class='form-group'><textarea placeholder='$i18n[message]' id='message' name='message'";
        if (isset($fout['input']['message'])) { $final_content .= "class='fout'"; } $final_content .= " cols='31' rows='10'>";
        if (!empty($message)) { $final_content .= stripslashes($message); } $final_content .= "</textarea></div>";

        // Add reCAPTCHA v2 Widget
        if (!empty($cfg['reCaptchaSiteKey']) && $cfg['reCaptchaSiteKey'] !== 'YOUR_RECAPTCHA_SITE_KEY') {
            $final_content .= "<div class='g-recaptcha' data-sitekey='{$cfg['reCaptchaSiteKey']}'></div>";
        }

        $final_content .= "<input type='submit' id='submitForm' class='btn btn-primary btn-block' name='submitForm' value='$i18n[submit]' />";
        $final_content .= "</p>";
        $final_content .= "</form>";
        $final_content .= "</div>";
    }

    return $final_content;
}

function contact_form() {
    global $Wcms;

    $result = '';
    if ($Wcms->currentPage == CONTACT_FORM_PAGE) {
        $result .= '<div class="container marginTop20"><div class="col-xs-12 col-md-6 col-md-offset-3">';
        $result .= '<div id="contactform" class="grayFont">';
        $result .= contactfCONTENT();
        $result .= '</div></div></div>';
    }
    return $result;
}
