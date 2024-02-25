<?php
ini_set('memory_limit', '10024M');

class Academico
{

    private $url;

    private $token;

    private $usuario;

    private $institucion;
    private $institucion_id;

    private $anno_lectivo;
    private $anno_lectivo_id;

    public function __construct($url_base,  $institucion_id = NULL, $anno_lectivo_id = NULL, $token = NULL)
    {
        $this->url = $url_base;
        $this->token = $token;
        $this->institucion_id = $institucion_id;
        $this->anno_lectivo_id = $anno_lectivo_id;
    }

   /* function iniciar_sesion($usuario, $clave, $tipo_usuario)
    {
        $params = '{"item": "' . $tipo_usuario . '", "name": "' . $usuario . '", "pass":"' . $clave . '"}';

        $r = $this->post(
            $this->url . "/sign",
            $params,
            [
                'Content-Type: application/json'
            ]
        );

        if ($r["http_code"] == "200") {
            $j = json_decode($r["response"], true);
            $this->token =  $j["Type"] . " " . $j["Code"];

            $this->usuario = [
                "usuario" => $usuario,
                "tipo" => $tipo_usuario,
                "imagen" => $j["Face"],
                "nombre" => $j["Name"],
                "apellido" => $j["Last"],
                "correo" => $j["Mail"],

            ];

            $inst = $j['Heap'][0];
            $this->institucion = [
                "id" => $inst["Item"],
                "imagen" => $inst["Icon"],
                "nombre" => $inst["Name"],
                "nombre_corto" => $inst["Nick"],
            ];

            $a = $inst['Heap'][0];

            $this->anno_lectivo  = [
                "id" => $a["Item"],
                "nombre" => $a["Name"]
            ];


            return [
                    "error" => false,
                    "msg" => "Ok",
                    "token" => $this->token
                ];
        } else {
            return [
                    "error" => true,
                    "msg" => $r["response"],
                    "token" => NULL
                ];
        }
    }*/

    function get_sesion($token)
    {
        $r = $this->post(
            $this->url . "/ping",
            NULL,
            [
                'Authorization: ' . $token,
                'Content-Length: 0'
            ]
        );

        if ($r["http_code"] == "200") {
            $j = json_decode($r["response"], true);
            $this->token_string =  $token;

            $this->usuario = [
                "usuario" => $j['Nick'],
                "tipo" => $j["Type"],
                "imagen" => $j["Face"],
                "nombre" => $j["Name"],
                "apellido" => $j["Last"],
                "correo" => $j["Mail"],

            ];

            $inst = $j['Data'];
            foreach ($j['Heap'] as $item) {
                if ($item['Item'] == $inst["Unit"]) {
                    $itemm = $item['Item'];
                    $code = $item['Code'];
                    $nick = $item['Nick'];
                    $name =$item['Name'];
                    $firm =$item['Firm'];
                    $icon =$item['Icon'];
                    break;
                }
            }

            $this->institucion = [
                "id" => $inst["Unit"],
                "idd" => $itemm,
                "codigo" => $code,
                "Institucion" => $firm,
                "sede" =>$name,
                "nombre_corto" => $nick,
                "imagen" => $icon,

            ];

            $a = $inst['Heap'][0];

            $this->anno_lectivo  = [
                "id" => $a["Item"],
                "nombre" => $a["Name"]
            ];

            return ["error" => false, "msg" => "Ok"];
        } else {
            return ["error" => true, "msg" => $r["response"]];
        }
    }

    function get_usuarios()
    {
        $respuesta = $this->get(
            $this->url . '/Usuario?take=16&page=0&find=&pipe=&with=Id,Asignaciones,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,CorreoPrincipal,TelefonoPrincipal,TipoIdentificacion[Codigo,Nombre]',
            NULL,
            [
                'Authorization: ' . $this->token,
                'unit: ' . $this->institucion_id,
                'time: ' . $this->anno_lectivo_id,
            ]
        );

        if ($respuesta["http_code"] == "200") {

            $j = json_decode($respuesta['response'], true);
            $data =  $j['Data'];

            $result = [];
            foreach ($data as $key => $row) {
                $result[] = [
                    'Id' => $row['Id'],
                    'PrimerNombre' => $row['PrimerNombre'],
                    'SegundoNombre' => $row['SegundoNombre'],
                    'PrimerApellido' => $row['PrimerApellido'],
                    'PrimerApellido' => $row['PrimerApellido'],
                    'CorreoPrincipal' => $row['CorreoPrincipal'],
                    'TelefonoPrincipal' => $row['TelefonoPrincipal'],
                    'Asignaciones' => $row['Asignaciones'],
                ];
            }
            return $result;
        } else {
            return [];
        }
    }

