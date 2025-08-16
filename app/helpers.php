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
  function goHome(int $httpCode): void
  {
    http_response_code($httpCode);
    header('Location: ' . BASE_URL);
    die();
  }
}

// Sanitize input
if (!function_exists('sanitizeInput')) {
  function sanitizeInput(string $input): string {
    return htmlspecialchars(stripslashes(trim($input)));
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
    $where = $search = '';

    if (preg_match(JOYO_REGEX, $searchValue, $eval)) {
      $joyoLevel = ($eval[1] === 's' || $eval[1] === 'secondary') ? 7 : $eval[1];
      $where  = ' WHERE joyo LIKE :search';
      $search = $joyoLevel;
    } else if (preg_match(JLPT_REGEX, $searchValue, $eval)) {
      $jlptLevel = getLevelIndex('N' . $eval[1], JLPT_LEVEL);
      $where  = ' WHERE jlpt LIKE :search';
      $search = $jlptLevel;
    }

    return [
      'where'  => $where,
      'search' => $search
    ];
  }
}

// Convert from snake_case to camelCase
if (!function_exists('snakeToCamel')) {
  function snakeToCamel(string $snake): string {
    $words = explode('_', $snake);
    $camel = strtolower(array_shift($words));
    foreach ($words as $word) {
      $camel .= ucfirst(strtolower($word));
    }
    return $camel;
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
