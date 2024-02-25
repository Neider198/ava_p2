<?php
/* For license terms, see /license.txt */

use ChamiloSession as Session;

/**
 * Plugin class ava plugin.
 *
 * @package chamilo.ava
 *
 * @author Implementación de un modelo de i+d+i para gestionar y fortalecer la calidad de la educación básica y media del departamento <calidadeducativa@utch.edu.co>
 */
class MigracionEducativaPlugin extends Plugin
{
    private const authors = "llllProyecto Implementación de un modelo de I+D+I para gestionar y
    fortalecer la calidad de la educación básica y
    media del departamento del Chocó <br/>";

    private const settings = [
        'url_service' => 'text'
    ];

    private const version = '2.0';

    public $isAdminPlugin = true;

    /**
     * AvaPlugin constructor.
     */
    public function __construct()
    {


        parent::__construct(
            static::version,
            static::authors,
            static::settings
        );
    }

    /**
     * @return MigracionEducativaPlugin
     */
    public static function create()
    {
        static $result = null;

        return $result ? $result : $result = new self();
    }

    /**
     * install plugin event.
     */
    public function install()
    {

        /*$this->manageTab('true');*/
    }

    /**
     * uninstall plugin event.
     */
    public function uninstall()
    {

        /*$this->manageTab('false');*/
    }

    /**
     * update plugin event.
     */
    public function update()
    {
    }
    // academico.

}
