<?php
include './templates/header.php';
include 'config/db_connect.php';
?>
<?php
//create an errors array that gets updated along the line .thus the need for empty array at the initial start.
$title = $email = $ingredients = '';
$errors = array('email' => '', 'title' => '', 'ingredients' => '');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $title = $_POST['title'];
    $ingredients = $_POST['ingredients'];

    // echo htmlspecialchars prevent running of XSS attack on your script("Email : $email Title: $title Ingredients: $ingredients ");
    // php form validation
    //check email
    //php has built filters that enable you check the format of an input . it only has specific ones and thus the need for regex in validating some format
    if (empty($_POST['email'])) {
        $errors['email'] = "An email is required <br/>";
    } else {
        // echo htmlspecialchars($_POST['email']);
        // if(filter_var($email,FILTER_VALIDATE_EMAIL)){

        // }else{
        //     echo "not a valid email";
        // }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email must be valid email address <br/>";
        }
    }
    //check title
    if (empty($_POST['title'])) {
        $errors['title'] = "A title is required <br/>";
    } else {
        //preg_match is used to match regex patterns with the intended result. The regex says i am starting (we use ^ to start and  +$ to end as well)  and just like less than sign we have forward  slash to enclose it and must be in quote.\s is used to denote any space any length.! is used because we need it only when it is false.
        //regex
        if (!preg_match('/^[a-zA-Z\s]+$/', $title)) {
            $errors['title'] = "Title must be letters and spaces only";
        }
    }
    //check ingredients

    if (empty($_POST['ingredients'])) {
        $errors['ingredients'] = "At least one Ingredient is required <br/>";
    } else {
        // echo htmlspecialchars($_POST['ingredients']);
        if (!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/', $ingredients)) {
            $errors['ingredients'] = "Ingredients must be comma seperated list";
        }
    }
    //array_filter
    if (array_filter($errors)) {
        //do nothing since there is something handling it already.
    } else {
        //just like htmlspecialchar .. we have something to prepare us for sql injection
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        // $tb1 = mysqli_real_escape_string($conn, $_POST['tb1']);
        // $tb2 = mysqli_real_escape_string($conn, $_POST['tb2']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);

        //create sql 
        $sql = "INSERT INTO pizzas(title,email,ingredients) VALUES('$title','$email','$ingredients')";

        //save to db and check if it works
        if (mysqli_query($conn, $sql)) {
            //success
            header('location: index.php');
        } else {
            //error
            echo "Query Error:" . mysqli_error($conn);
        }
        //happens only if there is no error


    }
} // end of POST check however this doesn't check to see if it is a correct email format or ingredient separated by commas.
?>
<section class="container green-text">
    <h4 class="center">Add a Pizza</h4>
    <form class="white" action="add.php" method="POST">
        <label for="email">Your Email:</label>
        <!-- Always use server side and client side validation after using required -->
        <input type="text" name="email" placeholder="your email" value="<?php echo htmlspecialchars($email); ?>" required>
        <div class="red-text">
            <!-- display errors right after the input field -->
            <?php echo $errors['email']; ?>
        </div>
        <label for="title">Pizza Title:</label>
        <input type="text" name="title" placeholder="Pizza Title" value="<?php echo htmlspecialchars($title); ?>" required>
        <div class="red-text">
            <?php echo $errors['title']; ?>
        </div>
        <label for="">Ingredients (comma Separated):</label>
        <input type="text" name="ingredients" placeholder="Your Ingredients" value="<?php echo htmlspecialchars($ingredients); ?>" required>
        <div class="red-text">
            <?php echo $errors['ingredients']; ?>
        </div>
        <div class="center">
            <button type="submit" class="btn brand z-depth-0" name="submit" value="submit">Submit</button>
        </div>
    </form>
</section>
<?php
include './templates/footer.php';

?>