<div style="width:100px; margin:0 auto; text-align:center">
<div style="float:left; cursor:pointer; color:blue;" id="buttonTableauDeBordPlier">Plier</div>
<div style="float:left; cursor:pointer; color:blue;" id="buttonTableauDeBordDeplier">Déplier</div>
</div>
<!--CHART--> 
<div style="clear:both"></div>
<!--PlaceHolder-->
<section id="TableauDeBord" class="row text-center placeholders">
    <h3><b>Tableaux de bord</b></h3>
    
    <div class="col-6 col-sm-3 placeholder containerGraph">
        <img src="data:image/gif;base64,R0lGODlhAQABAIABAAJ12AAAACwAAAAAAQABAAACAkQBADs=" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
            <h4>Répartition des monnaies</h4>
            <span class="text-muted"></span>
    </div>
    <div class="col-6 col-sm-3 placeholder containerGraph" >
        <img src="data:image/gif;base64,R0lGODlhAQABAIABAADcgwAAACwAAAAAAQABAAACAkQBADs=" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
            <h4>Cours du BTC</h4>
            <span class="text-muted"></span>
    </div>

</section>
<div style="float:left"><h3><b>Mon compte</b></h3></div>
<div style="float:left; margin-top:5px;margin-left:10px; cursor:pointer; color:blue;" id="Tous">Voir tous</div>
<div style="clear:both" id="totalCompte"></div>
<div style="text-align:center;margin:0 auto" id="pause"><h4>Actualisation automatique</h4></div>
<div class="table-responsive">
    <table id="tableHome" class="table table-striped table-bordered">
        <thead>
            <tr id="titreTableauHome">
                <th>Crypto</th>
                <th>Mon volume</th>
                <th>Mon volume disponible</th>
                <th>Volume total</th>
                <th>Dernier prix connu</th>
                <th>Dernier prix d'achat</th>
                <th>BTC / USDT / USD</th>
                <th>Bénéfices ou pertes</th>
            </tr>

        </thead>
        <tbody id="tableau">

        </tbody>
    </table>
</div>


<script type="text/javascript">
    var monfiltre = "";
    var inter;
    $(document).ready(function () {
        $("#pause").hide();
         $("#TableauDeBord").slideUp();
         $("#buttonTableauDeBordPlier").hide();
        
         $("#titreTableauHome").click(function(){
            clearInterval(inter);
            $("#pause").show();
         });
         $("#pause").click(function(){
            clearInterval(inter);
            $("#pause").hide();
            if(monfiltre==="tous")
            {
                 $('#ajax-loading').show();
        clearInterval(inter);
        inter = setInterval(function () {
       $('#ajax-loading').show();
        $.ajax({
            url: "ajax/t_home.php",
            type : 'POST',
                        data: { 
                        'filtre':'tous'
                        },
            dataType: "json",
            success: function (data) {
                
                $("#totalCompte").html(data[1]);
                $('#tableHome').DataTable().destroy();
                $("#tableau").html(data[0]);
               $('#ajax-loading').hide();
            },
            error: function () {
                $('#ajax-loading').hide();
                $("#tableau").html('<tr><td class="erreurData" colspan="8"><b>Une erreur est survenue lors du chargement des données</b></td></tr>');
            }
        });
    }, 20000);
            }else{
                 $('#ajax-loading').show();
           var inter = setInterval(function () {
    monfiltre = "";
    $('#ajax-loading').show();
        $.ajax({
            url: "ajax/t_home.php",
            dataType: "json",
            success: function (data) {
                $("#totalCompte").html(data[1]);
                $('#tableHome').DataTable().destroy();
                 $("#tableau").html(data[0]);
                $('#tableHome').DataTable({
                    "info": false,
                    "paging": false,
                    "searching": true,
                });
                $('#ajax-loading').hide();
            },
            error: function () {
                $('#ajax-loading').hide();
                $("#tableau").html('<tr><td class="erreurData" colspan="8"><b>Une erreur est survenue lors du chargement des données</b></td></tr>');
            }
        });
    }, 20000);
            }
         });
        
        $("#buttonTableauDeBordPlier").click(function(){
  $("#TableauDeBord").slideUp();
    $("#buttonTableauDeBordDeplier").show();
  $("#buttonTableauDeBordPlier").hide();
});
$("#buttonTableauDeBordDeplier").click(function(){
  $("#TableauDeBord").slideDown();
  $("#buttonTableauDeBordDeplier").hide();
  $("#buttonTableauDeBordPlier").show();
});
        $( "#Tous" ).click(function() {
        $('#ajax-loading').show();
        clearInterval(inter);
        inter = setInterval(function () {
            monfiltre = "tous";
       $('#ajax-loading').show();
        $.ajax({
            url: "ajax/t_home.php",
            type : 'POST',
                        data: { 
                        'filtre':'tous'
                        },
            dataType: "json",
            success: function (data) {
                
                $("#totalCompte").html(data[1]);
                $('#tableHome').DataTable().destroy();
                $("#tableau").html(data[0]);
                $('#tableHome').DataTable({
                    "info": false,
                    "paging": false,
                    "searching": true,
                    "stateSave": true
                });
               $('#ajax-loading').hide();
            },
            error: function () {
                $('#ajax-loading').hide();
                $("#tableau").html('<tr><td class="erreurData" colspan="8"><b>Une erreur est survenue lors du chargement des données</b></td></tr>');
            }
        });
    }, 20000);
});
        $(".nav li a").removeClass("active");
        $('#lien_home').addClass('active');
        $('#ajax-loading').show();
        monfiltre = "perso";
        $.ajax({
            url: "ajax/t_home.php",
            dataType: "json",
            success: function (data) {
                $('#ajax-loading').hide();
                $("#totalCompte").html(data[1]);
                $("#tableau").html(data[0]);
                $('#tableHome').DataTable({
                    "info": false,
                    "paging": false,
                   "searching": true,
                   "stateSave": true
                });
                
            },
            error: function () {
                $('#ajax-loading').hide();
                $("#tableau").html('<tr><td class="erreurData" colspan="8";><b>Une erreur est survenue lors du chargement des données</b></td></tr>');
            }
        });
    });

    inter = setInterval(function () {
    monfiltre = "";
    $('#ajax-loading').show();
        $.ajax({
            url: "ajax/t_home.php",
            dataType: "json",
            success: function (data) {
                $("#totalCompte").html(data[1]);
                $('#tableHome').DataTable().destroy();
                 $("#tableau").html(data[0]);
                $('#tableHome').DataTable({
                    "info": false,
                    "paging": false,
                    "searching": true,
                    "stateSave": true
                });
                $('#ajax-loading').hide();
            },
            error: function () {
                $('#ajax-loading').hide();
                $("#tableau").html('<tr><td class="erreurData" colspan="8"><b>Une erreur est survenue lors du chargement des données</b></td></tr>');
            }
        });
    }, 20000);

</script>
