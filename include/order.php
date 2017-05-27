          <h3><b>Mes transactions</b></h3>
            <div id="totalCompte" style='font-family: Comics; color:green; font-size: 1.5em'></div>
          <div class="table-responsive">
           <table id="tableHome" class="table table-striped table-bordered" width="100%" cellspacing="0" style="text-align:center">
              <thead>
                <tr>
                    <th>Crypto</th>
                    <th>GlobalTradeID</th>
                    <th>TradeID</th>
                    <th>Date</th>
                    <th>Rate</th>
                    <th>Montant</th>
                    <th>total</th>
                    <th>Frais</th>
                    <th>Numéro</th>
                    <th>Type</th>
                    <th>Catégorie</th>
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
                $('#tableHome').DataTable({
                    "paging": false,
                    "searching": false,
                    "info": false 
                }); 
                },
                error:function(erreur){
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
