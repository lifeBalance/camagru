# Sending Email
One of the main features of our application is the ability to send email in response to certain user interactions. We've used a couple of utilities for this:

* [sendmail](https://en.wikipedia.org/wiki/Sendmail)
* [msmtp](https://marlam.de/msmtp/)

## Sendmail
`sendmail` is an old command-line utility that dates back to the early days of the internet. This program allows us to send email from the command-line:
```
echo "Subject: Hello this is subject" | sendmail recipient@domain.com
```

> The `-v` flag (short of **verbose**) comes in handy when debugging errors.

`sendmail` is an [MUA](https://en.wikipedia.org/wiki/Email_client) (short for **Mail User Agent**, aka an **email client** that uses the [SMTP](https://en.wikipedia.org/wiki/Simple_Mail_Transfer_Protocol) protocol, (short for Simple Mail Transfer Protocol).

## MSMTP
[msmtp](https://marlam.de/msmtp/) is another SMTP client, meaning an **email client** that uses the **SMTP protocol**. This is actually the tool I used to send emails from the app. In order to do that, I had to install to my container the following packages:

* [msmtp]()
* [msmtp-mta](), which basically creates a symlink to ``sendmail``.
* [mailutils]()

Then I created the following [configuration file](https://marlam.de/msmtp/msmtp.html):
```
# Set default values for all following accounts.
defaults
port            587
tls             on
tls_starttls    on
tls_trust_file  /etc/ssl/certs/ca-certificates.crt
tls_certcheck   on
logfile         /root/msmtp.log

# Outlook account
account     outlook
host        smtp.office365.com
auth        on
from        "someaccount@outlook.com"
user        "someaccount@outlook.com"
password    "xxxxxxx"
# passwordeval gpg --no-tty --quiet --decrypt ~/.msmtp-gmail.gpg
# passwordeval gpg --no-tty --quiet --decrypt ~/.msmtp-outlook.gpg

# Set a default account
account default : outlook
```

## Switch to Gmail
During development, the app was sending plenty of email to *temporary email addresses* (such as [temp-mail.org](https://temp-mail.org/en) or [tempail.com](https://tempail.com/en/), which ended up in blocking my **outlook account**. Then I decided to create an account in gmail, then updated the msmtp config file:
```
# Set default values for all following accounts.
defaults
port            587
tls             on
tls_starttls    on
tls_trust_file  /etc/ssl/certs/ca-certificates.crt
tls_certcheck   on

# Outlook account
account     gmail
host        smtp.gmail.com
auth        on
from        "some.email@gmail.com"
user        "some.email@gmail.com"
password    "xxxxxxxxxxxx"
```

But I was having problems authenticating with just username and password:
```
sendmail: authentication failed (method PLAIN)
sendmail: server message: 535-5.7.8 Username and Password not accepted.
```

The reason for that was:
```
From May 30, 2022, ​​Google no longer supports the use of third-party apps or devices which ask you to sign in to your Google Account using only your username and password.
```

So I had to take the following the steps:

1. Go to your Google Account (click on your avatar -> Manage your Google Account(above list of yout all account))
2. Find Security Menu (left side menu)
3. Enable **2-Step Verification**.
4. After that you will see "App Password" option in Security page. (If not search it in Google Account Search box.
5. Select Mail from the "Select App" dropdown
6. Select the device from "Device" dropdown and click the "Generate" button. Copy password and use it in your application where required.

> As tempting as it may sounds, it's not advisable to send email **temporary addresses** to test the app, because it could get your main account blocked, and fail during evaluation.

Then, during development and evaluation, I used the first mail account I created in outlook for the users of the app.

## Docker Setup
