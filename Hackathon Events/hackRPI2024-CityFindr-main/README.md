# hackRPI2024-CityFindr

**hackRPI2024-CityFindr** is a web application designed to simplify the process of finding activities and organizations in your city.

## Usage

### index.php
- Provides login and signup functionality.
  - Users can create an account by clicking the **Signup** button and log in using the **Login** button.
  - Users must navigate through this page to access any other pages and perform actions on them.
  - **Note:** Security features are still under development, so some pages may be accessible without proper authentication. However, users will be unable to complete any tasks on those pages.

### profile.php
- Contains multiple forms:
  - **Preference Form:**
    - Users can add their preferences by clicking the **Add Preference** button. They can either type in their own preference or choose from an autocomplete selection.
  - **Make Event Form:**
    - Users can create their own events on the platform.
    - New events will have null ratings until users provide feedback.
  - **Make Organization Form:**
    - Users can create their own organizations on the platform.
    - New organizations will also have null ratings until rated by users.

### settings.php
- Contains multiple forms that handle:
  - Changing the password
  - Updating location
  - Deleting the account
  - **Note:** Backend functionality for these features is currently incomplete.

## Unfinished Work
- Adding social features to events and organizations, including:
  - Dedicated event/organization pages
  - Allowing users to sign up for events or organizations
  - Implementing a rating system for user submissions
-Putting backend functionality for settings.php

## Future Enhancements
- Implement secure password hashing, session management, and access control to protect user accounts and sensitive data.
-Optimize the web application for mobile devices to ensure a seamless user experience across different screen sizes.
-Implement a feature for users to submit feedback on their experiences, which can be used to improve the platform further.

## Sources
- [jQuery UI Autocomplete](https://jqueryui.com/autocomplete/) - For autocomplete functionality
- [Blackbox.ai](https://www.blackbox.ai/) - For debugging purposes