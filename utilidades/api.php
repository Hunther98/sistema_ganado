<?php
require_once '../config/config.php';

class ApiManager {
    private static $apiKeys = [];
    
    public static function init() {
        self::$apiKeys = [
            'google_maps' => GOOGLE_MAPS_API_KEY,
            // Puedes agregar más APIs aquí en el futuro
        ];
    }
    
    public static function getApiKey($service) {
        if (isset(self::$apiKeys[$service])) {
            return self::$apiKeys[$service];
        }
        return null;
    }
    
    public static function isEnabled($service) {
        switch ($service) {
            case 'google_maps':
                return GOOGLE_MAPS_ENABLED;
            case 'email':
                return EMAIL_SERVICE_ENABLED;
            case 'sms':
                return SMS_SERVICE_ENABLED;
            default:
                return false;
        }
    }
    
    public static function getGoogleMapsScript() {
        if (!self::isEnabled('google_maps') || empty(self::getApiKey('google_maps'))) {
            return '<!-- Google Maps deshabilitado -->';
        }
        
        $apiKey = self::getApiKey('google_maps');
        return "<script async defer src=\"https://maps.googleapis.com/maps/api/js?key={$apiKey}&callback=initMaps&libraries=places\"></script>";
    }
}

// Inicializar el manager
ApiManager::init();
?>