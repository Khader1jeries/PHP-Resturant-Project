
                function toggleForgotPasswordForm() {
                    // Toggle the visibility of the form when the text is clicked
                    const form = document.getElementById('forgotPasswordForm');
                    form.style.display = form.style.display === 'block' ? 'none' : 'block';

                    // Toggle the text of the link
                    const text = document.getElementById('forgotPasswordText');
                    text.innerHTML = form.style.display === 'block' ? 'Hide Reset Password Form' : 'Forgot your password?';
                }
