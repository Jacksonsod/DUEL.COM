<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        footer {
            text-align: center;
            padding: 20px;
            background: #343a40;
            color: white;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h3 class="text-center mb-4"><i class="fa-solid fa-user-plus"></i> Student Registration</h3>
    <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="firstname" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="lastname" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Student ID</label>
            <input type="text" name="id_number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Course</label>
            <select name="course_id" class="form-select" required>
                <option value="">Select Course</option>
                <!-- PHP: Populate from DB -->
            </select>
        </div>
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Upload JPG Picture (≤ 50KB)</label>
            <input type="file" name="picture" class="form-control" accept=".jpg" required>
        </div>
        <button type="submit" name="register" class="btn btn-primary w-100">
            <i class="fa-solid fa-paper-plane"></i> Register
        </button>
    </form>
</div>

<footer>
    <p>&copy; 2025 Voting System | Follow us:
        <i class="fab fa-facebook mx-1"></i>
        <i class="fab fa-instagram mx-1"></i>
        <i class="fab fa-linkedin mx-1"></i>
        <i class="fab fa-whatsapp mx-1"></i>
    </p>
</footer>

<script>
    function validateForm() {
        const fname = document.querySelector('[name="firstname"]').value;
        const lname = document.querySelector('[name="lastname"]').value;
        const pass = document.querySelector('[name="password"]').value;
        const pic = document.querySelector('[name="picture"]').files[0];

        if (pass === fname || pass === lname) {
            alert("Password must not match your name.");
            return false;
        }

        if (pic && (pic.type !== "image/jpeg" || pic.size > 51200)) {
            alert("Only JPG files under 50KB are allowed.");
            return false;
        }

        return true;
    }
</script>

</body>
</html>
