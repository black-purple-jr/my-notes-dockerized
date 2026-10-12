<?php
session_start();

// Set this to your live domain, without a trailing slash.
$siteUrl = 'https://your-domain.com';

// Logged-in users skip the welcome page.
// Change 'user_id' to whatever key your login code actually sets.
if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Notes - Simple, private notes you can search</title>
  <meta name="description" content="My Notes is a simple, private place to write, edit and search your notes. Create a free account and add your first note in seconds.">
  <meta name="robots" content="index, follow">
  <meta name="theme-color" content="#121212">
  <link rel="canonical" href="<?= $siteUrl ?>/">

  <link rel="icon" href="favicon.svg" type="image/svg+xml">

  <meta property="og:type" content="website">
  <meta property="og:site_name" content="My Notes">
  <meta property="og:title" content="My Notes - Simple, private notes you can search">
  <meta property="og:description" content="Write it down. Find it later. A simple, private place for your notes.">
  <meta property="og:url" content="<?= $siteUrl ?>/">
  <meta property="og:image" content="<?= $siteUrl ?>/og-image.png">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="My Notes - Simple, private notes you can search">
  <meta name="twitter:description" content="Write it down. Find it later. A simple, private place for your notes.">
  <meta name="twitter:image" content="<?= $siteUrl ?>/og-image.png">

  <script type="application/ld+json">
    <?= json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'WebApplication',
      'name' => 'My Notes',
      'url' => $siteUrl . '/',
      'description' => 'A simple, private place to write, edit and search your notes.',
      'applicationCategory' => 'ProductivityApplication',
      'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD'],
    ], JSON_UNESCAPED_SLASHES) ?>
  </script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --brand-main-color: #5b5eeb;
      --background-main-color: #121212;
      --surface-color: #1b1b1f;
      --border-color: #2a2a31;
      --text-color: #f2f2f5;
      --muted-color: #9b9ba8;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html {
      scrollbar-gutter: stable;
    }

    body {
      font-family: "Geist", system-ui, sans-serif;
      background: var(--background-main-color);
      color: var(--text-color);
      line-height: 1.6;
    }

    a {
      color: inherit;
      text-decoration: none;
    }

    a:focus-visible {
      outline: 2px solid var(--brand-main-color);
      outline-offset: 3px;
    }

    svg.icon {
      width: 20px;
      height: 20px;
      fill: none;
      stroke: currentColor;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
      flex-shrink: 0;
    }

    .wrap {
      max-width: 1080px;
      margin: 0 auto;
      padding: 0 24px;
    }

    header .wrap {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 72px;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 600;
      font-size: 1.05rem;
    }

    .logo svg {
      color: var(--brand-main-color);
    }

    nav {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 10px 18px;
      border-radius: 8px;
      font-weight: 500;
      font-size: .95rem;
      border: 1px solid transparent;
      transition: background .15s, border-color .15s;
    }

    .btn-primary {
      background: var(--brand-main-color);
      color: #fff;
    }

    .btn-primary:hover {
      background: #4a4dd6;
    }

    .btn-ghost {
      color: var(--muted-color);
    }

    .btn-ghost:hover {
      color: var(--text-color);
    }

    .btn-outline {
      border-color: var(--border-color);
    }

    .btn-outline:hover {
      border-color: var(--muted-color);
    }

    .hero {
      display: grid;
      grid-template-columns: 1.1fr 1fr;
      gap: 56px;
      align-items: center;
      padding: 80px 0 96px;
    }

    h1 {
      font-size: clamp(2.2rem, 5vw, 3.6rem);
      line-height: 1.08;
      letter-spacing: -0.03em;
      font-weight: 700;
    }

    .hero p {
      margin: 20px 0 32px;
      color: var(--muted-color);
      font-size: 1.1rem;
      max-width: 46ch;
    }

    .actions {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }

    /* Preview of the note cards users will see inside the app */
    .stack {
      position: relative;
      height: 340px;
    }

    .note {
      position: absolute;
      width: 78%;
      padding: 20px;
      background: var(--surface-color);
      border: 1px solid var(--border-color);
      border-radius: 12px;
    }

    .note h3 {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 6px;
    }

    .note p {
      font-size: .9rem;
      color: var(--muted-color);
      margin: 0;
      max-width: none;
    }

    .note time {
      display: block;
      margin-top: 14px;
      font-size: .78rem;
      color: var(--muted-color);
    }

    .note:nth-child(1) {
      top: 0;
      left: 0;
    }

    .note:nth-child(2) {
      top: 108px;
      left: 16%;
      border-color: var(--brand-main-color);
    }

    .note:nth-child(3) {
      top: 216px;
      left: 6%;
    }

    .features {
      border-top: 1px solid var(--border-color);
      padding: 72px 0;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 40px;
    }

    .feature svg {
      color: var(--brand-main-color);
      margin-bottom: 14px;
    }

    .feature h2 {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 6px;
    }

    .feature p {
      color: var(--muted-color);
      font-size: .95rem;
    }

    .cta {
      border-top: 1px solid var(--border-color);
      padding: 72px 0;
      text-align: center;
    }

    .cta h2 {
      font-size: 1.8rem;
      letter-spacing: -0.02em;
      margin-bottom: 24px;
    }

    footer {
      border-top: 1px solid var(--border-color);
      padding: 24px 0;
      color: var(--muted-color);
      font-size: .85rem;
    }

    @media (max-width: 800px) {
      .hero {
        grid-template-columns: 1fr;
        padding: 48px 0 64px;
        gap: 40px;
      }

      .features {
        grid-template-columns: 1fr;
        gap: 32px;
      }

      .stack {
        height: 320px;
      }
    }
  </style>
