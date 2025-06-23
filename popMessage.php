<?php

add_hook('ClientAreaHeadOutput', 1, function($vars) {
    // Only show the popup to logged-in users
    if (isset($_SESSION['uid'])) {

        // Set this variable to true to show popup every time the user logs in, or false to show only once
        $showEveryTime = false; // Change this to true if you want the popup to show every time

        // Set the URL of the background image for the popup
        $backgroundImageUrl = "https://www.yourdomain.com/images/popup-bg.jpg"; // Add the URL of your background image

        // Option to turn background image on or off
        $bgImageEnabled = false; // Set this to false to disable the background image, true to enable it

        // Set the maximum number of times the popup can show per day
        $popupLimitPerDay = 0; // Change this to the number of times you want the popup to show each day

        // HTML content for the popup message (you can customize this part)
        $popupContent = '
            <div style="text-align: center; color: black;">
                <h2>Welcome to Nivohost!</h2>
                <p>We are glad to have you with us. Explore our hosting services.</p>
                <img src="https://www.yourdomain.com/images/welcome.jpg" alt="Welcome" style="max-width: 100%; height: auto;">
                <p>Contact our support for any help!</p>
            </div>
        ';

        return "
        <style>
            /* Default styling for the popup modal */
            .popup-modal {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                padding: 20px;
                box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
                z-index: 10000;
                text-align: center;
                width: 80%; /* Responsive width for smaller screens */
                max-width: 400px; /* Maximum width for larger screens */
                border-radius: 10px;
                box-sizing: border-box;
                " . ($bgImageEnabled && !empty($backgroundImageUrl) ? "background-image: url('$backgroundImageUrl'); background-size: cover; background-position: center; background-repeat: no-repeat;" : "background-color: #fff;") . "
            }

            /* Responsive styling for different devices */
            @media (min-width: 768px) {
                .popup-modal {
                    width: 60%; /* For tablets */
                    max-width: 600px;
                }
            }

            @media (min-width: 1024px) {
                .popup-modal {
                    width: 40%; /* For laptops and desktops */
                    max-width: 800px;
                }
            }

            .popup-close-btn {
                margin-top: 10px;
                padding: 10px 20px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .popup-close-btn:hover {
                background-color: #0056b3;
            }
        </style>

        <script type='text/javascript'>
            document.addEventListener('DOMContentLoaded', function() {
                var showEveryTime = " . ($showEveryTime ? 'true' : 'false') . ";
                var popupLimitPerDay = $popupLimitPerDay;

                // Get the current date
                var today = new Date().toISOString().split('T')[0];

                // Retrieve popup data from localStorage
                var popupData = JSON.parse(localStorage.getItem('popupData')) || { count: 0, date: today };

                // Reset count if the stored date is not today
                if (popupData.date !== today) {
                    popupData.count = 0;
                    popupData.date = today;
                }

                // Check if popup has reached the limit for today
                if (showEveryTime || popupData.count < popupLimitPerDay) {
                    // Create the popup modal
                    var modal = document.createElement('div');
                    modal.classList.add('popup-modal'); // Apply the class for styling

                    // Insert custom HTML content
                    modal.innerHTML = `$popupContent`;

                    // Add a close button
                    var closeButton = document.createElement('button');
                    closeButton.textContent = 'Close';
                    closeButton.classList.add('popup-close-btn'); // Apply button styling
                    closeButton.onclick = function() {
                        document.body.removeChild(modal);

                        // Increment the popup count and update localStorage
                        popupData.count++;
                        localStorage.setItem('popupData', JSON.stringify(popupData));
                    };
                    modal.appendChild(closeButton);

                    // Append the modal to the body
                    document.body.appendChild(modal);
                }
            });
        </script>
        ";
    }
});