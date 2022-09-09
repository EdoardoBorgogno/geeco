<link rel="stylesheet" href="<?php echo $base ?>Features/Common/Components/UserNavbar/css/userNavbar.css">

<nav class="navbar navbar-light navbar-expand-md py-3">

    <div class="container">

        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="<?php echo $base ?>Assets/images/brand/geeco.png" alt="geeco_logo" class="logo">
        </a>

        <button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1">
            <span class="visually-hidden">Toggle navigation</span>
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo $base ?>dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base ?>shopinfo">Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base ?>myreport">Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base ?>myanalitycs">Analitycs</a>
                </li>
            </ul>

            <div class="two-button">
                <button class="btn btn-primary" type="button">Geeco Shop</button>
                <button class="btn btn-outline" type="button" onclick="logout()">Logout</button>
            </div>

        </div>

    </div>

</nav>

<script src="<?php echo $base ?>Assets/bootstrap/js/bootstrap.min.js"></script>
<script>

    // Logout function
    function logout() {
        document.cookie = "UusJvalUs=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        window.location.href = "<?php echo $base ?>userLogin";
    }
    
</script>