    //INSTITUCIONES
    function get_instituciones()
    {
        if ($this->get_total_instituciones() == 0){
            echo "No se encontraron registros de Instituciones";
            exit();
        }
        $total_registros = $this->get_total_instituciones();
        $paginas = ceil($total_registros / 128);
        $result_total = [];

        for ($i=0; $i < $paginas; $i++) {
            $respuesta = $this->get(
                $this->url . '/Institucion?take=1000&page='.$pagina.'&find=&pipe=&with=Id,Alias,Nombre,CorreoPrincipal,TelefonoPrincipal,Sector[Codigo,Nombre]',
                NULL,
                [
                    'Authorization: ' . $this->token,
                    'unit: ' . $this->institucion_id,
                    'time: ' . $this->anno_lectivo_id,
                ]
            );
            if ($respuesta["http_code"] == "200") {
                $j = json_decode($respuesta['response'], true);
                $data = $j['Data'];

                $result = [];
                foreach ($data as $key => $row) {
                    $result[] = [
                        'InstitucionId' => $row['Id'],
                        'InstitucionNombre' => $row['Nombre'],
                        'InstitucionAlias' => $row['Alias'],
                        //'Total' => $total_registros,
                    ];
                }
                $result_total = array_merge($result_total,$result);
                //return $result;
            } else {
                return [];
            }
            $pagina++;
        }
        return $result_total;
    }
    function get_total_instituciones()
    {
        $respuesta = $this->get(
            $this->url . '/Institucion',
            NULL,
            [
                'Authorization: ' . $this->token,
                'unit: ' . $this->institucion_id,
                'time: ' . $this->anno_lectivo_id,
            ]
        );

        if ($respuesta["http_code"] == "200") {

            $j = json_decode($respuesta['response'], true);
            $data =  $j['Size'];

        } else {
            $data = 0;
        }
        return $data;
    }

    //SEDES
    function get_sedes()
    {
        if ($this->get_total_sedes() == 0){
            echo "No se encontraron registros de Sedes";
            exit();
        }
        $total_registros = $this->get_total_sedes();
        $paginas = ceil($total_registros / 128);
        $result_total = [];

        for ($i=0; $i < $paginas; $i++) {
            $respuesta = $this->get(
                $this->url . '/Sede?take=1000&page='.$pagina.'&find=&pipe=&with=Id,Alias,InstitucionId,Nombre,Direccion,CodigoDane,CorreoPrincipal,TelefonoPrincipal,Institucion[Alias]',
                NULL,
                [
                    'Authorization: ' . $this->token,
                    'unit: ' . $this->institucion_id,
                    'time: ' . $this->anno_lectivo_id,
                ]
            );

            if ($respuesta["http_code"] == "200") {

                $j = json_decode($respuesta['response'], true);
                $data = $j['Data'];
                //$data1 =  $j['Size'];

                $result = [];
                foreach ($data as $key => $row) {
                    $result[] = [
                        'SedeId' => $row['Id'],
                        'InstitucionId' => $row['InstitucionId'],
                        'SedeNombre' => $row['Nombre'],
                        'SedeAlias' => $row['Alias'],
                        'InstitucionAlias' => $row['Institucion']['Alias'],
                       /// 'Total' => $total_registros,
                    ];
                }
                $result_total = array_merge($result_total,$result);
            } else {
                return [];
            }
            $pagina++;
        }
        return $result_total;
    }
    function get_total_sedes()
    {
        $respuesta = $this->get(
            $this->url . '/Sede',
            NULL,
            [
                'Authorization: ' . $this->token,
                'unit: ' . $this->institucion_id,
                'time: ' . $this->anno_lectivo_id,
            ]
        );

        if ($respuesta["http_code"] == "200") {

            $j = json_decode($respuesta['response'], true);
            $data =  $j['Size'];

        } else {
            $data = 0;
        }
        return $data;
    }

    //PERSONAS
    function get_personas()
    {
        if ($this->get_total_personas() == 0){
            echo "No se encontraron registros de Personas";
            exit();
        }
        $total_registros = $this->get_total_personas();
        $paginas = ceil($total_registros / 128);
        $result_total = [];


        for ($i=0; $i < $paginas; $i++) {
            $respuesta = $this->get(
                $this->url . '/Persona?take=1000&page='.$pagina.'&find=&pipe=&with=Id,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,Identificacion,CorreoPrincipal,TelefonoPrincipal,Sexo[Nombre]',
                NULL,
                [
                    'Authorization: ' . $this->token,
                    'unit: ' . $this->institucion_id,
                    'time: ' . $this->anno_lectivo_id,

                ]
            );

            if ($respuesta["http_code"] == "200") {

                $j = json_decode($respuesta['response'], true);
                $data =  $j['Data'];

                $result = [];
                foreach ($data as $key => $row) {
                    $result[] = [
                        'Id' => $row['Id'],
                        'Identificacion' => $row['Identificacion'],
                        'Sexo' => $row['Sexo']['Nombre'],
                        'Correo Principal' => $row['CorreoPrincipal'],
                        'Telefono Principal' => $row['TelefonoPrincipal'],
                    ];
                }
                $result_total = array_merge($result_total,$result);
            } else {
                return [];
            }
            $pagina++;
        }

        return $result_total;
    }
    function get_total_personas()
    {
        $respuesta = $this->get(
            $this->url . '/Persona',
            NULL,
            [
                'Authorization: ' . $this->token,
                'unit: ' . $this->institucion_id,
                'time: ' . $this->anno_lectivo_id,
            ]
        );

        if ($respuesta["http_code"] == "200") {

            $j = json_decode($respuesta['response'], true);
            $data =  $j['Size'];

        } else {
            $data = 0;
        }
        return $data;
    }

