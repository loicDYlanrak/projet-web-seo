<?php
// Configuration et fonctions partagées
session_start();

require_once __DIR__ . '/../../includes/function.php';

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Fonction pour rediriger si non connecté
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Fonction pour obtenir la vue actuelle
function getCurrentView() {
    return isset($_GET['view']) ? $_GET['view'] : 'dashboard';
}

// Fonction pour générer les URLs propres
function url($view = 'dashboard') {
    return "index.php?view=" . $view;
}

// Nouvelle fonction pour gérer le thème
function getTheme() {
    // Vérifier si un thème est stocké en session ou localStorage (via cookie)
    if (isset($_COOKIE['vertonews_theme'])) {
        return $_COOKIE['vertonews_theme'];
    }
    // Retourner 'light' par défaut
    return 'light';
}