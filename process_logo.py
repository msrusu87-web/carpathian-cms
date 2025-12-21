#!/usr/bin/env python3
"""
Process Carphatian logo: make background transparent and resize
"""

try:
    from PIL import Image
    print("✓ PIL/Pillow available")
except ImportError:
    print("✗ PIL/Pillow not available. Installing...")
    import subprocess
    subprocess.check_call(['pip3', 'install', '--user', 'Pillow'])
    from PIL import Image
    print("✓ PIL/Pillow installed")

import os

# Paths
input_path = "/home/ubuntu/carpathian-cms/public/images/logopngcorrect.png"
output_dir = "/var/www/carphatian.ro/html/public/images"
output_filename = "carphatian-logo-transparent.png"
output_path = os.path.join(output_dir, output_filename)

print(f"📥 Loading logo from: {input_path}")

# Load image
img = Image.open(input_path)
print(f"   Original size: {img.size} ({img.width}x{img.height})")
print(f"   Original mode: {img.mode}")

# Convert to RGBA if not already
if img.mode != 'RGBA':
    img = img.convert('RGBA')
    print(f"   Converted to RGBA mode")

# Get pixel data
data = img.getdata()

# Make white/light backgrounds transparent
new_data = []
threshold = 240  # Pixels with RGB values above this become transparent

for item in data:
    # If pixel is mostly white (all RGB channels > threshold)
    if item[0] > threshold and item[1] > threshold and item[2] > threshold:
        # Make it transparent (alpha = 0)
        new_data.append((255, 255, 255, 0))
    else:
        # Keep original pixel
        new_data.append(item)

img.putdata(new_data)
print(f"✓ Made white background transparent")

# Resize to reasonable width (keeping aspect ratio)
target_width = 400  # Good size for website header
aspect_ratio = img.height / img.width
target_height = int(target_width * aspect_ratio)

img_resized = img.resize((target_width, target_height), Image.Resampling.LANCZOS)
print(f"✓ Resized to: {target_width}x{target_height}")

# Save optimized PNG
img_resized.save(output_path, 'PNG', optimize=True)
print(f"✅ Saved to: {output_path}")

# Also create a smaller version for mobile
mobile_width = 200
mobile_height = int(mobile_width * aspect_ratio)
img_mobile = img.resize((mobile_width, mobile_height), Image.Resampling.LANCZOS)
mobile_path = os.path.join(output_dir, "carphatian-logo-transparent-mobile.png")
img_mobile.save(mobile_path, 'PNG', optimize=True)
print(f"✅ Saved mobile version to: {mobile_path}")

print(f"\n🎉 Logo processing complete!")
print(f"   Desktop: {output_filename} ({target_width}x{target_height})")
print(f"   Mobile: carphatian-logo-transparent-mobile.png ({mobile_width}x{mobile_height})")
