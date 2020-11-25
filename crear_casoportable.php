<?php
session_start();

if(isset($_SESSION['id_u'])) {
    
    $link_portable = mysqli_connect("localhost", "root", ".google.", "safe_portable");
       
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    }
    
    $carpeta = '../../../Scripts';
    if (!file_exists($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
    
    $myid_caso=base64_decode($_GET['id_caso']);
    $select_caso=mysqli_query($link_portable, "Select * FROM caso WHERE id_caso=$myid_caso");
    $ret_caso=mysqli_fetch_array($select_caso);
    $nombre=strtr($ret_caso['nombre'], " ", "_");
    $nombre_archivo=$ret_caso['numero']."_".substr($ret_caso['año'], 2);
    $direccion='../../../Scripts/'.$nombre_archivo.'.sql';
    $archivo = fopen($direccion,"w");
    
    
    // Primero comprobamos si existe algun caso con ese mismo número y año, para no añadirlo de nuevo
    // En caso de que no exista comprobamos si el caso que queremos añadir tiene diligencias asociadas. Si no tiene se inserta el caso directamente.
    if(isset($ret_caso['id_diligencias'])) {
            $select_diligencias=mysqli_query($link_portable, "Select * FROM diligencias WHERE id_diligencias=$ret_caso[id_diligencias]");
            $ret_diligencias=mysqli_fetch_array($select_diligencias);
            if($ret_caso['id_diligencias']!=null or $ret_caso['diligencias']!='') {
                $sql_insert_diligencias="INSERT INTO diligencias (numero, año, id_juzgado, fecha) values ($ret_diligencias[numero], $ret_diligencias[año], $ret_diligencias[id_juzgado], NOW())";
                //mysqli_query($link,$sql_insert_diligencias);
                fputs($archivo,$sql_insert_diligencias.";\n");
                $sql = "INSERT INTO caso (numero, año, id_diligencias, id_tipo_caso, id_grupo_investigacion, nombre, fecha_alta_caso, descripcion, id_estado_caso) values ($ret_caso[numero], $ret_caso[año], Xid_diligenciasX, $ret_caso[id_tipo_caso], $ret_caso[id_grupo_investigacion], '$nombre', NOW(), '$ret_caso[descripcion]', 1)";
                //mysqli_query($link, $sql);
                fputs($archivo,$sql.";\n");
                
            }
    }
    else {
        $sql = "INSERT INTO caso (numero, año, id_tipo_caso, id_grupo_investigacion, nombre, fecha_alta_caso, descripcion, id_estado_caso) values ($ret_caso[numero], $ret_caso[año], $ret_caso[id_tipo_caso], $ret_caso[id_grupo_investigacion], '$nombre', NOW(), '$ret_caso[descripcion]', 1)";
        //mysqli_query($link, $sql);
        fputs($archivo,$sql.";\n");
        
    }
    
    
    
    
    $myid_caso_safe='Xid_casoX';
    $myid_sujeto_activo_safe='Xid_sujeto_activoX';
    $myid_intervencion='Xid_intervencionX';
    $myid_evidencia='Xid_evidenciaX';
    $myid_evidencia_dependiente='Xid_evidencia_dependienteX';
    $myid_hash='Xid_hashX';
    //si hay, primero vamos a agregar los sujetos_activos del caso
    $sql="Select id_sujeto_activo,nombre,apellido1,apellido2 from sujeto_activo where id_caso=$myid_caso";
    $result_sujeto=mysqli_query($link_portable, $sql);
    $count_sujeto=mysqli_num_rows($result_sujeto);
    if($count_sujeto!=0) {
        while ($fila_sujeto = mysqli_fetch_row($result_sujeto)) {
            $nombre=strtr($fila_sujeto[1], " ", "_");
            $apellido1=strtr($fila_sujeto[2], " ", "_");
            $apellido2=strtr($fila_sujeto[3], " ", "_");
            $sql="INSERT INTO sujeto_activo (id_caso, nombre, apellido1, apellido2) values ($myid_caso_safe, '$nombre', '$apellido1', '$apellido2')";
            //mysqli_query($link, $sql);
            fputs($archivo,$sql.";\n");
            //se selecionan las intervenciones a añadir y se procede a ello, diferenciando si el id_sujeto_activo es 1(SIN DETENIDO) o no
            $sql="select * from intervencion where id_caso=$myid_caso and (id_sujeto_activo=$fila_sujeto[0])";
            $result_intervencion=mysqli_query($link_portable, $sql);
            $count_intervencion=mysqli_num_rows($result_intervencion);
            if($count_intervencion!=0) {
                while ($fila_intervencion = mysqli_fetch_row($result_intervencion)) {

                    if($fila_intervencion[3]!=1) {
                        $sql="INSERT INTO intervencion (id_caso, id_tipo_intervencion, id_sujeto_activo, numero_intervencion, direccion, descripcion, fecha_alta_intervencion) values ($myid_caso_safe, $fila_intervencion[2], $myid_sujeto_activo_safe, $fila_intervencion[4], '$fila_intervencion[5]', '$fila_intervencion[6]', '$fila_intervencion[7]')";
                        fputs($archivo,$sql.";\n");
                    }
                    else {
                        $sql="INSERT INTO intervencion (id_caso, id_tipo_intervencion, id_sujeto_activo, numero_intervencion, direccion, descripcion, fecha_alta_intervencion) values ($myid_caso_safe, $fila_intervencion[2], 1, $fila_intervencion[4], '$fila_intervencion[5]', '$fila_intervencion[6]', '$fila_intervencion[7]')";
                        fputs($archivo,$sql.";\n");
                    }
                    //mysqli_query($link, $sql);
                    // se selecionan las evidencias que dependen del caso y de la intervención
                    $sql="Select * from evidencia where id_caso=$myid_caso and id_intervencion=$fila_intervencion[0] and relacionado_con is null";
                    $result_evidencias=mysqli_query($link_portable, $sql);
                    $count_evidencias=mysqli_num_rows($result_evidencias);
                    if($count_evidencias!=0) {
                        while ($fila_evidencias = mysqli_fetch_row($result_evidencias)) {
                            if(empty($fila_evidencias[3])) {
                                $fila_evidencias[3]='null';
                            }
                            if(empty($fila_evidencias[9])) {
                                $fila_evidencias[9]='null';
                            }
                            if(empty($fila_evidencias[14])) {
                                $fila_evidencias[14]='null';
                            }
                            if(empty($fila_evidencias[19])) {
                                $fila_evidencias[19]='null';
                            }
                            $sql = "INSERT INTO evidencia (nombre, numero_evidencia, id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, tiene_subevidencias, relacionado_con, alias, patron, pin, id_tipo_capacidad ) values ('$fila_evidencias[6]', '$fila_evidencias[15]', $fila_evidencias[1], $fila_evidencias[2], $fila_evidencias[3], $myid_caso_safe, $myid_intervencion, '$fila_evidencias[7]', '$fila_evidencias[8]', $fila_evidencias[9], '$fila_evidencias[10]', '$fila_evidencias[11]', '$fila_evidencias[12]', '$fila_evidencias[13]', $fila_evidencias[14], '$fila_evidencias[16]', '$fila_evidencias[17]', '$fila_evidencias[18]', $fila_evidencias[19])";
                            //mysqli_query($link, $sql);
                            fputs($archivo,$sql.";\n");
                            // se comprueba si la evidencia añadida tiene hashes asociados y si es así, se agrega el hash a la BBDD
                            $sql="Select * from hash where id_evidencia=$fila_evidencias[0]";
                            $result_hash=mysqli_query($link_portable, $sql);
                            $count_hash=mysqli_num_rows($result_hash);
                            if($count_hash!=0){
                                while ($fila_hash = mysqli_fetch_row($result_hash)) {
                                    $sql = "INSERT INTO hash (id_evidencia, id_tipo_hash, hash) values ($myid_evidencia, $fila_hash[2], '$fila_hash[3]')";
                                    //mysqli_query($link, $sql);
                                    fputs($archivo,$sql.";\n");
                                    //se añade el registro asociado a ese hash
                                    $sql="Select * from evidencia_registro where id_hash=$fila_hash[0]";
                                    $result_registro=mysqli_query($link_portable, $sql);
                                    $count_registro=mysqli_num_rows($result_registro);
                                    if($count_registro!=0) {
                                        while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                            if(empty($fila_registro[4])) {
                                                $fila_registro[4]='null';
                                            }
                                            if(empty($fila_registro[5])) {
                                                $fila_registro[5]='null';
                                            }
                                            if(empty($fila_registro[6])) {
                                                $fila_registro[6]='null';
                                            }
                                            $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash, observaciones, fecha_alta_estado) values ($myid_evidencia, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $myid_hash, '$fila_registro[8]', '$fila_registro[9]')";
                                            //mysqli_query($link, $sql);
                                            fputs($archivo,$sql.";\n");
                                        }
                                    }
                                }
                                
                            }
                            // se añaden el resto de registros que no tienen hashes asociados
                            $sql="Select * from evidencia_registro where id_evidencia=$fila_evidencias[0] and id_hash is null";
                            $result_registro=mysqli_query($link_portable, $sql);
                            $count_registro=mysqli_num_rows($result_registro);
                            if($count_registro!=0) {
                                while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                    if(empty($fila_registro[4])) {
                                        $fila_registro[4]='null';
                                    }
                                    if(empty($fila_registro[5])) {
                                        $fila_registro[5]='null';
                                    }
                                    if(empty($fila_registro[6])) {
                                        $fila_registro[6]='null';
                                    }
                                    if(empty($fila_registro[7])) {
                                        $fila_registro[7]='null';
                                    }
                                    $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash, observaciones, fecha_alta_estado) values ($myid_evidencia, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $fila_registro[7], '$fila_registro[8]', '$fila_registro[9]')";
                                    //mysqli_query($link, $sql);
                                    fputs($archivo,$sql.";\n");
                                }
                            }
                            // se comprueba si existen evidencias que dependen de la que acabamos de añadir y en su caso se añaden
                            $sql="Select * from evidencia where relacionado_con=$fila_evidencias[0]";
                            $result_evidencias_dependientes=mysqli_query($link_portable, $sql);
                            $count_evidencias_dependientes=mysqli_num_rows($result_evidencias_dependientes);
                            if($count_evidencias_dependientes!=0) {
                                while ($fila_evidencias_dependientes = mysqli_fetch_row($result_evidencias_dependientes)) {
                                    if(empty($fila_evidencias_dependientes[3])) {
                                        $fila_evidencias_dependientes[3]='null';
                                    }
                                    if(empty($fila_evidencias_dependientes[9])) {
                                        $fila_evidencias_dependientes[9]='null';
                                    }
                                    if(empty($fila_evidencias_dependientes[19])) {
                                        $fila_evidencias_dependientes[19]='null';
                                    }
                                    $sql = "INSERT INTO evidencia (nombre, numero_evidencia, relacionado_con, id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, tiene_subevidencias, alias, patron, pin, id_tipo_capacidad ) values ('$fila_evidencias_dependientes[6]', '$fila_evidencias_dependientes[15]', $myid_evidencia, $fila_evidencias_dependientes[1], $fila_evidencias_dependientes[2], $fila_evidencias_dependientes[3], $myid_caso_safe, $myid_intervencion, '$fila_evidencias_dependientes[7]', '$fila_evidencias_dependientes[8]', $fila_evidencias_dependientes[9], '$fila_evidencias_dependientes[10]', '$fila_evidencias_dependientes[11]', '$fila_evidencias_dependientes[12]', '$fila_evidencias_dependientes[13]', '$fila_evidencias_dependientes[16]', '$fila_evidencias_dependientes[17]', '$fila_evidencias_dependientes[18]', $fila_evidencias_dependientes[19])";
                                    //mysqli_query($link, $sql);
                                    fputs($archivo,$sql.";\n");
                                    // se comprueba si la evidencia añadida tiene hashes asociados y en su caso se añaden
                                    $sql="Select * from hash where id_evidencia=$fila_evidencias_dependientes[0]";
                                    $result_hash=mysqli_query($link_portable, $sql);
                                    $count_hash=mysqli_num_rows($result_hash);
                                    if($count_hash!=0){
                                        while ($fila_hash = mysqli_fetch_row($result_hash)) {
                                            $sql = "INSERT INTO hash (id_evidencia, id_tipo_hash, hash) values ($myid_evidencia_dependiente, $fila_hash[2], '$fila_hash[3]')";
                                            //mysqli_query($link, $sql);
                                            fputs($archivo,$sql.";\n");
                                            //se añade el registro asociado a ese hash
                                            $sql="Select * from evidencia_registro where id_hash=$fila_hash[0]";
                                            $result_registro=mysqli_query($link_portable, $sql);
                                            $count_registro=mysqli_num_rows($result_registro);
                                            if($count_registro!=0) {
                                                while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                    if(empty($fila_registro[4])) {
                                                        $fila_registro[4]='null';
                                                    }
                                                    if(empty($fila_registro[5])) {
                                                        $fila_registro[5]='null';
                                                    }
                                                    if(empty($fila_registro[6])) {
                                                        $fila_registro[6]='null';
                                                    }
                                                    $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash,observaciones, fecha_alta_estado) values ($myid_evidencia_dependiente, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $myid_hash, '$fila_registro[8]', '$fila_registro[9]')";
                                                    //mysqli_query($link, $sql);
                                                    fputs($archivo,$sql.";\n");
                                                }
                                            }
                                        }
                                    }
                                    // se añaden el resto de registros que no tienen hashes asociados
                                    $sql="Select * from evidencia_registro where id_evidencia=$fila_evidencias_dependientes[0] and id_hash is null";
                                    $result_registro=mysqli_query($link_portable, $sql);
                                    $count_registro=mysqli_num_rows($result_registro);
                                    if($count_registro!=0) {
                                        while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                            if(empty($fila_registro[4])) {
                                                $fila_registro[4]='null';
                                            }
                                            if(empty($fila_registro[5])) {
                                                $fila_registro[5]='null';
                                            }
                                            if(empty($fila_registro[6])) {
                                                $fila_registro[6]='null';
                                            }
                                            if(empty($fila_registro[7])) {
                                                $fila_registro[7]='null';
                                            }
                                            $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash,observaciones, fecha_alta_estado) values ($myid_evidencia_dependiente, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $fila_registro[7], '$fila_registro[8]', '$fila_registro[9]')";
                                            //mysqli_query($link, $sql);
                                            fputs($archivo,$sql.";\n");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            //en caso de que existan el mismo numero de sujetos que de intervenciones y al menos una de las intervenciones es sin detenido, las añadimos aqui
            else {
                $sql="select * from intervencion where id_caso=$myid_caso and (id_sujeto_activo=1)";
                $result_intervencion=mysqli_query($link_portable, $sql);
                $count_intervencion=mysqli_num_rows($result_intervencion);
                if($count_intervencion!=0) {
                    while ($fila_intervencion = mysqli_fetch_row($result_intervencion)) {
                        
                        $sql="INSERT INTO intervencion (id_caso, id_tipo_intervencion, id_sujeto_activo, numero_intervencion, direccion, descripcion, fecha_alta_intervencion) values ($myid_caso_safe, $fila_intervencion[2], 1, $fila_intervencion[4], '$fila_intervencion[5]', '$fila_intervencion[6]', '$fila_intervencion[7]')";
                        fputs($archivo,$sql.";\n");
                        //mysqli_query($link, $sql);
                        // se selecionan las evidencias que dependen del caso y de la intervención
                        $sql="Select * from evidencia where id_caso=$myid_caso and id_intervencion=$fila_intervencion[0] and relacionado_con is null";
                        $result_evidencias=mysqli_query($link_portable, $sql);
                        $count_evidencias=mysqli_num_rows($result_evidencias);
                        if($count_evidencias!=0) {
                            while ($fila_evidencias = mysqli_fetch_row($result_evidencias)) {
                                if(empty($fila_evidencias[3])) {
                                    $fila_evidencias[3]='null';
                                }
                                if(empty($fila_evidencias[9])) {
                                    $fila_evidencias[9]='null';
                                }
                                if(empty($fila_evidencias[14])) {
                                    $fila_evidencias[14]='null';
                                }
                                if(empty($fila_evidencias[19])) {
                                    $fila_evidencias[19]='null';
                                }
                                $sql = "INSERT INTO evidencia (nombre, numero_evidencia, id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, tiene_subevidencias, relacionado_con, alias, patron, pin, id_tipo_capacidad ) values ('$fila_evidencias[6]', '$fila_evidencias[15]', $fila_evidencias[1], $fila_evidencias[2], $fila_evidencias[3], $myid_caso_safe, $myid_intervencion, '$fila_evidencias[7]', '$fila_evidencias[8]', $fila_evidencias[9], '$fila_evidencias[10]', '$fila_evidencias[11]', '$fila_evidencias[12]', '$fila_evidencias[13]', $fila_evidencias[14], '$fila_evidencias[16]', '$fila_evidencias[17]', '$fila_evidencias[18]', $fila_evidencias[19])";
                                //mysqli_query($link, $sql);
                                fputs($archivo,$sql.";\n");
                                // se comprueba si la evidencia añadida tiene hashes asociados y si es así, se agrega el hash a la BBDD
                                $sql="Select * from hash where id_evidencia=$fila_evidencias[0]";
                                $result_hash=mysqli_query($link_portable, $sql);
                                $count_hash=mysqli_num_rows($result_hash);
                                if($count_hash!=0){
                                    while ($fila_hash = mysqli_fetch_row($result_hash)) {
                                        $sql = "INSERT INTO hash (id_evidencia, id_tipo_hash, hash) values ($myid_evidencia, $fila_hash[2], '$fila_hash[3]')";
                                        //mysqli_query($link, $sql);
                                        fputs($archivo,$sql.";\n");
                                        //se añade el registro asociado a ese hash
                                        $sql="Select * from evidencia_registro where id_hash=$fila_hash[0]";
                                        $result_registro=mysqli_query($link_portable, $sql);
                                        $count_registro=mysqli_num_rows($result_registro);
                                        if($count_registro!=0) {
                                            while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                if(empty($fila_registro[4])) {
                                                    $fila_registro[4]='null';
                                                }
                                                if(empty($fila_registro[5])) {
                                                    $fila_registro[5]='null';
                                                }
                                                if(empty($fila_registro[6])) {
                                                    $fila_registro[6]='null';
                                                }
                                                $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash, observaciones, fecha_alta_estado) values ($myid_evidencia, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $myid_hash, '$fila_registro[8]', '$fila_registro[9]')";
                                                //mysqli_query($link, $sql);
                                                fputs($archivo,$sql.";\n");
                                            }
                                        }
                                    }
                                    
                                }
                                // se añaden el resto de registros que no tienen hashes asociados
                                $sql="Select * from evidencia_registro where id_evidencia=$fila_evidencias[0] and id_hash is null";
                                $result_registro=mysqli_query($link_portable, $sql);
                                $count_registro=mysqli_num_rows($result_registro);
                                if($count_registro!=0) {
                                    while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                        if(empty($fila_registro[4])) {
                                            $fila_registro[4]='null';
                                        }
                                        if(empty($fila_registro[5])) {
                                            $fila_registro[5]='null';
                                        }
                                        if(empty($fila_registro[6])) {
                                            $fila_registro[6]='null';
                                        }
                                        if(empty($fila_registro[7])) {
                                            $fila_registro[7]='null';
                                        }
                                        $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash, observaciones, fecha_alta_estado) values ($myid_evidencia, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $fila_registro[7], '$fila_registro[8]', '$fila_registro[9]')";
                                        //mysqli_query($link, $sql);
                                        fputs($archivo,$sql.";\n");
                                    }
                                }
                                // se comprueba si existen evidencias que dependen de la que acabamos de añadir y en su caso se añaden
                                $sql="Select * from evidencia where relacionado_con=$fila_evidencias[0]";
                                $result_evidencias_dependientes=mysqli_query($link_portable, $sql);
                                $count_evidencias_dependientes=mysqli_num_rows($result_evidencias_dependientes);
                                if($count_evidencias_dependientes!=0) {
                                    while ($fila_evidencias_dependientes = mysqli_fetch_row($result_evidencias_dependientes)) {
                                        if(empty($fila_evidencias_dependientes[3])) {
                                            $fila_evidencias_dependientes[3]='null';
                                        }
                                        if(empty($fila_evidencias_dependientes[9])) {
                                            $fila_evidencias_dependientes[9]='null';
                                        }
                                        if(empty($fila_evidencias_dependientes[19])) {
                                            $fila_evidencias_dependientes[19]='null';
                                        }
                                        $sql = "INSERT INTO evidencia (nombre, numero_evidencia, relacionado_con, id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, tiene_subevidencias, alias, patron, pin, id_tipo_capacidad ) values ('$fila_evidencias_dependientes[6]', '$fila_evidencias_dependientes[15]', $myid_evidencia, $fila_evidencias_dependientes[1], $fila_evidencias_dependientes[2], $fila_evidencias_dependientes[3], $myid_caso_safe, $myid_intervencion, '$fila_evidencias_dependientes[7]', '$fila_evidencias_dependientes[8]', $fila_evidencias_dependientes[9], '$fila_evidencias_dependientes[10]', '$fila_evidencias_dependientes[11]', '$fila_evidencias_dependientes[12]', '$fila_evidencias_dependientes[13]', '$fila_evidencias_dependientes[16]', '$fila_evidencias_dependientes[17]', '$fila_evidencias_dependientes[18]', $fila_evidencias_dependientes[19])";
                                        //mysqli_query($link, $sql);
                                        fputs($archivo,$sql.";\n");
                                        // se comprueba si la evidencia añadida tiene hashes asociados y en su caso se añaden
                                        $sql="Select * from hash where id_evidencia=$fila_evidencias_dependientes[0]";
                                        $result_hash=mysqli_query($link_portable, $sql);
                                        $count_hash=mysqli_num_rows($result_hash);
                                        if($count_hash!=0){
                                            while ($fila_hash = mysqli_fetch_row($result_hash)) {
                                                $sql = "INSERT INTO hash (id_evidencia, id_tipo_hash, hash) values ($myid_evidencia_dependiente, $fila_hash[2], '$fila_hash[3]')";
                                                //mysqli_query($link, $sql);
                                                fputs($archivo,$sql.";\n");
                                                //se añade el registro asociado a ese hash
                                                $sql="Select * from evidencia_registro where id_hash=$fila_hash[0]";
                                                $result_registro=mysqli_query($link_portable, $sql);
                                                $count_registro=mysqli_num_rows($result_registro);
                                                if($count_registro!=0) {
                                                    while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                        if(empty($fila_registro[4])) {
                                                            $fila_registro[4]='null';
                                                        }
                                                        if(empty($fila_registro[5])) {
                                                            $fila_registro[5]='null';
                                                        }
                                                        if(empty($fila_registro[6])) {
                                                            $fila_registro[6]='null';
                                                        }
                                                        $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash,observaciones, fecha_alta_estado) values ($myid_evidencia_dependiente, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $myid_hash, '$fila_registro[8]', '$fila_registro[9]')";
                                                        //mysqli_query($link, $sql);
                                                        fputs($archivo,$sql.";\n");
                                                    }
                                                }
                                            }
                                        }
                                        // se añaden el resto de registros que no tienen hashes asociados
                                        $sql="Select * from evidencia_registro where id_evidencia=$fila_evidencias_dependientes[0] and id_hash is null";
                                        $result_registro=mysqli_query($link_portable, $sql);
                                        $count_registro=mysqli_num_rows($result_registro);
                                        if($count_registro!=0) {
                                            while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                if(empty($fila_registro[4])) {
                                                    $fila_registro[4]='null';
                                                }
                                                if(empty($fila_registro[5])) {
                                                    $fila_registro[5]='null';
                                                }
                                                if(empty($fila_registro[6])) {
                                                    $fila_registro[6]='null';
                                                }
                                                if(empty($fila_registro[7])) {
                                                    $fila_registro[7]='null';
                                                }
                                                $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash,observaciones, fecha_alta_estado) values ($myid_evidencia_dependiente, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $fila_registro[7], '$fila_registro[8]', '$fila_registro[9]')";
                                                //mysqli_query($link, $sql);
                                                fputs($archivo,$sql.";\n");
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        //comprobamos si existen intervenciones sin sujeto en un  caso que tiene mas intervenciones que sujetos y en su caso se añaden aqui
        $sql="select * from intervencion where id_caso=$myid_caso and (id_sujeto_activo=1)";
        $result_intervencion=mysqli_query($link_portable, $sql);
        $count_intervencion=mysqli_num_rows($result_intervencion);
        if($count_intervencion!=0) {
            while ($fila_intervencion = mysqli_fetch_row($result_intervencion)) {
                
                $sql="INSERT INTO intervencion (id_caso, id_tipo_intervencion, id_sujeto_activo, numero_intervencion, direccion, descripcion, fecha_alta_intervencion) values ($myid_caso_safe, $fila_intervencion[2], 1, $fila_intervencion[4], '$fila_intervencion[5]', '$fila_intervencion[6]', '$fila_intervencion[7]')";
                fputs($archivo,$sql.";\n");
                //mysqli_query($link, $sql);
                // se selecionan las evidencias que dependen del caso y de la intervención
                $sql="Select * from evidencia where id_caso=$myid_caso and id_intervencion=$fila_intervencion[0] and relacionado_con is null";
                $result_evidencias=mysqli_query($link_portable, $sql);
                $count_evidencias=mysqli_num_rows($result_evidencias);
                if($count_evidencias!=0) {
                    while ($fila_evidencias = mysqli_fetch_row($result_evidencias)) {
                        if(empty($fila_evidencias[3])) {
                            $fila_evidencias[3]='null';
                        }
                        if(empty($fila_evidencias[9])) {
                            $fila_evidencias[9]='null';
                        }
                        if(empty($fila_evidencias[14])) {
                            $fila_evidencias[14]='null';
                        }
                        if(empty($fila_evidencias[19])) {
                            $fila_evidencias[19]='null';
                        }
                        $sql = "INSERT INTO evidencia (nombre, numero_evidencia, id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, tiene_subevidencias, relacionado_con, alias, patron, pin, id_tipo_capacidad ) values ('$fila_evidencias[6]', '$fila_evidencias[15]', $fila_evidencias[1], $fila_evidencias[2], $fila_evidencias[3], $myid_caso_safe, $myid_intervencion, '$fila_evidencias[7]', '$fila_evidencias[8]', $fila_evidencias[9], '$fila_evidencias[10]', '$fila_evidencias[11]', '$fila_evidencias[12]', '$fila_evidencias[13]', $fila_evidencias[14], '$fila_evidencias[16]', '$fila_evidencias[17]', '$fila_evidencias[18]', $fila_evidencias[19])";
                        //mysqli_query($link, $sql);
                        fputs($archivo,$sql.";\n");
                        // se comprueba si la evidencia añadida tiene hashes asociados y si es así, se agrega el hash a la BBDD
                        $sql="Select * from hash where id_evidencia=$fila_evidencias[0]";
                        $result_hash=mysqli_query($link_portable, $sql);
                        $count_hash=mysqli_num_rows($result_hash);
                        if($count_hash!=0){
                            while ($fila_hash = mysqli_fetch_row($result_hash)) {
                                $sql = "INSERT INTO hash (id_evidencia, id_tipo_hash, hash) values ($myid_evidencia, $fila_hash[2], '$fila_hash[3]')";
                                //mysqli_query($link, $sql);
                                fputs($archivo,$sql.";\n");
                                //se añade el registro asociado a ese hash
                                $sql="Select * from evidencia_registro where id_hash=$fila_hash[0]";
                                $result_registro=mysqli_query($link_portable, $sql);
                                $count_registro=mysqli_num_rows($result_registro);
                                if($count_registro!=0) {
                                    while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                        if(empty($fila_registro[4])) {
                                            $fila_registro[4]='null';
                                        }
                                        if(empty($fila_registro[5])) {
                                            $fila_registro[5]='null';
                                        }
                                        if(empty($fila_registro[6])) {
                                            $fila_registro[6]='null';
                                        }
                                        $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash, observaciones, fecha_alta_estado) values ($myid_evidencia, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $myid_hash, '$fila_registro[8]', '$fila_registro[9]')";
                                        //mysqli_query($link, $sql);
                                        fputs($archivo,$sql.";\n");
                                    }
                                }
                            }
                            
                        }
                        // se añaden el resto de registros que no tienen hashes asociados
                        $sql="Select * from evidencia_registro where id_evidencia=$fila_evidencias[0] and id_hash is null";
                        $result_registro=mysqli_query($link_portable, $sql);
                        $count_registro=mysqli_num_rows($result_registro);
                        if($count_registro!=0) {
                            while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                if(empty($fila_registro[4])) {
                                    $fila_registro[4]='null';
                                }
                                if(empty($fila_registro[5])) {
                                    $fila_registro[5]='null';
                                }
                                if(empty($fila_registro[6])) {
                                    $fila_registro[6]='null';
                                }
                                if(empty($fila_registro[7])) {
                                    $fila_registro[7]='null';
                                }
                                $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash, observaciones, fecha_alta_estado) values ($myid_evidencia, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $fila_registro[7], '$fila_registro[8]', '$fila_registro[9]')";
                                //mysqli_query($link, $sql);
                                fputs($archivo,$sql.";\n");
                            }
                        }
                        // se comprueba si existen evidencias que dependen de la que acabamos de añadir y en su caso se añaden
                        $sql="Select * from evidencia where relacionado_con=$fila_evidencias[0]";
                        $result_evidencias_dependientes=mysqli_query($link_portable, $sql);
                        $count_evidencias_dependientes=mysqli_num_rows($result_evidencias_dependientes);
                        if($count_evidencias_dependientes!=0) {
                            while ($fila_evidencias_dependientes = mysqli_fetch_row($result_evidencias_dependientes)) {
                                if(empty($fila_evidencias_dependientes[3])) {
                                    $fila_evidencias_dependientes[3]='null';
                                }
                                if(empty($fila_evidencias_dependientes[9])) {
                                    $fila_evidencias_dependientes[9]='null';
                                }
                                if(empty($fila_evidencias_dependientes[19])) {
                                    $fila_evidencias_dependientes[19]='null';
                                }
                                $sql = "INSERT INTO evidencia (nombre, numero_evidencia, relacionado_con, id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, tiene_subevidencias, alias, patron, pin, id_tipo_capacidad ) values ('$fila_evidencias_dependientes[6]', '$fila_evidencias_dependientes[15]', $myid_evidencia, $fila_evidencias_dependientes[1], $fila_evidencias_dependientes[2], $fila_evidencias_dependientes[3], $myid_caso_safe, $myid_intervencion, '$fila_evidencias_dependientes[7]', '$fila_evidencias_dependientes[8]', $fila_evidencias_dependientes[9], '$fila_evidencias_dependientes[10]', '$fila_evidencias_dependientes[11]', '$fila_evidencias_dependientes[12]', '$fila_evidencias_dependientes[13]', '$fila_evidencias_dependientes[16]', '$fila_evidencias_dependientes[17]', '$fila_evidencias_dependientes[18]', $fila_evidencias_dependientes[19])";
                                //mysqli_query($link, $sql);
                                fputs($archivo,$sql.";\n");
                                // se comprueba si la evidencia añadida tiene hashes asociados y en su caso se añaden
                                $sql="Select * from hash where id_evidencia=$fila_evidencias_dependientes[0]";
                                $result_hash=mysqli_query($link_portable, $sql);
                                $count_hash=mysqli_num_rows($result_hash);
                                if($count_hash!=0){
                                    while ($fila_hash = mysqli_fetch_row($result_hash)) {
                                        $sql = "INSERT INTO hash (id_evidencia, id_tipo_hash, hash) values ($myid_evidencia_dependiente, $fila_hash[2], '$fila_hash[3]')";
                                        //mysqli_query($link, $sql);
                                        fputs($archivo,$sql.";\n");
                                        //se añade el registro asociado a ese hash
                                        $sql="Select * from evidencia_registro where id_hash=$fila_hash[0]";
                                        $result_registro=mysqli_query($link_portable, $sql);
                                        $count_registro=mysqli_num_rows($result_registro);
                                        if($count_registro!=0) {
                                            while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                if(empty($fila_registro[4])) {
                                                    $fila_registro[4]='null';
                                                }
                                                if(empty($fila_registro[5])) {
                                                    $fila_registro[5]='null';
                                                }
                                                if(empty($fila_registro[6])) {
                                                    $fila_registro[6]='null';
                                                }
                                                $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash,observaciones, fecha_alta_estado) values ($myid_evidencia_dependiente, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $myid_hash, '$fila_registro[8]', '$fila_registro[9]')";
                                                //mysqli_query($link, $sql);
                                                fputs($archivo,$sql.";\n");
                                            }
                                        }
                                    }
                                }
                                // se añaden el resto de registros que no tienen hashes asociados
                                $sql="Select * from evidencia_registro where id_evidencia=$fila_evidencias_dependientes[0] and id_hash is null";
                                $result_registro=mysqli_query($link_portable, $sql);
                                $count_registro=mysqli_num_rows($result_registro);
                                if($count_registro!=0) {
                                    while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                        if(empty($fila_registro[4])) {
                                            $fila_registro[4]='null';
                                        }
                                        if(empty($fila_registro[5])) {
                                            $fila_registro[5]='null';
                                        }
                                        if(empty($fila_registro[6])) {
                                            $fila_registro[6]='null';
                                        }
                                        if(empty($fila_registro[7])) {
                                            $fila_registro[7]='null';
                                        }
                                        $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash,observaciones, fecha_alta_estado) values ($myid_evidencia_dependiente, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $fila_registro[7], '$fila_registro[8]', '$fila_registro[9]')";
                                        //mysqli_query($link, $sql);
                                        fputs($archivo,$sql.";\n");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    
    }
    else {
        $sql="select * from intervencion where id_caso=$myid_caso and id_sujeto_activo=1";
        $result_intervencion=mysqli_query($link_portable, $sql);
        $count_intervencion=mysqli_num_rows($result_intervencion);
        if($count_intervencion!=0) {
            while ($fila_intervencion = mysqli_fetch_row($result_intervencion)) {
                
                $sql="INSERT INTO intervencion (id_caso, id_tipo_intervencion, id_sujeto_activo, numero_intervencion, direccion, descripcion, fecha_alta_intervencion) values ($myid_caso_safe, $fila_intervencion[2], 1, $fila_intervencion[4], '$fila_intervencion[5]', '$fila_intervencion[6]', '$fila_intervencion[7]')";
                fputs($archivo,$sql.";\n");
                //mysqli_query($link, $sql);
                // se selecionan las evidencias que dependen del caso y de la intervención
                $sql="Select * from evidencia where id_caso=$myid_caso and id_intervencion=$fila_intervencion[0] and relacionado_con is null";
                $result_evidencias=mysqli_query($link_portable, $sql);
                $count_evidencias=mysqli_num_rows($result_evidencias);
                if($count_evidencias!=0) {
                    while ($fila_evidencias = mysqli_fetch_row($result_evidencias)) { 
                            if(empty($fila_evidencias[3])) {
                                $fila_evidencias[3]='null';
                            }
                            if(empty($fila_evidencias[9])) {
                                $fila_evidencias[9]='null';
                            }
                            if(empty($fila_evidencias[14])) {
                                $fila_evidencias[14]='null';
                            }
                            if(empty($fila_evidencias[19])) {
                                $fila_evidencias[19]='null';
                            }
                            $sql = "INSERT INTO evidencia (nombre, numero_evidencia, id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, tiene_subevidencias, relacionado_con, alias, patron, pin, id_tipo_capacidad ) values ('$fila_evidencias[6]', '$fila_evidencias[15]', $fila_evidencias[1], $fila_evidencias[2], $fila_evidencias[3], $myid_caso_safe, $myid_intervencion, '$fila_evidencias[7]', '$fila_evidencias[8]', $fila_evidencias[9], '$fila_evidencias[10]', '$fila_evidencias[11]', '$fila_evidencias[12]', '$fila_evidencias[13]', $fila_evidencias[14], '$fila_evidencias[16]', '$fila_evidencias[17]', '$fila_evidencias[18]', $fila_evidencias[19])";
                            //mysqli_query($link, $sql);
                            fputs($archivo,$sql.";\n");
                            // se comprueba si la evidencia añadida tiene hashes asociados y si es así, se agrega el hash a la BBDD
                            $sql="Select * from hash where id_evidencia=$fila_evidencias[0]";
                            $result_hash=mysqli_query($link_portable, $sql);
                            $count_hash=mysqli_num_rows($result_hash);
                            if($count_hash!=0){
                                while ($fila_hash = mysqli_fetch_row($result_hash)) {
                                    $sql = "INSERT INTO hash (id_evidencia, id_tipo_hash, hash) values ($myid_evidencia, $fila_hash[2], '$fila_hash[3]')";
                                    //mysqli_query($link, $sql);
                                    fputs($archivo,$sql.";\n");
                                    //se añade el registro asociado a ese hash
                                    $sql="Select * from evidencia_registro where id_hash=$fila_hash[0]";
                                    $result_registro=mysqli_query($link_portable, $sql);
                                    $count_registro=mysqli_num_rows($result_registro);
                                    if($count_registro!=0) {
                                        while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                            if(empty($fila_registro[4])) {
                                                $fila_registro[4]='null';
                                            }
                                            if(empty($fila_registro[5])) {
                                                $fila_registro[5]='null';
                                            }
                                            if(empty($fila_registro[6])) {
                                                $fila_registro[6]='null';
                                            }
                                            $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash, observaciones, fecha_alta_estado) values ($myid_evidencia, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $myid_hash, '$fila_registro[8]', '$fila_registro[9]')";
                                            //mysqli_query($link, $sql);
                                            fputs($archivo,$sql.";\n");
                                        }
                                    }
                                }
                                
                            }
                            // se añaden el resto de registros que no tienen hashes asociados
                            $sql="Select * from evidencia_registro where id_evidencia=$fila_evidencias[0] and id_hash is null";
                            $result_registro=mysqli_query($link_portable, $sql);
                            $count_registro=mysqli_num_rows($result_registro);
                            if($count_registro!=0) {
                                while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                    if(empty($fila_registro[4])) {
                                        $fila_registro[4]='null';
                                    }
                                    if(empty($fila_registro[5])) {
                                        $fila_registro[5]='null';
                                    }
                                    if(empty($fila_registro[6])) {
                                        $fila_registro[6]='null';
                                    }
                                    if(empty($fila_registro[7])) {
                                        $fila_registro[7]='null';
                                    }
                                    $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash, observaciones, fecha_alta_estado) values ($myid_evidencia, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $fila_registro[7], '$fila_registro[8]', '$fila_registro[9]')";
                                    //mysqli_query($link, $sql);
                                    fputs($archivo,$sql.";\n");
                                }
                            }
                            // se comprueba si existen evidencias que dependen de la que acabamos de añadir y en su caso se añaden
                            $sql="Select * from evidencia where relacionado_con=$fila_evidencias[0]";
                            $result_evidencias_dependientes=mysqli_query($link_portable, $sql);
                            $count_evidencias_dependientes=mysqli_num_rows($result_evidencias_dependientes);
                            if($count_evidencias_dependientes!=0) {
                                echo "Entro";
                                while ($fila_evidencias_dependientes = mysqli_fetch_row($result_evidencias_dependientes)) {
                                    echo "SALGO";
                                    if($count_evidencias_dependientes!=0){
                                        if(empty($fila_evidencias_dependientes[3])) {
                                            $fila_evidencias_dependientes[3]='null';
                                        }
                                        if(empty($fila_evidencias_dependientes[9])) {
                                            $fila_evidencias_dependientes[9]='null';
                                        }
                                        if(empty($fila_evidencias_dependientes[19])) {
                                            $fila_evidencias_dependientes[19]='null';
                                        }
                                        $sql = "INSERT INTO evidencia (nombre, numero_evidencia, relacionado_con, id_tipo_evidencia, id_subtipo_evidencia, id_disco_almacenado, id_caso, id_intervencion, fecha_alta_evidencia, n_s, capacidad, marca, modelo, observaciones, tiene_subevidencias, alias, patron, pin, id_tipo_capacidad ) values ('$fila_evidencias_dependientes[6]', '$fila_evidencias_dependientes[15]', $myid_evidencia, $fila_evidencias_dependientes[1], $fila_evidencias_dependientes[2], $fila_evidencias_dependientes[3], $myid_caso_safe, $myid_intervencion, '$fila_evidencias_dependientes[7]', '$fila_evidencias_dependientes[8]', $fila_evidencias_dependientes[9], '$fila_evidencias_dependientes[10]', '$fila_evidencias_dependientes[11]', '$fila_evidencias_dependientes[12]', '$fila_evidencias_dependientes[13]', '$fila_evidencias_dependientes[16]', '$fila_evidencias_dependientes[17]', '$fila_evidencias_dependientes[18]', $fila_evidencias_dependientes[19])";
                                        echo $sql;
                                        //mysqli_query($link, $sql);
                                        fputs($archivo,$sql.";\n");
                                        // se comprueba si la evidencia añadida tiene hashes asociados y en su caso se añaden
                                        $sql="Select * from hash where id_evidencia=$fila_evidencias_dependientes[0]";
                                        $result_hash=mysqli_query($link_portable, $sql);
                                        $count_hash=mysqli_num_rows($result_hash);
                                        if($count_hash!=0){
                                            while ($fila_hash = mysqli_fetch_row($result_hash)) {
                                                $sql = "INSERT INTO hash (id_evidencia, id_tipo_hash, hash) values ($myid_evidencia_dependiente, $fila_hash[2], '$fila_hash[3]')";
                                                //mysqli_query($link, $sql);
                                                fputs($archivo,$sql.";\n");
                                                //se añade el registro asociado a ese hash
                                                $sql="Select * from evidencia_registro where id_hash=$fila_hash[0]";
                                                $result_registro=mysqli_query($link_portable, $sql);
                                                $count_registro=mysqli_num_rows($result_registro);
                                                if($count_registro!=0) {
                                                    while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                        if(empty($fila_registro[4])) {
                                                            $fila_registro[4]='null';
                                                        }
                                                        if(empty($fila_registro[5])) {
                                                            $fila_registro[5]='null';
                                                        }
                                                        if(empty($fila_registro[6])) {
                                                            $fila_registro[6]='null';
                                                        }
                                                        $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash, observaciones, fecha_alta_estado) values ($myid_evidencia_dependiente, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $myid_hash, '$fila_registro[8]', '$fila_registro[9]')";
                                                        //mysqli_query($link, $sql);
                                                        fputs($archivo,$sql.";\n");
                                                    }
                                                }
                                            }
                                        }
                                        // se añaden el resto de registros que no tienen hashes asociados
                                        $sql="Select * from evidencia_registro where id_evidencia=$fila_evidencias_dependientes[0] and id_hash is null";
                                        $result_registro=mysqli_query($link_portable, $sql);
                                        $count_registro=mysqli_num_rows($result_registro);
                                        if($count_registro!=0) {
                                            while ($fila_registro = mysqli_fetch_row($result_registro)) {
                                                if(empty($fila_registro[4])) {
                                                    $fila_registro[4]='null';
                                                }
                                                if(empty($fila_registro[5])) {
                                                    $fila_registro[5]='null';
                                                }
                                                if(empty($fila_registro[6])) {
                                                    $fila_registro[6]='null';
                                                }
                                                if(empty($fila_registro[7])) {
                                                    $fila_registro[7]='null';
                                                }
                                                $sql = "INSERT INTO evidencia_registro (id_evidencia, id_estado_evidencia, id_usuario, id_ordenadores, id_programa, id_accion_programa, id_hash, observaciones, fecha_alta_estado) values ($myid_evidencia_dependiente, $fila_registro[2], $fila_registro[3], $fila_registro[4], $fila_registro[5], $fila_registro[6], $fila_registro[7], '$fila_registro[8]', '$fila_registro[9]')";
                                                //mysqli_query($link, $sql);
                                                fputs($archivo,$sql.";\n");
                                            }
                                        }
                                    }
                                }
                            }
                    }
                }
            }
        }
    }
    
    fclose($archivo);
    
    $_SESSION['respuesta']=1;
    echo '<script type="text/javascript">
	   window.location.replace("inicio.php");
        </script>';
}
else {
    echo "Error";
}

