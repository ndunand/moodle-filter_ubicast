# Ad-hoc filter for UbiCast Nudgis Atto plugin

This filter alleviates the problem which arises when atto_ubicast tries to inject an `<iframe>` HTML tag directly into Atto. This works for teachers and "trusted users" but not all users. Optionally, the atto_ubicast cans insert an `<img>` HTML tag instead. This tag's usage is unrestricted and is then turned into an iframe at the rendering stage by this plugin.


## Installation

You can either use the official files available in moodle.org or install the filter manually.

To install the filter manually:

```
cd moodle/filter
git clone https://github.com/UbiCastTeam/moodle-filter_ubicast ubicast
```
