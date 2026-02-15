<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitor Check In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php 
    $pageTitle = "Visitor Check In"; 
    include 'header.php'; 
?>

<div class="container wide">

    <div class="form-wrapper">

        <div class="action-row">
            <button class="check-out-btn check-out-large">Check Out</button>
        </div>

        <div class="card form-card">
            <h3>Check In</h3>
            <p class="form-subtitle">Please provide the following</p>

            <form action="process.php" method="POST">
                <div class="grid-2">
                    <div class="input-group">
                        <label>Your Name*</label>
                        <input type="text" name="visitor_name" required>
                    </div>

                    <div class="input-group">
                        <label>Phone Number*</label>
                        <input type="text" name="phone" required>
                    </div>

                    <div class="input-group">
                        <label>Apartment Number*</label>
                        <select name="apt" required>
                            <option value=""></option>
                            <option value="A-101">A-101</option>
                            <option value="A-102">A-102</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Purpose of Visit*</label>
                        <select name="purpose" required>
                            <option value=""></option>
                            <option value="Family/Friend">Family/Friend</option>
                            <option value="Delivery">Delivery</option>
                        </select>
                    </div>
                </div>

                <div class="input-group">
                    <label>Your Email*</label>
                    <input type="email" name="email" required>
                </div>

                <button type="submit" class="btn-primary">Check In</button>
            </form>
        </div>

    </div>

</div>


</body>
</html>
