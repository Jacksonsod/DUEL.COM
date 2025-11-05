<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Voting System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <!-- Left-side navigation -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_candidates.php">Manage Candidates</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_positions.php">Manage Positions</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_courses.php">Manage Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_parties.php">Manage Parties</a></li>
                    <li class="nav-item"><a class="nav-link" href="results.php">Results</a></li>
                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'student'): ?>
                    <li class="nav-item"><a class="nav-link" href="vote.php">Vote</a></li>
                    <li class="nav-item"><a class="nav-link" href="results.php">Results</a></li>
                <?php endif; ?>
            </ul>

            <!-- Right-side profile dropdown -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../uploads/<?= htmlspecialchars($_SESSION['profile_pic'] ?? 'default.jpg') ?>" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;" alt="Profile">
                            <span><?= htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>
