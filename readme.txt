=== Really Simple Calendar ===
Contributors: sandman.jp
Donate link: https://paypal.me/sandmanjp
Tags: calendar, rsc, rs calendar, really simple calendar, event calendar
Requires at least: 6.7
Tested up to: 6.8
Stable tag: 0.4.13
Requires PHP: 8.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The single calendar for multi purpos with multi users. Unlimited events and responsive display. 

== Description ==

Really simple calendar is one of most easy managing calendar. This plugin provide mutil purpos view, unlimited events, customized css, simple managiment of users and more functions by extensions.

== View Settings ==

There are three types of display methods during calendar time. Each item can be locked so that others cannot change it.

= Calendar Type =

<dl>
<dt>Monthly</dt>
<dd>Calendar tables are divided for each month.</dd>
<dt>Weekly</dt>
<dd>Calendar for weekly display. Separate tables for each week.</dd>
<dt>Monthly</dt>
<dd>A calendar that displays each day in a single table. Intended for use in confined spaces such as widgets. Available in horizontal and vertical formats.</dd>
</dl>

= Start From =

<dl>
<dt>Previous</dt>
<dd>CUsed to start the display from the past month, week, or day; must be less than 0.</dd>
<dt>Current (Month / Week/ Day)</dt>
<dd>The current month, week, and day shall be the starting point.</dd>
<dt>Next (Month / Week/ Day)</dt>
<dd>The following month, week, or day shall be the starting point.</dd>
<dt>Today</dt>
<dd>The date is displayed starting from today. Past dates are not displayed, even if they are in monthly or weekly format.</dd>
<dt>Date</dt>
<dd>The specific date is the end date. Unlike the other options, this is an absolute value.</dd>
</dl>

= Display Period =

<dl>
<dt>Period</dt>
<dd>Sets the number of months, weeks, and days to display; must be greater than or equal to 0, in which case only one calendar is displayed.</dd>
<dt>Date</dt>
<dd>The specific date is the start date. Unlike the other options, this is an absolute value.</dd>
</dl>

= Start day of week =

Sets which day of the week the calendar starts. For “today,” the relative value is the day of the week that the calendar starts on.


== Configration ==

Permissions can be set for each extension and user role. Only administrators can change these settings.

= Extensions Setting =

<dl>
<dt>Advanced Event Field</dt>
<dd>Switch fields on the Event Settings screen.</dd>
</dl>

= Manage Capability =

<dl>
<dt>Full</dt>
<dd>All the same privileges as the administrator./dd>
<dt>Manage</dt>
<dd>Same as “all” except for the lock function.</dd>
<dt>Edit</dt>
<dd>Only short code generation is possible./dd>
<dt>Read</dt>
<dd>Simply view the calendar on the administration page./dd>
</dl>


= Links =
* [Website](https://note.com/sandman_jp/m/m0657144dd8fb) Japanese descriptions.

= Milestone =

- Custom Label extension. (Comming soon)
- Advanced CSS editor. (Someday)
- Booking system. (Someday)

== Installation ==

1. Upload the entire `really-simple-calendar` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the **Plugins** screen (**Plugins > Installed Plugins**).

== Changelog ==

= 0.4.13 =
- Implement "Multi Calendar" extension.

= 0.3.12 =
- Fixed a critical bug.

= 0.3.11 =
- Change the name in the "Contributors" list.
- Move inline JS to the footer.
- More sanitizing and escaping for echo text.
- Check nonce for all posted variables.
- Change namespace name.
- Change prefix text for variables and functions.
- Add deny conditions for direct access.

= 0.3.10 =
- Added more Japanese language.
- Fixed a bug that caused it to appear in other custom post types.
- Reorganized functions.
- Fixed other minor bugs.
- Fix JS and CSS.
- Change classname.
- Review of some codes.

= 0.3.7 =
First release.

== Upgrade Notice ==
