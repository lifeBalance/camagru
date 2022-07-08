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

## Docker Setup: Password
In order to avoid committing my password to source control, decided to use the following approach:

1. In my `compose.yaml` file, I set up the following **environment variable** for the container:
```
environment:
    APACHE_DOCUMENT_ROOT: /var/www/html/public
    MAILPWD: ${MAILPWD}
```

2. In my `msmtprc` file, I used `passwordeval` to read from the environment variable running in the container:
```
passwordeval echo "$MAILPWD"
```

3. Before running `docker compose up`, I just set up the environment variable containing password in my host machine:
```
export MAINPWD=1234ass
```

4. Now we can start the containers:
```
docker compose up
```

## Timezone
Something I noticed once the emails we're showing up, was the wrong times due to a misconfigured timezone in the containers. The solution was quite simple, setting up an [environment variable](https://docs.docker.com/engine/reference/builder/#env) in the `Dockerfile`:
```
ENV TZ=Europe/Helsinki
```

For that to work, we need the [tzdata](https://packages.debian.org/buster/tzdata) installed in the container, which we can check using:
```
$ dpkg -s tzdata
Package: tzdata
Status: install ok installed
[...]
```

In my case it was already installed, so setting the aforementioned environment variable was enough. By the way, you can make sure the time is right using the `date` command. Once the **system timezone** was correct, I had to add the following setting to my **PHP configuration**:
```
date.timezone = "Europe/Helsinki"
```

To check all is working, we have to restart the server, pull up a php interactive shell and print the date:
```
$ php -a
php> echo date('l jS \of F Y h:i:s A');
Friday 8th of July 2022 10:51:41 AM
```

The last part of the puzzle was to set the email headers in [mail()](https://www.php.net/manual/en/function.mail.php):
```
$headers .= 'Date: ' . date("r") . "\r\n";
```