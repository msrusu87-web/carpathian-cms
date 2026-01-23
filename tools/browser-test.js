#!/usr/bin/env node
/**
 * Carpathian CMS Browser Test Script
 * Tests all major routes and checks for JavaScript errors
 * Usage: node browser-test.js
 */

const puppeteer = require('puppeteer-core');

const BASE_URL = 'https://demo.qubitpage.com';

// Routes to test
const routes = {
    frontend: [
        { path: '/', name: 'Homepage' },
        { path: '/login', name: 'Login Page' },
        { path: '/register', name: 'Register Page' },
        { path: '/forgot-password', name: 'Forgot Password' },
        { path: '/shop', name: 'Shop' },
        { path: '/blog', name: 'Blog' },
        { path: '/contact', name: 'Contact' },
        { path: '/about', name: 'About' },
    ],
    admin: [
        { path: '/admin/login', name: 'Admin Login' },
    ]
};

const results = {
    passed: [],
    failed: [],
    jsErrors: [],
    consoleWarnings: []
};

async function testRoute(page, route) {
    const url = `${BASE_URL}${route.path}`;
    const routeErrors = [];
    
    const consoleHandler = msg => {
        if (msg.type() === 'error') {
            routeErrors.push({ route: route.path, message: msg.text() });
        }
    };
    
    page.on('console', consoleHandler);
    page.on('pageerror', error => {
        routeErrors.push({ route: route.path, message: error.message });
    });
    
    try {
        const response = await page.goto(url, { waitUntil: 'networkidle2', timeout: 30000 });
        const status = response.status();
        
        if (status >= 400) {
            results.failed.push({ route: route.path, name: route.name, status, error: `HTTP ${status}` });
            console.log(`❌ ${route.name} (${route.path}) - HTTP ${status}`);
        } else {
            const content = await page.content();
            const phpErrors = ['Fatal error', 'Parse error', 'ErrorException'];
            let hasError = phpErrors.some(e => content.includes(e));
            
            if (hasError) {
                results.failed.push({ route: route.path, name: route.name, error: 'PHP Error detected' });
                console.log(`❌ ${route.name} (${route.path}) - PHP Error`);
            } else {
                results.passed.push({ route: route.path, name: route.name, status });
                console.log(`✅ ${route.name} (${route.path}) - OK (${status})`);
            }
        }
        
        if (routeErrors.length > 0) {
            results.jsErrors.push(...routeErrors);
            console.log(`   ⚠️  ${routeErrors.length} JavaScript error(s)`);
        }
    } catch (error) {
        results.failed.push({ route: route.path, name: route.name, error: error.message });
        console.log(`❌ ${route.name} (${route.path}) - ${error.message}`);
    }
    
    page.off('console', consoleHandler);
}

async function runTests() {
    console.log('🚀 Carpathian CMS Browser Tests\n');
    console.log(`   URL: ${BASE_URL}\n`);
    
    const browser = await puppeteer.launch({
        headless: 'new',
        executablePath: '/usr/bin/chromium-browser',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage', '--disable-gpu']
    });
    
    const page = await browser.newPage();
    await page.setViewport({ width: 1920, height: 1080 });
    
    console.log('📱 Frontend Routes:\n');
    for (const route of routes.frontend) {
        await testRoute(page, route);
    }
    
    console.log('\n🔐 Admin Routes:\n');
    for (const route of routes.admin) {
        await testRoute(page, route);
    }
    
    await browser.close();
    
    console.log('\n═══════════════════════════════');
    console.log('         SUMMARY              ');
    console.log('═══════════════════════════════\n');
    console.log(`✅ Passed: ${results.passed.length}`);
    console.log(`❌ Failed: ${results.failed.length}`);
    console.log(`⚠️  JS Errors: ${results.jsErrors.length}`);
    
    if (results.failed.length > 0) {
        console.log('\n❌ Failed:');
        results.failed.forEach(f => console.log(`   - ${f.name}: ${f.error}`));
    }
    
    const fs = require('fs');
    fs.writeFileSync('/var/www/demo.qubitpage.com/html/storage/logs/browser-test-report.json', 
        JSON.stringify({ timestamp: new Date().toISOString(), results }, null, 2));
    
    console.log('\n📄 Report saved to storage/logs/browser-test-report.json');
    process.exit(results.failed.length > 0 ? 1 : 0);
}

runTests().catch(console.error);
