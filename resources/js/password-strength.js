document.addEventListener("DOMContentLoaded", function () {
    const passwordInputs = document.querySelectorAll(
        ".password-strength-meter"
    );

    passwordInputs.forEach((input) => {
        const strengthBar = document.createElement("div");
        strengthBar.className =
            "h-1 w-full mt-1 rounded-full bg-gray-200 overflow-hidden transition-all duration-300";

        const strengthFill = document.createElement("div");
        strengthFill.className =
            "h-full w-0 transition-all duration-300 ease-out";
        strengthBar.appendChild(strengthFill);

        const strengthText = document.createElement("div");
        strengthText.className =
            "text-xs mt-1 text-right transition-all duration-300";

        input.parentNode.insertBefore(strengthBar, input.nextSibling);
        input.parentNode.insertBefore(strengthText, strengthBar.nextSibling);

        input.addEventListener("input", function () {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            updateStrengthIndicator(strengthFill, strengthText, strength);
        });
    });

    function calculatePasswordStrength(password) {
        if (!password) return 0;

        let strength = 0;

        // Length check
        if (password.length >= 8) strength += 20;
        if (password.length >= 12) strength += 10;

        // Complexity checks
        if (/[a-z]/.test(password)) strength += 15; // lowercase
        if (/[A-Z]/.test(password)) strength += 15; // uppercase
        if (/[0-9]/.test(password)) strength += 15; // numbers
        if (/[^A-Za-z0-9]/.test(password)) strength += 25; // special chars

        return Math.min(100, strength);
    }

    function updateStrengthIndicator(fill, text, strength) {
        // Update width
        fill.style.width = strength + "%";

        // Clear previous classes except for height and transition properties
        fill.className = "h-full transition-all duration-300 ease-out";

        // Update color based on strength
        if (strength < 30) {
            fill.classList.add("bg-red-500");
            text.textContent = "Weak";
            text.className = "text-xs mt-1 text-right text-red-500 transition-all duration-300";
        } else if (strength < 60) {
            fill.classList.add("bg-yellow-500");
            text.textContent = "Fair";
            text.className = "text-xs mt-1 text-right text-yellow-600 transition-all duration-300";
        } else if (strength < 80) {
            fill.classList.add("bg-blue-500");
            text.textContent = "Good";
            text.className = "text-xs mt-1 text-right text-blue-600 transition-all duration-300";
        } else {
            fill.classList.add("bg-gradient-to-r", "from-green-400", "to-green-600", "animate-gradient", "bg-200%");
            text.textContent = "Strong";
            text.className = "text-xs mt-1 text-right text-green-600 transition-all duration-300";
        }
    }
});
