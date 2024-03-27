# Purge Queuer File Urls
Integrates with the Purge framework to provide external cache invalidation of
files. Any time a fieldable entity is updated, files referenced from fields on
that entity will be queued for invalidation. In addition, the image styles of
any discovered files will be queued as well.

The Image Styles Queuer plugin will queue invalidations on image style flush.
This includes when an image style is updated and saved.

## Invalidations
Out of the box, the invalidation expression for files will be the URL of the
file. These invalidations will be added to whatever queue plugin is currently
active. A Purger must be configured to handle invalidations of the appropriate
type. This module provides several invalidation types:

- Url (relative)
- Url (absolute)
- Url (wildcard, relative)
- Url (wildcard, absolute)
