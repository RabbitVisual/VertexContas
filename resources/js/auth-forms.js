/**
 * Auth Forms Helpers
 * Handles interactions for Login, Register and Recovery forms.
 */

document.addEventListener('DOMContentLoaded', () => {
    // Password visibility toggle helper
    const setupPasswordToggle = (buttonId, inputId) => {
        const button = document.getElementById(buttonId);
        const input = document.getElementById(inputId);

        if (button && input) {
            button.addEventListener('click', () => {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';

                // Update icons if they exist in a standard structure
                const icon = button.querySelector('i');
                if (icon) {
                    if (isPassword) {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            });
        }
    };

    // Initialize common toggles
    setupPasswordToggle('toggle-password', 'password');
    setupPasswordToggle('toggle-password-confirmation', 'password_confirmation');
});
