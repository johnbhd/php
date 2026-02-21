<style>
html, body {
    height: 100%;
    margin: 0;
}
nav {
    display: flex;
    font-size: 1rem;
    gap: 20px;
    background-color: #1E293B;
    color: #E2E8F0;
    padding: 20px;
}

nav img {
    width: 70px;
    height: 70px;
}

.usernav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 0 20px;
}

aside {
    display: flex;
    flex-direction: column;
    padding: 30px 30px;
    min-height: 100vh; 
    gap: 30px;
    background-color: #111827;
}

aside a {
    display: grid;
    grid-template-columns: 30px 1fr;
    align-items: center;
    justify-content: start;
    text-decoration: none;
    color: #CBD5E1;
    cursor: pointer;
    font-size: 1.3rem;
    gap: 10px;
}

aside a:hover {
    color: #FFFFFF;
}

main {
    display: flex;
    min-height: 100vh;
}
.nav-title {
    display: flex;
    align-items: center;
    gap: 20px;
}
.nav-title img {
    width: 70px;
    height: 70px;
}
.nav-title h4 {
    font-size: 2rem;
}
.nav-user {
    display: flex;
    flex-direction: column;
    font-size: 1.3rem;
}
.mobile-nav {
    display: none;
}

@media screen and (max-width:  768px) {
    .mobile-nav {
        display: flex ;
        align-items: center;
        width: 100%;
        gap: 20px;
        padding: 0 10px;
    }
    #hamburger {
        font-size: 1.5rem;
    }
    .nav-title {
        gap: 15px;
    }
    .nav-title img {
        width: 40px;
        height: 40px;
    }
    .mobile-nav h4 {
        font-size: 1.1rem;
    }
    .usernav {
        display: none;
    }
    #sidebar {
        display: none;
    }
}
</style>
  <nav>
        <div class="usernav">
            <div class="nav-title">
                <img src="../assets/img/aulogo.png" alt="">
                <h4><?php echo $_SESSION['title'];?></h4>
            </div>
            <?php
                if (isset($_SESSION['username'])) {
            ?>
                <div class="nav-user" >
                    <p>Welcome, <?php echo $_SESSION['username']?></p>
                    <p>Account Type: <?php echo $_SESSION['usertype']?> </p>
                </div>
            
            <?php } else {
                    header('location: ../login.php');
                }
            ?>
        </div>
        <div class="mobile-nav">
            <i class="fa-solid fa-bars" id="hamburger"></i>
            <div class="nav-title">
                <img src="../assets/img/aulogo.png" alt="">
                <h4><?php echo $_SESSION['title'];?></h4>
            </div>
        </div>
    </nav>
    <main>
        <aside id="sidebar">

            <?php if ($_SESSION['usertype'] == 'ADMINISTRATOR') { ?>
                
                <a href="../userManagement/accounts-management.php">
                    <i class="fa-regular fa-user"></i>    
                    <span>Accounts</span>
                </a>
                <a href="../equipmentManagement/equipment-management.php">
                    <i class="fa-solid fa-computer"></i>
                    <span>Equipments</span>
                </a>
                <a href="../ticket-management/ticket-management-admin.php">
                    <i class="fa-solid fa-ticket"></i>
                    <span>Tickets</span>
                </a>
            <?php } ?>

            <?php if ($_SESSION['usertype'] == 'TECHNICAL') { ?>
                <a href="../equipmentManagement/equipment-management.php">
                    <i class="fa-solid fa-computer"></i>
                    <span>Equipments</span>
                </a>
                <a href="../ticket-management/ticket-management-technical.php">
                    <i class="fa-solid fa-ticket"></i>
                    <span>Tickets</span>
                </a>
            <?php } ?>

            <?php if ($_SESSION['usertype'] == 'USER') { ?>
                <a href="../equipmentManagement/equipment-management.php">
                    <i class="fa-solid fa-computer"></i>
                    <span>Equipments</span>
                </a>
                <a href="../ticket-management/ticket-management-user.php">
                    <i class="fa-solid fa-ticket"></i>
                    <span>Tickets</span>
                </a>
            <?php } ?>

            <a href="../auth/logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>

        </aside>

<script>
    const hamburger = document.getElementById("hamburger");
    const sidebar = document.getElementById("sidebar");

    hamburger.addEventListener("click", (e) => {
        if (sidebar.style.display == "none") {
            sidebar.style.display = "flex"
        } else {
            sidebar.style.display = "none"    
        }
    })
</script>