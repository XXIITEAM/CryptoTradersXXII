          <h3><b>Mon compte</b></h3>
            <div id="totalCompte" style='font-family: Comics; color:green; font-size: 1.5em'></div>
          <div class="table-responsive">
            <table class="table table-sortable">
              <thead>
                <tr>
                    <th>Crypto</th>
                    <th>Mon volume</th>
                    <th>Mon volume dispo</th>
                    <th>Volume total</th>
                    <th>BTC / USDT / USD</th>
                    <th>Dernier achat</th>
                    <th>Bénéfice</th>
                </tr>
                
              </thead>
              <tbody id="tableau_ordres">
                  
              </tbody>
            </table>
          </div>

  
    <script type="text/javascript">
            $(document).ready(function() {
                $(".nav li a").removeClass("active"); 
                $('#lien_order').addClass('active');
                    $.ajax({
                url: "ajax/t_order.php",
                dataType : "json",
                success: function(data){
                $("#tableau_ordres").html(data[0]) ;
                alert(data[0]);
                },
                error:function(erreur){
                    //alert(erreur);
                    alert(data[0]);
                }
            });     
            });
            
             setInterval(function(){
                    $.ajax({
                url: "ajax/t_order.php",
                dataType : "json",
                success: function(data){
                  $("#tableau_ordres").html(data[0]) ;
                },
                error:function(erreur){
                    //alert(erreur);
                }
            });
                },10000);    
            
        
	</script>
