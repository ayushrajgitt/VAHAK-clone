<?php
// Vahak Clone — Homepage
$site_name = "Vahak";
$tagline = "India's Largest Transport Network";
$current_year = date("Y");

$features = [
  [
    "icon" => "truck",
    "title" => "Instant Load Matching",
    "desc" => "Get matched with verified transporters in minutes. No delays, no middlemen — just seamless freight movement."
  ],
  [
    "icon" => "shield-check",
    "title" => "Verified Transporters",
    "desc" => "Every transporter on our platform is background-checked, GST-verified, and rated by real shippers."
  ],
  [
    "icon" => "map-pin",
    "title" => "Live GPS Tracking",
    "desc" => "Track your shipment in real time from pickup to delivery. Know exactly where your load is at all times."
  ],
  [
    "icon" => "currency-rupee",
    "title" => "Best Market Rates",
    "desc" => "Compare quotes from multiple transporters and get the most competitive freight rates in the market."
  ],
  [
    "icon" => "headset",
    "title" => "24/7 Support",
    "desc" => "Our dedicated logistics experts are available around the clock to assist you with any shipment."
  ],
  [
    "icon" => "file-invoice",
    "title" => "Digital Documentation",
    "desc" => "E-way bills, PODs, and invoices — all paperwork handled digitally so you never miss a compliance step."
  ],
];

$steps = [
  ["num" => "01", "title" => "Post Your Load", "desc" => "Enter pickup, destination, load details, and your budget in under 60 seconds."],
  ["num" => "02", "title" => "Get Quotes", "desc" => "Verified transporters bid on your load. Compare rates, ratings, and vehicle type."],
  ["num" => "03", "title" => "Confirm & Track", "desc" => "Book your preferred transporter and track the shipment live on the map."],
  ["num" => "04", "title" => "Safe Delivery", "desc" => "Receive your load, digitally sign the POD, and release payment — done."],
];

$stats = [
  ["num" => "15L+", "label" => "Registered Transporters"],
  ["num" => "50L+", "label" => "Loads Moved"],
  ["num" => "28+", "label" => "States Covered"],
  ["num" => "98%", "label" => "On-time Delivery"],
];

$reviews = [
  [
    "name" => "Ramesh Gupta",
    "role" => "Textile Manufacturer, Surat",
    "initials" => "RG",
    "stars" => 5,
    "quote" => "Vahak transformed how we ship goods. Earlier we'd spend hours calling brokers — now I post a load and get 5 quotes in 10 minutes. Unbelievable efficiency."
  ],
  [
    "name" => "Priya Sharma",
    "role" => "E-commerce Seller, Delhi",
    "initials" => "PS",
    "stars" => 5,
    "quote" => "The live tracking feature is a game-changer. My customers always ask for updates and now I can give them real-time location. Absolutely love this platform."
  ],
  [
    "name" => "Mahendra Singh",
    "role" => "Truck Owner, Rajasthan",
    "initials" => "MS",
    "stars" => 5,
    "quote" => "As a transporter, finding return loads used to be a nightmare. Vahak keeps my trucks loaded both ways. My revenue has gone up by nearly 40% in six months."
  ],
  [
    "name" => "Anita Reddy",
    "role" => "FMCG Distributor, Hyderabad",
    "initials" => "AR",
    "stars" => 4,
    "quote" => "Great platform overall. The verified transporter badge gives me confidence that I'm dealing with professionals. Customer support is also very responsive."
  ],
];

$footer_links = [
  "Company" => [
    ["label" => "About Us", "href" => "#"],
    ["label" => "Careers", "href" => "#"],
    ["label" => "Press & Media", "href" => "#"],
    ["label" => "Blog", "href" => "#"],
    ["label" => "Contact Us", "href" => "#"],
  ],
  "For Shippers" => [
    ["label" => "Post a Load", "href" => "#"],
    ["label" => "Find Trucks", "href" => "#"],
    ["label" => "Freight Rates", "href" => "#"],
    ["label" => "E-way Bill Help", "href" => "#"],
    ["label" => "Insurance", "href" => "#"],
  ],
  "For Transporters" => [
    ["label" => "Find Loads", "href" => "#"],
    ["label" => "Manage Fleet", "href" => "#"],
    ["label" => "Driver App", "href" => "#"],
    ["label" => "Earnings Calculator", "href" => "#"],
    ["label" => "Partner Program", "href" => "#"],
  ],
  "Legal" => [
    ["label" => "Terms of Service", "href" => "#"],
    ["label" => "Privacy Policy", "href" => "#"],
    ["label" => "Cookie Policy", "href" => "#"],
    ["label" => "Grievance Officer", "href" => "#"],
  ],
];

