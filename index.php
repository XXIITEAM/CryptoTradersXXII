<?php
  include'include/head.php'; 
  ?>  
<?php
if(isset($_GET['page'])){
  switch($_GET['page']) {
      case 'home':
          include('include/home.php');
          break;
      case 'order':
          include('include/order.php');
          break;
      case 'polo':
          include('include/polo.php');
          break;
      default:
          include('include/home.php');
          
  }
  } else {
    include'include/home.php';   
  }
  
?>  

<?php
  include'include/foot.php'; 