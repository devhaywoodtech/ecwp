=== Monthly Events Calendar ===
Contributors: haywoodtech
Tags: events calendar, events, calendar, Event, organizer, schedule
Requires at least: 6.0
Tested up to: 7.1.2
Stable tag: 1.5
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Monthly Events Calendar for WordPress is a powerful and user-friendly plugin that allows you to effortlessly manage and showcase events on your WordPress website.

== Description ==

The Monthly Events Calendar for WordPress is a feature-rich plugin that enhances your WordPress website by providing a comprehensive and visually appealing way to manage, display, and promote events. This plugin is designed to help individuals, businesses, organizations, and communities efficiently organize and showcase events such as conferences, seminars, workshops, meetings, performances, webinars, social gatherings, and much more.

[Check the documentation of Events Calendar's](https://wpmonthlyevents.com/)

This plugin used the REST API to display the events. 

🚀 Use the shortcode **[wp_monthly_events]** to display it on the page.

## 📅 Key features of an Monthly Events Calendar for WordPress typically include: ##

### ✨ Event Creation and Management ###
Easily create, edit, and manage events through an intuitive user interface. You can set event details such as title, date, time, location, description, categories, tags, and featured images.

### ✨ Interactive Calendar Display ###
Display events in a user-friendly calendar format, allowing visitors to view events by month, day, or even in an agenda-style list. Users can navigate through dates and click on events to access more information.

### ✨ Customizable Event Views ###
Users can choose different views based on their preferences, like monthly grid view, day schedule view, or a list view. This customization provides a tailored experience for your audience.

### ✨ Venue Management ###
Maintain a database of venues or locations where events are held. Users can easily access address details.

### ✨ Event Categorization and Tags ###
Categorize events into different types (e.g., workshops, conferences, concerts) and provide tagging options so users can find events of interest more easily.

### ✨ Responsive Design ###
Ensuring that the calendar is mobile-friendly and adjusts its layout to different screen sizes for a consistent user experience across devices.

Overall, an Events Calendar for WordPress empowers website owners to effectively communicate their event schedules, engage their audience, and enhance the user experience by providing a centralized hub for event-related information. It simplifies the management of events and encourages user participation, making it an essential tool for any WordPress site aiming to promote and organize events.

* [Monthly Events Calendar's GitHub repository](https://github.com/devhaywoodtech/ecwp/) - Includes all the uncompressed files.


== Installation ==

This section describes how to install the plugin and get it working. 

e.g.

1. Upload `ecwp` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin named "Monthly Events Calendar" through the 'Plugins' menu in WordPress.
3. Navigate to WordPress Admin end "Pages => Add New" in the admin end.
4. Add the shortcode **[wp_monthly_events]** to display the events calendar.
5. Hurrah! You have configured the plugin successfully.


== Frequently Asked Questions ==

= How do I find events on the calendar? =

You can browse events by using the calendar's navigation buttons or selecting different views (Month, Day, List). You can also use the search options to find specific types of events.

= Does it work without REST API? =

No. You need to enable the REST API if some of the security plugins denied that.

== Screenshots ==

1. Screenshot for the Monthly View in the Events Calendar.
2. Screenshot for the Daily View in the Events Calendar.
3. Screenshot for the List View in the Events Calendar.

== Changelog ==

= 1.5 =
* New: Compatible with Loco Translate Plugin.
* Fix: Text domain now matches the plugin slug, so translations and language packs load correctly.
* Compatibility: Tested up to WordPress 7.0; PHP requirement set to 7.4.

= 1.4 =
* New: Gutenberg block for inserting the events calendar (shortcode still supported).
* Fix: Text domain now matches the plugin slug, so translations and language packs load correctly.
* Compatibility: Tested up to WordPress 7.0; PHP requirement set to 7.4.

= 1.3 =
* Fix: Month and Upcoming views now resolve event dates reliably across timezones; past events no longer appear under Upcoming, and events now show in the correct month.
* Fix: Calendar navigation now advances to the correct year when moving from December to January (and back).
* Security: Restricted the settings REST endpoint to administrators and added input sanitization on save.
* Compatibility: Tested up to WordPress 6.8.

= 1.0.0 =
* Initial Release of the Plugin

== Upgrade Notice ==

= 1.3 =
Security and bug-fix release. Update is recommended for all users.

= 1.0.0 =
Initial Release of the Plugin