    //DOCENTES
    function get_docentes()
    {
        if ($this->get_total_docentes() == 0){
            echo "No se encontraron registros de Docentes";
            exit();
        }
        $total_registros = $this->get_total_docentes();
        $paginas = ceil($total_registros / 128);
        $result_total = [];

        for ($i=0; $i < $paginas; $i++) {
            $respuesta = $this->get(
                $this->url . '/Docente?take=1000&page='.$pagina.'&find=&pipe=&with=Id,FechaIngreso,Sede[Nombre],Persona[Id,Identificacion,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,CorreoPrincipal,TelefonoPrincipal,Sexo[Codigo],TipoIdentificacion[Codigo]],EstadoFuncionario[Nombre]',
                NULL,
                [
                    'Authorization: ' . $this->token,
                    'unit: ' . $this->institucion_id,
                    'time: ' . $this->anno_lectivo_id

                ]
            );

            if ($respuesta["http_code"] == "200") {

                $j = json_decode($respuesta['response'], true);

                $data = $j['Data'];

                $result = [];
                foreach ($data as $key => $row) {

                    $result[] = [
                        'DocenteId' => $row['Id'],
                        'Identificacion' => $row['Persona']['Identificacion'],
                        'TipoIdentificacion' => $row['Persona']['TipoIdentificacion']['Codigo'],
                        'PrimerNombre' => $row['Persona']['PrimerNombre'],
                        'SegundoNombre' => $row['Persona']['SegundoNombre'],
                        'PrimerApellido' => $row['Persona']['PrimerApellido'],
                        'SegundoApellido' => $row['Persona']['SegundoApellido'],
                        'CorreoPrincipal' => $row['Persona']['CorreoPrincipal'],
                        'TelefonoPrincipal' => $row['Persona']['TelefonoPrincipal'],
                        'Sexo' => $row['Persona']['Sexo']['Codigo'],
                        'SedeNombre' => $row['Sede']['Nombre'],
                        'EstadoFuncionario' => $row['EstadoFuncionario']['Nombre'],
                        //'Total' => $total_registros,
                    ];
                }
                $result_total = array_merge($result_total,$result);
            } else {
                return [];
            }
            $pagina++;
        }
        return $result_total;
    }
    function get_total_docentes()
    {
        $respuesta = $this->get(
            $this->url . '/Docente',
            NULL,
            [
                'Authorization: ' . $this->token,
                'unit: ' . $this->institucion_id,
                'time: ' . $this->anno_lectivo_id,
            ]
        );

        if ($respuesta["http_code"] == "200") {

            $j = json_decode($respuesta['response'], true);
            $data =  $j['Size'];

        } else {
            $data = 0;
        }
        return $data;
    }

