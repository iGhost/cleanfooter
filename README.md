# Clean Footer

`ighost/cleanfooter` is a phpBB 3.3 extension that cleans up the bottom of the
board index for anonymous visitors and bots.

## Features

- Generates the “Who is online” list only for registered, non-bot users.
- Removes the anonymous quick-login form from the bottom of the board index.

The normal navigation-bar login link and dedicated login page remain
available.

## Implementation

For anonymous visitors and bot accounts, online-list generation is disabled in
the `core.page_header` event before phpBB calls `obtain_users_online()`.

phpBB 3.3 does not expose a template event around the index quick-login form,
so the form is removed from `index_body.html` through the
`core.twig_environment_render_template_after` event.

## Requirements

- PHP 7.4 or newer
- phpBB 3.3.x
