# Ad-hoc filter for UbiCast Nudgis Atto plugin

This filter alleviates the problem which arises when `atto_ubicast` tries to inject an `<iframe>` HTML tag directly into text fields. This works for teachers and "trusted users" but not all users and not in all locations. Optionally, the `atto_ubicast` cans insert an `<img>` HTML tag instead. This tag's usage is unrestricted and is then turned into an iframe at the rendering stage by this plugin.


## Installation

You can either use the official files available in moodle.org or install the filter manually.

The moodle.org link is:

https://moodle.org/plugins/filter_ubicast

To install the filter manually:

```bash
cd moodle/filter
git clone https://github.com/UbiCastTeam/moodle-filter_ubicast ubicast
```


## Activation

First, you need to turn on the filter in the `/admin/filters.php` page of your Moodle instance.

Then, you need to enable the `<img>` tag insertion in the `atto_ubicast` plugin. This can be done in the `atto_ubicast` plugin settings: `/admin/settings.php?section=atto_ubicast_settings`.