    //ESTUDIANTES
    function get_estudiantes()
    {
        if ($this->get_total_estudiantes() == 0){
            echo "No se encontraron registros de Estudiante";
            exit();
        }
        $total_registros = $this->get_total_estudiantes();
        $paginas = ceil($total_registros / 128);
        $result_total = [];

        for ($i=0; $i < $paginas; $i++) {
            $respuesta = $this->get(
                $this->url . '/Estudiante?take=1000&page=' . $pagina . '&find=&pipe=&with=Id,Sede[Nombre],Grupo[Nombre],Jornada[Nombre],Persona[Identificacion,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,CorreoPrincipal,TelefonoPrincipal],EstadoEstudiante[Nombre]',
                NULL,
                [
                    'Authorization: ' . $this->token,
                    'unit: ' . $this->institucion_id,
                    'time: ' . $this->anno_lectivo_id,

                ]
            );

            if ($respuesta["http_code"] == "200") {

                $j = json_decode($respuesta['response'], true);
                $data = $j['Data'];

                $result = [];
                foreach ($data as $key => $row) {
                    $result[] = [
                        'EstudianteId' => $row['Id'],
                        'Identificacion' => $row['Persona']['Identificacion'],
                        'PrimerNombre' => $row['Persona']['PrimerNombre'],
                        'SegundoNombre' => $row['Persona']['SegundoNombre'],
                        'PrimerApellido' => $row['Persona']['PrimerApellido'],
                        'SegundoApellido' => $row['Persona']['SegundoApellido'],
                        'CorreoPrincipal' => $row['Persona']['CorreoPrincipal'],
                        'TelefonoPrincipal' => $row['Persona']['TelefonoPrincipal'],
                        'Sede' => $row['Sede']['Nombre'],
                        'Grupo' => $row['Grupo']['Nombre'],
                        'Jornada' => $row['Jornada']['Nombre'],
                        'Estado ' => $row['EstadoEstudiante']['Nombre'],
                        // 'SegundoNombre' => $row['SegundoNombre'],
                        // 'PrimerApellido' => $row['PrimerApellido'],
                        // 'PrimerApellido' => $row['PrimerApellido'],
                        // 'CorreoPrincipal' => $row['CorreoPrincipal'],
                        // 'TelefonoPrincipal' => $row['TelefonoPrincipal'],
                        // 'Asignaciones' => $row['Asignaciones'],
                        //'Total' => $total_registros,
                    ];
                }
                $result_total = array_merge($result_total,$result);
            } else {
                return [];
            }
            $pagina++;
        }
        return $result_total;
    }
    function get_total_estudiantes()
    {
        $respuesta = $this->get(
            $this->url . '/Estudiante',
            NULL,
            [
                'Authorization: ' . $this->token,
                'unit: ' . $this->institucion_id,
                'time: ' . $this->anno_lectivo_id,
            ]
        );

        if ($respuesta["http_code"] == "200") {

            $j = json_decode($respuesta['response'], true);
            $data =  $j['Size'];

        } else {
            $data = 0;
        }
        return $data;
    }

    //CURSOS
    function get_cursos($docente_id = NULL)
    {
        if ($this->get_total_cursos() == 0){
            echo "No se encontraron registros de Cursos";
            exit();
        }
        $total_registros = $this->get_total_cursos();
        $paginas = ceil($total_registros / 128);
        $result_total = [];

        for ($i=0; $i < $paginas; $i++) {
            $respuesta = $this->get(
                $this->url . '/Curso?take=1000&page='.$pagina.'&find=&when=&with=Id,Docente[Persona[Identificacion,PrimerNombre,PrimerApellido,SegundoNombre,SegundoApellido]],GrupoEspecifico[Grupo[Id,Nombre],Jornada[Id,Nombre],GradoEspecifico[Id,Nombre,AnoLectivo[Sede[Id,Nombre,Institucion[Id,Nombre]]]]],AsignaturaEspecifica[Id,Nombre],CantidadEstudiantes&sort=GrupoEspecifico,Jornada,AsignaturaEspecificaEspecifico[Id,Sede[Nombre],Grupo[Id,Nombre],Jornada[Id,Nombre],GradoEspecifico[Id,Nombre,AnoLectivo[Sede[Id,Nombre,Institucion[Id,Nombre]]]]]',
                NULL,
                [
                    'Authorization: ' . $this->token,
                    'unit: ' . $this->institucion_id,
                    'time: ' . $this->anno_lectivo_id,

                ]
            );

            if ($respuesta["http_code"] == "200") {
                $j = json_decode($respuesta['response'], true);
                $data =  $j['Data'];
                $ii = 1;
                $result = [];
                foreach ($data as $key => $row) {
                    $result[] = [
                        'N' => $ii++,
                        'CursoId' => $row['Id'],
                        'AsignaturaId' => $row['AsignaturaEspecifica']['Id'],
                        'AsignaturaNombre' => $row['AsignaturaEspecifica']['Nombre'],
                        'InstitucionId' => $row['GrupoEspecifico']['GradoEspecifico']['AnoLectivo']['Sede']['Institucion']['Id'],
                        'InstitucionNombre' => $row['GrupoEspecifico']['GradoEspecifico']['AnoLectivo']['Sede']['Institucion']['Nombre'],
                        'SedeId' => $row['GrupoEspecifico']['GradoEspecifico']['AnoLectivo']['Sede']['Id'],
                        'SedeNombre' => $row['GrupoEspecifico']['GradoEspecifico']['AnoLectivo']['Sede']['Nombre'],
                        'JornadaId' => $row['GrupoEspecifico']['Jornada']['Id'],
                        'JornadaNombre' => $row['GrupoEspecifico']['Jornada']['Nombre'],
                        'GradoId' => $row['GrupoEspecifico']['GradoEspecifico']['Id'],
                        'GradoNombre' => $row['GrupoEspecifico']['GradoEspecifico']['Nombre'],
                        'Grupo' => $row['GrupoEspecifico']['Grupo']['Nombre'],
                        'DocenteId' => $row['Docente']['Id'],
                        'DocenteIdentificacion' =>
                            $row['Docente']['Persona']['Identificacion'],
                    ];
                }
                $result_total = array_merge($result_total,$result);
            } else {
                return [];
            }
            $pagina++;
        }
        return $result_total;
    }
    function get_total_cursos()
    {
        $respuesta = $this->get(
            $this->url . '/Curso',
            NULL,
            [
                'Authorization: ' . $this->token,
                'unit: ' . $this->institucion_id,
                'time: ' . $this->anno_lectivo_id,
            ]
        );

        if ($respuesta["http_code"] == "200") {

            $j = json_decode($respuesta['response'], true);
            $data =  $j['Size'];

        } else {
            $data = 0;
        }
        return $data;
    }

