=== BuddyActivist User Location ===
Contributors: BuddyActivist
Tags: buddypress, location, map, osm, leaflet, xprofile
Requires at least: 5.8
Tested up to: 6.7
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds a localized BuddyPress xProfile address field, geocoding, user map on OSM, and optional auto-join to nearest BuddyPress group.

== Description ==

BuddyActivist User Location adds a localized address field to BuddyPress xProfile, geocodes user locations, and provides a global user map based on OpenStreetMap and Leaflet. Optionally, if BuddyActivist Group Location is active, users can be automatically subscribed to the nearest BuddyPress group.

== Installation ==

1. Upload the plugin to `/wp-content/plugins/`.
2. Activate the plugin.
3. Configure settings under “BuddyActivist User Location”.
4. Add the xProfile field type in BuddyPress profile fields.
5. Use `[baul_user_map]` shortcode to display the user map.

== Frequently Asked Questions ==

= Does it require BuddyActivist Group Location? =
No. The plugin works standalone for user geolocation and map. If BuddyActivist Group Location is active, you can optionally auto-join users to the nearest group.

= How do I show the user map? =
Create a page and add the shortcode `[baul_user_map]`.

== Changelog ==

= 1.0.0 =
* Initial release.
