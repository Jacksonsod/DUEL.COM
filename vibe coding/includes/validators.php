<?php
function isSecurePassword($password, $firstname, $lastname, $student_id) {
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    if (!preg_match($pattern, $password)) {
        return "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
    }

    $lower = strtolower($password);
    if (strpos($lower, strtolower($firstname)) !== false ||
        strpos($lower, strtolower($lastname)) !== false ||
        strpos($lower, strtolower($student_id)) !== false) {
        return "Password should not contain your name or student ID.";
    }

    return true;
}
