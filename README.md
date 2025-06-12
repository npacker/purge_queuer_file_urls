Purge Queuer File Urls
======================

The Purge Queuer File URLs module integrates with the Purge framework to provide external cache invalidation of files. This module ensures that any time a fieldable entity is updated, files referenced from fields on that entity are queued for invalidation. Additionally, the image styles of any discovered files are also queued for invalidation.

Features
--------

*   **Full Purge Ecosystem Integration**: Provides a Queuer for file invalidations, allowing flexible integration with any Queue or Processor plugin(s).
*   **Highly Configurable**: Choose between different strategies for each invalidation case (files, image styles, image derivative), filter based on file schemes (public, private, etc.), and manually configure base URLs.
*   **Image Style Flush Handling**: The Image Styles Queuer plugin queues invalidations on image style flush, including when an image style is updated and saved.
*   **Customizable Invalidation Expressions**: Allows site developers to control how files are invalidated based on the requirements of their external caching layer.

Setup
-----

*   Navigate to `admin/config/development/performance/purge` and enable the **Files Queuer** and **Image Styles Queuer** (if desired).
*   Select the **configure** drop-down on your **Files Queuer** instance to access the configuration options.

### Invalidations

Out of the box, a file's relative URL is used as the invalidation expression. A corresponding Purger **must** be configured to handle invalidations of the appropriate type (e.g., `relativeurl`, `absoluteurl`, etc.). This module provides several suitable invalidation types beyond those included with the base Purge module, to support use-cases relevant to file invalidation.

Extending
---------

Invalidation expression generation can be modified or extended by interacting with the ExpressionStrategy plugin system included with this module. This system allows for fine-grained control over how the URL expressions used for invalidation are generated.

### ExpressionStrategy Plugin System

The `ExpressionStrategy` plugin system is the core mechanism for customizing invalidation expressions. You can create additional plugins if you need to generate custom expressions for specific use cases, or you can use the standard Drupal hook system to alter the plugins already available.

#### Overriding Existing Invalidation Expression Strategies

Implement the hook `expression_strategy_info_alter` in a custom module to override an existing plugin definition with your own.

#### Writing a Custom `ExpressionStrategy` Plugin

If you need to generate custom invalidation expressions, you can create a new plugin class that implements one of the expression strategy interfaces provided by this module. You will need to create a custom module to hold your plugins. Here's a basic example of how to create a custom plugin:

1.  **Create a Plugin Class**: Extend the `ExpressionStrategyBase` class in order for your class to be correctly discovered as an `ExpressionStrategy` plugin. Then, implement one or more of the expression strategy interfaces (e.g., `FileExpressionStrategyInterface`, `DerivativeExpressionStrategyInterface`, `StyleExpressionStrategyInterface`) to define the types of expressions your plugin can generate.
2.  **Define the Expression Generation Logic**: In your plugin class, implement the necessary methods and define the logic for generating invalidation expressions. This logic can be as simple or as complex as needed to meet your caching requirements.
3.  **Enable the Plugin**: Ensure your custom module is enabled, then go to the Purge configuration page and select your custom plugin from the list of available expression strategies.

### Overriding Services

Aside from the `ExpressionStrategy` plugin system, most of the remaining core functionality of this module is exposed through clear interfaces and registered as services. Any of these components can be extended and overridden in a custom module as needed.