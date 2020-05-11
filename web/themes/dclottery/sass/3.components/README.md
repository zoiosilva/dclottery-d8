# Components

What's a component?  Just about anything can be a component. Components can and often do contain other components.

In this organizational structure, a component is anything that overrides global styling, targets a specific area, control, region, feature, widget, etc.

In most cases, the name of a component file should mirror the top level selector in that file. So a component that styles .`paragraph--accordion` should live in a file called `_paragraph--accordion.scss`.

Feel free to group components into subfolders as makes sense. It's likely you will have `node`, `paragraph`, and `block` folders, for example.

## Layout Components
Generally, a component should only be concerned about styling itself and it should only worry about the layout of child components and elements, if any.

You can and should have some components that are just for layout. For example, header and footer regions have a `.l-header` and `.l-footer` just for laying out the elements within the header and footer.

Classes that are purely for layout, meaning they are just containers or only deal in the positioning of child elements should be prefixed `.l-`.

## Decor Components
Try to keep purely decorative components inside mixins in `1.utilities/decor/` and prefix related classes with `.decor--` (for more information, see the readme.md inside of the `decor/` folder).

