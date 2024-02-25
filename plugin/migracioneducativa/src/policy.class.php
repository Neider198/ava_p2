<?php

class policy {

    public static function init() {

        return new policy();
    }

    public  function onlyAdmin() {

        if (!api_is_platform_admin()) {
            exit('No tienes los permisos para ejecutar esta acciòn.');
        }

        return $this;
    }



    public  function onlyGuest() {

        if (!api_is_anonymous()) {
            exit('Solo para usuarios invitados');
        }

        return $this;
    }

    public  function onlyPluginActive() {
        if (!(new AppPlugin)->isInstalled('ava')) {
            exit('Plugin no instalado.');
        }

        return $this;
    }
}