</head>

<body>
  <header>
    <div class="wrap">
      <a href="welcome.php" class="logo">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M2 6h4" />
          <path d="M2 10h4" />
          <path d="M2 14h4" />
          <path d="M2 18h4" />
          <rect width="16" height="20" x="4" y="2" rx="2" />
          <path d="M9.5 8h5" />
          <path d="M9.5 12H16" />
          <path d="M9.5 16H14" />
        </svg>
        My Notes
      </a>
      <nav>
        <a href="login.php" class="btn btn-ghost">Log in</a>
        <a href="signup.php" class="btn btn-primary">Sign up</a>
      </nav>
    </div>
  </header>

  <main class="wrap">
    <section class="hero">
      <div>
        <h1>Write it down. Find it later.</h1>
        <p>My Notes is a private place for everything you want to remember. Create an account, add your first note in seconds, and search your notes whenever you need them.</p>
        <div class="actions">
          <a href="signup.php" class="btn btn-primary">Create your account</a>
          <a href="login.php" class="btn btn-outline">Log in</a>
        </div>
      </div>
      <div class="stack" aria-hidden="true">
        <div class="note">
          <h3>Groceries</h3>
          <p>Rice, olive oil, mint, lemons.</p><time>Edited today</time>
        </div>
        <div class="note">
          <h3>Project ideas</h3>
          <p>A landing page template pack for local shops.</p><time>Edited yesterday</time>
        </div>
        <div class="note">
          <h3>Exam revision</h3>
          <p>MERISE diagrams, then SQL joins.</p><time>Edited 3 days ago</time>
        </div>
      </div>
    </section>

    <section class="features">
      <div class="feature">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M12 20h9" />
          <path d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838a.5.5 0 0 1-.62-.62l.838-2.872a2 2 0 0 1 .506-.854z" />
        </svg>
        <h2>Write and edit</h2>
        <p>Add a note, change it later, and see when it was last edited.</p>
      </div>
      <div class="feature">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
          <circle cx="11" cy="11" r="8" />
          <path d="m21 21-4.3-4.3" />
        </svg>
        <h2>Search</h2>
        <p>Type a word and get the notes that contain it.</p>
      </div>
      <div class="feature">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
          <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
          <path d="M7 11V7a5 5 0 0 1 10 0v4" />
        </svg>
        <h2>Private</h2>
        <p>Your notes are tied to your account. Nobody else can open them.</p>
      </div>
    </section>

    <section class="cta">
      <h2>Start your first note</h2>
      <a href="signup.php" class="btn btn-primary">Sign up for free</a>
    </section>
  </main>

  <footer>
    <div class="wrap">My Notes</div>
  </footer>
</body>

</html>