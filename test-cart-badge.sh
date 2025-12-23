#!/bin/bash

echo "=== Testing Cart Badge System ==="
echo ""

# Test 1: Check cart count endpoint (empty cart)
echo "1. Testing /cart/count endpoint (empty session):"
curl -s http://carphatian.ro/cart/count | jq '.'
echo ""

# Test 2: Add a product to cart
echo "2. Adding product to cart (ID 1):"
curl -s -X POST -H "Content-Type: application/x-www-form-urlencoded" \
  -d "quantity=2" \
  -c /tmp/cart-cookies.txt \
  http://carphatian.ro/cart/add/1
echo "Done"
echo ""

# Test 3: Check cart count after adding
echo "3. Testing /cart/count after adding product:"
curl -s -b /tmp/cart-cookies.txt http://carphatian.ro/cart/count | jq '.'
echo ""

# Test 4: Check cart view
echo "4. Checking if cart view loads:"
STATUS=$(curl -s -o /dev/null -w "%{http_code}" -b /tmp/cart-cookies.txt http://carphatian.ro/cart)
if [ "$STATUS" -eq 200 ]; then
    echo "✅ Cart view loads successfully (HTTP $STATUS)"
else
    echo "❌ Cart view failed (HTTP $STATUS)"
fi
echo ""

# Cleanup
rm -f /tmp/cart-cookies.txt

echo "=== Test Complete ==="
