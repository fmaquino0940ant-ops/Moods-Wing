<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Flavor Menu | Moods Wing</title>
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body class="menu-body">
  <header class="site-header inner">
    <a href="home.php" class="logo"><img src="Logo.png" alt="Moods Wing logo"></a>
    <nav class="nav">
      <a href="menu.php" class="nav-btn active">Flavor Menu</a>
      <a href="order.php" class="nav-btn">Order</a>
      <a href="booking.php" class="nav-btn">Book a Table</a>
    </nav>
  </header>
  <main class="menu-page">
    <h1 class="menu-title">Find your Flavor</h1>
    <p class="menu-sub">Pick a mood, then pick your wings</p>
    <section class="flavor-grid">
      <a class="flavor-card" href="order.php?flavor=High">
        <div class="card-face">
          <h2>High</h2>
          <p class="tag"><span class="dot savory"></span><span>Savory</span></p>
          <img src="high.png" alt="High wings">
          <p class="desc">Toasted in roasted garlic and parmesan. Savory, nutty, and hard to put down.</p>
        </div>
      </a>
      <a class="flavor-card" href="order.php?flavor=Romantic">
        <div class="card-face">
          <h2>Romantic</h2>
          <p class="tag"><span class="dot savory"></span><span>Cheesy</span></p>
          <img src="romantic.png" alt="Romantic wings">
          <p class="desc">A rich, creamy cheese. Made for sharing, or not.</p>
        </div>
      </a>
      <a class="flavor-card" href="order.php?flavor=Lazy">
        <div class="card-face">
          <h2>Lazy</h2>
          <p class="tag"><span class="dot sweet"></span><span>Sweet</span></p>
          <img src="lazy.png" alt="Lazy wings">
          <p class="desc">Sweet honey meets mellow garlic. Easy flavor, zero effort.</p>
        </div>
      </a>
      <a class="flavor-card" href="order.php?flavor=Happy">
        <div class="card-face">
          <h2>Happy</h2>
          <p class="tag"><span class="dot sweetsavory"></span><span>Sweet &amp; Savory</span></p>
          <img src="happy.png" alt="Happy wings">
          <p class="desc">Sweet and savory teriyaki glaze. Classic comfort with a smile.</p>
        </div>
      </a>
      <a class="flavor-card" href="order.php?flavor=Surprised">
        <div class="card-face">
          <h2>Surprised</h2>
          <p class="tag"><span class="dot secret"></span><span>Secret<br>Sauce</span></p>
          <img src="surprised.png" alt="Surprised wings">
          <p class="desc">Our secret sauce. We're not telling, so you'll have to taste it.</p>
        </div>
      </a>
      <a class="flavor-card" href="order.php?flavor=Excited">
        <div class="card-face">
          <h2>Excited</h2>
          <p class="tag"><span class="dot spicy"></span><span>Spicy</span></p>
          <img src="excited.png" alt="Excited wings">
          <p class="desc">Bold Korean-style sauce with sweet, savory, and spicy notes. Wing night energy.</p>
        </div>
      </a>
      <a class="flavor-card" href="order.php?flavor=Naughty">
        <div class="card-face">
          <h2>Naughty</h2>
          <p class="tag"><span class="dot booze"></span><span>Alcohol-Infused</span></p>
          <img src="naughty.png" alt="Naughty wings">
          <p class="desc">Infused with alcohol for a bold, boozy kick. Bad on purpose.</p>
        </div>
      </a>
      <a class="flavor-card" href="order.php?flavor=Guilty">
        <div class="card-face">
          <h2>Guilty</h2>
          <p class="tag"><span class="dot spicy"></span><span>Spicy</span></p>
          <img src="guilty.png" alt="Guilty wings">
          <p class="desc">Sticky sweet chili glaze with a little heat. Sweet now, guilty later.</p>
        </div>
      </a>
      <a class="flavor-card" href="order.php?flavor=Angry">
        <div class="card-face">
          <h2>Angry</h2>
          <p class="tag"><span class="dot spicy"></span><span>Spicy</span></p>
          <img src="angry.png" alt="Angry wings">
          <p class="desc">Fiery heat that doesn't hold back, for when you're not in the mood to play nice.</p>
        </div>
      </a>
      <a class="flavor-card" href="order.php?flavor=Grumpy">
        <div class="card-face">
          <h2>Grumpy</h2>
          <p class="tag"><span class="dot spicy"></span><span>Spicy</span></p>
          <img src="grumpy.png" alt="Grumpy wings">
          <p class="desc">Rich, creamy salted egg coating with a spicy bite. Moody but worth it.</p>
        </div>
      </a>
    </section>
  </main>

  <footer class="footer">
    <div class="footer-top">
      <div>
        <a href="home.php" class="footer-logo-link">
          <img class="footer-logo" src="Logo.png" alt="Moods Wing">
        </a>
        <p class="footer-tagline">Find your flavor, own your mood.</p>
      </div>

      <div>
        <h4>EXPLORE</h4>
        <a href="menu.php">Flavor Menu</a>
        <a href="order.php">Order</a>
        <a href="booking.php">Book a Table</a>
      </div>

      <div>
        <h4>VISIT US</h4>
        <p>8229 Dr. Santos Ave, Parañaque, Philippines, 1700</p>
        <p>11:00 AM - 9:00 PM <br>(Closed every Tuesday)</p>
        <p>0920 929 8083</p>
      </div>

      <div>
        <h4>FOLLOW</h4>
        <a href="https://www.facebook.com/share/1HPRXDbYWZ/" target="_blank" rel="noopener noreferrer">Facebook</a>
        <a href="mailto:masterdonburi@gmail.com">Email</a>
      </div>
    </div>

    <p class="copy">© 2026 Moods Wing. All rights reserved.</p>
  </footer>
</body>
</html>