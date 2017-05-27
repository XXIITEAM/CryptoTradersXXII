          <h3><b>Mon compte</b></h3>
            <div id="totalCompte" style='font-family: Comics; color:green; font-size: 1.5em'></div>
          <div class="table-responsive">
            <table class="table table-sortable" style="text-align:center">
              <thead>
                <tr>
                    <th style="text-align:center">Crypto</th>
                    <th style="text-align:center">Mon volume</th>
                    <th style="text-align:center">Mon volume dispo</th>
                    <th style="text-align:center">Volume total</th>
                    <th style="text-align:center">Dernier prix connu</th>
                    <th style="text-align:center">Dernier prix d'achat</th>
                    <th style="text-align:center">BTC / USDT / USD</th>
                    <th style="text-align:center">Bénéfices</th>
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
                  $("#tableau").html(data[0]) ;
                  $("#totalCompte").html(data[1]);
                },
                error:function(erreur){
                    //alert("Une erreur est survenue);
                }
            });     
            });
            
             setInterval(function(){
                    $.ajax({
                url: "ajax/t_home.php",
                dataType : "json",
                success: function(data){
                  $("#tableau").html(data[0]) ;
                  $("#totalCompte").html(data[1]);
                },
                error:function(erreur){
                    //alert(erreur);
                }
            });
                },5000);    
            
        
	</script>
