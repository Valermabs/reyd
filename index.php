<?php
session_start();
$status = $_SESSION['contact_status'] ?? null;
unset($_SESSION['contact_status']);

$products = [
  [
    'brand' => 'Dahua Technology',
    'tagline' => 'Smarter Security Solutions',
    'description' => 'Experience ultra-high-definition surveillance with AI-powered analytics. Dahua provides world-class security cameras perfect for commercial and residential security.',
    'features' => [
      'AI Perimeter Protection',
      'Full-color Night Vision',
      'Active Deterrence',
      '4K Ultra HD Resolution',
    ],
  ],
  [
    'brand' => 'Hikvision',
    'tagline' => 'Vision Meets Intelligence',
    'description' => 'Hikvision delivers robust cameras with exceptional clarity, weather resistance, and smart tracking capabilities for modern surveillance systems.',
    'features' => [
      'AcuSense Technology',
      'DarkFighter Low-light',
      'Motorized Pan/Tilt/Zoom',
      'Cloud Storage Integration',
    ],
  ],
];

$services = [
  [
    'title' => 'Video Surveillance Installation',
    'icon' => 'cctv',
  ],
  [
    'title' => 'Access Control System Installation',
    'icon' => 'network',
  ],
  [
    'title' => 'Data and Voice Cabling',
    'icon' => 'network',
  ],
  [
    'title' => 'Telephone/PABX System Installation',
    'icon' => 'network',
  ],
  [
    'title' => 'Specialization: DAHUA Cameras',
    'icon' => 'fingerprint',
  ],
  [
    'title' => 'Maintenance and Support',
    'icon' => 'wrench',
  ],
  [
    'title' => 'Storage Solutions (NVR/DVR/Cloud)',
    'icon' => 'hard-drive',
  ],
  [
    'title' => 'Consultation and Quotation',
    'icon' => 'shield',
  ],
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>REYD Telecommunication Services</title>
  <meta name="description" content="REYD Telecommunication Services - Security and telecom solutions in Cagayan de Oro." />
  <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
  <header class="header">
    <div class="container nav-wrap">
      <a href="#home" class="brand" aria-label="REYD Telecommunication Services home">
        <img
          src="assets/reyd-logo.jfif"
          alt="REYD Telecommunication Services logo"
          class="brand-logo"
          width="52"
          height="52"
        />
        <span class="brand-text">REYD Telecommunication Services</span>
      </a>
      <nav class="nav nav-desktop" id="main-nav" aria-label="Primary">
        <a href="#home">Home</a>
        <a href="#products">Products</a>
        <a href="#services">Services</a>
        <a href="#contact" class="btn btn-small">Get a Quote</a>
      </nav>

      <button
        type="button"
        class="nav-toggle"
        id="nav-toggle"
        aria-controls="mobile-nav"
        aria-expanded="false"
        aria-label="Open navigation menu"
      >
        <span class="icon-open">&#9776;</span>
        <span class="icon-close">&#10005;</span>
      </button>

      <nav class="nav-mobile" id="mobile-nav" aria-label="Mobile">
        <a href="#home">Home</a>
        <a href="#products">Products</a>
        <a href="#services">Services</a>
        <a href="#contact" class="btn">Get a Quote</a>
      </nav>
    </div>
  </header>

  <main>
    <section id="home" class="hero">
      <div class="hero-grid-overlay" aria-hidden="true"></div>
      <div class="container hero-grid">
        <div class="hero-copy">
          <p class="eyebrow eyebrow-with-icon"><i data-lucide="shield-check" class="lucide-inline" aria-hidden="true"></i>Trusted Security and Telecom Experts</p>
          <h1>Securing Your World with Advanced Tech</h1>
          <p class="lead">
            REYD Telecommunication Services is a Cagayan de Oro-based company providing
            computer and surveillance services, including high-definition video monitoring installations.
          </p>
          <div class="actions">
            <a class="btn" href="#contact">Get a Free Consultation <i data-lucide="arrow-right" class="btn-icon" aria-hidden="true"></i></a>
            <a class="btn btn-outline" href="#products">View Our Products</a>
          </div>
        </div>
        <aside class="hero-card hero-card-animated">
          <div class="hero-visual" aria-hidden="true">
            <div class="orbit orbit-a"></div>
            <div class="orbit orbit-b"></div>
            <div class="core">
              <i data-lucide="video" class="core-icon" aria-hidden="true"></i>
            </div>
            <div class="floating-badge badge-top">24/7 Monitoring</div>
            <div class="floating-badge badge-bottom"><i data-lucide="shield-check" class="lucide-inline" aria-hidden="true"></i>High Security</div>
          </div>
          <h3>What We Deliver</h3>
          <ul>
            <li>24/7 Monitoring Setup</li>
            <li>High Security Hardware</li>
            <li>Professional Installation</li>
          </ul>
        </aside>
      </div>
    </section>

    <section id="products" class="section">
      <div class="container">
        <p class="eyebrow reveal-on-scroll" style="--reveal-delay: 20ms;">Premium Hardware</p>
        <h2 class="reveal-on-scroll" style="--reveal-delay: 80ms;">Partnering with the Best</h2>
        <div class="grid two">
          <?php foreach ($products as $index => $product): ?>
            <article class="card reveal-on-scroll" style="--reveal-delay: <?= (int) ($index * 120) ?>ms;">
              <h3><?= htmlspecialchars($product['brand']) ?></h3>
              <p class="sub"><?= htmlspecialchars($product['tagline']) ?></p>
              <p><?= htmlspecialchars($product['description']) ?></p>
              <ul class="checks">
                <?php foreach ($product['features'] as $feature): ?>
                  <li><i data-lucide="check-circle-2" class="feature-icon" aria-hidden="true"></i><?= htmlspecialchars($feature) ?></li>
                <?php endforeach; ?>
              </ul>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="services" class="section alt">
      <div class="container">
        <p class="eyebrow reveal-on-scroll" style="--reveal-delay: 20ms;">Our Expertise</p>
        <h2 class="reveal-on-scroll" style="--reveal-delay: 80ms;">Comprehensive Solutions</h2>
        <div class="grid three">
          <?php foreach ($services as $index => $service): ?>
            <article class="card service reveal-on-scroll" style="--reveal-delay: <?= (int) ($index * 85) ?>ms;">
              <div class="service-icon-wrap"><i data-lucide="<?= htmlspecialchars($service['icon']) ?>" class="service-icon" aria-hidden="true"></i></div>
              <h3><?= htmlspecialchars($service['title']) ?></h3>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section id="contact" class="section contact">
      <div class="container contact-grid">
        <div class="reveal-on-scroll" style="--reveal-delay: 40ms;">
          <p class="eyebrow">Get In Touch</p>
          <h2>Let's Secure Your Future</h2>
          <p>
            For inquiries and quotations on high-definition video monitoring and DAHUA Full Color IR Bullet Cameras,
            contact Engr. Reynald. Serving Cagayan de Oro and nearby areas.
          </p>
          <div class="contact-points">
            <p class="contact-point">
              <i data-lucide="phone" class="contact-icon" aria-hidden="true"></i>
              <span><strong>Phone:</strong> <a href="tel:09265358893">0926-535-8893</a></span>
            </p>
            <p class="contact-point">
              <i data-lucide="mail" class="contact-icon" aria-hidden="true"></i>
              <span><strong>Email:</strong> <a href="mailto:reydtelecom@gmail.com">reydtelecom@gmail.com</a></span>
            </p>
            <p class="contact-point">
              <i data-lucide="map-pin" class="contact-icon" aria-hidden="true"></i>
              <span><strong>Location:</strong> Cagayan de Oro, Philippines</span>
            </p>
          </div>
        </div>

        <div class="card form-card reveal-on-scroll" style="--reveal-delay: 140ms;">
          <?php if ($status): ?>
            <p class="notice <?= $status['ok'] ? 'ok' : 'err' ?>"><?= htmlspecialchars($status['message']) ?></p>
          <?php endif; ?>

          <form action="contact.php" method="post" class="form">
            <label>
              Full Name
              <input type="text" name="name" required minlength="2" />
            </label>

            <label>
              Email Address
              <input type="email" name="email" required />
            </label>

            <label>
              Phone Number
              <input type="text" name="phone" required minlength="8" />
            </label>

            <label>
              Message
              <textarea name="message" rows="5" required minlength="10"></textarea>
            </label>

            <button type="submit" class="btn">Send Message</button>
          </form>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container footer-wrap reveal-on-scroll" style="--reveal-delay: 40ms;">
      <p>&copy; <?= date('Y') ?> REYD Telecommunication Services, Cagayan de Oro. All rights reserved.</p>
      <p>Authorized Partner of Dahua and Hikvision</p>
    </div>
  </footer>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    (function () {
      if (window.lucide) {
        window.lucide.createIcons();
      }

      var header = document.querySelector(".header");
      var toggle = document.getElementById("nav-toggle");
      var mobileNav = document.getElementById("mobile-nav");
      if (!header || !toggle || !mobileNav) return;

      function syncHeaderState() {
        if (window.scrollY > 20) {
          header.classList.add("scrolled");
        } else {
          header.classList.remove("scrolled");
        }
      }

      function closeMenu() {
        toggle.setAttribute("aria-expanded", "false");
        mobileNav.classList.remove("open");
      }

      toggle.addEventListener("click", function () {
        var isOpen = toggle.getAttribute("aria-expanded") === "true";
        toggle.setAttribute("aria-expanded", isOpen ? "false" : "true");
        mobileNav.classList.toggle("open", !isOpen);
      });

      mobileNav.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", closeMenu);
      });

      window.addEventListener("resize", function () {
        if (window.innerWidth > 900) closeMenu();
      });

      var revealElements = document.querySelectorAll(".reveal-on-scroll");
      if ("IntersectionObserver" in window && revealElements.length) {
        var revealObserver = new IntersectionObserver(function (entries, observer) {
          entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            entry.target.classList.add("in-view");
            observer.unobserve(entry.target);
          });
        }, { threshold: 0.22, rootMargin: "0px 0px -32px 0px" });

        revealElements.forEach(function (el) {
          revealObserver.observe(el);
        });
      } else {
        revealElements.forEach(function (el) {
          el.classList.add("in-view");
        });
      }

      window.addEventListener("scroll", syncHeaderState, { passive: true });
      syncHeaderState();
    })();
  </script>
</body>
</html>
