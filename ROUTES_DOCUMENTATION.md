# Carpathian.RO - Complete Routes Documentation
**Generated:** December 20, 2025
**Total Routes:** 67 frontend routes

## 🌍 LANGUAGE ROUTES (6)
- `GET /en` - Switch to English
- `GET /de` - Switch to German  
- `GET /es` - Switch to Spanish
- `GET /fr` - Switch to French
- `GET /it` - Switch to Italian
- `GET /ro` - Switch to Romanian

## 🏠 MAIN PAGES (6)
- `GET /` - Homepage (home)
- `GET /blog` - Blog listing (blog)
- `GET /portfolio` - Portfolio listing (portfolio.index)
- `GET /contact` - Contact page (contact)
- `GET /search` - Search results (search)
- `POST /contact/send` - Contact form submission

## 🛒 SHOP ROUTES (3 + MISSING)
- `GET /shop` - Shop homepage (shop.index)
- `GET /shop/products/{slug}` - Individual product page (shop.show)
- `GET /shop/category/{slug}` - Category products (shop.category)
- **MISSING:** `GET /shop/products` - All products listing

## 🛍️ CART ROUTES (5)
- `GET /cart` - View cart (cart.index)
- `POST /cart/add/{id}` - Add to cart (cart.add)
- `PATCH /cart/update/{id}` - Update quantity (cart.update)
- `DELETE /cart/remove/{id}` - Remove item (cart.remove)
- `DELETE /cart/clear` - Clear cart (cart.clear)
- `GET /cart/count` - Get cart count (cart.count)

## 💳 CHECKOUT ROUTES (3)
- `GET /checkout` - Checkout page (checkout.index)
- `POST /checkout/process` - Process order (checkout.process)
- `GET /checkout/success/{order?}` - Order success (checkout.success)

## 📝 BLOG ROUTES (23)
- `GET /posts/{slug}` - Single post (post.show)
- `GET /category/{slug}` - Category posts (category)
- `GET /tag/{slug}` - Tag posts (tag)
- Multiple blog-related API endpoints

## 📁 PORTFOLIO ROUTES (2)
- `GET /portfolio` - Portfolio listing (portfolio.index)
- `GET /portfolio/{slug}` - Project details (portfolio.show)

## 🔐 AUTHENTICATION ROUTES
- Filament admin panel: `/admin`
- Laravel Sanctum API auth endpoints

## 🛠️ PRE-SALE INQUIRY ROUTES (3)
- `GET /product/{id}/pre-sale-inquiry` - Pre-sale form (pre-sale.form)
- `POST /product/{id}/pre-sale-inquiry` - Submit inquiry (pre-sale.submit)
- `GET /pre-sale-inquiry/thank-you/{id}` - Thank you page (pre-sale.thank-you)

## 📊 ADMIN ROUTES (Filament)
- `/admin` - Admin dashboard
- `/admin/login` - Admin login
- All Filament resource routes (products, orders, users, etc.)

## ⚙️ UTILITY ROUTES
- `POST /currency` - Currency switcher (currency.switch)
- `POST /admin/locale/switch` - Admin locale switch

---

## 🚨 KNOWN ISSUES
1. **MISSING:** `/shop/products` route (returns 404)
   - Should list all products
   - Need to add to web.php

## ✅ STATUS SUMMARY
- **Working Pages:** 66/67
- **404 Errors:** 1 (shop/products)
- **Total Routes:** 67 frontend + ~50 admin routes

---

**Last Updated:** December 20, 2025
**Maintained By:** System Admin
