# Purge Queuer File URLs

The Purge Queuer File URLs module integrates with the Purge framework to
provide external cache invalidation of files. This module ensures that any time
a fieldable entity is updated, files referenced from fields on that entity are
queued for invalidation. Additionally, the image styles of any discovered files
are also queued for invalidation.

## Key Features

- **Automatic Invalidation**: Automatically queues files and their image styles
  for invalidation whenever a file entity is updated.
- **Image Style Flush Handling**: The Image Styles Queuer plugin queues
  invalidations on image style flush, including when an image style is updated
  and saved.
- **Customizable Invalidation Expressions**: Allows site developers to control
  how files are invalidated based on the requirements of their external caching
  layer.

## Invalidations

Out of the box, the invalidation expression for files is the relative URL of
the file. A Purger must be configured to handle invalidations of the
appropriate type (e.g., relativeurl, absoluteurl, etc.). This module provides
several suitable invalidation type plugins beyond those included with the base
Purge module to support likely use-cases relevant to file invalidation.

## Extending

The method by which invalidation expressions are generated can be modified or
extended by interacting with the ExpressionStrategy plugin system included with
this module. This system allows for fine-grained control over how files and
their derivatives are invalidated.

### ExpressionStrategy Plugin System

The ExpressionStrategy plugin system is the core mechanism for customizing
invalidation expressions. You can create additional plugins if you need to
generate custom expressions for specific use cases, or you can use the standard
Drupal hook system to alter the plugins already available.

### Customizing Invalidation Expressions

If you need to generate custom invalidation expressions, you can create a new
plugin class that implements one of the expression strategy interfaces provided
by this module. You will need to create a custom module to hold your plugins.
Here's a basic example of how to create a custom plugin:

1. **Create a Plugin Class**: Extend the `ExpressionStrategyBase` class in
   order for your class to be correctly discovered as an `ExpressionStrategy`
   plugin. Then, implement one or more of the expression strategy interfaces
   (e.g., `FileExpressionStrategyInterface`,
   `DerivativeExpressionStrategyInterface`, `StyleExpressionStrategyInterface`)
   to define the types of expressions your plugin can generate.

2. **Define the Expression Generation Logic**: In your plugin class, implement
   the necessary methods and define the logic for generating invalidation
   expressions. This logic can be as simple or as complex as needed to meet
   your caching requirements.

3. **Enable the Plugin**: Ensure your custom module is enabled, then go to the
   Purge configuration page and select your custom plugin from the list of
   available expression strategies.