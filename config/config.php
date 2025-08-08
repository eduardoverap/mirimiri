<?php
// Set UTF-8 encoding
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_regex_encoding('UTF-8');

// App paths
define('APP_ROOT', __DIR__ . '/..');
define('BASE_URL', str_replace('public/index.php', '', $_SERVER['PHP_SELF']));
define('DB_PATH', APP_ROOT . '/database/app.db');
define('CORPUS_DB_PATH', APP_ROOT . '/database/corpus.db');
define('DB_CONN', APP_ROOT . '/config/database.php');
define('KD2_PATH', APP_ROOT . '/database/kanjidic2.xml');
define('SETTINGS_PATH', APP_ROOT . '/storage/settings.json');
define('JOURNAL_LOG', APP_ROOT . '/storage/journal.log');

// Level constants
define('JOYO_LEVEL', ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6', 'Secondary', 'Jinmeiyo']);
define('KENTEI_LEVEL', ['10', '9', '8', '7', '6', '5', '4', '3', 'pre-2', '2', 'pre-1', '1']);
define('JLPT_LEVEL', ['N5', 'N4', 'N3', 'N2', 'N1']);

// Regex patterns
define('JOYO_REGEX', '/^joyo:([1-6]|s|secondary)$/i');
define('JLPT_REGEX', '/^jlpt:n?([1-5])$/i');
define('HAN_REGEX', '/\p{Han}/u');

// Web components
define('BTN_ACTIONS', '<input type="button" class="btn-edit" value="Edit" />');

// Set timezone
$tz = json_decode(file_get_contents(SETTINGS_PATH))->timezone;
date_default_timezone_set($tz);