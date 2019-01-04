<?php declare(strict_types=1);

return
[
	/**
	 * The application environment
	 *
	 * MUST contain: "local", development", "stage" or "production".
	 *
	 * @var string
	 */
	'env' => 'development',

	/**
	 * Database Connection Configuration
	 *
	 * @var array
	 *
	 * @link https://www.doctrine-project.org/projects/doctrine-dbal/en/2.9/reference/configuration.html
	 */
	'database' =>
	[
		'driver' => 'pdo_mysql',
		'host' => '127.0.0.1',
		'port' => 3306,
		'user' => 'rgmHtHYsPK6q7CQb',
		'password' => 'jHsmw2D8HjQbLUYD',
		'dbname' => 'app',
		'charset' => 'utf8mb4',
	],
];
