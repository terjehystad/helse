<?php
// helse.terjehystad.com — privat innlogging (cookie-basert, PWA-vennlig)
// Data-appen ligger i app.html (blokkert for direkte HTTP via .htaccess),
// serveres kun her etter innlogging. Passord-hash i secret.php (utenfor git).
session_set_cookie_params(2592000); // 30 dager
session_start();
$secretFile = __DIR__ . '/secret.php';
$err = '';

if (isset($_GET['logout'])) { $_SESSION = []; session_destroy(); header('Location: index.php'); exit; }

// Førstegangs-oppsett: sett passord (kun mulig når secret.php ikke finnes ennå)
if (!file_exists($secretFile) && isset($_POST['newpw'])) {
  $pw = (string)$_POST['newpw'];
  if (strlen($pw) >= 6) {
    $hash = password_hash($pw, PASSWORD_DEFAULT);
    file_put_contents($secretFile, "<?php return " . var_export($hash, true) . ";\n");
    $_SESSION['auth'] = true;
    header('Location: index.php'); exit;
  } else { $err = 'Passordet må være minst 6 tegn.'; }
}

// Innlogging
if (file_exists($secretFile) && isset($_POST['pw'])) {
  $hash = include $secretFile;
  if (is_string($hash) && password_verify((string)$_POST['pw'], $hash)) {
    $_SESSION['auth'] = true;
    header('Location: index.php'); exit;
  } else { $err = 'Feil passord.'; }
}

// Innlogget → server appen
if (!empty($_SESSION['auth']) && file_exists(__DIR__ . '/app.html')) {
  header('Content-Type: text/html; charset=utf-8');
  readfile(__DIR__ . '/app.html');
  exit;
}

$setup = !file_exists($secretFile);
?><!doctype html>
<html lang="no"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Helse">
<meta name="theme-color" content="#0a0d13">
<title>Helse — innlogging</title>
<style>
:root{color-scheme:dark}
*{box-sizing:border-box}html,body{margin:0;height:100%}
body{background:#0a0d13;color:#eef3fa;font:16px/1.5 -apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
 display:grid;place-items:center;padding:24px}
.card{width:100%;max-width:340px;text-align:center}
.mono{width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#5b8cff,#7c5cff);
 display:grid;place-items:center;font-weight:800;font-size:20px;color:#fff;margin:0 auto 18px;box-shadow:0 8px 30px rgba(92,140,255,.4)}
h1{font-size:20px;margin:0 0 4px;font-weight:800}
p.sub{color:#8b98ad;font-size:13px;margin:0 0 20px}
input{width:100%;background:#151c28;border:1px solid #26303f;color:#eef3fa;border-radius:12px;
 padding:14px 16px;font-size:16px;margin-bottom:10px;outline:none}
input:focus{border-color:#5b8cff}
button{width:100%;background:linear-gradient(135deg,#5b8cff,#7c5cff);color:#fff;border:0;border-radius:12px;
 padding:14px;font-size:16px;font-weight:700}
.err{background:rgba(255,107,129,.15);color:#ff6b81;border-radius:10px;padding:9px;font-size:13px;margin-bottom:12px}
.hint{color:#5d6a7d;font-size:12px;margin-top:16px;line-height:1.5}
</style></head><body>
<form class="card" method="post">
 <div class="mono">TH</div>
 <h1><?= $setup ? 'Velg et passord' : 'Terje · Helse' ?></h1>
 <p class="sub"><?= $setup ? 'Førstegangs-oppsett — sett ditt private passord' : 'Logg inn for å se helsedataene' ?></p>
 <?php if ($err) echo '<div class="err">' . htmlspecialchars($err) . '</div>'; ?>
 <input type="password" name="<?= $setup ? 'newpw' : 'pw' ?>" placeholder="Passord" autocomplete="<?= $setup ? 'new-password' : 'current-password' ?>" autofocus>
 <button><?= $setup ? 'Sett passord og logg inn' : 'Logg inn' ?></button>
 <?php if ($setup): ?><div class="hint">Passordet lagres kun kryptert på serveren (hash) — aldri i klartekst, aldri i GitHub.</div><?php endif; ?>
</form>
</body></html>
