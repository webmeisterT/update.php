<?php
include "config/db_connect.php";

  // initializing the error variables
  $title = $email = $ingredients = '';
  $errrors = array('email' => '', 'title' => '', 'ingredients'=>'');

  //VALIDATING AND UPDATING
if (isset($_POST['update'])) {
    $id_up = $_POST['id_update'];
   $email =$_POST['email'];
   $title =$_POST['title'];
   $ingredients = $_POST['ingredients'];

    //validating the form 

    //Checking for emptiness (For email input)
    if (empty($_POST['email'])) {
        $errrors['email'] = "Enter your email here! <br/>";
    }else{
        // echo htmlspecialchars($_POST['email']);
        // if(filter_var($email, FILTER_VALIDATE_EMAIL)){

        // }else{echo "not a valid email";}

    //Checking for Valid Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
           $errrors['email'] = "Enter a valid email address!";
        }
    }

     //Checking for emptiness (For title input)

     if (empty($_POST['title'])) {
        $errrors['title'] = "A title is required! <br/>";
     }else{
     //Checking if title contains only valid characters

     if (!preg_match('/^[a-zA-Z\s]+$/', $title)) {
         $errrors['title'] = "Enter only letters and spaces!";
        }
     }

     //Checking for emptiness (For Ingredients input)

     if (empty($_POST['ingredients'])) {
        $errrors['ingredients'] = "Enter atleast one ingredient <br/>";
     }else{
         //echo htmlspecialchars($ingredients);

         //Checking if ingredients contains only valid characters
         if (!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/', $ingredients)) {
             $errrors['ingredients'] = "Ingredients should be letters with space only!";
         }
     }

        //array_filter
     if (array_filter($errrors)) {
         //do nothing since it has been catered for by the codes(checking for emptiness and validation) above

     }else{
         //Now allows the user's input to be stored in the database after all the validation is done
         $email = mysqli_real_escape_string($conn, $_POST['email']);
         $title = mysqli_real_escape_string($conn, $_POST['title']);
         $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);

         //create sql
         $sql3 = "UPDATE pizzas SET title='$title', email='$email', ingredients='$ingredients' WHERE id = $id_up";

         //Saving into the database and checking if it works
         if (mysqli_query($conn, $sql3)) {
             //Successful connection
             header('location: index.php');
         }else{
             //error
             echo "Query Error:" . mysqli_error($conn);
         }

     }//ends array filter else statement

}//end of POST check

//codes to check GET request id parameter
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
  
    $sql1 = "SELECT * FROM pizzas WHERE id = $id";

    $result1 = mysqli_query($conn, $sql1);

    $pizza1 = mysqli_fetch_assoc($result1);
    mysqli_free_result($result1);
    mysqli_close($conn);

 }

 include './templates/header.php';

?>

    <section class="container text-success" style="margin-top:2rem; font-size:1.4rem;">
        <div class="h3 text-center">Update Your Pizza Here</div>

        <form action="update.php" method="post" class="bg-light" style=" max-width: 660px; margin: 1.5rem auto; padding: 4rem;">

            <div class="form-group">

                <div class="form-group">
                    <input type="hidden" name="id_update" value="<?php echo $pizza1['id'];?>" class="form-control">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="emal" class="form-control" value="<?php echo $pizza1['email']; ?>" required>
                </div>
                <!-- The below div displays $errors -->
                <div class="text-danger text-center" style="font-size:1.1rem;">
                    <?php echo $errrors['email'] ;?>
                        <br/>
                </div>
                <!-- end of displays $errors -->

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="titl" class="form-control" value="<?php echo $pizza1['title'] ;?>" required>
                </div>
                <!-- The below div displays $errors -->
                <div class="text-danger" style="font-size:1.1rem;">
                    <?php echo $errrors['title']; ?>
                        <br/>
                </div>
                <!-- end of displays $errors -->

                <div class="form-group">
                    <label for="ingredient">Ingredients</label>
                    <input type="text" name="ingredients" id="ingre" class="form-control" value="<?php echo $pizza1['ingredients'] ;?>" required>
                </div>
                <!-- The below div displays $errors -->
                <div class="text-danger text-center" style="font-size:1.1rem;">
                    <?php echo $errrors['ingredients'] ;?>
                        <br/>
                        <br/>
                </div>
                <!-- end of displays $errors -->

                <div class="form-group text-center">
                    <button type="submit" name="update" class="btn btn-primary btn-lg">Update</button>
                </div>
        </form>

    </section>

<?php
include('templates/footer.php');
?>
