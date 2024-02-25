<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


<?php

class Boostrap {

    public $policy;

    public function __construct()
    {
     $this->policy =  policy::init();
     $this->policy->onlyPluginActive();
    }

    public function sign () {
     $this->policy->onlyGuest();
     AvaPlugin::create()->login_ava();
    }

    public function  start() {
        $action = $_REQUEST['action'] ?? 'index';
        $action =  method_exists(Boostrap::class, $action) ? $action : 'index';

        (new Boostrap)->$action();
    }

    public function index () {
        $this->policy->onlyAdmin();
        $contenido ="<div class='container-fluid contenedor-padre'>
                        <div class='container contenedor-hijo' >
                            <h2 class='titulo'> MIGRACION DE DATOS SGA - AVA </h2>
                            <hr>
                                <a  type='button'
                                    class='btn btn-secondary btn-sm secundario'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=usuario_activo'>
                                        Usuario Activo
                                </a>
                                <a  type='button'
                                    class='btn btn-secondary btn-sm secundario'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=institucion_activo'>
                                        Institucion Activo
                                </a>
                                <a  type='button'
                                    class='btn btn-secondary btn-sm secundario'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=año_electivo'>
                                        Año Electivo Activo
                                </a>
                                <a  type='button'
                                    class='btn btn-secondary btn-sm secundario'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=usuarios'>
                                        Usuarios
                                </a>
                            <hr>
                                <a  type='button'
                                    class='btn btn-primary btn-sm primario'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=instituciones'>
                                        Instituciones
                                </a>
                                <a  type='button'
                                    class='btn btn-warning btn-sm alerta'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=total_instituciones'>
                                        Total Instituciones
                                </a>
                                <a  type='button'
                                    class='btn btn-success btn-sm correpto'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=migrar_instituciones'>
                                        Migrar Instituciones
                                </a>
                            <hr>
                            <a  type='button'
                                    class='btn btn-primary btn-sm primario'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=sedes'>
                                        Sedes
                                </a>
                                <a  type='button'
                                    class='btn btn-warning btn-sm alerta'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=total_sedes'>
                                        Total Sedes
                                </a>
                                <a  type='button'
                                    class='btn btn-success btn-sm correpto'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=migrar_sedes'>
                                        Migrar Sedes
                                </a>
                            <hr>
                            <!-- <a  type='button'
                                    class='btn btn-primary btn-sm primario'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=personas'>
                                        Personas
                                </a>
                                <a  type='button'
                                    class='btn btn-warning btn-sm alerta'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=total_personas'>
                                        Total Personas
                                </a>
                            <hr>-->
                                <a  type='button'
                                    class='btn btn-primary btn-sm primario'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=docentes'>
                                        Docentes
                                </a>
                                <a  type='button'
                                    class='btn btn-warning btn-sm alerta'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=total_docentes'>
                                        Total Docentes
                                </a>
                                <a  type='button'
                                    class='btn btn-success btn-sm correpto'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=migrar_docentes'>
                                        Migrar Docentes
                                </a>
                            <hr>
                             <a  type='button'
                                    class='btn btn-primary btn-sm primario'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=estudiantes'>
                                        Estudiantes
                                </a>
                                <a  type='button'
                                    class='btn btn-warning btn-sm alerta'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=total_estudiantes'>
                                        Total Estudiantes
                                </a>
                                <a  type='button'
                                    class='btn btn-success btn-sm correpto'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=migrar_estudiantes'>
                                        Migrar Estudiantes
                                </a>
                            <hr>
                             <a  type='button'
                                    class='btn btn-primary btn-sm primario'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=cursos'>
                                        Cursos
                                </a>
                                <a  type='button'
                                    class='btn btn-warning btn-sm alerta'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=total_cursos'>
                                        Total Cursos
                                </a>
                                <a  type='button'
                                    class='btn btn-success btn-sm correpto'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=migrar_cursos'>
                                        Migrar Cursos
                                </a>
                            <hr>
                             <a  type='button'
                                    class='btn btn-primary btn-sm primario'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=grupos'>
                                        Grupos
                                </a>
                                <a   type='button'
                                    class='btn btn-warning btn-sm alerta'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=total_grupos'>
                                        Total Grupos
                                </a>
                                <a  type='button'
                                    class='btn btn-success btn-sm correpto'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=migrar_grupos'>
                                        Migrar Grupos
                                </a>
                            <hr>
                            <a  type='button'
                                    class='btn btn-primary btn-sm primario'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=tutores'>
                                        Tutores
                                </a>

                                <a   type='button'
                                    class='btn btn-warning btn-sm alerta'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=total_tutores'>
                                        Total Tutores
                                </a>
                                <a  type='button'
                                    class='btn btn-success btn-sm correpto'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=migrar_tutores'>
                                        Migrar Tutores
                                </a>
                            <hr>
                            <a  type='button'
                                class='btn btn-primary btn-sm primario'
                                href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=asignatura'>
                                    Asignatura
                            </a>
                            <a   type='button'
                                class='btn btn-warning btn-sm alerta'
                                href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=total_asignatura'>
                                    Total Asignatura
                            </a>
                            <hr>
                            <a  type='button'
                                class='btn btn-primary btn-sm primario'
                                href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=matriculas'>
                                    Matriculas
                            </a>
                            <a   type='button'
                                class='btn btn-warning btn-sm alerta'
                                href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=total_matriculas'>
                                    Total Matriculas
                            </a>
                            <a  type='button'
                                    class='btn btn-success btn-sm correpto'
                                    href='http://localhost/chamilo-1.1.18/plugin/ava/index.php?action=migrar_matriculas'>
                                        Registrar Matriculas
                                </a>
                        </div>
                    </div>";
        echo $contenido;
    }

