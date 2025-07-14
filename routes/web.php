<?php

// Get the requested route
$route  = $_GET['route'] ?? 'home';  // Default route: 'home'
$action = 'index';

// Split route into controller and action
if ($route === 'xmlimport') {
  $controllerName = 'XMLImportController';
} else if ($route === 'csvimport') {
  $controllerName = 'CSVImportController';
} else {
  $parts = explode('/', $route);
  $controllerName = ucfirst(strtolower($parts[0])) . 'Controller';
  $action = $parts[1] ?? 'index';
}

// Load the matching controller
$controllerClass = "App\\Controllers\\$controllerName";

if (class_exists($controllerClass)) {
  $controller = new $controllerClass();

  // Method exists
  if (method_exists($controller, $action)) {
    $controller->$action();
  } else {
    // Action not found
    http_response_code(404);
    echo "Method '$action' not found in $controllerName.";
  }
} else {
  $controller = new App\Controllers\HomeController();
  $controller->index();
  header('Content-Type: text/html; charset=UTF-8');
  header("Location: " . BASE_URL);
}
