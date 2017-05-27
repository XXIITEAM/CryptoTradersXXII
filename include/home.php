          <h3><b>Mon compte</b></h3>
            <div id="totalCompte" style='font-family: Comics; color:green; font-size: 1.5em'></div>
          <div class="table-responsive">
            <table id="tableHome" class="table table-striped table-bordered" width="100%" cellspacing="0" style="text-align:center">
              <thead>
                <tr>
                    <th style="text-align:center; cursor:pointer;">Crypto</th>
                    <th style="text-align:center; cursor:pointer;">Mon volume</th>
                    <th style="text-align:center; cursor:pointer;">Mon volume disponible</th>
                    <th style="text-align:center; cursor:pointer;">Volume total</th>
                    <th style="text-align:center; cursor:pointer;">Dernier prix connu</th>
                    <th style="text-align:center; cursor:pointer;">Dernier prix d'achat</th>
                    <th style="text-align:center; cursor:pointer;">BTC / USDT / USD</th>
                    <th style="text-align:center; cursor:pointer;">Bénéfices ou pertes</th>
                </tr>
                
              </thead>
              <tbody id="tableau">
                  
              </tbody>
            </table>
          </div>

  
    <script type="text/javascript">
            $(document).ready(function() {
                $(".nav li a").removeClass("active"); 
                $('#lien_home').addClass('active');

                    $.ajax({
                url: "ajax/t_home.php",
                dataType : "json",
                success: function(data){
                    $("#tableau").html(data[0]);
                    $("#totalCompte").html(data[1]);
                    $('#tableHome').DataTable({
                    "paging": false,
                    "searching": false,
                    "info": false 
                }); 
                },
                error:function(){
                    $("#tableau").html('<tr><td style="color:red; font-family: Comics; font-size: 1.5em" colspan="7"><b>Une erreur est survenue lors du charghement des données</b></td></tr>');
                }
            });     
            });
            
             setInterval(function(){
                    $.ajax({
                url: "ajax/t_home.php",
                dataType : "json",
                success: function(data){
                  $("#tableau").html(data[0]);
                  $("#totalCompte").html(data[1]);
                },
                error:function(){
                    $("#tableau").html('<tr><td style="color:red; font-family: Comics; font-size: 1.5em" colspan="7"><b>Une erreur est survenue lors du charghement des données</b></td></tr>');
                }
            });
                },10000); 
                      
	</script>