    public function usuario_activo () {
        $token = AvaPlugin::create()->getToken();
        $academico =  AvaPlugin::create()->academico();
        $academico->get_sesion($token);
        $respuesta = $academico->get_usuario_activo();
        print_r(json_encode($respuesta));
    }

    public function institucion_activo () {
        $token = AvaPlugin::create()->getToken();
        $academico =  AvaPlugin::create()->academico();
        $academico->get_sesion($token);
        $respuesta = $academico->get_instititucion_activa();
        print_r(json_encode($respuesta));
    }

    public function año_electivo () {
        $token = AvaPlugin::create()->getToken();
        $academico =  AvaPlugin::create()->academico();
        $academico->get_sesion($token);
        $respuesta = $academico->get_anno_lectivo();
        print_r(json_encode($respuesta));
    }

    public function usuarios () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_usuarios();
        print_r(json_encode($respuesta));
    }

    //INSTITUCIONES
    public function instituciones () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_instituciones();
        print_r(json_encode($respuesta));
    }

    public function total_instituciones () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_total_instituciones();
        print_r(json_encode($respuesta));
    }

    public function migrar_instituciones(){
        AvaPlugin::create()->registrar_instituciones();
    }

    //SEDES
    public function sedes () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_sedes();
        print_r(json_encode($respuesta));
    }
    public function total_sedes () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_total_sedes();
        print_r(json_encode($respuesta));
    }

    public function migrar_sedes(){
        AvaPlugin::create()->registrar_sedes();
    }

    //PERSONAS
    public function personas () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_personas();
        print_r(json_encode($respuesta));
    }
    public function total_personas () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_total_personas();
        print_r(json_encode($respuesta));
    }

    //DOCENTES
    public function docentes () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_docentes();
        print_r(json_encode($respuesta));
    }
    public function total_docentes () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_total_docentes();
        print_r(json_encode($respuesta));
    }
    public function migrar_docentes(){
        AvaPlugin::create()->registrar_docentes();
    }

    //ESTUDIANTES
    public function estudiantes () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_estudiantes();
        print_r(json_encode($respuesta));
    }
    public function total_estudiantes () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_total_estudiantes();
        print_r(json_encode($respuesta));
    }
    public function migrar_estudiantes(){
        AvaPlugin::create()->registrar_estudiantes();
    }

    //CURSOS
    public function cursos () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_cursos();
        print_r(json_encode($respuesta));
    }
    public function total_cursos () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_total_cursos();
        print_r(json_encode($respuesta));
    }
    public function migrar_cursos(){
        AvaPlugin::create()->registrar_cursos();
    }

    //GRUPOS
    public function grupos () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_grupos();
        print_r(json_encode($respuesta));
    }
    public function total_grupos () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_total_grupos();
        print_r(json_encode($respuesta));
    }
    public function migrar_grupos(){
        AvaPlugin::create()->registrar_grupos();
    }

    //TUTOR
    public function tutores () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_tutores();
        print_r(json_encode($respuesta));
    }
    public function total_tutores () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_total_tutores();
        print_r(json_encode($respuesta));
    }
    public function migrar_tutores(){
        AvaPlugin::create()->registrar_tutores();
    }

    public function asignatura () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_asignatura();
        print_r(json_encode($respuesta));
    }

    public function total_asignatura () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_total_asignatura();
        print_r(json_encode($respuesta));
    }
    //MATRICULAS
    public function matriculas () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_matriculas();
        print_r(json_encode($respuesta));
    }
    public function total_matriculas () {
        $academico =  AvaPlugin::create()->academico();
        $respuesta = $academico->get_total_matriculas();
        print_r(json_encode($respuesta));
    }
    public function migrar_matriculas(){
        AvaPlugin::create()->registrar_matriculas();
    }
}

