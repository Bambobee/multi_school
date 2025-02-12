<div class="header">
            <?php
    $stmt = $conn->prepare("SELECT * FROM schools WHERE id = :school_id");
    $stmt->execute(['school_id' => $school_id]);

    // Fetch the results as an associative array
    $school = $stmt->fetch(PDO::FETCH_ASSOC);

    // Ensure variables are set before using them
    $school_badge = isset($school['school_badge']) ? $school['school_badge'] : 'default_badge.png';
    $slogan = isset($school['slogun']) ? $school['slogun'] : 'SCH';
    ?>
            <div class="header-left">
                <a href="index" class="logo">
                    <div style="display: flex;">
                        <img src="../<?php echo htmlspecialchars($school_badge); ?>" alt="School Badge" />
                        <h3><?php echo htmlspecialchars($slogan); ?></h3>
                    </div>
                </a>
                <a href="index" class="logo logo-small">
                    <img src="../assets/img/logo-small.png" alt="Logo" width="30" height="30" />
                </a>
            </div>
            <div class="menu-toggle">
                <a href="javascript:void(0);" id="toggle_btn">
                    <i class="fas fa-bars"></i>
                </a>
            </div>

            <a class="mobile_btn" id="mobile_btn">
                <i class="fas fa-bars"></i>
            </a>

            <ul class="nav user-menu">
                <li class="nav-item zoom-screen me-2">
                    <a href="#" class="nav-link header-nav-list win-maximize">
                        <img src="../assets/img/icons/header-icon-04.svg" alt="" />
                    </a>
                </li>

                <li class="nav-item dropdown has-arrow new-user-menus">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle" src="../assets/img/profiles/avatar-01.jpg" width="31"
                                alt="Soeng Souy" />
                            <div class="user-text">
                                <?php 
                                $user_name = $_SESSION['username']; 
                                ?>
                                <h6><?=$user_name; ?></h6>
                                <p class="text-muted mb-0">Administrator</p>
                            </div>
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="../assets/img/profiles/avatar-01.jpg" alt="User Image"
                                    class="avatar-img rounded-circle" />
                            </div>
                            <div class="user-text">
                                <h6><?=$user_name; ?></h6>
                                <p class="text-muted mb-0">Administrator</p>
                            </div>
                        </div>
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#password" href="profile">Change Password</a>
                        <a class="dropdown-item" href="logout">Logout</a>
                    </div>
                </li>
            </ul>
        </div>