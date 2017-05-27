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
              <tbody id="tableau">
                  
              </tbody>
            </table>
          </div>

  
    <script type="text/javascript">
            $(document).ready(function() {
                $(".nav li a").removeClass("active"); 
                $('#lien_order').addClass('active');
                    $.ajax({
                url: "traitement.php",
                dataType : "json",
                success: function(data){
                  $("#tableau").html(data[0]) ;
                  $("#totalCompte").html(data[1]);
                },
                error:function(erreur){
                    alert(erreur);
                }
            });     
            });
            
             setInterval(function(){
                    $.ajax({
                url: "traitement.php",
                dataType : "json",
                success: function(data){
                  $("#tableau").html(data[0]) ;
                  $("#totalCompte").html(data[1]);
                },
                error:function(erreur){
                    alert(erreur);
                }
            });
                },5000);    
            
        
	</script>
