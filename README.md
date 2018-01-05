# Secretary
## Still under construction

Message handling utility for websites.

Smarter handling of contact forms and such.

### The Problem

In a Laravel app, when a user submits some kind of contact form as a guest, and you'd like to notify someone of that event, you'd have to find a user or notifiable entity and then send whatever notifications. This package simplifies that by supplying an entity that can handle site messages and send them on to the configured channels, as well as keeping a database record.



