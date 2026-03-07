<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php#contact');
  exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($phone) < 8 || strlen($message) < 10) {
  $_SESSION['contact_status'] = [
    'ok' => false,
    'message' => 'Please complete all fields with valid details.',
  ];
  header('Location: index.php#contact');
  exit;
}

$to = 'valmabs4@gmail.com';
$subject = 'New Contact Form Submission from ' . $name;
$body = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nMessage: {$message}";

/**
 * Read env vars from getenv/$_ENV/$_SERVER for compatibility across setups.
 */
function env_value(string $key, ?string $default = null): ?string
{
  $value = getenv($key);
  if ($value !== false && $value !== null && $value !== '') {
    return (string) $value;
  }

  if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
    return (string) $_ENV[$key];
  }

  if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
    return (string) $_SERVER[$key];
  }

  return $default;
}

function smtp_read_line($socket): string
{
  $line = '';
  while (($part = fgets($socket, 515)) !== false) {
    $line .= $part;
    if (strlen($part) < 4 || $part[3] !== '-') {
      break;
    }
  }
  return trim($line);
}

function smtp_expect($socket, array $codes, string &$error, string $context): bool
{
  $line = smtp_read_line($socket);
  if ($line === '') {
    $error = "SMTP empty response during {$context}";
    return false;
  }

  $code = (int) substr($line, 0, 3);
  if (!in_array($code, $codes, true)) {
    $error = "SMTP {$context} failed: {$line}";
    return false;
  }

  return true;
}

function smtp_send_command($socket, string $command, array $codes, string &$error, string $context): bool
{
  if (fwrite($socket, $command . "\r\n") === false) {
    $error = "SMTP write failed during {$context}";
    return false;
  }

  return smtp_expect($socket, $codes, $error, $context);
}

/**
 * Send email directly via SMTP with AUTH LOGIN using PHP streams.
 */
function send_via_smtp(array $cfg, string $to, string $subject, string $body, string $replyTo, string &$error): bool
{
  $host = $cfg['host'];
  $port = (int) $cfg['port'];
  $username = $cfg['username'];
  $password = $cfg['password'];
  $fromEmail = $cfg['from_email'];
  $fromName = $cfg['from_name'];
  $secure = $cfg['secure'];

  $target = ($secure === 'ssl' ? 'ssl://' : '') . $host . ':' . $port;
  $socket = @stream_socket_client($target, $errno, $errstr, 20);
  if (!$socket) {
    $error = "SMTP connection failed: {$errstr} ({$errno})";
    return false;
  }

  stream_set_timeout($socket, 20);

  if (!smtp_expect($socket, [220], $error, 'greeting')) {
    fclose($socket);
    return false;
  }

  if (!smtp_send_command($socket, 'EHLO localhost', [250], $error, 'EHLO')) {
    fclose($socket);
    return false;
  }

  if ($secure === 'tls') {
    if (!smtp_send_command($socket, 'STARTTLS', [220], $error, 'STARTTLS')) {
      fclose($socket);
      return false;
    }

    $cryptoOk = @stream_socket_enable_crypto(
      $socket,
      true,
      STREAM_CRYPTO_METHOD_TLS_CLIENT
    );

    if ($cryptoOk !== true) {
      $error = 'Unable to enable TLS encryption for SMTP connection';
      fclose($socket);
      return false;
    }

    if (!smtp_send_command($socket, 'EHLO localhost', [250], $error, 'EHLO after STARTTLS')) {
      fclose($socket);
      return false;
    }
  }

  if (!smtp_send_command($socket, 'AUTH LOGIN', [334], $error, 'AUTH LOGIN')) {
    fclose($socket);
    return false;
  }

  if (!smtp_send_command($socket, base64_encode($username), [334], $error, 'SMTP username')) {
    fclose($socket);
    return false;
  }

  if (!smtp_send_command($socket, base64_encode($password), [235], $error, 'SMTP password')) {
    fclose($socket);
    return false;
  }

  if (!smtp_send_command($socket, 'MAIL FROM:<' . $fromEmail . '>', [250], $error, 'MAIL FROM')) {
    fclose($socket);
    return false;
  }

  if (!smtp_send_command($socket, 'RCPT TO:<' . $to . '>', [250, 251], $error, 'RCPT TO')) {
    fclose($socket);
    return false;
  }

  if (!smtp_send_command($socket, 'DATA', [354], $error, 'DATA')) {
    fclose($socket);
    return false;
  }

  $headers = [
    'From: ' . $fromName . ' <' . $fromEmail . '>',
    'To: ' . $to,
    'Reply-To: ' . $replyTo,
    'Subject: ' . $subject,
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
    'X-Mailer: PHP/' . phpversion(),
  ];

  $message = implode("\r\n", $headers) . "\r\n\r\n" . str_replace(["\r\n", "\r"], "\n", $body);
  $message = str_replace("\n", "\r\n", $message);
  $message = str_replace("\r\n.", "\r\n..", $message);

  if (fwrite($socket, $message . "\r\n.\r\n") === false) {
    $error = 'SMTP write failed while sending DATA payload';
    fclose($socket);
    return false;
  }

  if (!smtp_expect($socket, [250], $error, 'DATA completion')) {
    fclose($socket);
    return false;
  }

  @smtp_send_command($socket, 'QUIT', [221], $error, 'QUIT');
  fclose($socket);
  return true;
}

