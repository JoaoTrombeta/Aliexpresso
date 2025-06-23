<?php
    namespace Aliexpresso\Helper;

    class Auth {
        public static function isLoggedIn(): bool {
            return isset($_SESSION['usuario']);
        }

        public static function user() {
            return $_SESSION['usuario'] ?? null;
        }
        
        public static function role(): ?string {
            return self::isLoggedIn() ? self::user()['tipo'] : null;
        }

        public static function isAdmin(): bool {
            return self::role() === 'admin';
        }

        public static function isManager(): bool {
            return self::role() === 'gerente';
        }

        public static function isVendor(): bool {
            return self::role() === 'vendedor';
        }

        public static function canManageProducts(): bool {
            return self::isVendor() || self::isManager() || self::isAdmin();
        }
        
        public static function canManageVendors(): bool {
            return self::isManager() || self::isAdmin();
        }
        
        public static function canManageManagers(): bool {
            return self::isAdmin();
        }
    }
?>