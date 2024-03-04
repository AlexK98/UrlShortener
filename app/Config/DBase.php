<?php

namespace App\Config;

class DBase
{
	public static string $host = 'localhost';
	public static string $user = 'root';
	public static string $pass = '';

	public static string $dbase = 'short_urls';
	public static string $table = 'storage';

	public static string $charset = 'utf8mb4';
	public static string $collate = 'utf8mb4_unicode_ci';

    // defining table structure
	public static string $colHashed = 'hashed';
	public static string $colSource = 'source_url';
	public static string $colVisits = 'visit_count';
	public static string $colCreatedAt = 'created_at';
	public static string $colExpiresOn = 'expires_on'; // should be updated on last visit
}
