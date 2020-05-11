# Global

This is where all of your "default" or "base" styling is setup. If your design includes a style guide, much of that style guide styling will end up here.

This is the default look and feel of the site's text, links, form inputs, headings, etc. 

**Styles in `3.components` are loaded after this, and as such, are intended to override what's here safely.** For that reason, it is important that selectors in Global maintain as simple as possible to create no greater specificity challenges at the Component level than necessary.