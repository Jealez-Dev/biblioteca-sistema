<?php 
   require_once('controllers/NoticiaController.php');
   $controller= new NoticiaController();
   $result_noticia= $controller->ListarNoticia1();
   $numrows = mysqli_num_rows($result_noticia);
?>

<div class="contaniner">

<br> <br>
<h4> Listado de Noticias Registradas en el Sistema de Telemedicina </h4>
<br> <br>
 

<div class="table-responsive">
<table id="dtBasicExample" data-order='[[ 0, "asc" ]]' data-page-length='10' class="table table-sm table-striped table-hover table-bordered" cellspacing="0" width="100%" >
 
  <thead>
     <tr>
              <th class="th-sm">Id</th>
              <th class="th-sm">Titular</th>
              <th class="th-sm">Categoría</th>
              <th class="th-sm">Fecha</th>
              <th class="th-sm">Visible</th>
              <th class="th-sm">Imagen</th>
              <th class="th-sm">Modificar</th>
              <th class="th-sm">Eliminar</th>
     </tr>

  </thead>

 <tbody>
 <?php 
     
      if ($numrows != 0)
      {
                       while ($numrows = mysqli_fetch_array($result_noticia))
                       {?>
                            <tr>
                                <?php
                                    $i = $numrows["id"];
                                ?>
                                <th scope="row"><?php echo $numrows["id"]; ?></th>

                                <td>
                                    <a title="Ver Noticia" href="<?php echo $numrows["url_noticia"]; ?>" target="_blank"> <?php echo $numrows["titular"]; ?> </a>
                                </td>
                                         
                                <td><?php echo "$numrows[categoria]";?></td>
                                <td><?php echo "$numrows[fecha]";?></td>
                                <td align= "center"><?php echo "$numrows[visible]";?></td>
                                <td align= "center">
                                    <a title="Ver Imagen" href="<?php echo $numrows["url_imagen"]; ?>" target="_blank"><img width="50px" height ="50px" src="<?php echo $numrows["url_imagen"]; ?>" alt=""></></a>
                                </td>
                              
                                <td align= "center">
                                    <?php echo "<a href='?controller=Noticia&action=UpdateNoticia&i=$i' title= 'Modificar'>";?>  
                                    <img width="50px" height ="50px" src="..\ejercicio\imagenes\update_icon.jpg" alt=""> </a>
                                </td>
                                <td align= "center">
                                    <?php echo "<a href='?controller=Noticia&action=DeleteNoticia&i=$i' title= 'Eliminar'>";?>  
                                    <img width="50px" height ="50px" src="..\ejercicio\imagenes\delete_icon.jpg" alt=""> </a>
                                </td>
                           
                            </tr>
                  <?php }
      }
?>
</tbody>
</table>
</div>
</div>


 


