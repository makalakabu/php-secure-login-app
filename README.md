# Secure Login and Evaluation System

This project is a secure web application built using PHP and XAMPP for a fictional antique appraisal business. It was developed as part of the Computer Security module for the BSc degree at the University of Sussex.

The application allows users to register, log in, and submit antique object evaluations. It emphasizes modern security best practices including CSRF protection, XSS prevention, secure password handling, two-factor authentication, file upload restrictions, and role-based access control.

## Project Report

A detailed technical report is included in this repository:
- Project_Report.docx

The report outlines:
- Design objectives
- Security requirements
- Feature breakdown (user registration, login, password recovery, evaluation requests, admin panel)
- Code-level security measures (CSRF, XSS, SQL injection prevention, etc.)
- Database design
- Implementation limitations and further improvement suggestions

## Features

### User Registration
- Registration form collects name, email, phone number, and password
- Input validation and sanitization
- CSRF token implementation
- Google reCAPTCHA integration to prevent bots
- Email-based two-factor authentication (2FA)
- Passwords hashed with bcrypt
- Security questions stored in a separate table for password recovery

### Secure Login
- Input sanitization and SQL injection prevention (via PDO)
- 2FA via one-time PIN sent to email
- Rate-limiting and account lockout after repeated login failures
- Ban system after multiple invalid 2FA attempts

### Password Strength & Recovery
- Frontend and backend password strength checks
- Secure password reset via security question and 2FA
- Access restrictions to reset route unless verified

### Evaluation Request Form
- Available only to logged-in users
- Allows object description, contact preference (email or phone), and image upload
- Strict file validation (type & size restrictions)
- Sanitized database insertion

### Admin Evaluation Listing
- Admin-only page for viewing all user-submitted evaluations
- Joins multiple tables to show user and request details
- Output sanitization for every database field


## Technologies Used

- PHP
- XAMPP (Apache, MySQL, PHP)
- HTML, CSS, JavaScript
- Google reCAPTCHA
- PDO (for secure DB access)
- bcrypt (password hashing)


## Security Highlights

- Cross-Site Scripting (XSS) prevention
- Cross-Site Request Forgery (CSRF) token implementation
- CAPTCHA integration
- SQL injection prevention with prepared statements
- Rate-limiting and brute-force protection
- Secure file upload handling
- Role-based access control (admin panel)

