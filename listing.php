<?php
//index.php
$connect =new mysqli("localhost", "root", "", "product");
$message = '';

if(isset($_POST["upload"]))
{
 if($_FILES['product_file']['name'])
 {
  $filename = explode(".", $_FILES['product_file']['name']);
  if(end($filename) == "csv")
  {
   $handle = fopen($_FILES['product_file']['tmp_name'], "r");
   while($data = fgetcsv($handle))
   {

    $product_id = mysqli_real_escape_string($connect, $data[0]);
    $product_category = mysqli_real_escape_string($connect, $data[1]);  
    $product_name = mysqli_real_escape_string($connect, $data[2]);
    $product_price = mysqli_real_escape_string($connect, $data[3]);
    $sku = mysqli_real_escape_string($connect, $data[4]);

    print_r($data);
    echo $product_category;
    if($product_category == "product_category" || $product_name == "product_name" || $product_price == "product_price" || $sku == "sku")
    {
      ////
    }
    else
    { 
      $querys = "SELECT * FROM daily_product WHERE id='$product_id'";
      $result=mysqli_query($connect,$querys);
      $count = mysqli_num_rows($result);
      echo "<br>";
      echo $count;
      if($count !=0)
      {
        $query = "UPDATE daily_product SET product_category = '$product_category', product_name = '$product_name',product_price = '$product_price' WHERE id = '$product_id'";
       mysqli_query($connect, $query);

      }
      else
      {
        $query = "INSERT INTO daily_product(product_category,product_name,product_price,sku,status) 
        VALUES('$product_category','$product_name','$product_price','$sku','0')  ";
        mysqli_query($connect, $query);
        
      }
     
    }
    // print_r($data);

   }  

   fclose($handle);
   
//    header("location:listing.php");
  }
  else
  {
   $message = '<label class="text-danger">Please Select CSV File only</label>';
  }
 }
 else
 {
  $message = '<label class="text-danger">Please Select File</label>';
 }
}

if(isset($_GET["updation"]))
{
 $message = '<label class="text-success">Product Updation Done</label>';
}

$query = "SELECT * FROM daily_product";
$result = mysqli_query($connect, $query);
?>
<!DOCTYPE html>
<html>
 <head>
  <title>Update Mysql Database through Upload CSV File using PHP</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <br />
  <div class="container">
   <h2 align="center">Update Mysql Database through Upload CSV File using PHP</a></h2>
   <br />
   <form method="post" enctype='multipart/form-data'>
    <p><label>Please Select File(Only CSV Formate)</label>
    <input type="file" name="product_file" /></p>
    <br />
    <input type="submit" name="upload" class="btn btn-info" value="Upload" />
   </form>
   <br />
   <?php echo $message; ?>
   <h3 align="center">Deals of the Day</h3>
   <br />
   <div class="table-responsive">
    <table class="table table-bordered table-striped">
     <tr>
      <th>Category</th>
      <th>Product Name</th>
      <th>Product Price</th>
     </tr>
     <?php
     while($row = mysqli_fetch_array($result))
     {
      echo '
      <tr>
       <td>'.$row["product_category"].'</td>
       <td>'.$row["product_name"].'</td>
       <td>'.$row["product_price"].'</td>
      </tr>
      ';
     }
     ?>
    </table>
   </div>
  </div>
 </body>
</html>