    //GRUPOS
    function get_grupos($docente_id = NULL)
    {
        if ($this->get_total_grupos() == 0){
            echo "No se encontraron registros de Grupos";
            exit();
        }
        $total_registros = $this->get_total_grupos();
        $paginas = ceil($total_registros / 128);
        $result_total = [];

        for ($i=0; $i < $paginas; $i++) {
            $respuesta = $this->get(
                $this->url . '/Curso?take=1000&page='.$pagina.'&find=&pipe=DocenteId:' . $docente_id
                .  '&with=Id,Nombre,Docente[Id],AsignaturaEspecifica[Id,Nombre],GrupoEspecifico[Id,Sede[Nombre],Grupo[Id,Nombre],Jornada[Id,Nombre],GradoEspecifico[Id,Nombre,AnoLectivo[Sede[Id,Nombre,Institucion[Id,Nombre]]]]]',
                NULL,
                [
                    'Authorization: ' . $this->token,
                    'unit: ' . $this->institucion_id,
                    'time: ' . $this->anno_lectivo_id,

                ]
            );

            if ($respuesta["http_code"] == "200") {
                $j = json_decode($respuesta['response'], true);
                $data =  $j['Data'];

                $result = [];
                foreach ($data as $key => $row) {
                    $result[] = [
                        'CursoId' => $row['Id'],
                        'AsignaturaId' => $row['AsignaturaEspecifica']['Id'],
                        'AsignaturaNombre' => $row['AsignaturaEspecifica']['Nombre'],
                        'InstitucionId' => $row['GrupoEspecifico']['GradoEspecifico']['AnoLectivo']['Sede']['Institucion']['Id'],
                        'InstitucionNombre' => $row['GrupoEspecifico']['GradoEspecifico']['AnoLectivo']['Sede']['Institucion']['Nombre'],
                        'SedeId' => $row['GrupoEspecifico']['GradoEspecifico']['AnoLectivo']['Sede']['Id'],
                        'SedeNombre' => $row['GrupoEspecifico']['GradoEspecifico']['AnoLectivo']['Sede']['Nombre'],
                        'JornadaId' => $row['GrupoEspecifico']['Jornada']['Id'],
                        'JornadaNombre' => $row['GrupoEspecifico']['Jornada']['Nombre'],
                        'GradoId' => $row['GrupoEspecifico']['GradoEspecifico']['Id'],
                        'GradoNombre' => $row['GrupoEspecifico']['GradoEspecifico']['Nombre'],
                        'Grupo' => $row['GrupoEspecifico']['Grupo']['Nombre'],
                    ];
                }
                $result_total = array_merge($result_total,$result);
            } else {
                return [];
            }
            $pagina++;
        }
        return $result_total;
    }
    function get_total_grupos()
    {
        $respuesta = $this->get(
            $this->url . '/Curso',
            NULL,
            [
                'Authorization: ' . $this->token,
                'unit: ' . $this->institucion_id,
                'time: ' . $this->anno_lectivo_id,
            ]
        );

        if ($respuesta["http_code"] == "200") {

            $j = json_decode($respuesta['response'], true);
            $data =  $j['Size'];

        } else {
            $data = 0;
        }
        return $data;
    }

    //TUTORES

