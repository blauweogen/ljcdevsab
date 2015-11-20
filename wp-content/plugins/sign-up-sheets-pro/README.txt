=== Sign-up Sheets Pro ===
Author: DLS Software Studios <www.dlssoftwarestudios.com>
Version: 2.0.18.1

== Installation ==

If you already have the lite version of the plugin installed

1. Deactivate the current Sign-up Sheets plugin
2. Delete the "sign-up-sheets" folder and it's contents from your plugins directory
3. Follow the step for a fresh install below (note, that you will retain all previous data)

Fresh install...

1. Copy the new "sign-up-sheets-pro" folder into your /wp-content/plugins/ directory or import the zip file via your WordPress admin in Plugins > Add New > Import
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a new blank page and add the shortcode [sign_up_sheet]

== Frequently Asked Questions ==

= How do I create a Sign-up Sheet page on my site? =
You can do this by creating any page or post and adding the shortcode `[sign_up_sheet]` to the content.  Then, go to the "Sign-up Sheets" section of your WP admin and create a new sheet.

= Can I change the "from" address on the confirmation email? =
Yes, in `Settings > Sign-up Sheets` you can specify any email you want.  It defaults to the email address set in `Settings > General`.

= How can I suggest an idea for the plugin? =
Send us an email through our website at http://www.dlssoftwarestudios.com/contact-us/  We appreciate any and all feedback, but can't guarantee it will make it into the next version.  If you are in need a modification immediately, we are available for hire.  Please contact us at the link above and we can provide a quote.

= How do I display sheets from only 1 specific category (Pro version only) =
To filter by category, you can include the category id # in the shortcode to determine which category will display on that page.   As an example, the following shortcode would show all sheets associated with category #5... `[sign_up_sheet category_id="5"]`

= When are email reminders sent? (Pro version only) =
When you have the "reminder" setting turned on in `Settings > Sign-up Sheets`, a WordPress event will be triggered to check for reminders needing to be sent out.  This happens when someone visits your site, but no more than once per hour.  You can set how many days prior to the event you would like reminders to go out.

= How to I change the sheet list heading? =
The list title defaults to 'Current Sign-up Sheets'.  To customize this, you can add the option `list_title` to your shortcode (example: `[sign_up_sheet list_title=""]`).  If you are using the Pro version and filtering by a specific category, you can also have this default to the name of the category by adding the option `list_title_is_category` (example: `[sign_up_sheet category_id=4 list_title_is_category=true]`).

= What dynamic variables can I use in my confirmation email? =
{site_name}
{site_url}
{from_email}
{removal_link}
{signup_details}
{signup_firstname}
{signup_lastname}
{signup_email}


== Changelog ==

= 2.0.18.1 =
* Fixed timezone bug on Settings page

= 2.0.18 =
* Added ability to mark custom sign-up fields to display results on frontend
* Added Help section
* Fixed missing Sign-up Sheets menu conflict with some plugins
* Fixed ascii characters in site title in emails
* Fixed custom checkboxes remembering submission if error occurs
* Fixed required custom field checkboxes from blocking form submission
* Fixed sign-up form incorrectly submitting to HTTPS on certain servers
* Fixed calendar popup on newly added tasks
* Update "Settings" link location to be under Sign-up Sheets menu item

= 2.0.17 =
* Added ability to set custom fields as required
* Added option to expand Additional Settings by default
* Fixed HTML validation errors
* Fixed PHP warning on sign-up form when WP_DEBUG is enabled

= 2.0.16.1 =
* Fixed PHP warning in admin when editing sheet
* Fixed custom task fields showing up on all sheet in edit screen

= 2.0.16 =
* Added custom task fields
* Added visual editor to sheet details
* Added ability to change the task title label
* Added ability to edit removal confirmation email content
* Updated settings page for a cleaner look and feel
* Updated categories selection box to be hidden if none are created
* Fixed bug where trashed sheets were showing up on Export All
* Fixed fatal error if activating Pro before deactivating the free version

= 2.0.15 =
* Added ability to display sheets in compact view
* Added ability to edit reminder email content
* Fixed date in confirmation email to display task date if applicable rather than sheet date
* Fixed admin menu icon

= 2.0.14 =
* Added task date to sign-up form if applicable
* Updated export all to allow getting larger amounts of data for certain setups
* Fixed copy functionality to included additional sheet fields and categories as well as recent bug that prevented tasks from being copied
* Fixed sheet fields table so empty records are removed
* Fixed Sign-up Sheets Manager Role from throwing permissions issue on Profile page
* Fixed PHP Notices

= 2.0.13 =
* Fixed security bug with Remember Me

= 2.0.12 =
* Added option to set dates on the task level instead of the entire sheet for display and reminders
* Added sheet specific settings options for phone/address fields
* Added ability to assign custom fields to only specific sheets
* Added option to display all user data on sign-ups on the frontend
* Added optional "remember me" checkbox on the sign-up form
* Updated styles on add/remove task indicators in admin
* Fixed HTML special characters from displaying in email headers and in plaintext emails
* Fixed empty table cell width on View Sign-up Sheets page in admin
* Fixed sheets not expiring when called directly
* Fixed database update check

= 2.0.11 =
* Added ability to have specific BCC's per sheet
* Fixed call to missing image in admin
* Fixed random PHP Notices thrown in admin
* Fixed sporadic issue with emails not being sent after updating to Pro
* Updated code comments

= 2.0.10 =
* Added ability to create global custom fields on the sign-up form

= 2.0.9 =
* Added fields to allow adding customer name and email address to confirmation email
* Added CSS classes to backlinks
* Added version to stylesheet
* Updated signup form CSS to be more responsive-friendly
* Fixed displaying multiple shortcodes on a page where id is specified
* Fixed backlink from displaying on shortcodes where id is specified
* Fixed missing domain for text translations

= 2.0.8 =
* Added ability to edit confirmation email
* Fixed missing asterisk on simple captcha

= 2.0.7 =
* Fixed bug where trashed sheets with no date specified would display on frontend
* Fixed bug where trashed individual sheet pages were available on the frontend

= 2.0.6 =
* Added options for the front-end display name for signed up users

= 2.0.5 =
* Added ability to have more than one [sign-up-sheet] shortcode on one page
* Added ability to set list title to the category name by adding shortcode option list_title_is_category=true

= 2.0.4 =
* Fixed sheets disappearing before the end of day on date of event
* Fixed bug that disallowed leaving the date field blank

= 2.0.3 =
* Added optional event reminders
* Fixed security bug on export
* Fixed sheet edit screen to prevent the quantity of available tasks from being decreased below the number of current signups
* Fixed default email subject display in Settings
* Fixed "No, Thanks" link on sign-up form

= 2.0.2 =
* Fixed export file header row
* Fixed bug with database phone and address fields default value
* Fixed possible plugin conflicts when using reCAPTCHA
* Updated creating of token so it occurs on every signup
* Updated reCAPTCHA base functions to prevent conflicts with other plugins using similar library
* Added date_created to each signup in DB
* Added brackets around removal link to help prevent line breaks killing the link
* Added check and confirmation button for possible duplicate sign-ups on the same task

= 2.0.1 =
* Fixed bug with category filtering