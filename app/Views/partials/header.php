<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mirimiri Kanji CRUD</title>
  <link rel="icon" href="data:,">
  <!-- Google fonts (Noto Sans JP) -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" />
  <!-- jQuery and DataTables includes -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous" defer></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
  <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js" defer></script>
  <!-- Application styles and scripts -->
  <link rel="stylesheet" href="src/styles.css" />
  <link rel="stylesheet" href="src/modal.css" />
  <script src="src/modal.js" defer></script>
</head>
<body>
  <header>
    <h1>Mirimiri Kanji CRUD</h1>
    <nav>
      <ul>
        <li><a href="<?= url(); ?>">Home</a></li>
        <li><a href="<?= url('admin'); ?>">My database</a></li>
      </ul>
    </nav>
  </header>
