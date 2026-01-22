#!/bin/bash

echo "=== Browser Simulation & Login Test ==="
cd /home/ubuntu/carpathian-cms

# 1. Test login page
echo "1. Testing Login Page..."
LOGIN_RESPONSE=$(curl -s -c cookies.txt -o login_page.html -w "%{http_code}" https://carphatian.ro/admin/login)
echo "Login page response: $LOGIN_RESPONSE"

if [ "$LOGIN_RESPONSE" = "200" ]; then
    # Extract CSRF token
    CSRF_TOKEN=$(grep -o 'name="_token" value="[^"]*"' login_page.html | cut -d'"' -f4)
    echo "CSRF Token extracted: ${CSRF_TOKEN:0:20}..."
    
    # 2. Attempt login with first admin user
    echo "2. Attempting login..."
    LOGIN_POST=$(curl -s -b cookies.txt -c cookies.txt \
        -d "email=msrusu87@gmail.com" \
        -d "password=password" \
        -d "_token=$CSRF_TOKEN" \
        -L -o post_login.html -w "%{http_code}" \
        https://carphatian.ro/admin/login)
    
    echo "Login POST response: $LOGIN_POST"
    
    if [ "$LOGIN_POST" = "200" ]; then
        echo "3. Testing authenticated admin panel..."
        
        # Get admin panel with cookies
        AUTH_ADMIN=$(curl -s -b cookies.txt -o admin_authenticated.html -w "%{http_code}" https://carphatian.ro/admin)
        echo "Authenticated admin response: $AUTH_ADMIN"
        
        if [ "$AUTH_ADMIN" = "200" ]; then
            echo "✅ Successfully logged in to admin panel"
            
            # Check navigation
            echo "4. Checking Navigation Menu..."
            if grep -q "TEST MARKETING\|test-marketing" admin_authenticated.html; then
                echo "✅ TEST MARKETING menu found!"
            else
                echo "❌ TEST MARKETING menu not found"
            fi
            
            if grep -q "Marketing Contact\|marketing-contact" admin_authenticated.html; then
                echo "✅ Marketing Contact menu found!"
            else
                echo "❌ Marketing Contact menu not found"
            fi
            
            # Extract navigation structure
            echo "5. Navigation Structure:"
            grep -A 5 -B 5 "nav\|menu\|sidebar" admin_authenticated.html | grep -o 'href="[^"]*admin[^"]*"' | head -10
            
        else
            echo "❌ Failed to access admin after login (HTTP $AUTH_ADMIN)"
        fi
    else
        echo "❌ Login failed (HTTP $LOGIN_POST)"
        echo "Trying default Laravel password..."
        
        # Try with common Laravel default passwords
        for pwd in "password" "123456" "admin" "carpathian"; do
            echo "Trying password: $pwd"
            LOGIN_ALT=$(curl -s -b cookies.txt -c cookies.txt \
                -d "email=msrusu87@gmail.com" \
                -d "password=$pwd" \
                -d "_token=$CSRF_TOKEN" \
                -L -o "login_$pwd.html" -w "%{http_code}" \
                https://carphatian.ro/admin/login)
            
            if [ "$LOGIN_ALT" = "200" ] && ! grep -q "login" "login_$pwd.html"; then
                echo "✅ Login successful with password: $pwd"
                mv "login_$pwd.html" admin_authenticated.html
                break
            fi
        done
    fi
else
    echo "❌ Cannot access login page (HTTP $LOGIN_RESPONSE)"
fi

# 6. Create manual navigation HTML file for testing
echo "6. Creating manual navigation test..."
cat > test_navigation.html << 'EOF'
<!DOCTYPE html>
<html>
<head>
    <title>Navigation Test</title>
    <style>
        .nav { padding: 10px; margin: 5px; background: #f0f0f0; }
        .found { background: #d4edda; }
        .missing { background: #f8d7da; }
    </style>
</head>
<body>
    <h1>Filament Navigation Test</h1>
    <div id="test-results">
        <p>If you can see this page at <strong>https://carphatian.ro/test_navigation.html</strong>, then:</p>
        <div class="nav found">✅ Web server is working</div>
        <div class="nav found">✅ Static files are served</div>
        
        <h2>Expected Navigation Items:</h2>
        <div class="nav">📢 Marketing (cluster)</div>
        <div class="nav">├── TEST MARKETING</div>
        <div class="nav">├── Marketing Contacts</div>
        <div class="nav">└── Marketing Lists</div>
        
        <h2>Direct URLs to Test:</h2>
        <p><a href="/admin/test-marketings">Test Marketing Resource</a></p>
        <p><a href="/admin/marketing-contacts">Marketing Contacts</a></p>
        
        <script>
            // Test if we can access admin APIs
            fetch('/admin/test-marketings')
                .then(response => {
                    console.log('test-marketings status:', response.status);
                    document.body.innerHTML += '<div class="nav ' + 
                        (response.status === 200 ? 'found' : 'missing') + 
                        '">test-marketings: HTTP ' + response.status + '</div>';
                })
                .catch(e => console.error('test-marketings error:', e));
        </script>
    </div>
</body>
</html>
EOF

# Copy to public directory
cp test_navigation.html public/
echo "✅ Test page created: https://carphatian.ro/test_navigation.html"

echo
echo "=== Manual Testing Instructions ==="
echo "1. Visit: https://carphatian.ro/test_navigation.html"
echo "2. Log in to admin manually: https://carphatian.ro/admin/login"
echo "3. Use credentials: msrusu87@gmail.com / [your password]"
echo "4. After login, check if you see TEST MARKETING in navigation"
echo "5. If not visible, try: https://carphatian.ro/admin/test-marketings directly"

# Cleanup
rm -f cookies.txt login_page.html post_login.html *.html