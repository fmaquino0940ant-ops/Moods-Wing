<?php
require "db.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$isPost = $_SERVER["REQUEST_METHOD"] === "POST";

if (!isset($_SESSION["user_id"])) {
    if ($isPost) {
        http_response_code(401);
        header("Content-Type: application/json");
        echo json_encode(["ok" => false, "error" => "login"]);
    } else {
        header("Location: login.html");
    }
    exit;
}

if ($isPost) {
    header("Content-Type: application/json");

    // Prices live on the server so they can't be tampered with in the browser
    $menu = [
        1 => ["Mood Bites", 168], 2 => ["Famous Mood", 178], 3 => ["Solo Mood", 188],
        4 => ["6 pcs Moods Wing", 238], 5 => ["12 pcs Moods Wing", 368], 6 => ["24 pcs Moods Wing", 698],
        7 => ["Party Mood", 298], 8 => ["Taken Mood", 128], 9 => ["Single Mood", 98],
        10 => ["Carbonara", 150], 11 => ["Moods Fries", 120],
        12 => ["Soda (220 ml)", 65], 13 => ["Water (500 ml)", 25],
        14 => ["Mood Blends (Glass)", 35], 15 => ["Mood Blends (Bottomless)", 45],
        16 => ["Mood Blends (Pitcher)", 120], 17 => ["Beer in a Can", 95],
        18 => ["Moods Wing Extra Sauce", 100], 19 => ["Rice", 30],
        20 => ["Chips", 25], 21 => ["Parmesan Cheese", 30],
    ];

    $data = json_decode(file_get_contents("php://input"), true);
    $lines = [];
    $total = 0;

    foreach (($data["items"] ?? []) as $it) {
        $id  = (int)($it["id"] ?? 0);
        $qty = (int)($it["qty"] ?? 0);
        if (!isset($menu[$id]) || $qty < 1 || $qty > 50) continue;
        $lines[] = $qty . "x " . $menu[$id][0];
        $total += $menu[$id][1] * $qty;
    }

    if (!$lines) {
        echo json_encode(["ok" => false, "error" => "Your order is empty."]);
        exit;
    }

    try {
        $q = $con->prepare("SELECT email FROM users WHERE id = ?");
        $q->bind_param("i", $_SESSION["user_id"]);
        $q->execute();
        $row = $q->get_result()->fetch_assoc();
        if (!$row) {
            echo json_encode(["ok" => false, "error" => "Please log in again."]);
            exit;
        }

        $items = implode(", ", $lines);
        $stmt = $con->prepare("INSERT INTO orders (email, items, total) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $row["email"], $items, $total);
        $stmt->execute();

        echo json_encode(["ok" => true, "total" => $total]);
    } catch (mysqli_sql_exception $e) {
        echo json_encode(["ok" => false, "error" => "DB error: " . $e->getMessage()]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Order | Moods Wing</title>
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Montserrat:wght@500;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body class="menu-body">
  <header class="site-header inner">
  <a href="home.php" class="logo"><img src="Logo.png" alt="Moods Wing logo"></a>
  <nav class="nav">
    <a href="menu.php" class="nav-btn">Flavor Menu</a>
    <a href="order.php" class="nav-btn active">Order</a>
    <a href="booking.php" class="nav-btn">Book a Table</a>
  </nav>
</header>
  <main class="order-page">
    <h1 class="page-title">Build your order</h1>
    <div class="order-layout">
      <section class="menu-col">
        <div class="tabs" role="tablist">
          <button class="tab active" role="tab" data-tab="special" aria-selected="true">Moods Special</button>
          <button class="tab" role="tab" data-tab="moodie" aria-selected="false">Moodie</button>
          <button class="tab" role="tab" data-tab="favorites" aria-selected="false">Favorites</button>
          <button class="tab" role="tab" data-tab="drinks" aria-selected="false">Drinks</button>
          <button class="tab" role="tab" data-tab="extras" aria-selected="false">Extras</button>
        </div>

        <!-- MOODS SPECIAL -->
        <div class="tab-panel active" id="tab-special" role="tabpanel">
          <p class="cat-note">With Signature Moods Wing sauce</p>
          <div class="item-grid cols-2">
            <article class="item-card">
              <div class="item-text"><h3>Mood Bites</h3><p>Chicken bites (1 flavor) + rice + drink</p></div>
              <div class="item-foot"><span class="price">₱168</span><button class="add-btn" data-id="1" data-name="Mood Bites" data-price="168">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>Famous Mood</h3><p>Leg quarter (1 flavor) + rice + drink</p></div>
              <div class="item-foot"><span class="price">₱178</span><button class="add-btn" data-id="2" data-name="Famous Mood" data-price="178">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>Solo Mood</h3><p>4 pcs wings (1 flavor) + rice + drink</p></div>
              <div class="item-foot"><span class="price">₱188</span><button class="add-btn" data-id="3" data-name="Solo Mood" data-price="188">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>6 pcs Moods Wing</h3><p>Half dozen (1 flavor)</p></div>
              <div class="item-foot"><span class="price">₱238</span><button class="add-btn" data-id="4" data-name="6 pcs Moods Wing" data-price="238">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>12 pcs Moods Wing</h3><p>1 dozen (2 flavors)</p></div>
              <div class="item-foot"><span class="price">₱368</span><button class="add-btn" data-id="5" data-name="12 pcs Moods Wing" data-price="368">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>24 pcs Moods Wing</h3><p>Chicken bites (1 flavor) + rice + drink</p></div>
              <div class="item-foot"><span class="price">₱698</span><button class="add-btn" data-id="6" data-name="24 pcs Moods Wing" data-price="698">Add</button></div>
            </article>
          </div>
        </div>

        <!-- MOODIE -->
        <div class="tab-panel" id="tab-moodie" role="tabpanel">
          <p class="cat-note">Crispy flavored chicken skin</p>
          <div class="item-grid cols-2">
            <article class="item-card">
              <div class="item-text"><h3>Party Mood</h3><p>Party tray (2 flavor)</p></div>
              <div class="item-foot"><span class="price">₱298</span><button class="add-btn" data-id="7" data-name="Party Mood" data-price="298">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>Taken Mood</h3><p>Good for sharing (1 flavor)</p></div>
              <div class="item-foot"><span class="price">₱128</span><button class="add-btn" data-id="8" data-name="Taken Mood" data-price="128">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>Single Mood</h3><p>Solo with rice (1 flavor)</p></div>
              <div class="item-foot"><span class="price">₱98</span><button class="add-btn" data-id="9" data-name="Single Mood" data-price="98">Add</button></div>
            </article>
          </div>
        </div>

        <!-- FAVORITES -->
        <div class="tab-panel" id="tab-favorites" role="tabpanel">
          <div class="item-grid cols-1">
            <article class="item-card">
              <div class="item-text"><h3>Carbonara</h3><p>Fettuccine in creamy white sauce with bacon, ham and mushroom, topped with parmesan cheese, served with garlic toasted bread.</p></div>
              <div class="item-foot"><span class="price">₱150</span><button class="add-btn" data-id="10" data-name="Carbonara" data-price="150">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>Moods Fries</h3><p>Secret flavored Mood Fries (200g)</p></div>
              <div class="item-foot"><span class="price">₱120</span><button class="add-btn" data-id="11" data-name="Moods Fries" data-price="120">Add</button></div>
            </article>
          </div>
        </div>

        <!-- DRINKS -->
        <div class="tab-panel" id="tab-drinks" role="tabpanel">
          <div class="item-grid cols-2">
            <article class="item-card">
              <div class="item-text"><h3>Soda (220 ml)</h3><p>Coke, Sprite, Royal, Mountain Dew</p></div>
              <div class="item-foot"><span class="price">₱65</span><button class="add-btn" data-id="12" data-name="Soda (220 ml)" data-price="65">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>Water (500 ml)</h3></div>
              <div class="item-foot"><span class="price">₱25</span><button class="add-btn" data-id="13" data-name="Water (500 ml)" data-price="25">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>Mood Blends (Glass)</h3><p>Red Iced Tea or Cucumber Lime</p></div>
              <div class="item-foot"><span class="price">₱35</span><button class="add-btn" data-id="14" data-name="Mood Blends (Glass)" data-price="35">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>Mood Blends (Bottomless)</h3><p>Red Iced Tea or Cucumber Lime</p></div>
              <div class="item-foot"><span class="price">₱45</span><button class="add-btn" data-id="15" data-name="Mood Blends (Bottomless)" data-price="45">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>Mood Blends (Pitcher)</h3><p>Red Iced Tea or Cucumber Lime</p></div>
              <div class="item-foot"><span class="price">₱120</span><button class="add-btn" data-id="16" data-name="Mood Blends (Pitcher)" data-price="120">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>Beer in a Can</h3><p>Red Horse, San Mig Light, Pale Pilsen</p><p class="age">18+ only</p></div>
              <div class="item-foot"><span class="price">₱95</span><button class="add-btn" data-id="17" data-name="Beer in a Can" data-price="95" data-age="1">Add</button></div>
            </article>
          </div>
        </div>

        <!-- EXTRAS -->
        <div class="tab-panel" id="tab-extras" role="tabpanel">
          <div class="item-grid cols-2">
            <article class="item-card">
              <div class="item-text"><h3>Moods Wing Extra Sauce</h3><p>Additional signature sauce</p></div>
              <div class="item-foot"><span class="price">₱100</span><button class="add-btn" data-id="18" data-name="Moods Wing Extra Sauce" data-price="100">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>Rice</h3></div>
              <div class="item-foot"><span class="price">₱30</span><button class="add-btn" data-id="19" data-name="Rice" data-price="30">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>Chips</h3></div>
              <div class="item-foot"><span class="price">₱25</span><button class="add-btn" data-id="20" data-name="Chips" data-price="25">Add</button></div>
            </article>
            <article class="item-card">
              <div class="item-text"><h3>Parmesan Cheese</h3></div>
              <div class="item-foot"><span class="price">₱30</span><button class="add-btn" data-id="21" data-name="Parmesan Cheese" data-price="30">Add</button></div>
            </article>
          </div>
        </div>
      </section>

      <aside class="order-panel" aria-label="Your order">
        <h2>Your Order</h2>
        <p class="empty" id="cart-empty">Nothing here yet. Add something from the menu</p>
        <ul class="cart-list" id="cart-list"></ul>
        <div class="total-row"><span>Total</span><span id="cart-total">₱000</span></div>
        <button class="place-btn" id="place-order" disabled>Place Order</button>
      </aside>
    </div>
  </main>
  <div class="modal" id="order-modal" hidden>
    <div class="modal-box">
      <h2>Order placed!</h2>
      <p>Thanks for ordering at Moods Wing. We're getting your food ready.</p>
      <button class="add-btn" id="modal-close">Close</button>
    </div>
  </div>

  <script>
  const cart = {};   // id -> {name, price, qty}
  const peso = n => "₱" + n.toLocaleString();

  // Tabs
  document.querySelectorAll(".tab").forEach(tab => {
    tab.addEventListener("click", () => {
      document.querySelectorAll(".tab").forEach(t => {
        t.classList.remove("active");
        t.setAttribute("aria-selected", "false");
      });
      document.querySelectorAll(".tab-panel").forEach(p => p.classList.remove("active"));
      tab.classList.add("active");
      tab.setAttribute("aria-selected", "true");
      document.getElementById("tab-" + tab.dataset.tab).classList.add("active");
    });
  });

  // Add to cart
  document.querySelectorAll(".item-card .add-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      if (btn.dataset.age && !confirm("Alcohol is for 18+ only. Are you 18 or older?")) return;
      const id = btn.dataset.id;
      if (!cart[id]) cart[id] = { name: btn.dataset.name, price: +btn.dataset.price, qty: 0 };
      cart[id].qty++;
      render();
    });
  });

  function render() {
    const list = document.getElementById("cart-list");
    list.innerHTML = "";
    let total = 0;

    Object.entries(cart).forEach(([id, it]) => {
      total += it.price * it.qty;
      const li = document.createElement("li");
      const label = document.createElement("span");
      label.textContent = it.qty + "x " + it.name + " - " + peso(it.price * it.qty);
      const rm = document.createElement("button");
      rm.textContent = "×";
      rm.setAttribute("aria-label", "Remove one " + it.name);
      rm.addEventListener("click", () => {
        if (--it.qty <= 0) delete cart[id];
        render();
      });
      li.append(label, rm);
      list.appendChild(li);
    });

    const empty = Object.keys(cart).length === 0;
    document.getElementById("cart-empty").hidden = !empty;
    document.getElementById("cart-total").textContent = empty ? "₱000" : peso(total);
    document.getElementById("place-order").disabled = empty;
  }

  // Place order
  document.getElementById("place-order").addEventListener("click", async e => {
    const btn = e.currentTarget;
    btn.disabled = true;
    try {
      const res = await fetch("order.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          items: Object.entries(cart).map(([id, it]) => ({ id: +id, qty: it.qty }))
        })
      });

      if (res.status === 401) { window.location = "login.html"; return; }

      const data = await res.json();
      if (data.ok) {
        for (const k in cart) delete cart[k];
        render();
        document.getElementById("order-modal").hidden = false;
      } else {
        alert(data.error || "Could not place your order.");
        btn.disabled = false;
      }
    } catch (err) {
      alert("Network error. Please try again.");
      btn.disabled = false;
    }
  });

  document.getElementById("modal-close").addEventListener("click", () => {
    document.getElementById("order-modal").hidden = true;
  });
</script>
  
</body>
</html>