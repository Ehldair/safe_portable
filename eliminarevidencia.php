<?php
session_start();

$link = mysqli_connect("localhost", "root", ".google.", "safe_portable");


$myid_ev=mysqli_real_escape_string($link,$_POST["category"]);
   
    //ELIMINA LA EVIDENCIA SI NO TIENE ASOCIADAS 
    //Primero elimina los registros asociados de la evidencia en la tabla evidencia_registro
    
    $resultado_ev_reg=mysqli_query($link, "SELECT * from evidencia_registro where id_evidencia=$myid_ev");
    $num_ev_reg=mysqli_num_rows($resultado_ev_reg);
    if($num_ev_reg!=0) {
        while ($line_evidencias = mysqli_fetch_array($resultado_ev_reg, MYSQLI_ASSOC)) {
            $id_evidencia_reg = $line_evidencias['id_evidencia'];
            
            $sql="DELETE FROM evidencia_registro where id_evidencia=$id_evidencia_reg";
            $result = mysqli_query($link,$sql);
            if (!$result) {
                ?>
         
             <script type="text/javascript">
                alert("Fallo al eliminar la evidencia de la tabla : evidencia_registro.\n\n Detalles: \n\n <?php echo  mysqli_error($link) ?>");
                location.href = "detalle_evidencia.php";
             </script>
        
            <?php
            }
        
        } //while ($line_evidencias
    } // if($num_ev_reg!=0) 
    
    //Segundo elimina la evidencia de la tabla hash
    
    
    
    $sql="DELETE FROM hash where id_evidencia=$myid_ev";
    $result = mysqli_query($link,$sql);
    if (!$result) {
        ?>
            
    				<script type="text/javascript">
                        alert("Fallo al eliminar el hash de la tabla : hash.\n\n Detalles: \n\n <?php echo  mysqli_error($link) ?>");
                        location.href = "detalle_evidencia.php";
                     </script>
                
            	<?php
    }
    
    //compruebo si hay alguna evidencia que dependa de la misma evidencia, en el caso de que la evidencia a eliminar fuera dependiente
    $sql="Select relacionado_con from evidencia where id_evidencia=$myid_ev";
    $resultado=mysqli_query($link, $sql);
    $ret=mysqli_fetch_array($resultado);
    
    $sql="Select * from evidencia where relacionado_con=$ret[relacionado_con] AND id_evidencia!=$myid_ev";
    $resultado=mysqli_query($link, $sql);
    $count=mysqli_num_rows($resultado);
    if($count==0) {
        $sql="Update Evidencia set tiene_subevidencias=0 where id_evidencia=$ret[relacionado_con]";
        mysqli_query($link, $sql);
    }
           
    //Tercero elimina la evidencia de la tabla evidencia
    
    $sql="DELETE FROM evidencia where id_evidencia=$myid_ev";
    $result = mysqli_query($link,$sql);
    if (!$result) {
        ?>
         
         <script type="text/javascript">
            alert("Fallo al eliminar la evidencia de la tabla : evidencia.\n\n Detalles: \n\n <?php echo  mysqli_error($link) ?>");
            location.href = "detalle_evidencia.php";
         </script>
    
    <?php
    }
    else
    {
        $_SESSION['respuesta']=7;
        echo 7;
    }


?>

