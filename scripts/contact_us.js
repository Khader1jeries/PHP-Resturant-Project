    // Front-end validation using JavaScript
    document.getElementById('contactForm').addEventListener('submit', function(event) {
        let name = document.getElementById('name').value;
        let phone = document.getElementById('phone').value;
        let email = document.getElementById('email').value;
        let message = document.getElementById('message').value;
        let errors = [];

        // Validate name (Ensure it's not empty and contains only letters and spaces)
        if (!name.match(/^[a-zA-Z ]*$/)) {
            errors.push("Name can only contain letters and spaces.");
        }

        // Validate phone number (Ensure it's exactly 10 digits and only numbers)
        if (!phone.match(/^\d{10}$/)) {
            errors.push("Phone number must be exactly 10 digits (only numbers).");
        }

        // Validate email (Ensure it's a valid email format)
        if (!email.match(/^\S+@\S+\.\S+$/)) {
            errors.push("Invalid email format.");
        }

        // Validate message (Ensure it's not empty)
        if (message.trim() === "") {
            errors.push("Message is required.");
        }

        // If there are errors, prevent form submission and alert the user
        if (errors.length > 0) {
            event.preventDefault();
            alert(errors.join('\n'));
        }
    });