// Sender should be bgmabs and reply should go to the person who submitted the form.
$headers = [
  'From: REYD Telecom Webpage <bgmabs@gmail.com>',
  'Reply-To: ' . $email,
  'Content-Type: text/plain; charset=UTF-8',
  'X-Mailer: PHP/' . phpversion(),
];

$smtpConfig = [
  'host' => env_value('SMTP_HOST', ''),
  'port' => env_value('SMTP_PORT', '587'),
  'username' => env_value('SMTP_USERNAME', env_value('SMTP_USER', '')),
  'password' => env_value('SMTP_PASSWORD', env_value('EMAIL_PASSWORD', '')),
  'from_email' => env_value('SMTP_FROM_EMAIL', 'bgmabs@gmail.com'),
  'from_name' => env_value('SMTP_FROM_NAME', 'REYD Telecom Webpage'),
  'secure' => strtolower(env_value('SMTP_SECURE', 'tls') ?? 'tls'),
];

if (!in_array($smtpConfig['secure'], ['tls', 'ssl', 'none'], true)) {
  $smtpConfig['secure'] = 'tls';
}

$mailError = '';
$smtpReady = $smtpConfig['host'] !== '' && $smtpConfig['username'] !== '' && $smtpConfig['password'] !== '';

if ($smtpReady) {
  $sent = send_via_smtp($smtpConfig, $to, $subject, $body, $email, $mailError);
} else {
  set_error_handler(static function ($severity, $message) use (&$mailError) {
    $mailError = $message;
    return true;
  });

  $sent = @mail($to, $subject, $body, implode("\r\n", $headers));
  restore_error_handler();
}

if (!$sent && $mailError !== '') {
  error_log('Contact form mail error: ' . $mailError);
}

$queuedToLocalInbox = false;
if (!$sent) {
  $storageDir = __DIR__ . DIRECTORY_SEPARATOR . 'storage';
  $inboxFile = $storageDir . DIRECTORY_SEPARATOR . 'contact-inbox.log';

  if (!is_dir($storageDir)) {
    @mkdir($storageDir, 0775, true);
  }

  if (is_dir($storageDir) && is_writable($storageDir)) {
    $entry = [
      'timestamp' => date('c'),
      'name' => $name,
      'email' => $email,
      'phone' => $phone,
      'message' => $message,
      'mail_error' => $mailError === '' ? null : $mailError,
    ];

    $logLine = json_encode($entry, JSON_UNESCAPED_SLASHES) . PHP_EOL;
    $queuedToLocalInbox = file_put_contents($inboxFile, $logLine, FILE_APPEND | LOCK_EX) !== false;
  }
}

$_SESSION['contact_status'] = [
  'ok' => $sent || $queuedToLocalInbox,
  'message' => $sent
    ? 'Your message was sent successfully.'
    : ($queuedToLocalInbox
      ? 'Mail server is unavailable right now, but your message was received and queued.'
      : 'Unable to send your message right now. Please contact us directly at reydtelecom@gmail.com.'),
];

header('Location: index.php#contact');
exit;
