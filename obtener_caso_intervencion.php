<?php

session_start();

$link = mysqli_connect("localhost", "root", ".google.", "safe_portable"); 

if (mysqli_connect_errno()) {
    printf("Fallo la conexion: %s\n", mysqli_connect_error());
    exit();
}

$myid_caso=$_GET['id_caso'];











if (strpos($myid_caso, '-') !== false) {
    $datos_caso=explode("-", $myid_caso);
    $numcaso=$datos_caso[0];
    $numintervencion=$datos_caso[1];
    
}
else
{
    $numcaso=$myid_caso;
    $resultado_evidencias_caso = mysqli_query($link, "select  e.tiene_subevidencias, e.id_evidencia, e.nombre, e.numero_evidencia, s.nombre as nom_sub,  e.n_s, e.capacidad, e.marca, e.modelo, e.alias
                                        from evidencia e
                                        inner join tipo_evidencia t on t.id_tipo_evidencia=e.id_tipo_evidencia
                                        inner join subtipo_evidencia s on s.id_subtipo_evidencia=e.id_subtipo_evidencia
                                        inner join caso c on c.id_caso=e.id_caso
                                        inner join evidencia_registro er ON er.id_evidencia=e.id_evidencia
                                        where c.id_caso=$numcaso AND relacionado_con is null
                                        GROUP BY e.id_evidencia
                                        order By nombre asc");
    $count=mysqli_num_rows($resultado_evidencias_caso);
    if($count!=0) 
    {
        $count_evidencias = mysqli_num_rows($resultado_evidencias_caso);
        
        if ($count_evidencias != 0) { 
            $contador = 0;
            $tiene_sub = 0;
            $entra = 0;
            
                                                                        echo "<table id='exampletable' class='display' style='width:100%'>";
                                        								echo '	<thead>
                                        										<tr>
                                                                                    <th></th>   
                                        											<th>Nombre</th>
                                                                                    <th>Depende de</th>
                                                                                    <th>Subtipo</th>
                                                                                    <th>N/S</th>
                                                                                    <th>Marca</th>
                                                                                    <th>Modelo</th>
                                                                                    <th>Estado</th>
                                                                                    <th>Identificacdor</th> 
                                        										</tr>
                                        									</thead>
                                        									<tbody>';
                                               
                                        		$i=1;
                                                while ($line_evidencias = mysqli_fetch_array($resultado_evidencias_caso, MYSQLI_ASSOC)) {
                                                    $tiene_sub=$line_evidencias['tiene_subevidencias'];
                                                    $id_evidencia=$line_evidencias['id_evidencia'];
                                                    $nombre=$line_evidencias['nombre'];
                                                    $numero_evidencia=$line_evidencias['numero_evidencia'];
                                                    $subtipo=$line_evidencias['nom_sub'];
                                                    $n_s=$line_evidencias['n_s'];
                                                    $marca=$line_evidencias['marca'];
                                                    $modelo=$line_evidencias['modelo'];
                                                    $alias=$line_evidencias['alias'];
                                                    
                                                                                                     
                                                    echo "<tr>";
                                                    
                                                    echo "<td>";
                                                    
                                                        echo "<input type='checkbox' name='id_evidencia$i' id='id_evidencia$i' value='$id_evidencia' onclick='getRow(this)'>";
                                                        echo"<label for='id_evidencia$i'>";
                                                        echo"</label>";
                                                        
                                                        
                                                        $i++;
                                                    echo "</td>";
                                                    echo "<td>";
                                                    
                                                    $entra = 0;
                                                    $nombre_original = $nombre;
                                                    $nombre=base64_encode($nombre_original);
                                                    $numero=base64_encode($numero_evidencia);
                                                    echo "$nombre_original$numero_evidencia";
                                                    if(!empty($alias)) {
                                                        echo "<br>($alias)";
                                                    }
                                                    echo "</td>";
                                                    echo "<td></td>";
                                                    echo "<td>".$subtipo."</td>";
                                                    echo "<td>".$n_s."</td>";
                                                    echo "<td>".$marca."</td>";
                                                    echo "<td>".$modelo."</td>";
                                                    
                                                    
                                                    $resultado_estado=mysqli_query($link, "select max(er.id_estado_evidencia) as id_estado from evidencia_registro er
                                                    where id_evidencia=$id_evidencia");
                                                    $ret_estado=mysqli_fetch_array($resultado_estado);
                                                    $resultado_estado_nombre=mysqli_query($link, "select nombre from estado_evidencia where id_estado_evidencia=$ret_estado[id_estado]");
                                                    $ret_nombre=mysqli_fetch_array($resultado_estado_nombre);
                                                    $resultado_ordenador=mysqli_query($link, "select o.nombre_ordenadores as ordenador from evidencia_registro er
                                                    Inner join ordenadores o on o.id_ordenadores=er.id_ordenadores
                                                    where id_evidencia=$id_evidencia");
                                                    $ret_ordenador=mysqli_fetch_array($resultado_ordenador); 
                                                    echo "<td>".$ret_nombre['nombre']."</td>";
                                                    if(!empty($ret_ordenador['ordenador'])) {
                                                        echo "<td>".$ret_ordenador['ordenador']."</td>";
                                                    }
                                                    else {
                                                        echo "<td></td>";
                                                    }
                                                    $entra = 1;
                                                    
                                                    echo "<td>";
                                                   
                                                    echo "$id_evidencia";
                                                    echo "</td>";
                                                    
                                                    if ($tiene_sub == 1 and $entra == 1) {     
                                                                echo "</tr>";
                                                                $entra = 0;
                                                                $result = mysqli_query($link, "select e.nombre, e.numero_evidencia, s.nombre as nom_sub,  e.n_s, e.capacidad, e.marca, e.modelo,e.id_evidencia, e.alias  from evidencia e
                                                                inner join tipo_evidencia t on t.id_tipo_evidencia=e.id_tipo_evidencia
                                                                inner join subtipo_evidencia s on s.id_subtipo_evidencia=e.id_subtipo_evidencia
                                                                inner join caso c on c.id_caso=e.id_caso
                                                                inner join evidencia_registro er ON er.id_evidencia=e.id_evidencia
                                                                where c.id_caso='$myid_caso' AND relacionado_con=$id_evidencia 
                                                                GROUP BY e.id_evidencia	
                                                                order By nombre asc");
                                                                $result2 = mysqli_query($link, "select e.nombre as nom, e.numero_evidencia as num from evidencia e where id_evidencia=$id_evidencia");
                                                                $ret=mysqli_fetch_array($result2);
                                                                while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                                                    $nombre=$line['nombre'];
                                                                    $numero_evidencia=$line['numero_evidencia'];
                                                                    $subtipo=$line['nom_sub'];
                                                                    $n_s=$line['n_s'];
                                                                    $marca=$line['marca'];
                                                                    $modelo=$line['modelo'];
                                                                    $id_evidencia_sub=$line['id_evidencia'];
                                                                    $alias=$line['alias'];
                                                                    echo "<tr>";
                                                                    echo "<td>";
                                                                    
                                                                    echo "<input type='checkbox' name='id_evidencia$i' id='id_evidencia$i' value='$id_evidencia' onclick='getRow(this)'>";
                                                                    echo"<label for='id_evidencia$i'>";
                                                                    echo"</label>";
                                                                    $i++;
                                                                    
                                                                    echo "</td>";
                                                                    
                                                                    echo "<td>";
                                                                    $nombre_original = $nombre;
                                                                    $nombre=base64_encode($nombre_original);
                                                                    $numero=base64_encode($numero_evidencia);
                                                                    echo "$nombre_original$numero_evidencia";
                                                                    if(!empty($alias)) {
                                                                        echo "<br>($alias)";
                                                                    }
                                                                    echo "</td>";
                                                                    echo "<td>";
                                                                    echo $ret['nom'].$ret['num'];
                                                                    echo "</td>";
                                                                    echo "<td>".$subtipo."</td>";
                                                                    echo "<td>".$n_s."</td>";
                                                                   // echo "<td>".$capacidad."</td>";
                                                                    echo "<td>".$marca."</td>";
                                                                    echo "<td>".$modelo."</td>";
                                                                    $resultado_estado=mysqli_query($link, "select max(er.id_estado_evidencia) as id_estado from evidencia_registro er
                                                                    where id_evidencia=$id_evidencia_sub");
                                                                    $ret_estado=mysqli_fetch_array($resultado_estado);
                                                                    $resultado_estado_nombre=mysqli_query($link, "select nombre from estado_evidencia where id_estado_evidencia=$ret_estado[id_estado]");
                                                                    $ret_nombre=mysqli_fetch_array($resultado_estado_nombre);
                                                                    $resultado_ordenador=mysqli_query($link, "select o.nombre_ordenadores as ordenador from evidencia_registro er
                                                                    Inner join ordenadores o on o.id_ordenadores=er.id_ordenadores
                                                                    where id_evidencia=$id_evidencia_sub");
                                                                    $ret_ordenador=mysqli_fetch_array($resultado_ordenador);
                                                                    echo "<td>".$ret_nombre['nombre']."</td>";
                                                                    if(!empty($ret_ordenador['ordenador'])) {
                                                                        echo "<td>".$ret_ordenador['ordenador']."</td>";
                                                                    }
                                                                    else {
                                                                        echo "<td></td>";
                                                                    }
                                                                    
                                                                    
                                                                    //echo '<input type="checkbox" id=$id_evidencia_sub name=$id_evidencia_sub value=$id_evidencia_sub>';
                                                                    //echo "<input type='checkbox' id=$id_evidencia_sub name=$id_evidencia_sub value=$id_evidencia_sub>";
                                                                    //echo "<td>";
                                                                    //echo "<input type='checkbox' id=$id_evidencia_sub name=$id_evidencia_sub value=$id_evidencia_sub onclick='getRow(this)' />";
                                                                    //echo "</td>";
                                                                    
                                                                    echo "<td>$id_evidencia_sub</td>";
                                                                    
                                                                    echo "</tr>";
                                                                }
                                                            }
                                                    echo "</tr>";
                                                }
                                                echo "</tbody></table>";
                                        
                                   
                                           	
        } else {  //   if ($count_evidencias != 0) { 
                                            
                                                echo '<ul>
                                            		<li>No hay evidencias relacionadas con esta operación</li>										
                                            	</ul>  
                                            	<br><br>';
                                          
        }		//   if ($count_evidencias != 0) { 
                                            
                                            										
        echo '</div>';	
        
    }

}

?>