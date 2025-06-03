<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}
?>
<header id="header">
    <div class="container-header">
        <div class="flex header-ul">
            <nav>
                <ul class="primary-navigation">
                    <img src="../img/logoforum2.png" alt="Logo">
                    <div class="options">
                        <li><a href="todas_denuncias.php">Home</a></li>
                        <li>
                            <div class="action" onclick="actionToggle();">
                                <span>
                                    <?php 
                                    if (isset($_SESSION['nome'])) {
                                        echo htmlspecialchars($_SESSION['nome']);
                                    } else {
                                        echo "Usuário";
                                    }
                                    ?>
                                </span>
                                <ul>
                                    <li><a href="minhas_denuncias.php">Minhas denúncias</a></li>
                                    <li><a href="nova_denuncia.php">Nova denúncia</a></li>
                                    <li><a href="../config/logout.php">Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </div>
                </ul>
            </nav>
        </div>    
    </div>
</header>
<script src="../js/header.js"></script>

<?php
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit;
}
?>