    function get_tutores()
    {
        if ($this->get_total_tutores() == 0){
            echo "No se encontraron registros de Tutores";
            exit();
        }
        $total_registros = $this->get_total_tutores();
        $paginas = ceil($total_registros / 128);
        $result_total = [];

        for ($i=0; $i < $paginas; $i++) {
            $respuesta = $this->get(
                $this->url . '/Docente?take=1000&page='.$pagina.'&find=&pipe=&with=Id,FechaIngreso,Sede[Nombre],Persona[Id,Identificacion,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,CorreoPrincipal,TelefonoPrincipal,Sexo[Codigo],TipoIdentificacion[Codigo]],EstadoFuncionario[Nombre]',
                NULL,
                [
                    'Authorization: ' . $this->token,
                    'unit: ' . $this->institucion_id,
                    'time: ' . $this->anno_lectivo_id

                ]
            );

            if ($respuesta["http_code"] == "200") {

                $j = json_decode($respuesta['response'], true);

                $data = $j['Data'];

                $result = [];
                foreach ($data as $key => $row) {

                    $result[] = [
                        'DocenteId' => $row['Id'],
                        'Identificacion' => $row['Persona']['Identificacion'],
                        'TipoIdentificacion' => $row['Persona']['TipoIdentificacion']['Codigo'],
                        'PrimerNombre' => $row['Persona']['PrimerNombre'],
                        'SegundoNombre' => $row['Persona']['SegundoNombre'],
                        'PrimerApellido' => $row['Persona']['PrimerApellido'],
                        'SegundoApellido' => $row['Persona']['SegundoApellido'],
                        'CorreoPrincipal' => $row['Persona']['CorreoPrincipal'],
                        'TelefonoPrincipal' => $row['Persona']['TelefonoPrincipal'],
                        'Sexo' => $row['Persona']['Sexo']['Codigo'],
                        'SedeNombre' => $row['Sede']['Nombre'],
                        'EstadoFuncionario' => $row['EstadoFuncionario']['Nombre'],
                        //'Total' => $total_registros,
                    ];
                }
                $result_total = array_merge($result_total,$result);
            } else {
                return [];
            }
            $pagina++;
        }
        return $result_total;
    }
    function get_total_tutores()
    {
        $respuesta = $this->get(
            $this->url . '/Docente',
            NULL,
            [
                'Authorization: ' . $this->token,
                'unit: ' . $this->institucion_id,
                'time: ' . $this->anno_lectivo_id,
            ]
        );

        if ($respuesta["http_code"] == "200") {

            $j = json_decode($respuesta['response'], true);
            $data =  $j['Size'];

        } else {
            $data = 0;
        }
        return $data;
    }

    //ASIGNARURA