$social_links = [
  ["name" => "Facebook", "icon" => "brand-facebook", "href" => "#"],
  ["name" => "Twitter / X", "icon" => "brand-x", "href" => "#"],
  ["name" => "LinkedIn", "icon" => "brand-linkedin", "href" => "#"],
  ["name" => "Instagram", "icon" => "brand-instagram", "href" => "#"],
  ["name" => "YouTube", "icon" => "brand-youtube", "href" => "#"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($site_name) ?> — <?= htmlspecialchars($tagline) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Lato:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --primary:   #E8460A;
      --primary-d: #c03a08;
      --dark:      #1a1a2e;
      --mid:       #16213e;
      --accent:    #0f3460;
      --light:     #f5f5f5;
      --white:     #ffffff;
      --grey:      #6c757d;
      --card-bg:   #ffffff;
      --text:      #2d2d2d;
      --radius:    10px;
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Lato', sans-serif;
      color: var(--text);
      background: var(--white);
      overflow-x: hidden;
    }

    /* ── NAV ── */
    nav {
      position: fixed; top: 0; left: 0; right: 0;
      z-index: 100;
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 6%; height: 68px;
      background: rgba(26,26,46,0.97);
      backdrop-filter: blur(8px);
      border-bottom: 1px solid rgba(255,255,255,0.07);
    }
    .nav-logo {
      font-family: 'Poppins', sans-serif;
      font-size: 22px; font-weight: 800;
      color: var(--white); letter-spacing: -0.5px;
      text-decoration: none;
    }
    .nav-logo span { color: var(--primary); }
    .nav-links {
      display: flex; align-items: center; gap: 32px; list-style: none;
    }
    .nav-links a {
      font-family: 'Poppins', sans-serif;
      font-size: 13.5px; font-weight: 500;
      color: rgba(255,255,255,0.75); text-decoration: none;
      transition: color 0.2s;
    }
    .nav-links a:hover { color: var(--white); }
    .nav-btn {
      background: var(--primary); color: var(--white) !important;
      padding: 8px 20px; border-radius: 6px; font-weight: 600 !important;
    }
    .nav-btn:hover { background: var(--primary-d) !important; color: var(--white) !important; }
    .hamburger {
      display: none; flex-direction: column; gap: 5px;
      cursor: pointer; background: none; border: none; padding: 4px;
    }
    .hamburger span { display: block; width: 24px; height: 2px; background: #fff; border-radius: 2px; }

    /* ── HERO ── */
    .hero {
      min-height: 100vh;
      background: linear-gradient(135deg, var(--dark) 0%, var(--mid) 50%, var(--accent) 100%);
      display: flex; align-items: center;
      padding: 100px 6% 80px;
      position: relative; overflow: hidden;
    }
    .hero::before {
      content: '';
      position: absolute; top: -120px; right: -120px;
      width: 500px; height: 500px; border-radius: 50%;
      background: radial-gradient(circle, rgba(232,70,10,0.18) 0%, transparent 70%);
    }
    .dots {
      position: absolute; inset: 0; pointer-events: none;
      background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
      background-size: 36px 36px;
    }
    .hero-inner {
      position: relative; z-index: 2;
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 60px; align-items: center; width: 100%;
    }
    .hero-tag {
      display: inline-flex; align-items: center; gap: 8px;
      background: rgba(232,70,10,0.15);
      border: 1px solid rgba(232,70,10,0.35);
      border-radius: 100px; padding: 5px 14px;
      font-size: 12px; font-weight: 600; letter-spacing: 0.1em;
      color: #ff7b4a; text-transform: uppercase;
      margin-bottom: 22px; animation: fadeUp 0.6s ease both;
    }
    .hero-tag::before {
      content: ''; width: 7px; height: 7px; border-radius: 50%;
      background: #ff7b4a; animation: blink 1.6s ease infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.2} }
    .hero-title {
      font-family: 'Poppins', sans-serif;
      font-size: clamp(32px,4.5vw,56px);
      font-weight: 800; line-height: 1.1;
      color: var(--white); margin-bottom: 20px;
      animation: fadeUp 0.6s 0.1s ease both;
    }
    .hero-title span { color: var(--primary); }
    .hero-desc {
      font-size: clamp(14px,1.2vw,17px);
      color: rgba(255,255,255,0.6); line-height: 1.7;
      max-width: 480px; margin-bottom: 36px;
      animation: fadeUp 0.6s 0.2s ease both;
    }
    .hero-btns {
      display: flex; gap: 14px; flex-wrap: wrap;
      animation: fadeUp 0.6s 0.3s ease both;
    }
    .btn-primary {
      background: var(--primary); color: #fff; border: none;
      padding: 13px 30px; border-radius: var(--radius);
      font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600;
      cursor: pointer; text-decoration: none; display: inline-block;
      transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 6px 20px rgba(232,70,10,0.35);
    }
    .btn-primary:hover {
      background: var(--primary-d); transform: translateY(-2px);
      box-shadow: 0 10px 28px rgba(232,70,10,0.45);
    }
    .btn-outline {
      background: transparent; color: #fff;
      border: 1.5px solid rgba(255,255,255,0.35);
      padding: 13px 30px; border-radius: var(--radius);
      font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600;
      cursor: pointer; text-decoration: none; display: inline-block;
      transition: border-color 0.2s, background 0.2s;
    }
    .btn-outline:hover { border-color: #fff; background: rgba(255,255,255,0.08); }
    .hero-stats {
      display: flex; gap: 36px; margin-top: 48px; flex-wrap: wrap;
      animation: fadeUp 0.6s 0.4s ease both;
    }
    .stat { display: flex; flex-direction: column; gap: 3px; }
    .stat-n {
      font-family: 'Poppins', sans-serif;
      font-size: 26px; font-weight: 800; color: #fff;
    }
    .stat-n em { color: var(--primary); font-style: normal; }
    .stat-l {
      font-size: 11px; color: rgba(255,255,255,0.45);
      text-transform: uppercase; letter-spacing: 0.08em;
    }

    /* hero right — illustrated card */
    .hero-visual {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 16px; padding: 32px 28px;
      backdrop-filter: blur(14px);
      animation: fadeUp 0.7s 0.15s ease both;
    }
    .hero-visual h3 {
      font-family: 'Poppins', sans-serif;
      font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 6px;
    }
    .hero-visual > p { font-size: 13px; color: rgba(255,255,255,0.4); margin-bottom: 24px; }
    .load-form .field { margin-bottom: 14px; }
    .load-form label {
      display: block; font-size: 11px; font-weight: 600;
      text-transform: uppercase; letter-spacing: 0.07em;
      color: rgba(255,255,255,0.4); margin-bottom: 6px;
    }
    .inp-wrap { position: relative; }
    .inp-wrap i {
      position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
      font-size: 15px; color: rgba(255,255,255,0.3); pointer-events: none;
    }
    .inp-wrap input, .inp-wrap select {
      width: 100%;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 8px; padding: 11px 14px 11px 38px;
      font-family: 'Lato', sans-serif; font-size: 14px; color: #fff;
      outline: none; transition: border-color 0.2s;
      -webkit-appearance: none; appearance: none;
    }
    .inp-wrap input::placeholder { color: rgba(255,255,255,0.2); }
    .inp-wrap input:focus, .inp-wrap select:focus { border-color: var(--primary); }
    .inp-wrap select option { background: var(--dark); color: #fff; }
    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .submit-btn {
      width: 100%; padding: 13px;
      background: var(--primary); border: none; border-radius: 8px;
      font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 700;
      color: #fff; cursor: pointer;
      transition: background 0.2s, transform 0.15s;
      margin-top: 4px;
    }
    .submit-btn:hover { background: var(--primary-d); transform: translateY(-1px); }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(22px); }
      to   { opacity: 1; transform: none; }
    }

    /* ── TRUST BAR ── */
    .trust-bar {
      background: var(--white);
      border-bottom: 1px solid #eee;
      padding: 18px 6%;
      display: flex; align-items: center;
      justify-content: center; gap: 48px; flex-wrap: wrap;
    }
    .trust-item {
      display: flex; align-items: center; gap: 8px;
      font-size: 13px; color: var(--grey); font-weight: 600;
    }
    .trust-item i { font-size: 18px; color: var(--primary); }

    /* ── SECTIONS ── */
    .section { padding: 80px 6%; }
    .section-label {
      font-size: 12px; font-weight: 700; letter-spacing: 0.14em;
      text-transform: uppercase; color: var(--primary); margin-bottom: 10px;
    }
    .section-title {
      font-family: 'Poppins', sans-serif;
      font-size: clamp(26px,3vw,38px); font-weight: 800;
      color: var(--dark); line-height: 1.2; margin-bottom: 14px;
    }
    .section-sub {
      font-size: 15px; color: var(--grey);
      max-width: 520px; line-height: 1.7; margin-bottom: 52px;
    }

    /* ── FEATURES ── */
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 24px;
    }
    .feat-card {
      background: var(--card-bg);
      border: 1px solid #e8e8e8; border-radius: 12px;
      padding: 28px 24px;
      transition: box-shadow 0.25s, transform 0.2s;
      position: relative; overflow: hidden;
    }
    .feat-card::before {
      content: ''; position: absolute; top: 0; left: 0;
      width: 4px; height: 100%; background: var(--primary);
      opacity: 0; transition: opacity 0.2s;
    }
    .feat-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.1); transform: translateY(-3px); }
    .feat-card:hover::before { opacity: 1; }
    .feat-icon {
      width: 48px; height: 48px; border-radius: 10px;
      background: rgba(232,70,10,0.08);
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 16px;
    }
    .feat-icon i { font-size: 22px; color: var(--primary); }
    .feat-card h4 {
      font-family: 'Poppins', sans-serif;
      font-size: 15px; font-weight: 700; color: var(--dark); margin-bottom: 8px;
    }
    .feat-card p { font-size: 13.5px; color: var(--grey); line-height: 1.65; }

    /* ── HOW IT WORKS ── */
    .hiw { background: var(--light); }
    .hiw-inner {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 60px; align-items: start;
    }
    .steps { display: flex; flex-direction: column; gap: 28px; }
    .step {
      display: flex; gap: 18px; align-items: flex-start;
      background: var(--white); border: 1px solid #e8e8e8;
      border-radius: 12px; padding: 20px 22px;
      transition: box-shadow 0.2s, border-color 0.2s;
    }
    .step:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.08); border-color: rgba(232,70,10,0.25); }
    .step-num {
      width: 44px; height: 44px; flex-shrink: 0;
      border-radius: 50%; background: var(--primary); color: #fff;
      font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 800;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 4px 14px rgba(232,70,10,0.35);
    }
    .step-body h4 {
      font-family: 'Poppins', sans-serif;
      font-size: 15px; font-weight: 700; color: var(--dark); margin-bottom: 5px;
    }
    .step-body p { font-size: 13.5px; color: var(--grey); line-height: 1.6; }
    .hiw-visual {
      background: linear-gradient(135deg, var(--dark), var(--accent));
      border-radius: 16px; padding: 36px 28px;
      display: flex; flex-direction: column; gap: 20px;
    }
    .hiw-visual-title {
      font-family: 'Poppins', sans-serif;
      font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 4px;
    }
    .hiw-visual-sub { font-size: 13px; color: rgba(255,255,255,0.4); margin-bottom: 8px; }
    .route-card {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 10px; padding: 16px 18px;
      display: flex; justify-content: space-between; align-items: center;
    }
    .route-info { display: flex; flex-direction: column; gap: 5px; }
    .route-cities {
      font-family: 'Poppins', sans-serif;
      font-size: 15px; font-weight: 700; color: #fff;
      display: flex; align-items: center; gap: 8px;
    }
    .route-cities i { font-size: 14px; color: var(--primary); }
    .route-meta { font-size: 12px; color: rgba(255,255,255,0.4); }
    .route-badge {
      background: rgba(232,70,10,0.15);
      border: 1px solid rgba(232,70,10,0.3);
      border-radius: 6px; padding: 4px 10px;
      font-size: 12px; font-weight: 700; color: #ff7b4a;
    }
    .bid-row {
      display: flex; align-items: center; justify-content: space-between;
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.07);
      border-radius: 8px; padding: 12px 14px;
    }
    .bid-transporter { display: flex; align-items: center; gap: 10px; }
    .bid-avatar {
      width: 32px; height: 32px; border-radius: 50%;
      background: var(--primary); color: #fff;
      font-family: 'Poppins', sans-serif; font-size: 11px; font-weight: 700;
      display: flex; align-items: center; justify-content: center;
    }
    .bid-name { font-size: 13px; font-weight: 700; color: #fff; }
    .bid-rating { font-size: 11px; color: #f5a623; }
    .bid-amount {
      font-family: 'Poppins', sans-serif;
      font-size: 16px; font-weight: 800; color: #fff;
    }
    .bid-amount span { font-size: 11px; font-weight: 400; color: rgba(255,255,255,0.4); }

    /* ── STATS BAND ── */
    .stats-band {
      background: linear-gradient(135deg, var(--dark), var(--accent));
      padding: 60px 6%;
    }
    .stats-inner {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 40px; text-align: center;
    }
    .band-stat-n {
      font-family: 'Poppins', sans-serif;
      font-size: 40px; font-weight: 800; color: #fff;
    }
    .band-stat-n em { color: var(--primary); font-style: normal; }
    .band-stat-l {
      font-size: 12px; color: rgba(255,255,255,0.45);
      text-transform: uppercase; letter-spacing: 0.08em; margin-top: 4px;
    }

    /* ── TESTIMONIALS ── */
    .testi-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 24px;
    }
    .testi-card {
      background: var(--card-bg);
      border: 1px solid #e8e8e8; border-radius: 12px;
      padding: 28px 24px; transition: box-shadow 0.2s;
    }
    .testi-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.08); }
    .stars { color: #f5a623; font-size: 14px; margin-bottom: 12px; letter-spacing: 2px; }
    .testi-card blockquote {
      font-size: 14px; color: #444; line-height: 1.7;
      font-style: italic; margin-bottom: 18px;
    }
    .testi-author { display: flex; align-items: center; gap: 12px; }
    .avatar {
      width: 40px; height: 40px; border-radius: 50%;
      background: var(--primary); color: #fff;
      font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 700;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .author-info strong { display: block; font-size: 13px; font-weight: 700; color: var(--dark); }
    .author-info span { font-size: 12px; color: var(--grey); }

    /* ── CTA BAND ── */
    .cta-band { background: var(--primary); padding: 80px 6%; text-align: center; }
    .cta-band h2 {
      font-family: 'Poppins', sans-serif;
      font-size: clamp(24px,3vw,38px); font-weight: 800;
      color: #fff; margin-bottom: 14px;
    }
    .cta-band p { color: rgba(255,255,255,0.8); font-size: 15px; margin-bottom: 30px; }
    .cta-btns { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
    .btn-white {
      background: #fff; color: var(--primary);
      padding: 13px 34px; border-radius: var(--radius);
      font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 700;
      text-decoration: none; display: inline-block;
      transition: transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .btn-white:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(0,0,0,0.2); }
    .btn-white-outline {
      background: transparent; color: #fff;
      border: 2px solid rgba(255,255,255,0.6);
      padding: 13px 34px; border-radius: var(--radius);
      font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 700;
      text-decoration: none; display: inline-block;
      transition: border-color 0.2s, background 0.2s;
    }
    .btn-white-outline:hover { border-color: #fff; background: rgba(255,255,255,0.1); }

    /* ── FOOTER ── */
    footer { background: var(--dark); padding: 60px 6% 28px; }
    .footer-top {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
      gap: 40px; margin-bottom: 48px;
    }
    .footer-brand .footer-logo {
      font-family: 'Poppins', sans-serif;
      font-size: 20px; font-weight: 800; color: var(--white);
      letter-spacing: -0.5px; text-decoration: none; display: inline-block;
      margin-bottom: 12px;
    }
    .footer-brand .footer-logo span { color: var(--primary); }
    .footer-brand p {
      font-size: 13.5px; color: rgba(255,255,255,0.38);
      line-height: 1.7; max-width: 240px; margin-bottom: 20px;
    }
    .social-links { display: flex; gap: 10px; flex-wrap: wrap; }
    .social-link {
      width: 36px; height: 36px; border-radius: 8px;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.1);
      display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,0.5); text-decoration: none;
      font-size: 17px;
      transition: background 0.2s, color 0.2s, border-color 0.2s;
    }
    .social-link:hover {
      background: var(--primary); border-color: var(--primary); color: #fff;
    }
    .footer-col h5 {
      font-family: 'Poppins', sans-serif;
      font-size: 13px; font-weight: 700; color: #fff;
      letter-spacing: 0.05em; margin-bottom: 16px;
    }
    .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
    .footer-col ul a {
      font-size: 13px; color: rgba(255,255,255,0.38);
      text-decoration: none; transition: color 0.2s;
    }
    .footer-col ul a:hover { color: #fff; }
    .app-badges { display: flex; flex-direction: column; gap: 10px; margin-top: 6px; }
    .app-badge {
      display: flex; align-items: center; gap: 10px;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 8px; padding: 8px 12px;
      text-decoration: none; transition: background 0.2s;
    }
    .app-badge:hover { background: rgba(255,255,255,0.12); }
    .app-badge i { font-size: 22px; color: rgba(255,255,255,0.7); }
    .app-badge-text { display: flex; flex-direction: column; }
    .app-badge-text small { font-size: 10px; color: rgba(255,255,255,0.35); }
    .app-badge-text strong { font-size: 13px; color: #fff; font-weight: 700; }
    .footer-divider { border: none; border-top: 1px solid rgba(255,255,255,0.07); margin-bottom: 20px; }
    .footer-bottom {
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 12px;
    }
    .footer-bottom p { font-size: 12.5px; color: rgba(255,255,255,0.25); }
    .footer-bottom-links { display: flex; gap: 20px; }
    .footer-bottom-links a {
      font-size: 12px; color: rgba(255,255,255,0.25);
      text-decoration: none; transition: color 0.2s;
    }
    .footer-bottom-links a:hover { color: rgba(255,255,255,0.6); }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) {
      .footer-top { grid-template-columns: 1fr 1fr 1fr; }
      .footer-brand { grid-column: 1 / -1; }
    }
    @media (max-width: 900px) {
      .hero-inner { grid-template-columns: 1fr; gap: 48px; }
      .hiw-inner { grid-template-columns: 1fr; }
      .hiw-visual { display: none; }
      .footer-top { grid-template-columns: 1fr 1fr; }
      .footer-brand { grid-column: 1 / -1; }
    }
    @media (max-width: 640px) {
      .hero { padding-top: 120px; }
      .nav-links { display: none; }
      .hamburger { display: flex; }
      .hero-stats { gap: 22px; }
      .hero-btns { flex-direction: column; }
      .btn-primary, .btn-outline { width: 100%; text-align: center; }
      .footer-top { grid-template-columns: 1fr; }
      .footer-bottom { flex-direction: column; text-align: center; }
      .trust-bar { gap: 24px; }
    }
    .nav-links.open {
      display: flex; flex-direction: column;
      position: fixed; top: 68px; left: 0; right: 0;
      background: var(--dark); padding: 20px 6% 28px;
      gap: 18px; border-bottom: 1px solid rgba(255,255,255,0.07);
      z-index: 99;
    }
  </style>
</head>
<body>

<!-- ======= NAV ======= -->
<nav id="navbar">
  <a href="#" class="nav-logo"><?= substr($site_name,0,1) ?><span><?= substr($site_name,1) ?></span></a>
  <ul class="nav-links" id="navLinks">
    <li><a href="#features">Features</a></li>
    <li><a href="#how-it-works">How It Works</a></li>
    <li><a href="#reviews">Reviews</a></li>
    <li><a href="#">For Transporters</a></li>
    <li><a href="register.php" class="nav-btn">Get Started Free</a></li>
  </ul>
  <button class="hamburger" id="hamburger" aria-label="Toggle menu">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- ======= HERO ======= -->
<section class="hero" id="home">
  <div class="dots"></div>
  <div class="hero-inner">
    <!-- left copy -->
    <div>
      <div class="hero-tag">🇮🇳 India's #1 Freight Network</div>
      <h1 class="hero-title">
        Move Freight.<br>
        <span>Smarter.</span> Faster.<br>
        Cheaper.
      </h1>
      <p class="hero-desc">
        Connect with 15 lakh+ verified transporters across India. Post your load, get competitive bids in minutes, and track shipments live — all on one platform.
      </p>
      <div class="hero-btns">
        <a href="#" class="btn-primary">Post a Load Free</a>
        <a href="#" class="btn-outline">I'm a Transporter →</a>
      </div>
      <div class="hero-stats">
        <?php foreach ($stats as $s): ?>
        <div class="stat">
          <div class="stat-n"><em><?= $s['num'] ?></em></div>
          <div class="stat-l"><?= $s['label'] ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- right — quick post card -->
    <div class="hero-visual">
      <h3>Post a Load in 60 Seconds</h3>
      <p>Get bids from verified transporters instantly</p>
      <form class="load-form" onsubmit="return false;">
        <div class="field-row">
          <div class="field">
            <label>From City</label>
            <div class="inp-wrap">
              <i class="ti ti-map-pin"></i>
              <input type="text" placeholder="e.g. Mumbai" />
            </div>
          </div>
          <div class="field">
            <label>To City</label>
            <div class="inp-wrap">
              <i class="ti ti-map-pin-filled"></i>
              <input type="text" placeholder="e.g. Delhi" />
            </div>
          </div>
        </div>
        <div class="field">
          <label>Material Type</label>
          <div class="inp-wrap">
            <i class="ti ti-package"></i>
            <select>
              <option value="">Select material...</option>
              <option>Textiles &amp; Garments</option>
              <option>FMCG / Consumer Goods</option>
              <option>Machinery &amp; Equipment</option>
              <option>Agricultural Produce</option>
              <option>Steel &amp; Metal</option>
              <option>Chemicals</option>
              <option>Other</option>
            </select>
          </div>
        </div>
        <div class="field-row">
          <div class="field">
            <label>Weight (Tonnes)</label>
            <div class="inp-wrap">
              <i class="ti ti-weight"></i>
              <input type="number" placeholder="e.g. 10" min="1" />
            </div>
          </div>
          <div class="field">
            <label>Vehicle Type</label>
            <div class="inp-wrap">
              <i class="ti ti-truck"></i>
              <select>
                <option value="">Select...</option>
                <option>Open Body</option>
                <option>Full Body / Container</option>
                <option>Flatbed</option>
                <option>Tanker</option>
                <option>Mini Truck</option>
              </select>
            </div>
          </div>
        </div>
        <button class="submit-btn">Find Transporters Now →</button>
      </form>
    </div>
  </div>
</section>

<!-- ======= TRUST BAR ======= -->
<div class="trust-bar">
  <div class="trust-item"><i class="ti ti-shield-check"></i> GST Verified Transporters</div>
  <div class="trust-item"><i class="ti ti-clock-24"></i> 24/7 Customer Support</div>
  <div class="trust-item"><i class="ti ti-map-search"></i> Live GPS Tracking</div>
  <div class="trust-item"><i class="ti ti-currency-rupee"></i> Zero Commission</div>
  <div class="trust-item"><i class="ti ti-file-certificate"></i> Digital POD & E-Way Bill</div>
</div>

<!-- ======= FEATURES ======= -->
<section class="section" id="features">
  <div class="section-label">Why Choose Vahak</div>
  <h2 class="section-title">Everything You Need to<br>Move Freight Confidently</h2>
  <p class="section-sub">
    From instant load matching to digital documentation — Vahak gives shippers and transporters every tool they need to operate efficiently at scale.
  </p>
  <div class="features-grid">
    <?php foreach ($features as $f): ?>
    <div class="feat-card">
      <div class="feat-icon"><i class="ti ti-<?= htmlspecialchars($f['icon']) ?>"></i></div>
      <h4><?= htmlspecialchars($f['title']) ?></h4>
      <p><?= htmlspecialchars($f['desc']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ======= HOW IT WORKS ======= -->
<section class="section hiw" id="how-it-works">
  <div class="section-label">How It Works</div>
  <h2 class="section-title">Shipping Made Simple,<br>in 4 Easy Steps</h2>
  <p class="section-sub">
    No calls, no brokers, no delays. The entire freight booking process is now digital, transparent, and takes just minutes.
  </p>
  <div class="hiw-inner">
    <div class="steps">
      <?php foreach ($steps as $step): ?>
      <div class="step">
        <div class="step-num"><?= $step['num'] ?></div>
        <div class="step-body">
          <h4><?= htmlspecialchars($step['title']) ?></h4>
          <p><?= htmlspecialchars($step['desc']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <!-- Live bidding mockup -->
    <div class="hiw-visual">
      <div class="hiw-visual-title">Live Bids Coming In</div>
      <div class="hiw-visual-sub">Mumbai → Delhi · 15 Tonnes · Container</div>
      <div class="route-card">
        <div class="route-info">
          <div class="route-cities">Mumbai <i class="ti ti-arrow-right"></i> Delhi</div>
          <div class="route-meta">Full Body · 15T · 1,400 km · Pickup: Tomorrow</div>
        </div>
        <div class="route-badge">6 Bids</div>
      </div>
      <?php
        $bids = [
          ["initials"=>"RS","name"=>"Ravi Sharma Logistics","rating"=>"★★★★★ 4.9","amount"=>"₹32,500"],
          ["initials"=>"PK","name"=>"PK Transport Co.","rating"=>"★★★★☆ 4.7","amount"=>"₹34,000"],
          ["initials"=>"MT","name"=>"Mahesh Trucking","rating"=>"★★★★☆ 4.6","amount"=>"₹35,200"],
        ];
        foreach ($bids as $bid):
      ?>
      <div class="bid-row">
        <div class="bid-transporter">
          <div class="bid-avatar"><?= $bid['initials'] ?></div>
          <div>
            <div class="bid-name"><?= htmlspecialchars($bid['name']) ?></div>
            <div class="bid-rating"><?= $bid['rating'] ?></div>
          </div>
        </div>
        <div class="bid-amount"><?= $bid['amount'] ?> <span>/trip</span></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ======= STATS BAND ======= -->
<section class="stats-band">
  <div class="stats-inner">
    <?php foreach ($stats as $s): ?>
    <div>
      <div class="band-stat-n"><?= preg_replace('/\+/', '<em>+</em>', htmlspecialchars($s['num'])) ?></div>
      <div class="band-stat-l"><?= htmlspecialchars($s['label']) ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ======= TESTIMONIALS ======= -->
<section class="section" id="reviews">
  <div class="section-label">Customer Reviews</div>
  <h2 class="section-title">Trusted by Lakhs of<br>Shippers &amp; Transporters</h2>
  <p class="section-sub">
    Hear directly from businesses and truck owners who rely on Vahak every single day to keep India moving.
  </p>
  <div class="testi-grid">
    <?php foreach ($reviews as $r): ?>
    <div class="testi-card">
      <div class="stars"><?= str_repeat('★', $r['stars']) ?><?= str_repeat('☆', 5 - $r['stars']) ?></div>
      <blockquote>"<?= htmlspecialchars($r['quote']) ?>"</blockquote>
      <div class="testi-author">
        <div class="avatar"><?= htmlspecialchars($r['initials']) ?></div>
        <div class="author-info">
          <strong><?= htmlspecialchars($r['name']) ?></strong>
          <span><?= htmlspecialchars($r['role']) ?></span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ======= CTA BAND ======= -->
<section class="cta-band">
  <h2>Ready to Transform How You Move Freight?</h2>
  <p>Join over 15 lakh businesses and transporters who ship smarter with Vahak. It's 100% free to get started.</p>
  <div class="cta-btns">
    <a href="#" class="btn-white">Post Your First Load Free</a>
    <a href="#" class="btn-white-outline">I'm a Transporter</a>
  </div>
</section>

<!-- ======= FOOTER ======= -->
<footer>
  <div class="footer-top">
    <!-- brand col -->
    <div class="footer-brand">
      <a href="#" class="footer-logo"><?= substr($site_name,0,1) ?><span><?= substr($site_name,1) ?></span></a>
      <p>India's largest online truck and load matching platform. Connecting shippers and transporters across all 28 states.</p>
      <div class="social-links">
        <?php foreach ($social_links as $s): ?>
        <a href="<?= htmlspecialchars($s['href']) ?>" class="social-link" aria-label="<?= htmlspecialchars($s['name']) ?>">
          <i class="ti ti-<?= htmlspecialchars($s['icon']) ?>"></i>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- link columns -->
    <?php foreach ($footer_links as $col_title => $links): ?>
    <div class="footer-col">
      <h5><?= htmlspecialchars($col_title) ?></h5>
      <ul>
        <?php foreach ($links as $link): ?>
        <li><a href="<?= htmlspecialchars($link['href']) ?>"><?= htmlspecialchars($link['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endforeach; ?>

    <!-- app downloads -->
    <div class="footer-col">
      <h5>Download App</h5>
      <div class="app-badges">
        <a href="#" class="app-badge">
          <i class="ti ti-brand-google-play"></i>
          <div class="app-badge-text">
            <small>Get it on</small>
            <strong>Google Play</strong>
          </div>
        </a>
        <a href="#" class="app-badge">
          <i class="ti ti-brand-apple"></i>
          <div class="app-badge-text">
            <small>Download on the</small>
            <strong>App Store</strong>
          </div>
        </a>
      </div>
    </div>
  </div>

  <hr class="footer-divider" />

  <div class="footer-bottom">
    <p>© <?= $current_year ?> <?= htmlspecialchars($site_name) ?> Technologies Pvt. Ltd. All rights reserved. Made with ❤️ in India.</p>
    <div class="footer-bottom-links">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="#">Cookie Policy</a>
      <a href="#">Sitemap</a>
    </div>
  </div>
</footer>

<script>
  // hamburger toggle
  const hamburger = document.getElementById('hamburger');
  const navLinks  = document.getElementById('navLinks');
  hamburger.addEventListener('click', () => navLinks.classList.toggle('open'));

  // close mobile menu on link click
  navLinks.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', () => navLinks.classList.remove('open'));
  });

  // smooth navbar bg on scroll
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar.style.background = window.scrollY > 40
      ? 'rgba(26,26,46,1)'
      : 'rgba(26,26,46,0.97)';
  });
</script>
</body>
</html>