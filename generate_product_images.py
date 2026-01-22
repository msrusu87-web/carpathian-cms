#!/usr/bin/env python3
"""
Generate professional SVG product images for Carphatian CMS
Each image includes the CARPHATIAN logo and product-specific design
"""

import os

# Product configurations with colors and icons
products = [
    # Web Development (1-4, 6)
    {"id": 1, "name": "Landing Page Premium", "color": "#3B82F6", "icon": "🌐", "category": "Web"},
    {"id": 2, "name": "Website Standard", "color": "#06B6D4", "icon": "💻", "category": "Web"},
    {"id": 3, "name": "Blog Professional", "color": "#8B5CF6", "icon": "📝", "category": "Web"},
    {"id": 4, "name": "Portal Corporate", "color": "#0EA5E9", "icon": "🏢", "category": "Web"},
    {"id": 6, "name": "Custom Web Application", "color": "#6366F1", "icon": "⚡", "category": "Web"},
    
    # E-Commerce (5)
    {"id": 5, "name": "E-Commerce Complete", "color": "#10B981", "icon": "🛒", "category": "Shop"},
    
    # AI & Automation (7-9)
    {"id": 7, "name": "Mobile App", "color": "#F59E0B", "icon": "📱", "category": "AI"},
    {"id": 8, "name": "AI Chatbot Agent", "color": "#EC4899", "icon": "🤖", "category": "AI"},
    {"id": 9, "name": "Workflow Automation", "color": "#8B5CF6", "icon": "⚙️", "category": "AI"},
    
    # Design & Branding (10-11)
    {"id": 10, "name": "Logo Design", "color": "#EF4444", "icon": "🎨", "category": "Design"},
    {"id": 11, "name": "Visual Identity Kit", "color": "#F97316", "icon": "✨", "category": "Design"},
    
    # Maintenance Web (12-14)
    {"id": 12, "name": "Basic Maintenance", "color": "#14B8A6", "icon": "🛡️", "category": "Maint"},
    {"id": 13, "name": "Standard Active", "color": "#06B6D4", "icon": "🔧", "category": "Maint"},
    {"id": 14, "name": "Premium Business", "color": "#0891B2", "icon": "⚡", "category": "Maint"},
    
    # E-Commerce Maintenance (15-16)
    {"id": 15, "name": "Shop Starter", "color": "#10B981", "icon": "🏪", "category": "Shop"},
    {"id": 16, "name": "Shop Growth", "color": "#059669", "icon": "📈", "category": "Shop"},
    
    # Content Management (17-19)
    {"id": 17, "name": "Simple Product Add", "color": "#8B5CF6", "icon": "➕", "category": "Content"},
    {"id": 18, "name": "Complex Product SEO", "color": "#7C3AED", "icon": "🔍", "category": "Content"},
    {"id": 19, "name": "Product Import Auto", "color": "#6D28D9", "icon": "⬇️", "category": "Content"},
    
    # On-Demand Services (20-21)
    {"id": 20, "name": "Malware Removal", "color": "#DC2626", "icon": "🔒", "category": "Urgent"},
    {"id": 21, "name": "Speed Optimization", "color": "#F59E0B", "icon": "⚡", "category": "Urgent"},
]

def create_svg(product, output_dir):
    """Create a professional SVG image for a product"""
    
    prod_id = product["id"]
    name = product["name"]
    color = product["color"]
    icon = product["icon"]
    category = product["category"]
    
    # SVG template with modern design
    svg = f'''<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600">
  <!-- Background gradient -->
  <defs>
    <linearGradient id="grad{prod_id}" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{color};stop-opacity:0.9" />
      <stop offset="100%" style="stop-color:{color};stop-opacity:0.6" />
    </linearGradient>
    <filter id="shadow{prod_id}">
      <feDropShadow dx="0" dy="4" stdDeviation="8" flood-opacity="0.3"/>
    </filter>
  </defs>
  
  <!-- Background -->
  <rect width="800" height="600" fill="#F9FAFB"/>
  
  <!-- Main card -->
  <rect x="50" y="50" width="700" height="500" rx="20" fill="url(#grad{prod_id})" filter="url(#shadow{prod_id})"/>
  
  <!-- Top brand bar -->
  <rect x="50" y="50" width="700" height="80" rx="20" fill="rgba(255,255,255,0.15)"/>
  
  <!-- CARPHATIAN Logo -->
  <text x="100" y="105" font-family="Arial, sans-serif" font-size="32" font-weight="bold" fill="white">
    CARPHATIAN
  </text>
  <text x="330" y="105" font-family="Arial, sans-serif" font-size="28" font-weight="300" fill="rgba(255,255,255,0.9)">
    CMS
  </text>
  
  <!-- Category badge -->
  <rect x="620" y="70" width="110" height="40" rx="20" fill="rgba(255,255,255,0.25)"/>
  <text x="675" y="97" font-family="Arial, sans-serif" font-size="16" font-weight="600" fill="white" text-anchor="middle">
    {category}
  </text>
  
  <!-- Large icon -->
  <circle cx="400" cy="280" r="100" fill="rgba(255,255,255,0.2)"/>
  <text x="400" y="315" font-size="100" text-anchor="middle">
    {icon}
  </text>
  
  <!-- Product name -->
  <rect x="100" y="410" width="600" height="70" rx="10" fill="rgba(255,255,255,0.2)"/>
  <text x="400" y="455" font-family="Arial, sans-serif" font-size="28" font-weight="600" fill="white" text-anchor="middle">
    {name}
  </text>
  
  <!-- Decorative elements -->
  <circle cx="120" cy="480" r="15" fill="rgba(255,255,255,0.15)"/>
  <circle cx="680" cy="480" r="15" fill="rgba(255,255,255,0.15)"/>
  <circle cx="150" cy="510" r="10" fill="rgba(255,255,255,0.1)"/>
  <circle cx="650" cy="510" r="10" fill="rgba(255,255,255,0.1)"/>
</svg>'''
    
    # Save to file
    filename = f"product-{prod_id}.svg"
    filepath = os.path.join(output_dir, filename)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(svg)
    
    print(f"✓ Created: {filename} ({name})")
    return filename

def main():
    output_dir = "/var/www/carphatian.ro/html/public/images/products"
    
    print("🎨 Generating professional SVG images for Carphatian CMS products...")
    print(f"📁 Output directory: {output_dir}\n")
    
    created_files = []
    
    for product in products:
        filename = create_svg(product, output_dir)
        created_files.append(filename)
    
    print(f"\n✅ Successfully created {len(created_files)} product images!")
    print(f"   All images include the CARPHATIAN logo")
    
if __name__ == "__main__":
    main()