    function get_total_asignatura()
    {
        $respuesta = $this->get(
            $this->url . '/Docente',
            NULL,
            [
                'Authorization: ' . $this->token,
                'unit: ' . $this->institucion_id,
                'time: ' . $this->anno_lectivo_id,
            ]
        );

        if ($respuesta["http_code"] == "200") {

            $j = json_decode($respuesta['response'], true);
            $data =  $j['Size'];

        } else {
            $data = 0;
        }
        return $data;
    }
    function get_asignatura($docente_id = NULL)
    {
        if ($this->get_total_cursos() == 0){
            echo "No se encontraron registros de Cursos";
            exit();
        }
        $total_registros = $this->get_total_cursos();
        $paginas = ceil($total_registros / 128);
        $result_total = [];

        for ($i=0; $i < $paginas; $i++) {
            $respuesta = $this->get(
                $this->url . '/Curso?take=1000&page='.$pagina.'&find=&when=&with=Id,Nombre,AsignaturaEspecifica[Id,Nombre],CantidadEstudiantes&sort=GrupoEspecifico,Jornada,AsignaturaEspecifica',
                NULL,
                [
                    'Authorization: ' . $this->token,
                    'unit: ' . $this->institucion_id,
                    'time: ' . $this->anno_lectivo_id,

                ]
            );

            if ($respuesta["http_code"] == "200") {
                $j = json_decode($respuesta['response'], true);
                $data =  $j['Data'];
                $ii = 1;
                $result = [];
                foreach ($data as $key => $row) {
                    $result[] = [
                        'N' => $ii++,
                        'CursoId' => $row['Id'],
                        'AsignaturaId' => $row['AsignaturaEspecifica']['Id'],
                        'AsignaturaNombre' => $row['AsignaturaEspecifica']['Nombre'],
                    ];
                }
                $result_total = array_merge($result_total,$result);
            } else {
                return [];
            }
            $pagina++;
        }
        return $result_total;
    }
    //MATRICULAS
    function get_matriculas()
    {

        if ($this->get_total_matriculas() == 0){
            echo "No se encontraron registros de Cursos";
            exit();
        }
        $total_registros = $this->get_total_matriculas();
        $paginas = ceil($total_registros / 128);
        $result_total = [];

        $pagina = 0;
        $n = 0;
        for ($i=0; $i < $paginas; $i++) {

            $respuesta = $this->get(
                $this->url . '/Matricula?take=1000&page='.$pagina.'&find=&pipe=&with=Id,Cursos[Id,CursoId,MatriculaId],Fecha,Estudiante[Id,Persona[Identificacion,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,Identificacion],Sede[Id,Nombre,Institucion[Id,Nombre]]],EstadoMatricula[Nombre],GrupoEspecifico[Grupo[Id,Nombre],Jornada[Id,Nombre],GradoEspecifico[Id,Nombre]]&sort=Estudiante',
                NULL,
                [
                    'Authorization: ' . $this->token,
                    'unit: ' . $this->institucion_id,
                    'time: ' . $this->anno_lectivo_id
                ]
            );

            if ($respuesta["http_code"] == "200") {
                $j = json_decode($respuesta['response'], true);
                $data =  $j['Data'];
                $result = [];
                $asignatura = $this->get_asignatura();

                $result_total = []; // Inicializamos el array total de resultados

// Definir el tamaño del lote
                $tamano_lote =20; // Puedes ajustar esto según tus
                // necesidades

// Iterar a través de los datos en lotes
                for ($i = 0; $i < count($data); $i += $tamano_lote) {
                    $lote_data = array_slice($data, $i, $tamano_lote);

                    foreach ($lote_data as $row) {
                        foreach ($row['Cursos'] as $row2) {
                            $matriculaCurso = $row2['CursoId'];

                            foreach ($asignatura as $row3) {
                                if ($matriculaCurso == $row3['CursoId']) {
                                    $AsignaturaId = $row3['AsignaturaId'];
                                    $AsignaturaNombre = $row3['AsignaturaNombre'];


                                    $AsignaturaId = $AsignaturaId;
                                    $AsignaturaNombre = $AsignaturaNombre;
                                    $InstitucionId = $row['Estudiante']['Sede']['Institucion']['Id'];
                                    $InstitucionNombre = $row['Estudiante']['Sede']['Institucion']['Nombre'];
                                    $SedeId = $row['Estudiante']['Sede']['Id'];
                                    $SedeNombre = $row['Estudiante']['Sede']['Nombre'];
                                    $JornadaId = $row['GrupoEspecifico']['Jornada']['Id'];
                                    $JornadaNombre = $row['GrupoEspecifico']['Jornada']['Nombre'];
                                    $GradoId =   $row['GrupoEspecifico']['GradoEspecifico']['Id'];
                                    $GradoNombre = $row['GrupoEspecifico']['GradoEspecifico']['Nombre'];
                                    $Grupo = $row['GrupoEspecifico']['Grupo']['Nombre'];

                                    $code = "I".$InstitucionId."S".$SedeId."A".$AsignaturaId."G".$GradoId."J".$JornadaId;
                                    $grupo_nombre = "Grupo " . $Grupo;

                                    $result = [
                                        'Id' => $n++,
                                        'MatriculaId' => $row['Id'],
                                        "CursoId" => $matriculaCurso,
                                        'EstudianteIdentificacion' => $row['Estudiante']['Persona']['Identificacion'],
                                        'Nombre1' => $row['Estudiante']['Persona']['PrimerNombre'],
                                        'Nombre2' => $row['Estudiante']['Persona']['SegundoNombre'],
                                        'Apellido1' => $row['Estudiante']['Persona']['PrimerApellido'],
                                        'Apellido2' => $row['Estudiante']['Persona']['SegundoApellido'],
                                        'Code' => $code,
                                        'GrupoNombre' => $grupo_nombre,
                                    ];

                                    $result_total[] = $result; // Concatenamos el resultado al array total
                                }
                            }
                        }
                    }

                    // Liberar memoria del lote de datos actual
                    unset($lote_data);
                }



//                foreach ($data as $key => $row) {
//                    foreach ($row['Cursos'] as $key2 => $row2){
//                        $matriculaCurso = $row2['CursoId'];
//
//                        foreach ($asignatura as $key3 => $row3){
//                            $t = 0;
//                            if ($matriculaCurso == $row3['CursoId'] ){
//
//                                $AsignaturaId = $row3['AsignaturaId'];
//                                $AsignaturaNombre = $row3['AsignaturaNombre'];
//
//                                echo "----------------" . $t++ . "-------------------------";
//                                echo "<br>";
//                                echo $matriculaCurso . " - " . $row3['CursoId'] . " - " . $AsignaturaId . " - " . $AsignaturaNombre . " - " . $row['Id'];
//
//                                echo "<br>";
//                                echo "<br>";
//
//                               $result[] = [
//                                    'Matricula_id' => $row['Id'],
//                                    /*'Identifica' => $row['Estudiante']['Persona']['Identificacion'],
//
//                                    'Nombre1' => $row['Estudiante']['Persona']['PrimerNombre'],
//                                    'Nombre2' => $row['Estudiante']['Persona']['SegundoNombre'],
//                                    'Apellido1' => $row['Estudiante']['Persona']['PrimerApellido'],
//                                    'Apellido2' => $row['Estudiante']['Persona']['SegundoApellido'],
//                                    'AsignaturaId' => $AsignaturaId,*/
////                                    'AsignaturaNombre' => $AsignaturaNombre,
////                                    'InstitucionId' => $row['Estudiante']['Sede']['Institucion']['Id'],
////                                    'InstitucionNombre' => $row['Estudiante']['Sede']['Institucion']['Nombre'],
////                                    'SedeId' => $row['Estudiante']['Sede']['Id'],
////                                    'SedeNombre' => $row['Estudiante']['Sede']['Nombre'],
////                                    'JornadaId' => $row['GrupoEspecifico']['Jornada']['Id'],
////                                    'JornadaNombre' => $row['GrupoEspecifico']['Jornada']['Nombre'],
////                                    'GradoId' => $row['GrupoEspecifico']['GradoEspecifico']['Id'],
////                                    'GradoNombre' => $row['GrupoEspecifico']['GradoEspecifico']['Nombre'],
////                                    'Grupo' => $row['GrupoEspecifico']['Grupo']['Nombre'],
//                                ];
//
//                               var_dump($result);
//                            }
////                            $result_total = array_merge($result_total,$result);
//                        }
//                    }
////                    /*foreach ($asignatura as $key3 => $row3) {
////
////                        $AsignaturaId = $row2['AsignaturaId'];
////
////
////                        $AsignaturaNombre = $row2['AsignaturaNombre'];
////                        $AsignaturaNombre = $row2['AsignaturaNombre'];
////                    }*/
////                    /*$cursos = [];
////
////                    if(isset($row['Cursos']) && is_array($row['Cursos']) ) {
////
////                        foreach($row['Cursos'] as $curso ){
////                            $cursos[] = $curso['CursoId'];
////                        }
////                    }*/
////                    /*$result[] = [
////                        'Matricula_id' => $row['Id'],
////                        'Identifica' => $row['Estudiante']['Persona']['Identificacion'],
////                        'Cursos' => $row['Cursos'],
////                        'Nombre1' => $row['Estudiante']['Persona']['PrimerNombre'],
////                        'Nombre2' => $row['Estudiante']['Persona']['SegundoNombre'],
////                        'Apellido1' => $row['Estudiante']['Persona']['PrimerApellido'],
////                        'Apellido2' => $row['Estudiante']['Persona']['SegundoApellido'],
////                        'AsignaturaId' => $AsignaturaId,
////                        'AsignaturaNombre' => $AsignaturaNombre,
////                        'InstitucionId' => $row['Estudiante']['Sede']['Institucion']['Id'],
////                        'InstitucionNombre' => $row['Estudiante']['Sede']['Institucion']['Nombre'],
////                        'SedeId' => $row['Estudiante']['Sede']['Id'],
////                        'SedeNombre' => $row['Estudiante']['Sede']['Nombre'],
////                        'JornadaId' => $row['GrupoEspecifico']['Jornada']['Id'],
////                        'JornadaNombre' => $row['GrupoEspecifico']['Jornada']['Nombre'],
////                        'GradoId' => $row['GrupoEspecifico']['GradoEspecifico']['Id'],
////                        'GradoNombre' => $row['GrupoEspecifico']['GradoEspecifico']['Nombre'],
////                        'Grupo' => $row['GrupoEspecifico']['Grupo']['Nombre'],
////                    ];*/
//                }
            } else {
                return [];
            }
            $pagina++;
        }
        return $result_total;
    }
    function get_total_matriculas()
    {
        $respuesta = $this->get(
            $this->url . '/Matricula',
            NULL,
            [
                'Authorization: ' . $this->token,
                'unit: ' . $this->institucion_id,
                'time: ' . $this->anno_lectivo_id,
            ]
        );

        if ($respuesta["http_code"] == "200") {

            $j = json_decode($respuesta['response'], true);
            $data =  $j['Size'];

        } else {
            $data = 0;
        }
        return $data;
    }
    function get_usuario_activo()
    {
        return $this->usuario;
    }

    function get_instititucion_activa()
    {
        return $this->institucion;
    }

    function get_anno_lectivo()
    {
        return $this->anno_lectivo;
    }

    function get_token()
    {
        return $this->token;
    }

    private function is_json($string)
    {
        return ((is_string($string) &&
            (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }

    private function get($url, $params = NULL, $headers = NULL)
    {
        $defaults = array(
            CURLOPT_URL => $url,
            //CURLOPT_POST => true,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            //CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => $headers
        );

        $ch = curl_init();
        curl_setopt_array($ch,  $defaults);

        $response = curl_exec($ch);

        $info = curl_getinfo($ch);
        $info['response'] = $response;
        curl_close($ch);

        return $info;
    }


    private function post($url, $params = NULL, $headers = [])
    {

        $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => $headers
        );

        $ch = curl_init();
        curl_setopt_array($ch,  $defaults);

        $response = curl_exec($ch);

        $info = curl_getinfo($ch);
        $info['response'] = $response;
        curl_close($ch);

        return $info;
    }
}
