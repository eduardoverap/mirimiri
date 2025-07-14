<?php

// URL function
if (!function_exists('url')) {
  function url(string $path = ''): string
  {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
  }
}

// Redirect to homepage
if (!function_exists('goHome')) {
  function goHome(): void
  {
    header('Location: ' . BASE_URL);
    die();
  }
}

// Get database index from level name
if (!function_exists('getLevelIndex')) {
  function getLevelIndex(int $value, array $array): int
  {
    return array_search($value, $array) + 1;
  }
}

// Get level name from database index
if (!function_exists('getLevelFromIndex')) {
  function getLevelFromIndex(int $index, array $array): string
  {
    return $array[$index - 1];
  }
}

// Parse search value
if (!function_exists('parseSearchValue')) {
  function parseSearchValue(string $searchValue): array
  {
    if (preg_match(JOYO_REGEX, $searchValue, $eval)) {
      $joyoLevel = ($eval[1] === 's' || $eval[1] === 'secondary') ? 7 : $eval[1];
      return [
        'where'  => ' WHERE Joyo LIKE :search',
        'search' => $joyoLevel
      ];
    } else if (preg_match(JLPT_REGEX, $searchValue, $eval)) {
      $jlptLevel = getLevelIndex('N' . $eval[1], JLPT_LEVEL);
      return [
        'where'  => ' WHERE Joyo LIKE :search',
        'search' => $jlptLevel
      ];
    } else {
      return [];
    }
  }
}

// Log error with date and time
if (!function_exists('logErrorWithTimestamp')) {
  function logErrorWithTimestamp(Throwable|PDOException $error, string $file): void
  {
    $timestamp = date('Y-m-d H:i:s');
    error_log("[{$timestamp}] Error from $file: {$error->getMessage()}\n", 3, JOURNAL_LOG);
  }
}
