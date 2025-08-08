<?php
// Set PDO connection
function setConn($dbPath = DB_PATH): PDO
{
  try {
    $conn = new PDO('sqlite:' . $dbPath);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  } catch (PDOException $e) {
    logErrorWithTimestamp($e, __FILE__);
    die($e->getMessage() . "\n" . $dbPath);
  }
}
