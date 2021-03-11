# Ad-hoc filter for UbiCast Atto plugin

This filter alleviates the problem which arises when atto_ubicast tries to inject an <iframe> HTML tag directly into Atto. This works for teachers and "trusted users" but not all users. Starting in the next release of atto_ubicast, an <img> HTML tag will be injected instead. This tag's usage is unrestricted and is then turned into an iframe at the rendering stage by this plugin.

