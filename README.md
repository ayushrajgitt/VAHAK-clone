# Vahak Logistics Demo

This is a PHP + SQL demo app for a Vahak-style logistics workflow.

## Run

From this folder:

```powershell
php -S 127.0.0.1:8080 -t public
```

Open:

```text
http://127.0.0.1:8080
```

The SQLite database is created automatically at `data/vahak.sqlite`.

## Folder Structure

```text
app/
  actions/        Form handlers for login, loads, OTP, reviews, admin, support
  core/           Database connection, setup, and helper functions
  views/
    pages/        Home, auth, load, profile, payment, support pages
    pages/roles/  Dashboard and admin pages
    partials/     Shared navbar and load card rendering
public/
  assets/css/     Stylesheets
  index.php       Main entry point
data/             Local SQLite database, ignored by Git
```

## GitHub Note

The local SQLite database is ignored in `.gitignore`, so your demo users, OTPs, and test support messages will not be pushed. When someone runs the project, the app creates a fresh database automatically.

## Demo Accounts

```text
admin@vahak.test / admin123
shipper@vahak.test / shipper123
driver@vahak.test / driver123
transporter@vahak.test / transport123
aman@vahak.test / driver123
sara@vahak.test / driver123
```

## Included Features

- Home page with login and signup in the navbar.
- Vahak logo returns guests to the home page.
- Signup allows one role per email: shipper, driver, or transporter.
- Role dashboards with different actions.
- Shippers can add loads, review profiles, confirm or reject accepted drivers.
- Drivers can find loads, accept loads, mark orders shipped, enter OTP, and trigger fake payment release.
- Transporters can accept loads and assign seeded fleet drivers.
- OTP is generated in-app and shown to the shipper after delivery is marked shipped.
- Both shipper and driver can review each other after fake payment is released.
- Users can edit their name, bio, and profile picture URL.
- Home page includes an FAQ section.
- Customer support lets users ask questions that only admin can answer.
- Payment history shows held and released fake payments.
- Admin can see platform actions and delete users or shipments.
- Admin can answer support tickets.
