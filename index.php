<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
            crossorigin="anonymous">
      <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
      <link rel="stylesheet" href="style.css">
      <title>Document</title>
</head>

<?php
$bd = "host=localhost dbname=bdpoireau_ok user=admin  password=admin";
$connect = pg_connect($bd);

?>

      <body>
            <!-- Menu -->
            <section class="navbar-top container-fluid">
                  <div class="row">
                        <div class="col-md-12 menu-logo">
                              <img class="logo" src="img/logo.png" alt="Logo de FaisPasLPoireau">
                        </div>
                  </div>
                  <?php 
                  $val = pg_query('SELECT pro_nom, sum(st) 
                     FROM (SELECT pro_leg,pro_nom, -sto_qte as st
                     FROM stock
                     INNER JOIN produit ON pro_id=spro_id
                     WHERE sto_pert = True
                     UNION
                     SELECT pro_leg, pro_nom, sto_qte as st
                     FROM stock
                     INNER JOIN produit ON pro_id=spro_id
                     WHERE sto_pert = false
                     UNION 
                     SELECT pro_leg, pro_nom, -con_qte as st
                     FROM contenu
                     INNER JOIN produit ON cpro_id = pro_id) as s
                     where pro_leg = false
                     GROUP BY pro_leg,pro_nom
                     ORDER BY  pro_leg,pro_nom');

                  while ($row = pg_fetch_array($val)) {
                        $row["sum"];
                  }
                  if ($row["sum"] <= 5) {
                        echo " Alerte: Regarder vos Stocks";
                  }
                  ?>


                  <!-- 
                       
            </section> -->
                  <!-- Fin menu  -->



                  <!-- ///////// Listes à gauche /////////////////-->

                  <section class="div-left">
                        <div class="table-items col-md-3 stock">

                              <h5>
                                    <i class="fa fa-print fa-1x" data-toggle="modal" data-target=".bd-example-modal-lg"></i>&nbsp;Stock</h5>

                              <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                          <div class="modal-content">
                                                <table class="table">
                                                      <thead>
                                                            <tr>
                                                                  <th id="name-column">Nom</th>
                                                                  <th>Qté_perdu</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                            <?php 
                                                $val = pg_query(" SELECT DISTINCT pro_nom, SUM(sto_qte) 
                                                FROM stock
                                                INNER JOIN produit ON produit.pro_id = stock.spro_id 
                                                WHERE sjour_id BETWEEN '55' AND '63'  AND sto_pert = TRUE
                                                GROUP BY (pro_nom)
                                                ORDER BY pro_nom");
                                                while ($row = pg_fetch_array($val)) {
                                                      echo "<tr>";
                                                      echo "<td>";
                                                      echo $row["pro_nom"];
                                                      echo "</td>";
                                                      echo "<td>";
                                                      echo $row["sum"];
                                                      echo "</td>";
                                                      echo "</tr>";
                                                }

                                                ?>
                                                      </tbody>
                                                      <thead>
                                                            <tr>
                                                                  <th id="name-column">Nom</th>
                                                                  <th>Qté_restante</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                      <?php 
                                                $val = pg_query("SELECT pro_nom, sum(st) 
                                                FROM (SELECT pro_leg,pro_nom, -sto_qte as st
                                                FROM stock
                                                INNER JOIN produit ON pro_id=spro_id
                                                WHERE sto_pert = True
                                                UNION
                                                SELECT pro_leg, pro_nom, sto_qte as st
                                                FROM stock
                                                INNER JOIN produit ON pro_id=spro_id
                                                UNION 
                                                SELECT pro_leg, pro_nom, -con_qte as st
                                                FROM contenu
                                                INNER JOIN produit ON cpro_id = pro_id) as s
                                                GROUP BY pro_leg,pro_nom
                                                ORDER BY  pro_leg,pro_nom");

                                                while ($row = pg_fetch_array($val)) {
                                                      echo "<tr>";
                                                      echo "<td>";
                                                      echo $row["pro_nom"];
                                                      echo "</td>";
                                                      echo "<td>";
                                                      echo $row["sum"];
                                                      echo "</td>";
                                                      echo "</tr>";

                                                }

                                                ?>
                                                </tbody>
                                                <thead>
                                                            <tr>
                                                                  <th id="name-column">Nom</th>
                                                                  <th>Qté_vendu</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
                                                      <?php 
                                                $val = pg_query("SELECT DISTINCT pro_nom, SUM(con_qte) 
                                                FROM stock
                                                INNER JOIN produit ON produit.pro_id = stock.spro_id 
                                                INNER JOIN contenu ON spro_id = cpro_id
                                                WHERE sjour_id BETWEEN '55' AND '63' AND pro_leg = FALSE AND sto_pert = true
                                                GROUP BY (pro_nom)
                                                ORDER BY pro_nom;");

                                                while ($row = pg_fetch_array($val)) {
                                                      echo "<tr>";
                                                      echo "<td>";
                                                      echo $row["pro_nom"];
                                                      echo "</td>";
                                                      echo "<td>";
                                                      echo $row["sum"];
                                                      echo "</td>";
                                                      echo "</tr>";

                                                }

                                                ?>
                                                </tbody>

                                                </table>
                                          </div>
                                    </div>
                              </div>

                         
                              <!-- Boutons pour sélectionner quel tableau afficher -->
                              <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                          <a class="nav-link active" data-toggle="tab" href="#home">Tous</a>
                                    </li>
                                    <li class="nav-item">
                                          <a class="nav-link" data-toggle="tab" href="#menu1">Fruits</a>
                                    </li>
                                    <li class="nav-item">
                                          <a class="nav-link" data-toggle="tab" href="#menu2">Légumes</a>
                                    </li>
                              </ul>
                              <!-- Tableaux -->
                              <div class="tab-content">
                                    <!-- Tableau Tous -->
                                    <div class="tab-pane active table-responsive" id="home">
                                          <table class="table">
                                                <thead>
                                                      <tr>
                                                            <th id="name-column">Nom</th>
                                                            <th>Qté</th>
                                                      </tr>
                                                </thead>
                                                <tbody>
                                                      <?php 
                                                $val = pg_query('SELECT pro_nom, sum(st) 
                                                FROM (SELECT pro_leg,pro_nom, -sto_qte as st
                                                FROM stock
                                                INNER JOIN produit ON pro_id=spro_id
                                                WHERE sto_pert = True
                                                UNION
                                                SELECT pro_leg, pro_nom, sto_qte as st
                                                FROM stock
                                                INNER JOIN produit ON pro_id=spro_id
                                                WHERE sto_pert = false
                                                UNION 
                                                SELECT pro_leg, pro_nom, -con_qte as st
                                                FROM contenu
                                                INNER JOIN produit ON cpro_id = pro_id) as s
                                                GROUP BY pro_leg,pro_nom
                                                ORDER BY  pro_leg,pro_nom');

                                                while ($row = pg_fetch_array($val)) {
                                                      echo "<tr>";
                                                      echo "<td>";
                                                      echo $row["pro_nom"];
                                                      echo "</td>";
                                                      echo "<td>";
                                                      echo $row["sum"];
                                                      echo "</td>";
                                                      echo "</tr>";

                                                }

                                                ?>
                                                </tbody>
                                          </table>
                                    </div>
                                    <!-- Tableau Fruits -->
                                    <div class="tab-pane table-responsive" id="menu1">
                                          <table class="table">
                                                <thead>
                                                      <tr>
                                                            <th id="name-column">Nom</th>
                                                            <th>Qté</th>
                                                      </tr>
                                                </thead>
                                                <tbody>
                                                      <?php 
                                                $val = pg_query('SELECT pro_nom, sum(st) 
                                                FROM (SELECT pro_leg,pro_nom, -sto_qte as st
                                                FROM stock
                                                INNER JOIN produit ON pro_id=spro_id
                                                WHERE sto_pert = True
                                                UNION
                                                SELECT pro_leg, pro_nom, sto_qte as st
                                                FROM stock
                                                INNER JOIN produit ON pro_id=spro_id
                                                WHERE sto_pert = false
                                                UNION 
                                                SELECT pro_leg, pro_nom, -con_qte as st
                                                FROM contenu
                                                INNER JOIN produit ON cpro_id = pro_id) as s
                                                where pro_leg = false
                                                GROUP BY pro_leg,pro_nom
                                                ORDER BY  pro_leg,pro_nom');

                                                while ($row = pg_fetch_array($val)) {
                                                      echo "<tr>";
                                                      echo "<td>";
                                                      echo $row["pro_nom"];
                                                      echo "</td>";
                                                      echo "<td>";
                                                      echo $row["sum"];
                                                      echo "</td>";
                                                      echo "</tr>";

                                                }

                                                if ($row["sto_qte"] < 50) {
                                                      echo "oui";

                                                }



                                                ?>
                                                </tbody>
                                          </table>
                                    </div>
                                    <!-- Tableau Légumes -->
                                    <div class="tab-pane table-responsive" id="menu2">

                                          <table class="table">
                                                <thead>
                                                      <tr>
                                                            <th id="name-column">Nom</th>
                                                            <th>Qté</th>
                                                      </tr>
                                                </thead>
                                                <tbody>
                                                      <?php 
                                                $val = pg_query("SELECT pro_nom, sum(st) 
                                                FROM (SELECT pro_leg,pro_nom, -sto_qte as st
                                                FROM stock
                                                INNER JOIN produit ON pro_id=spro_id
                                                WHERE sto_pert = True
                                                UNION
                                                SELECT pro_leg, pro_nom, sto_qte as st
                                                FROM stock
                                                INNER JOIN produit ON pro_id=spro_id
                                                WHERE sto_pert = false
                                                UNION 
                                                SELECT pro_leg, pro_nom, -con_qte as st
                                                FROM contenu
                                                INNER JOIN produit ON cpro_id = pro_id) as s
                                                where pro_leg = true
                                                GROUP BY pro_leg,pro_nom
                                                ORDER BY  pro_leg,pro_nom");

                                                while ($row = pg_fetch_array($val)) {
                                                      echo "<tr>";
                                                      echo "<td>";
                                                      echo $row["pro_nom"];
                                                      echo "</td>";
                                                      echo "<td>";
                                                      echo $row["sum"];
                                                      echo "</td>";
                                                      echo "</tr>";
                                                }

                                                ?>
                                                </tbody>
                                          </table>
                                    </div>
                              </div>
                        </div>
                        <!-- ///////// FIN Listes à gauche /////////////////-->


                        <!-- ///////// DIVs colonne nouvelle vente et colonne ajouter/supprimer/géomarketing  /////////////////-->
                        <div class="col-md-9">
                              <div class="row">

                                    <!-- Nouvelle vente -->
                                    <div class="col-md-7">
                                          <div class="container new-sale">
                                                <h5>Nouvelle vente</h5>
                                                <!-- formulaire -->
                                                <form action="">

                                                      <div class="row">
                                                            <div class="col-md-3">
                                                                  <div class="form-group">
                                                                        <label for="time">Heure</label>
                                                                        <input type="number" class="form-control" id="" placeholder="00:00" disabled>
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                  <div class="form-group">
                                                                        <label for="villes">Ville</label>
                                                                        <!-- Menu déroulant -->
                                                                        <select class="form-control" name="" id="">
                                                                              <option value="">Pamiers City</option>
                                                                        </select>
                                                                  </div>
                                                            </div>
                                                      </div>

                                                      <div class="row">
                                                            <div class="col-md-3">
                                                                  <div class="form-group">
                                                                        <label for="quantityToAdd">Quantité</label>
                                                                        <input type="number" class="form-control" id="" placeholder="">
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                  <div class="form-group">
                                                                        <label for="itemToAdd">Fruit/Légume</label>
                                                                        <select class="form-control" name="" id="">
                                                                              <?php
                                                                              $val = pg_query("SELECT DISTINCT pro_nom
                                                                              FROM produit
                                                                              ORDER BY pro_nom;"); 

                                                                                    while ($row = pg_fetch_array($val)) {
                                                                                          echo "<option>";
                                                                                          echo $row["pro_nom"];
                                                                                          echo "</option>";
                                                                                    }
                                                                              
                                                                              ?>
                                                                              <!-- <option value="">Orange</option>
                                                                              <option value="">Pomme de Terre</option> -->
                                                                        </select>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="row">
                                                            <div class="col-md-3">
                                                                  <div class="form-group">
                                                                        <input type="number" class="form-control" id="" placeholder="">
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                  <div class="form-group">
                                                                        <!-- Menu déroulant -->
                                                                        <select>
                                                                        <?php
                                                                              $val = pg_query("SELECT DISTINCT pro_nom
                                                                              FROM produit
                                                                              ORDER BY pro_nom;"); 

                                                                                    while ($row = pg_fetch_array($val)) {
                                                                                          echo "<option>";
                                                                                          echo $row["pro_nom"];
                                                                                          echo "</option>";
                                                                                    }
                                                                              
                                                                              ?>
                                                                        </select>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="row">
                                                            <div class="col-md-3">
                                                                  <div class="form-group">
                                                                        <input type="number" class="form-control" id="" placeholder="">
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                  <div class="form-group">
                                                                        <!-- Menu déroulant -->
                                                                        <select class="form-control" name="" id="">
                                                                        <?php
                                                                              $val = pg_query("SELECT DISTINCT pro_nom
                                                                              FROM produit
                                                                              ORDER BY pro_nom;"); 

                                                                                    while ($row = pg_fetch_array($val)) {
                                                                                          echo "<option>";
                                                                                          echo $row["pro_nom"];
                                                                                          echo "</option>";
                                                                                    }
                                                                              
                                                                              ?>
                                                                        </select>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="row">
                                                            <div class="col-md-3">
                                                                  <div class="form-group">
                                                                        <input type="number" class="form-control" id="" placeholder="">
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                  <div class="form-group">
                                                                        <!-- Menu déroulant -->
                                                                        <select class="form-control" name="" id="">
                                                                        <?php
                                                                              $val = pg_query("SELECT DISTINCT pro_nom
                                                                              FROM produit
                                                                              ORDER BY pro_nom;"); 

                                                                                    while ($row = pg_fetch_array($val)) {
                                                                                          echo "<option>";
                                                                                          echo $row["pro_nom"];
                                                                                          echo "</option>";
                                                                                    }
                                                                              
                                                                              ?>
                                                                        </select>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="row">
                                                            <div class="col-md-3">
                                                                  <div class="form-group">
                                                                        <input type="number" class="form-control" id="" placeholder="">
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                  <div class="form-group">
                                                                        <!-- Menu déroulant -->
                                                                        <select class="form-control" name="" id="">
                                                                        <?php
                                                                              $val = pg_query("SELECT DISTINCT pro_nom
                                                                              FROM produit
                                                                              ORDER BY pro_nom;"); 

                                                                                    while ($row = pg_fetch_array($val)) {
                                                                                          echo "<option>";
                                                                                          echo $row["pro_nom"];
                                                                                          echo "</option>";
                                                                                    }
                                                                              
                                                                              ?>
                                                                        </select>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="row">
                                                            <div class="col-md-3">
                                                                  <div class="form-group">
                                                                        <input type="number" class="form-control" id="" placeholder="">
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                  <div class="form-group">
                                                                        <!-- Menu déroulant -->
                                                                        <select class="form-control" name="" id="">
                                                                        <?php
                                                                              $val = pg_query("SELECT DISTINCT pro_nom
                                                                              FROM produit
                                                                              ORDER BY pro_nom;"); 

                                                                                    while ($row = pg_fetch_array($val)) {
                                                                                          echo "<option>";
                                                                                          echo $row["pro_nom"];
                                                                                          echo "</option>";
                                                                                    }
                                                                              
                                                                              ?>
                                                                        </select>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="row">
                                                            <div class="col-md-3">
                                                                  <div class="form-group">
                                                                        <input type="number" class="form-control" id="" placeholder="">
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                  <div class="form-group">
                                                                        <!-- Menu déroulant -->
                                                                        <select class="form-control" name="" id="">
                                                                        <?php
                                                                              $val = pg_query("SELECT DISTINCT pro_nom
                                                                              FROM produit
                                                                              ORDER BY pro_nom;"); 

                                                                                    while ($row = pg_fetch_array($val)) {
                                                                                          echo "<option>";
                                                                                          echo $row["pro_nom"];
                                                                                          echo "</option>";
                                                                                    }
                                                                              
                                                                              ?>
                                                                        </select>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <div class="row">
                                                            <div class="col-md-3">
                                                                  <div class="form-group">
                                                                        <input type="number" class="form-control" id="" placeholder="">
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                  <div class="form-group">
                                                                        <!-- Menu déroulant -->
                                                                        <select class="form-control" name="" id="">
                                                                        <?php
                                                                              $val = pg_query("SELECT DISTINCT pro_nom
                                                                              FROM produit
                                                                              ORDER BY pro_nom;"); 

                                                                                    while ($row = pg_fetch_array($val)) {
                                                                                          echo "<option>";
                                                                                          echo $row["pro_nom"];
                                                                                          echo "</option>";
                                                                                    }
                                                                              
                                                                              ?>
                                                                        </select>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                      <br>
                                                      <div class="action-buttons">
                                                            <button class="btn btn-danger" type="submit">Annuler</button>
                                                            <button class="btn btn-success" type="submit">Valider</button>
                                                      </div>

                                                </form>
                                          </div>
                                    </div>

                                    <!-- Ajouter/Supprimer/Géomarketing -->
                                    <div class="col-md-5 right-panel">

                                          <!-- Ajouter - Nouvelle entrée dans le stock -->
                                          <h5>Nouvelle entrée dans le stock</h5>
                                          <!-- formulaire -->
                                          
                                          <form method="post">
                                                <?php 
                                                $nomFrLeg = $_POST["ajoutIngr"];
                                                $numbFrLeg = $_POST["ajoutNmbr"];
                                                      if(isset($_POST['envoyerAjout']) && !empty($_POST['envoyerAjout'])){
                                                            $val = pg_query(" INSERT INTO stock(sto_qte)  VALUES (sto_qte='".$numbFrLeg."';)"&&" INSERT INTO produit(pro_nom)  VALUES (pro_nom='".$nomFrLeg."';)"); 
                                                          echo "envoyé";

                                                      }
                                                      else{
                                                            echo "nop";
                                                      }
                                                                        ?>
                                                <div class="container">
                                                      <div class="row">

                                                            <div class="col-md-4">
                                                                  <div class="form-group">
                                                                        <label for="quantityToAdd">Quantité</label>
                                                                        <input type="number" name="ajoutNmbr" class="form-control" id="" placeholder="">
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-8">
                                                                  <div class="form-group">
                                                                        <label for="itemToAdd">Fruit/Légume</label>
                                                                        <!-- Menu déroulant -->
                                                                        <select class="form-control" name="ajoutIngr" id="">
                                                                        <?php
                                                                              $val = pg_query("SELECT DISTINCT pro_nom
                                                                              FROM produit
                                                                              ORDER BY pro_nom;"); 

                                                                                    while ($row = pg_fetch_array($val)) {
                                                                                          echo "<option>";
                                                                                          echo $row["pro_nom"];
                                                                                          echo "</option>";
                                                                                    }
                                                                              
                                                                              ?>
                                                                        </select>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                                <div class="action-buttons">
                                                      <input type="submit" name="envoyerAjout" class="btn btn-success" value="ajouter">
                                                </div>
                                          </form>

                                          <!-- Supprimer - Quantité perdue/jetée -->
                                          <h5>Quantité perdue/jetée</h5>
                                          <!-- formulaire -->
                                          <form action="">
                                                <div class="container">
                                                      <div class="row">

                                                            <div class="col-md-4">
                                                                  <div class="form-group">
                                                                        <label for="quantityToRemove">Quantité</label>
                                                                        <input type="number" class="form-control" id="" placeholder="">
                                                                  </div>
                                                            </div>
                                                            <div class="col-md-8">
                                                                  <div class="form-group">
                                                                        <label for="itemToRemove">Fruit/Légume</label>
                                                                        <!-- Menu déroulant -->
                                                                        <select class="form-control" name="" id="">
                                                                        <?php
                                                                              $val = pg_query("SELECT DISTINCT pro_nom
                                                                              FROM produit
                                                                              ORDER BY pro_nom;"); 

                                                                                    while ($row = pg_fetch_array($val)) {
                                                                                          echo "<option>";
                                                                                          echo $row["pro_nom"];
                                                                                          echo "</option>";
                                                                                    }
                                                                              
                                                                              ?>
                                                                        </select>
                                                                  </div>
                                                            </div>
                                                      </div>
                                                </div>
                                                <div class="action-buttons">
                                                      <button type="submit" class="btn btn-danger">Supprimer</button>
                                                </div>
                                          </form>

                                          <!-- Geomarketing -->
                                          <div class="row">
                                                <div class="col-md-9 geo-title">
                                                      <h5>
                                                            <i class="fa fa-print fa-1x icon-menu">&nbsp;</i>Géomarketing</h5>

                                                </div>
                                                <div class="col-md-3 geo">


                                                </div>
                                          </div>
                                          <table class="table">
                                                <thead>
                                                      <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Nom</th>
                                                      </tr>
                                                </thead>
                                                <tbody>
                                                      <tr>
                                                            <th scope="row">1</th>
                                                            <td>Pamiers City</td>
                                                      </tr>
                                                </tbody>
                                          </table>
                                    </div>
                              </div>
                        </div>
                        <!-- ///////// FIN DIVs colonne nouvelle vente et colonne ajouter/supprimer/géomarketing  /////////////////-->

                  </section>
                  <section>


                        </sction>


                        <!-- Good luck -->

                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
                              crossorigin="anonymous"></script>
      </body>

</html>