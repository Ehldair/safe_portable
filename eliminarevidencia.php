<?php
session_start();

$link = mysqli_connect("localhost", "root", ".google.", "safe");

if(isset($_POST['id_ev'])) {
    $myid_ev = mysqli_real_escape_string($link,$_POST['id_ev']);
}
$myid_ev=$_SESSION['id_ev'];
   
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
    
    //Segundo elimina la evidencia de la tabla evidencia
    
    
    
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

