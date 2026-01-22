#!/usr/bin/env python3
import os

# Map product IDs to their image designs
# Products 13-22 need to be remapped since they shifted
product_configs = {
    13: {"name": "Basic Maintenance", "color": "#14B8A6", "icon": "🛡️", "category": "Maint"},
    14: {"name": "Standard Active", "color": "#06B6D4", "icon": "🔧", "category": "Maint"},
    15: {"name": "Premium Business", "color": "#0891B2", "icon": "⚡", "category": "Maint"},
    16: {"name": "Shop Starter", "color": "#10B981", "icon": "🏪", "category": "Shop"},
    17: {"name": "Shop Growth", "color": "#059669", "icon": "📈", "category": "Shop"},
    18: {"name": "Simple Product Add", "color": "#8B5CF6", "icon": "➕", "category": "Content"},
    19: {"name": "Complex Product SEO", "color": "#7C3AED", "icon": "🔍", "category": "Content"},
    20: {"name": "Product Import Auto", "color": "#6D28D9", "icon": "⬇️", "category": "Content"},
    21: {"name": "Malware Removal", "color": "#DC2626", "icon": "🔒", "category": "Urgent"},
    22: {"name": "Speed Optimization", "color": "#F59E0B", "icon": "⚡", "category": "Urgent"},
}

output_dir = "/var/www/carphatian.ro/html/public/images/products"

for prod_id, config in product_configs.items():
    name = config["name"]
    color = config["color"]
    icon = config["icon"]
    category = config["category"]
    
    svg = f'''<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600">
  <defs>
    <linearGradient id="grad{prod_id}" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{color};stop-opacity:0.9" />
      <stop offset="100%" style="stop-color:{color};stop-opacity:0.6" />
    </linearGradient>
    <filter id="shadow{prod_id}">
      <feDropShadow dx="0" dy="4" stdDeviation="8" flood-opacity="0.3"/>
    </filter>
  </defs>
  <rect width="800" height="600" fill="#F9FAFB"/>
  <rect x="50" y="50" width="700" height="500" rx="20" fill="url(#grad{prod_id})" filter="url(#shadow{prod_id})"/>
  <rect x="50" y="50" width="700" height="80" rx="20" fill="rgba(255,255,255,0.15)"/>
  <text x="100" y="105" font-family="Arial, sans-serif" font-size="32" font-weight="bold" fill="white">
    CARPHATIAN
  </text>
  <text x="330" y="105" font-family="Arial, sans-serif" font-size="28" font-weight="300" fill="rgba(255,255,255,0.9)">
    CMS
  </text>
  <rect x="620" y="70" width="110" height="40" rx="20" fill="rgba(255,255,255,0.25)"/>
  <text x="675" y="97" font-family="Arial, sans-serif" font-size="16" font-weight="600" fill="white" text-anchor="middle">
    {category}
  </text>
  <circle cx="400" cy="280" r="100" fill="rgba(255,255,255,0.2)"/>
  <text x="400" y="315" font-size="100" text-anchor="middle">
    {icon}
  </text>
  <rect x="100" y="410" width="600" height="70" rx="10" fill="rgba(255,255,255,0.2)"/>
  <text x="400" y="455" font-family="Arial, sans-serif" font-size="28" font-weight="600" fill="white" text-anchor="middle">
    {name}
  </text>
  <circle cx="120" cy="480" r="15" fill="rgba(255,255,255,0.15)"/>
  <circle cx="680" cy="480" r="15" fill="rgba(255,255,255,0.15)"/>
  <circle cx="150" cy="510" r="10" fill="rgba(255,255,255,0.1)"/>
  <circle cx="650" cy="510" r="10" fill="rgba(255,255,255,0.1)"/>
</svg>'''
    
    filepath = os.path.join(output_dir, f"product-{prod_id}.svg")
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(svg)
    print(f"✓ Created/Updated: product-{prod_id}.svg ({name})")

print("\n✅ All product images (13-22) created/updated!")
