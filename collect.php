<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Informations serveur
$ip_address = $_SERVER['REMOTE_ADDR'];
$access_date = date("Y-m-d H:i:s");
$user_agent_php = $_SERVER['HTTP_USER_AGENT'];
$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$http_referer = $_SERVER['HTTP_REFERER'] ?? 'Non disponible';
$http_accept = $_SERVER['HTTP_ACCEPT'];
$http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$http_accept_encoding = $_SERVER['HTTP_ACCEPT_ENCODING'];
$http_connection = $_SERVER['HTTP_CONNECTION'] ?? 'Non disponible';
$server_protocol = $_SERVER['SERVER_PROTOCOL'];
$remote_port = $_SERVER['REMOTE_PORT'];
$http_host = $_SERVER['HTTP_HOST'];
$server_name = $_SERVER['SERVER_NAME'];
$server_software = $_SERVER['SERVER_SOFTWARE'];
$server_port = $_SERVER['SERVER_PORT'];
$document_root = $_SERVER['DOCUMENT_ROOT'];
$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'Oui' : 'Non';

// Appel à l'API ipinfo.io
$ipinfo_token = "a641db05eaeae8";
$ipinfo_url = "https://ipinfo.io/{$ip_address}?token={$ipinfo_token}";
$ipinfo_data = json_decode(@file_get_contents($ipinfo_url), true);

// Appel à l'API ip-api.com
$ipapi_url = "http://ip-api.com/json/{$ip_address}";
$ipapi_data = json_decode(@file_get_contents($ipapi_url), true);

// Informations de géolocalisation de ipinfo.io
$city_ipinfo = $ipinfo_data['city'] ?? 'Non disponible';
$region_ipinfo = $ipinfo_data['region'] ?? 'Non disponible';
$country_ipinfo = $ipinfo_data['country'] ?? 'Non disponible';
$location_ipinfo = $ipinfo_data['loc'] ?? 'Non disponible';
$org_ipinfo = $ipinfo_data['org'] ?? 'Non disponible';
$timezone_ipinfo = $ipinfo_data['timezone'] ?? 'Non disponible';

// Informations de géolocalisation de ip-api.com
$city_ipapi = $ipapi_data['city'] ?? 'Non disponible';
$region_ipapi = $ipapi_data['regionName'] ?? 'Non disponible';
$country_ipapi = $ipapi_data['country'] ?? 'Non disponible';
$location_ipapi = isset($ipapi_data['lat']) && isset($ipapi_data['lon']) ? "{$ipapi_data['lat']},{$ipapi_data['lon']}" : 'Non disponible';
$org_ipapi = $ipapi_data['org'] ?? 'Non disponible';
$timezone_ipapi = $ipapi_data['timezone'] ?? 'Non disponible';
$isp_ipapi = $ipapi_data['isp'] ?? 'Non disponible';
$as_ipapi = $ipapi_data['as'] ?? 'Non disponible';

// Récupération des informations envoyées par le navigateur via JavaScript
$json_data = file_get_contents("php://input");
$browser_info = json_decode($json_data, true);

$screen_resolution = $browser_info['screenResolution'] ?? 'Non disponible';
$color_depth = $browser_info['colorDepth'] ?? 'Non disponible';
$language = $browser_info['language'] ?? 'Non disponible';
$platform = $browser_info['platform'] ?? 'Non disponible';
$user_agent_js = $browser_info['userAgent'] ?? 'Non disponible';
$cores = $browser_info['cores'] ?? 'Non disponible';
$connection = $browser_info['connection'] ?? 'Non disponible';
$timezone_js = $browser_info['timezone'] ?? 'Non disponible';
$supports_cookies = $browser_info['supportsCookies'] ?? 'Non disponible';
$supports_webgl = $browser_info['supportsWebGL'] ? 'Oui' : 'Non';
$webgl_info = $browser_info['webglInfo'] ? "Vendor - {$browser_info['webglInfo']['vendor']}, Renderer - {$browser_info['webglInfo']['renderer']}" : 'Non disponible';
$device_memory = $browser_info['deviceMemory'] ?? 'Non disponible';
$storage_estimate = $browser_info['storageEstimate'] ?? 'Non disponible';
$battery_level = $browser_info['battery']['level'] ?? 'Non disponible';
$charging_status = $browser_info['battery']['charging'] ?? 'Non disponible';

// Formatage de l'entrée pour le fichier
$entry = <<<EOD
Date: $access_date
IP: $ip_address
User-Agent (PHP): $user_agent_php
Méthode de requête: $request_method
URI de la requête: $request_uri
Référent HTTP: $http_referer
Accept HTTP: $http_accept
Langue Acceptée HTTP: $http_accept_language
Encodage Accepté HTTP: $http_accept_encoding
Connection HTTP: $http_connection
Protocole du Serveur: $server_protocol
Port Distant: $remote_port
Hôte HTTP: $http_host
Nom du Serveur: $server_name
Logiciel du Serveur: $server_software
Port du Serveur: $server_port
Racine du Document: $document_root
Accès HTTPS: $is_https

--- Informations de géolocalisation (API ipinfo.io) ---
Ville: $city_ipinfo
Région: $region_ipinfo
Pays: $country_ipinfo
Coordonnées: $location_ipinfo
FAI/Organisation: $org_ipinfo
Fuseau horaire (API ipinfo.io): $timezone_ipinfo

--- Informations de géolocalisation (API ip-api.com) ---
Ville: $city_ipapi
Région: $region_ipapi
Pays: $country_ipapi
Coordonnées: $location_ipapi
FAI/Organisation: $org_ipapi
ISP: $isp_ipapi
AS: $as_ipapi
Fuseau horaire (API ip-api.com): $timezone_ipapi

--- Informations du navigateur (via JavaScript) ---
Résolution de l'écran: $screen_resolution
Profondeur de couleur: $color_depth
Langue du navigateur: $language
Plateforme: $platform
User-Agent (JS): $user_agent_js
Nombre de cœurs processeur: $cores
Type de connexion réseau: $connection
Fuseau horaire (JS): $timezone_js
Supporte les cookies: $supports_cookies
Stockage local (expérimentale et peut-être imprécis) : $storage_estimate
Informations WebGL : $webgl_info
Mémoire de l'appareil (expérimentale et peut-être imprécis): $device_memory GB
Niveau de batterie: $battery_level
En charge : $charging_status
------------------------------------------------------------
EOD;

// Enregistrement dans le fichier
$file = 'encrypted_ips.txt';
if (file_put_contents($file, $entry . PHP_EOL, FILE_APPEND) !== false) {
    echo "Toutes les informations disponibles ont été stockées avec succès.";
} else {
    echo "Erreur : Impossible d'enregistrer les informations. Vérifiez les permissions du fichier.";
}
?>

