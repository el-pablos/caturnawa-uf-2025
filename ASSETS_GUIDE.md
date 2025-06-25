# 🎨 UNAS Fest 2025 - Asset Guide

## 📁 Asset Directory Structure

```
public/assets/
├── images/
│   ├── logo/
│   │   ├── unas-fest-logo.png          # Main logo (recommended: 400x120px)
│   │   ├── unas-fest-logo-white.png    # White version for dark backgrounds
│   │   ├── unas-fest-icon.png          # Icon only (recommended: 64x64px)
│   │   └── favicon.ico                 # Favicon (32x32px)
│   ├── hero/
│   │   ├── hero-bg.jpg                 # Hero background (recommended: 1920x1080px)
│   │   └── hero-illustration.svg       # Hero illustration (vector preferred)
│   ├── competitions/
│   │   ├── technology-banner.jpg       # Technology competition banner (800x600px)
│   │   ├── health-banner.jpg           # Health competition banner (800x600px)
│   │   └── biodiversity-banner.jpg     # Biodiversity competition banner (800x600px)
│   ├── departments/
│   │   ├── security.jpg                # Security department image (400x300px)
│   │   ├── infrastructure.jpg          # Infrastructure department image
│   │   ├── fnb.jpg                     # Food & Beverage department image
│   │   ├── health.jpg                  # Health department image
│   │   ├── debate.jpg                  # Debate department image
│   │   ├── pr.jpg                      # Public Relations department image
│   │   ├── finance.jpg                 # Finance department image
│   │   ├── scientific.jpg              # Scientific Paper department image
│   │   ├── digital.jpg                 # Digital Content department image
│   │   ├── partnership.jpg             # Partnership department image
│   │   ├── entertainment.jpg           # Entertainment department image
│   │   ├── secretarial.jpg             # Secretarial department image
│   │   ├── advertising.jpg             # Advertising department image
│   │   └── it.jpg                      # IT department image
│   ├── testimonials/
│   │   └── default-avatar.png          # Default avatar for testimonials (150x150px)
│   └── blog/
│       ├── tech-tips.jpg               # Blog post featured images (800x450px)
│       └── registration-guide.jpg      # Blog post featured images
├── animations/
│   ├── hero-animation.json             # Lottie animation for hero section
│   ├── loading.json                    # Loading animation
│   └── success.json                    # Success animation
└── videos/
    └── hero-video.mp4                  # Hero background video (optional)
```

## 🎯 Asset Requirements & Specifications

### 🖼️ **Logo Assets**
- **Main Logo**: `unas-fest-logo.png`
  - Size: 400x120px (or maintain aspect ratio)
  - Format: PNG with transparent background
  - Usage: Navbar, footer, general branding

- **White Logo**: `unas-fest-logo-white.png`
  - Size: 400x120px (same as main logo)
  - Format: PNG with transparent background
  - Usage: Dark backgrounds, hero section

- **Icon**: `unas-fest-icon.png`
  - Size: 64x64px
  - Format: PNG with transparent background
  - Usage: Mobile navbar, small spaces

- **Favicon**: `favicon.ico`
  - Size: 32x32px
  - Format: ICO
  - Usage: Browser tab icon

### 🌟 **Hero Section Assets**
- **Background Image**: `hero-bg.jpg`
  - Size: 1920x1080px (Full HD)
  - Format: JPG (optimized for web)
  - Usage: Hero section background overlay

- **Illustration**: `hero-illustration.svg`
  - Format: SVG (vector preferred) or PNG
  - Size: Scalable (SVG) or 800x600px (PNG)
  - Usage: Hero section main visual

### 🏆 **Competition Banners**
- **Technology**: `technology-banner.jpg`
- **Health**: `health-banner.jpg`
- **Biodiversity**: `biodiversity-banner.jpg`
  - Size: 800x600px each
  - Format: JPG (optimized for web)
  - Usage: Competition category cards

### 👥 **Department Images**
All department images should be:
- Size: 400x300px
- Format: JPG (optimized for web)
- Style: Professional, consistent theme
- Usage: About page department cards

### 💬 **Testimonial Assets**
- **Default Avatar**: `default-avatar.png`
  - Size: 150x150px
  - Format: PNG with transparent background
  - Usage: When user doesn't have profile picture

### 📝 **Blog Assets**
- **Featured Images**: Various blog post images
  - Size: 800x450px (16:9 aspect ratio)
  - Format: JPG (optimized for web)
  - Usage: Blog post featured images

### 🎬 **Animation Assets**
- **Lottie Animations**: JSON format
  - Hero animation: Interactive elements
  - Loading animation: Page transitions
  - Success animation: Form submissions

## 🎨 **Design Guidelines**

### **Color Palette**
```css
Primary Blue: #2563eb
Primary Dark: #1d4ed8
Secondary Green: #10b981
Accent Yellow: #f59e0b
Text Dark: #1f2937
Text Light: #6b7280
Background Light: #f8fafc
```

### **Typography**
- **Primary Font**: Inter (body text)
- **Secondary Font**: Poppins (headings)
- **Weights**: 300, 400, 500, 600, 700, 800, 900

### **Image Style Guidelines**
1. **Consistent Color Grading**: Use similar color temperature
2. **Professional Quality**: High resolution, sharp images
3. **Consistent Lighting**: Avoid harsh shadows
4. **Brand Colors**: Incorporate brand colors when possible
5. **Modern Style**: Clean, minimalist approach

## 📱 **Responsive Considerations**

### **Image Optimization**
- Use WebP format when possible for better compression
- Provide multiple sizes for responsive images
- Optimize file sizes (aim for <500KB for large images)
- Use lazy loading for better performance

### **Mobile Optimization**
- Ensure images look good on small screens
- Consider mobile-first design approach
- Test on various device sizes

## 🔧 **Implementation Notes**

### **Asset Loading**
Assets are loaded through the SEO service:
```php
$seo = app(\App\Services\SEOService::class);
$logoUrl = $seo->getAsset('logo.main');
```

### **Fallback Images**
Always provide fallback images or placeholders for missing assets.

### **Performance**
- Use appropriate image formats (WebP > JPG > PNG)
- Implement lazy loading
- Use CDN for better performance
- Optimize images before upload

## 📋 **Asset Checklist**

### **Essential Assets (Must Have)**
- [ ] Main logo (PNG)
- [ ] White logo (PNG)
- [ ] Favicon (ICO)
- [ ] Hero background (JPG)
- [ ] Competition banners (3x JPG)
- [ ] Default avatar (PNG)

### **Recommended Assets**
- [ ] Hero illustration (SVG/PNG)
- [ ] Department images (14x JPG)
- [ ] Blog featured images
- [ ] Lottie animations

### **Optional Assets**
- [ ] Hero background video
- [ ] Additional animations
- [ ] Custom icons

## 🚀 **Quick Start**

1. **Download/Create** assets according to specifications
2. **Place files** in appropriate directories
3. **Optimize images** for web (compress, resize)
4. **Test loading** on different devices
5. **Update** asset paths if needed

## 📞 **Support**

For questions about assets or design guidelines:
- Email: design@unasfest.com
- Documentation: Check this guide first
- Team: Contact IT Department

---

**Note**: This guide ensures consistent branding and optimal performance across the UNAS Fest 2025 website. Follow these guidelines for the best user experience.
