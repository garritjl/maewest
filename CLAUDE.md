# CLAUDE.md

Website for Mae West, a gallery/art space in Lausanne, Switzerland (see README.md). Built on **Kirby CMS 4** (PHP 8.1–8.4, file-based, no database) from the Kirby Starterkit. Deployed to `rookiemistake.garrit.net` (see CNAME). There is no build step, bundler, or test suite.

## Running locally

- `composer start` → `php -S localhost:8000 kirby/router.php` (Panel at `/panel`).
- `kirby/` is the vendored CMS core; do not edit it.
- `site/config/config.php` has `debug => true`; must be false in production.

## Content model

Content lives in `content/` as `.txt` files (Kirby fields separated by `----`), one folder per page.

- `content/works/<YYYYMMDD>_<slug>/` — the exhibitions/projects. Each is a `post` page with fields: `title`, `subtitle`, `number` (sequential exhibition number), `date`, `description`, `textede` (the "Texte de" author credit), `footnotes`, `poster` (cover image), `pics` (gallery files), `map` (optional). Images sit in the same folder with `.jpg.txt` sidecars (uuid metadata).
- `content/works/works.txt` is the parent "Works Index"; unlisted (no number prefix).
- Numbered folders `1_table`, `2_about`, `3_contact` are the listed top-level pages that make up the nav (`$site->children()->listed()`). `home`, `coverflow`, `sandbox`, `photography`, `error` are unnumbered/unlisted.
- `content/site.txt` holds site title and footer.
- Blueprints: `site/blueprints/pages/post.yml` (works), `sections/works.yml` (Panel list of works), plus starterkit leftovers (album, note, photography).

## Templates and snippets (`site/`)

The live design uses the `*2` variants and the templates below; much of the rest is unused Starterkit boilerplate (`header.php`, `footer.php`, `album`, `note(s)`, `photography`, `works`, `coverflow.*`, `layouts`, models/controllers/collections for notes and albums).

- `templates/home.php` — standalone HTML page (does not use header2). Renders a GSAP "infinite cover flow" carousel of works' posters. PHP builds `carouselTitles/Subtitles/Dates/Numbers` arrays from `page('works')->children()->listed()` and injects them as JS globals for `assets/js/coverflow.js`. Dates are formatted in French via `IntlDateFormatter('fr_FR')`. Styles: `assets/css/home.css`.
- `templates/post.php` — a single work; standalone HTML (does not use a header snippet, only `footer2`). Poster + thumbnail gallery with inline `selectImg()` JS, description, textede, footnotes, optional map, and previous/next arrows via `prevListed()/nextListed()`. Styles: `assets/css/post.css`.
- `templates/table.php` — the "index" table of all works (reverse order) using `header2`/`footer2`. Styles: `assets/css/index.css`.
- `templates/about.php`, `contact.php`, `default.php` — text pages using `header2`/`footer2`.
- `snippets/header2.php` / `footer2.php` — current shared header/footer for text pages.

The nav markup (works pages joined by a `⍟` separator) is duplicated in `home.php`, `post.php`, and `header2.php`; change all three when editing the nav.

## Frontend

- Plain CSS in `assets/css/` (`home.css`, `post.css`, `index.css`; `satoshi.css`/`candydarling.css` are font faces from `assets/fonts/`). Typekit stylesheet loaded in header2/post. Images/ornaments in `assets/images/` (logo `MWlogo_castiron.png`, iron arrows, borders).
- JS in `assets/js/`; `coverflow.js` loads GSAP 3.7 (+ScrollTrigger, Draggable) from Skypack, falling back to jsDelivr.
- Favicons/manifest are referenced from the site root (`/favicon-32x32.png`, etc.).

## Conventions and gotchas

- Templates use Kirby helpers: `->kti()` (inline kirbytext), `->kt()`, `->esc()`, `->toFile()`, `->toDate()`.
- New exhibition: create a page in the Panel (or a `content/works/YYYYMMDD_slug/` folder) using the `post` template and give it a numeric-less folder name; it becomes "listed" via status in the Panel. The home carousel, index table, and prev/next arrows all derive from the listed works, so ordering follows the folder/date order.
- `.gitignore` excludes `site/cache`, `site/sessions`, `site/accounts`, `media/`, and the Kirby license — never commit these.
- Commit messages in this repo are short, lowercase, and descriptive (e.g. "titlebox home media rules").
