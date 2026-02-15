<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Visitor Logger - Welcome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="centered-body">

    <div class="container centered">
        <div class="mainPage">
            <h1>Apartment Visitor Logger</h1>
            <p class="subtitle">Select access level</p>
            
            <div class="toggle-group">
                <a href="visitor.php" class="toggle-btn">Visitor</a>
                
                <button class="toggle-btn" id="adminButton" onclick="toggleAdminForm()">
                    Admin
                </button>
            </div>
        </div>

        <div id="adminLogin" class="card login-card hidden">
            <h3>Admin Login</h3>
            <form action="admin.php" method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" placeholder="Enter username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>
                <button type="submit" class="btn-primary">Login as Admin</button>
            </form>
        </div>
    </div>

    <script>
        function toggleAdminForm() {
            const loginCard = document.getElementById('adminLogin');
            const adminBtn = document.getElementById('adminButton');
            
            loginCard.classList.toggle('hidden');
            
            if (!loginCard.classList.contains('hidden')) {
                adminBtn.style.border = '2.5px solid #0b0b15';
            } else {
                adminBtn.style.border = '1.5px solid #d1d5db';
            }
        }
    </script>

</body>
</html>