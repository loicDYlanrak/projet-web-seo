<?php

declare(strict_types=1);

function envValue(string $key, ?string $default = null): ?string
{
	$value = getenv($key);
	if ($value === false || $value === '') {
		return $default;
	}

	return $value;
}

function dbConnection(): PDO
{
	static $pdo = null;

	if ($pdo instanceof PDO) {
		return $pdo;
	}

	$host = envValue('DB_HOST', 'db');
	$port = envValue('DB_PORT', '3306');
	$database = envValue('DB_DATABASE', 'seo_db');
	$username = envValue('DB_USERNAME', 'seo_user');
	$password = envValue('DB_PASSWORD', 'seo_password');

	$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $database);

	$pdo = new PDO($dsn, $username, $password, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	]);

	return $pdo;
}
