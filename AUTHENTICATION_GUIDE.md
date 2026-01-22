# Authentication System Guide

## Overview
Complete authentication system integrated into Carpathian CMS with Laravel Breeze, email verification, and checkout authentication.

## Features Implemented

### 1. **Laravel Breeze Installation**
- Blade stack with dark mode support
- Complete auth views and controllers
- Email verification support

### 2. **Authentication Routes**
All routes defined in `routes/auth.php`:

**Guest Routes:**
- `GET /register` - Registration form
- `POST /register` - Process registration
- `GET /login` - Login form
- `POST /login` - Process login
- `GET /forgot-password` - Password reset request
- `POST /forgot-password` - Send reset link
- `GET /reset-password/{token}` - Password reset form
- `POST /reset-password` - Process password reset

**Authenticated Routes:**
- `GET /verify-email` - Email verification prompt
- `GET /verify-email/{id}/{hash}` - Verify email link
- `POST /email/verification-notification` - Resend verification
- `GET /confirm-password` - Password confirmation
- `POST /confirm-password` - Process confirmation
- `PUT /password` - Update password
- `POST /logout` - Logout

### 3. **Email Verification**
User model updated with `MustVerifyEmail` interface:
```php
class User extends Authenticatable implements FilamentUser, MustVerifyEmail
```

All new registrations require email verification.

### 4. **Checkout Authentication**
`CheckoutController` now requires authentication:
```php
public function __construct()
{
    $this->middleware('auth');
}
```

**User Flow:**
1. User adds products to cart
2. Clicks "Proceed to Checkout"
3. If not logged in → Redirected to login page
4. After login → Redirected back to checkout
5. Can complete purchase

### 5. **Auth Views Created**
Located in `resources/views/auth/`:
- `login.blade.php` - Login form
- `register.blade.php` - Registration form with name, email, password
- `forgot-password.blade.php` - Request password reset
- `reset-password.blade.php` - Reset password form
- `verify-email.blade.php` - Email verification notice
- `confirm-password.blade.php` - Password confirmation

### 6. **Auth Controllers**
Located in `app/Http/Controllers/Auth/`:
- `AuthenticatedSessionController.php` - Login/logout
- `RegisteredUserController.php` - Registration
- `VerifyEmailController.php` - Email verification
- `EmailVerificationNotificationController.php` - Resend verification
- `EmailVerificationPromptController.php` - Verification prompt
- `PasswordResetLinkController.php` - Send reset link
- `NewPasswordController.php` - Reset password
- `ConfirmablePasswordController.php` - Confirm password
- `PasswordController.php` - Update password

## Configuration

### Email Setup
Configure in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@carphatian.ro
MAIL_FROM_NAME="${APP_NAME}"
```

### Session Configuration
Session already configured in `.env`:
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## Testing

### Test Registration Flow
1. Visit `/register`
2. Fill form: name, email, password, password confirmation
3. Submit → Should redirect to email verification notice
4. Check email for verification link
5. Click link → Email verified
6. Can now complete checkout

### Test Login Flow
1. Visit `/login`
2. Enter email and password
3. Check "Remember me" (optional)
4. Submit → Redirected to intended page or home

### Test Checkout with Auth
1. Add product to cart (no auth required)
2. Go to `/cart` → See products
3. Click "Proceed to Checkout"
4. If not logged in → Redirected to `/login` with return URL
5. After login → Redirected back to `/checkout`
6. Complete order

### Test Email Verification
1. Register new account
2. Should see "Verify your email" message
3. Check email inbox
4. Click verification link
5. Should see success message
6. Can now access all authenticated features

## User Model Structure

```php
protected $fillable = [
    'name',
    'email',
    'password',
];

protected $hidden = [
    'password',
    'remember_token',
];

protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
];
```

## Integration with Filament Admin

User model implements both:
- `FilamentUser` - For admin panel access
- `MustVerifyEmail` - For email verification

Admins can access `/admin` panel, regular users access customer dashboard (to be implemented in next phase).

## Next Steps

### Phase 4: User Roles & Groups
- Install and configure Spatie Permission
- Create Admin, Staff Operators, Customers groups
- Assign permissions per group
- Integrate with Filament resources

### Phase 5: Customer Dashboard
- Create `/dashboard` route for customers
- Display order history
- Order tracking
- Profile management

### Phase 6: Guest Checkout (Optional)
- Allow checkout without registration
- Create account after order
- Send verification email

## Security Features

✅ Password hashing with bcrypt
✅ CSRF protection on all forms
✅ Email verification required
✅ Password reset functionality
✅ Remember me token
✅ Session management
✅ Middleware protection for checkout

## Files Modified

1. **User Model:**
   - `/var/www/carphatian.ro/html/app/Models/User.php`
   - Added: `implements MustVerifyEmail`

2. **Routes:**
   - `/var/www/carphatian.ro/html/routes/web.php`
   - Added: `require __DIR__.'/auth.php';`

3. **CheckoutController:**
   - `/var/www/carphatian.ro/html/app/Http/Controllers/CheckoutController.php`
   - Added: Auth middleware constructor

4. **Views Created:**
   - `/var/www/carphatian.ro/html/resources/views/auth/*`
   - 6 authentication views

5. **Controllers Created:**
   - `/var/www/carphatian.ro/html/app/Http/Controllers/Auth/*`
   - 9 authentication controllers

## Troubleshooting

### Issue: Email not sending
**Solution:** Check `.env` mail configuration, test with:
```php
php artisan tinker
Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

### Issue: Login redirect loop
**Solution:** Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Issue: Email verification link not working
**Solution:** Check `APP_URL` in `.env` matches your domain

### Issue: Session not persisting
**Solution:** Check storage/framework/sessions is writable:
```bash
chmod -R 775 storage/
chown -R www-data:www-data storage/
```

---

**Implemented:** December 2024
**Status:** ✅ Complete and tested
**Next:** User Roles & Groups (Phase 4)
