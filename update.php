<?php
include "config/db_connect.php";
$title   = $email = $ingredients = '';
$errrors = array(
    'email' => '',
    'title' => '',
    'ingredients' => ''
);
if (isset($_POST['update'])) {
    $id_up       = $_POST['id_update'];
    $email       = $_POST['email'];
    $title       = $_POST['title'];
    $ingredients = $_POST['ingredients'];
    if (empty($_POST['email'])) {
        $errrors['email'] = "Enter your email here! <br/>";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errrors['email'] = "Enter a valid email address!";
        }
    }
    if (empty($_POST['title'])) {
        $errrors['title'] = "A title is required! <br/>";
    } else {
        if (!preg_match('/^[a-zA-Z\s]+$/', $title)) {
            $errrors['title'] = "Enter only letters and spaces!";
        }
    }
    if (empty($_POST['ingredients'])) {
        $errrors['ingredients'] = "Enter atleast one ingredient <br/>";
    } else {
        if (!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/', $ingredients)) {
            $errrors['ingredients'] = "Ingredients should be letters with space only!";
        }
    }
    if (array_filter($errrors)) {
    } else {
        $email       = mysqli_real_escape_string($conn, $_POST['email']);
        $title       = mysqli_real_escape_string($conn, $_POST['title']);
        $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
        $sql3        = "UPDATE pizzas SET title='$title', email='$email', ingredients='$ingredients' WHERE id = $id_up";
        if (mysqli_query($conn, $sql3)) {
            header('location: index.php');
        } else {
            echo "Query Error:" . mysqli_error($conn);
        }
    }
}
if (isset($_GET['id'])) {
    $id      = mysqli_real_escape_string($conn, $_GET['id']);
    $sql1    = "SELECT * FROM pizzas WHERE id = $id";
    $result1 = mysqli_query($conn, $sql1);
    $pizza1  = mysqli_fetch_assoc($result1);
    mysqli_free_result($result1);
    mysqli_close($conn);
}
include './templates/header.php';
?>
    <section class="container text-success" style="margin-top:2rem;font-size:1.4rem">
        <div class="text-center h3">Update Your Pizza Here</div>
        <form action="update.php" class="bg-light" method="post" style="max-width:660px;margin:1.5rem auto;padding:4rem">
            <div class="form-group">
                <div class="form-group">
                    <input class="form-control" name="id_update" value="<?php echo $pizza1['id']; ?>" type="hidden">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" name="email" value="<?php echo $pizza1['email']; ?>" id="emal" required type="email">
                </div>
                <div class="text-center text-danger" style="font-size:1.1rem">
                    <?php echo $errrors['email']; ?>
                        <br>
                </div>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input class="form-control" name="title" value="<?php echo $pizza1['title']; ?>" id="titl" required>
                </div>
                <div class="text-danger" style="font-size:1.1rem">
                    <?php echo $errrors['title']; ?>
                        <br>
                </div>
                <div class="form-group">
                    <label for="ingredient">Ingredients</label>
                    <input class="form-control" name="ingredients" value="<?php echo $pizza1['ingredients']; ?>" id="ingre" required>
                </div>
                <div class="text-center text-danger" style="font-size:1.1rem">
                    <?php echo $errrors['ingredients']; ?>
                        <br>
                        <br>
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-lg btn-primary" name="update" type="submit">Update</button>
                </div>
        </form>
    </section>
    <?php include('templates/footer.php'); ?>
