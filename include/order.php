          <h3><b>Mes transactions</b></h3>
            <div id="totalCompte" style='font-family: Comics; color:green; font-size: 1.5em'></div>
          <div class="table-responsive">
           <table id="tableHome" class="table table-striped table-bordered" width="100%" cellspacing="0" style="text-align:center">
              <thead>
                <tr id="titreTableauOrdres">
                    <th>Crypto</th>
                    <th>Block ID</th>
                    <th>Transaction ID</th>
                    <th>Date</th>
                    <th>Prix / U</th>
                    <th>Quantité</th>
                    <th>Prix total</th>
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
<?php
if(isset($_GET['monnaie']) && !empty($_GET['monnaie'])) {
$cle = $_GET['monnaie'];
}
else
{
    $cle = 'ALL';
}
?>
  
    <script type="text/javascript">
            $(document).ready(function() {
                $(".nav li a").removeClass("active"); 
                $('#lien_order').addClass('active');
                    $.post('ajax/t_order.php',{
                        cleOrder: '<?php echo $cle;?>',
        },
                
        function(data){ 
                 $("#tableau_ordres").html(data);
                    $('#tableHome').DataTable({
                    "paging": false,
                    "searching": false,
                    "info": false 
                }); 

                
               
                
              
            });     
            });    
	</script>
