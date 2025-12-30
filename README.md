# Prerequisites:-

    1) Domain Provider Details with Domain Name and SSL Details.
    2) AWS Account Details to set up EC2 Server \ S3 Bucket \ CloudFront.
            UBUNTU 22 with APACHE 2.4, PHP 8.2.0, MYSQL 8.0, NODE JS 22.00, GIT, COMPOSER
    3) TWILLO SMS API to send OTP.
    4) GOOGLE FCM ACCOUNT To send push notifications.
    5) MAILGUN ACCOUNT to send emails.

# UAT Details:-

    http://2gthr-uat.octallab.com/admin/login

	admin@octal.com / Octal@123

# STAGING Details:-

    http://192.168.1.238:8080/admin/login
	admin@octal.com / Octal@123






#1 Permission Module => Seed only relevant data in the permission list and then test it.
#2 Need to make sure emails, push notifications & OTP are sent, Need to share mailtrap login details to check the Email.
#3 Mention which sections are not part of QC in the current cycle.
#5 Need to make sure the client-side validations are enabled.
#6 Verify test cases are verified.
#7 Proper Image Text Hints
#8 Make changes in the permission migration if a new entry is added in the Dropdowns table.
#9 Cache Required to Flush
    #01 If any changes have taken place in the language table.
	#02 If any changes have occurred in the dropdowns table.
#10 Make sure LOGO and favicon are changed on LOGIN page and dashboard page.
#11 Proper linking of dashboard sections.
#13 Check if email header and footer logo are okay
#10 Update project details in composer.json and .env files.
#11 Update if section/module name changes as compared to SRS.
#12	lONG LENGTH TESTING OF CONTENT MAKE SURE TABLE DOES NOT HAVE ENOUGH LENGTH
#13 Remove all unwanted sections from navigations.
#14 Check if emails are received or not.
#15 Verify notifications are sent to app and update to QC about sync settings.
#17 Check if node js server is up
#18 Set 1MB audio and video limit
#19 Flush Old data
#20 Remove old Storage link and create new one.
#22 Check if video/audio or images are uploading.


o	Reported by users (listing of names: dropdown)
o	Longest streaks (counts)
o	Current streaks (counts)
o	Refer friends (counts)


Before going live.

    1) Write Cron to flush audio tokens which are not been used last 3 days
    2) Write Cron to flush attachments which are not been used last 3 days
    3) Implement SMS API.
    4) Implement Email API.
    5) Implement FCM Notification API.
    6) Update the Email Settings Logo of the header and footer.
    7) On edit video/audio delete old file.
    8) remove soft delete of community and post
    11) remove image and video column from blog table and comment table and others as well if there.
    12) delete community should delete all relevant data including community reports
    13) update s3 key secret of client
    14) php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
