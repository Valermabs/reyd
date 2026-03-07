# PHP Version of REYD Web Application

This folder contains a PHP implementation of the same website structure:
- Home
- Products
- Services
- Contact form

## Run Locally

From the `php-app` directory:

```bash
php -S localhost:8080
```

Then open `http://localhost:8080`.

## Contact Form Behavior

- Sender (`From`): `bgmabs@gmail.com`
- Receiver (`To`): `valmabs4@gmail.com`
- Reply-To: the email entered by the website visitor

## Important

This version uses PHP `mail()`. For production delivery reliability, configure your server mail transport (SMTP relay or local MTA).

This app now also supports direct authenticated SMTP (recommended for local Windows dev and production) using environment variables:

- `SMTP_HOST` (example: `smtp.gmail.com`)
- `SMTP_PORT` (example: `587`)
- `SMTP_SECURE` (`tls`, `ssl`, or `none`)
- `SMTP_USERNAME`
- `SMTP_PASSWORD`
- `SMTP_USER` (alias of `SMTP_USERNAME`)
- `EMAIL_PASSWORD` (alias of `SMTP_PASSWORD`)
- `SMTP_FROM_EMAIL` (optional, default: `bgmabs@gmail.com`)
- `SMTP_FROM_NAME` (optional, default: `REYD Telecom Webpage`)

When `SMTP_HOST`, `SMTP_USERNAME`, and `SMTP_PASSWORD` are present, the contact form sends using SMTP AUTH LOGIN.
Otherwise, it falls back to PHP `mail()`.

Use the website contact form to verify SMTP delivery after setting environment variables.

### Windows Note (OpenSSL)

For Gmail SMTP, OpenSSL must be enabled. Use the included local config and start server like this:

```powershell
& "C:\php\php.exe" -c "c:\xampp_new\htdocs\reyd\php.local.ini" -S localhost:8080
```

If `mail()` is unavailable (common on local Windows setups), submissions are written to:

`storage/contact-inbox.log`

Each line is a JSON record so no contact messages are lost during local development.
