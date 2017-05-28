<h3><b>Tableaux de bord</b></h3>
<!--CHART--> 

<!--PlaceHolder-->
<section class="row text-center placeholders">
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
    $(document).ready(function () {
        $( "#Tous" ).click(function() {
            alert("click ok");
            clearInterval(inter);
  setInterval(function () {
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
                    "info": false
                });
               
            },
            error: function () {
                $("#tableau").html('<tr><td class="erreurData" colspan="8"><b>Une erreur est survenue lors du chargement des données</b></td></tr>');
            }
        });
    }, 10000);
});
        $(".nav li a").removeClass("active");
        $('#lien_home').addClass('active');

        $.ajax({
            url: "ajax/t_home.php",
            dataType: "json",
            success: function (data) {
                
                $("#totalCompte").html(data[1]);
                $("#tableau").html(data[0]);
                $('#tableHome').DataTable({
                    "info": false
                });
                
            },
            error: function () {
                $("#tableau").html('<tr><td class="erreurData" colspan="8";><b>Une erreur est survenue lors du chargement des données</b></td></tr>');
            }
        });
    });

    var inter = setInterval(function () {
        $.ajax({
            url: "ajax/t_home.php",
            dataType: "json",
            success: function (data) {
                $("#totalCompte").html(data[1]);
                $('#tableHome').DataTable().destroy();
                 $("#tableau").html(data[0]);
                $('#tableHome').DataTable({
                    "info": false
                });
            },
            error: function () {
                $("#tableau").html('<tr><td class="erreurData" colspan="8"><b>Une erreur est survenue lors du chargement des données</b></td></tr>');
            }
        });
    }, 10000);

</script>
