<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/smoothness/jquery-ui.css"> <!-- Include jQuery UI CSS -->
    <link rel="stylesheet" type="text/css" href="./resources/cityfindr.css">
    <link rel="stylesheet" type="text/css" href="./resources/organization.css">
    <title>Create Event</title>
</head>
<body>
    <nav>
        <a href="./home.php">Home</a>
        <a href="./events.php">Events</a>
        <a href="./organizations.php">Organizations</a>
        <a href="./profile.php">Profile</a>
        <a href="./settings.php">Settings</a>
    </nav>
    
    <div>
        <br>
        <form id="preferenceForm" action="./resources/php/preferenceUpload.php" method="POST" name="preferences">
            <input type="text" id="preferences" name="preferences" placeholder="Type in your preferences here">
            <input type="submit" id="submit" value="Add Preference">
        </form>
    </div>
    
    <div>
        <br>
        <form id="makeEvent" action="./resources/php/submit_event.php" method="POST" enctype="multipart/form-data"> <!-- Added enctype for file uploads -->
            <label for="eventName">Event Name:</label>
            <input type="text" id="eventName" name="name" required>

            <label for="eventTimeOfEvent">Time of Event:</label>
            <input type="datetime-local" id="eventTimeOfEvent" name="timeOfEvent" required>

            <label for="eventAddressOne">Address One:</label>
            <input type="text" id="eventAddressOne" name="addressOne" required>

            <label for="eventAddressTwo">Address Two: (optional):</label>
            <input type="text" id="eventAddressTwo" name="addressTwo">

            <label for="eventCity">City:</label>
            <input type="text" id="eventCity" name="city" required>

            <label for="eventState">State:</label>
            <input type="text" id="eventState" name="state">

            <label for="eventPostalCode">Postal Code:</label>
            <input type="text" id="eventPostalCode" name="postalCode" required>

            <label for="eventCountry">Country:</label>
            <input type="text" id="eventCountry" name="country" required>

            <label for="eventDescription">Description:</label>
            <textarea id="eventDescription" name="description" required></textarea>

            <label for="eventPhoneNumber">Phone Number:</label>
            <input type="tel" id="eventPhoneNumber" name="phoneNumber">

            <label for="eventEmail">Email:</label>
            <input type="email" id="eventEmail" name="email" required>

            <label for="eventTags">Tags:</label>
            <input type="text" id="tagsInput" placeholder="Add tags..." />
            <select id="eventTags" name="tags[]" multiple style="display: none;"></select>
            <div id="selectedEventTags"></div>

            <button type="submit">Create Event</button>
        </form>
    </div>

    <div>
        <br>
        <form id="makeOrganization" action="./resources/php/submit_org.php" method="POST" enctype="multipart/form-data"> <!-- Added enctype for file uploads -->
            <label for="orgName">Organization Name:</label>
            <input type="text" id="orgName" name="name" required>

            <label for="orgTimeOfMeetings">Time of Meetings:</label>
            <input type="datetime-local" id="orgTimeOfMeetings" name="timeOfMeetings" required>

            <label for="orgAddressOne">Address One:</label>
            <input type="text" id="orgAddressOne" name="addressOne" required>

            <label for="orgAddressTwo">Address Two: (optional):</label>
            <input type="text" id="orgAddressTwo" name="addressTwo">

            <label for="orgCity">City:</label>
            <input type="text" id="orgCity" name="city" required>

            <label for="orgState">State:</label>
            <input type="text" id="orgState" name="state">

            <label for="orgPostalCode">Postal Code:</label>
            <input type="text" id="orgPostalCode" name="postalCode" required>

            <label for="orgCountry">Country:</label>
            <input type="text" id="orgCountry" name="country" required>

            <label for="orgDescription">Description:</label>
            <textarea id="orgDescription" name="description" required></textarea>

            <label for="orgPhoneNumber">Phone Number:</label>
            <input type="tel" id="orgPhoneNumber" name="phoneNumber">

            <label for="orgEmail">Email:</label>
            <input type="email" id="orgEmail" name="email" required>

            <label for="orgTags">Tags:</label>
            <input type="text" id="tagsInput2" placeholder="Add tags..." />
            <select id="orgTags" name="tags[]" multiple style="display: none;"></select>
            <div id="selectedOrgTags"></div>

            <button type="submit">Create Organization</button>
        </form>
    </div>
    
    <script>
        $(function() {
            $.ajax({
                url: './resources/php/fetchTags.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Autocomplete for event tags
                    $("#tagsInput").autocomplete({
                        source: data,
                        minLength: 1,
                        delay: 300,
                        select: function(event, ui) {
                            addTag(ui.item.value, 'event');
                            this.value = ""; // Clear the input after selection
                            return false;
                        }
                    });

                    // Autocomplete for organization tags
                    $("#tagsInput2").autocomplete({
                        source: data,
                        minLength: 1,
                        delay: 300,
                        select: function(event, ui) {
                            addTag(ui.item.value, 'organization');
                            this.value = ""; // Clear the input after selection
                            return false;
                        }
                    });
                },
                error: function() {
                    console.error("Error fetching tags.");
                }
            });

            function addTag(tag, formType) {
                const tagContainer = formType === 'event' ? '#selectedEventTags' : '#selectedOrgTags';
                const hiddenSelect = formType === 'event' ? '#eventTags' : '#orgTags';

                // Check if the tag already exists
                if (tag && $(hiddenSelect + ' option[value="' + tag + '"]').length === 0) {
                    const tagElement = $(`<span class="tag">${tag}<span class="remove-tag">×</span></span>`);
                    $(tagContainer).append(tagElement);
                    $(hiddenSelect).append(`<option value="${tag}">${tag}</option>`); // Add to the hidden select
                    tagElement.find('.remove-tag').on('click', function() {
                        $(this).parent().remove();
                        $(hiddenSelect + ' option[value="' + tag + '"]').remove(); // Remove from the hidden select
                    });
                }
            }

            // Handle form submission for event
            $('#makeEvent').on('submit', function(event) {
                if ($('#selectedEventTags .tag').length === 0) {
                    alert("Please add at least one tag for the event.");
                    event.preventDefault(); // Prevent form submission
                }
            });

            // Handle form submission for organization
            $('#makeOrganization').on('submit', function(event) {
                if ($('#selectedOrgTags .tag').length === 0) {
                    alert("Please add at least one tag for the organization.");
                    event.preventDefault(); // Prevent form submission
                }
            });
        });
    </script>
    <script src="./resources/profile/profile.js"></script>
</body>
</html>