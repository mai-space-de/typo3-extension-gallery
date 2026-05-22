# Features — `mai_gallery`

Image gallery and retrospectives extension using TYPO3 FAL.
Supports year archives and `sys_category` filtering, sharing the same category
tree as `mai_news`, `mai_faq`, and `mai_timeline`.

---

## 1. Gallery Record

Stored in `tx_maigallery_gallery`.

| Field | Type | Required | Notes |
| --- | --- | --- | --- |
| `title` | `varchar(255)` | ✔ | Label field; used as alt text for cover image in card view |
| `description` | `text` | — | Rich text, rendered with `<f:format.html>` in the detail view |
| `year` | `int` (1900–2100) | — | Used for the year archive filter; 0 = no year |
| `images` | FAL relation (`sys_file_reference`) | — | Ordered; `images` count stored as int in the record |
| `categories` | `sys_category` relation | — | `sys_category_record_mm`; shared category tree |

Cover image: `Gallery::getCoverImage()` returns the first image in the ordered
`ObjectStorage`; returns `null` if the gallery has no images.

---

## 2. sys_category Integration

`mai_gallery` uses the built-in TYPO3 `sys_category` type (`CategoryConfig`) — no
custom category table exists and none may ever be added.

The category tree is **shared** with `mai_news`, `mai_faq`, `mai_testimonials`, and
`mai_timeline`. Category records created or imported for one extension are
immediately available to the others.

Relations are stored in `sys_category_record_mm` via the standard TYPO3 MM
mechanism; no custom join table is needed.

---

## 3. Content Element Plugin

| Property | Value |
| --- | --- |
| Extension key | `mai_gallery` |
| Plugin name | `Gallery` |
| CType | `maigallery_gallery` |
| Controller | `GalleryController` |
| Actions (cached) | `show` |
| Actions (non-cached) | `list` |
| Plugin type | Content element (`PLUGIN_TYPE_CONTENT_ELEMENT`) |
| Icon identifier | `mai-content` (from `mai_base` shared icon set) |
| Backend group | `plugins` |

The FlexForm (`GalleryPlugin.xml`) is attached to the `maigallery_gallery` CType.
The `layout`, `select_key`, `pages`, and `recursive` fields are excluded from the
content element form in favour of the FlexForm fields.

---

## 4. Frontend Rendering

### `listAction` — gallery grid

URL parameter: `{year: int, category: int, currentPage: int}`.

Priority chain for the query result passed to `{galleries}`:

1. `findFilteredAndSorted($category, $year)` — always used; applies both filters
   when provided, falls back gracefully to unfiltered when both are 0.

Template variables assigned to `list.html`:

| Variable | Type | Notes |
| --- | --- | --- |
| `galleries` | `QueryResultInterface` | Full result used for empty-check |
| `years` | `int[]` | Distinct non-zero years, DESC — drives the year filter nav |
| `selectedYear` | `int` | Current URL `year` parameter |
| `selectedCategory` | `int` | Current URL `category` parameter |
| `pagination` | `PaginationInterface` | From `PaginationTrait`; controls prev/next/page-numbers |
| `paginator` | `PaginatorInterface` | `$paginator->paginatedItems` is the visible slice |
| `settings` | `array` | TypoScript/FlexForm settings |
| `contentObject` | `array` | `tt_content` row for the current plugin record |

Paginated items are rendered via `Gallery/Card.html`.
The year filter nav is rendered via `Gallery/YearFilter.html` (only when `{years}` is non-empty).
The pagination nav is rendered via `Gallery/Pagination.html` (only when there is more than one page).

### `showAction` — gallery detail

Resolved via `DetailActionTrait::resolveDetailOrNotFound()` — falls back to a 404
response when the gallery UID is absent or the record is hidden.

Template variables assigned to `show.html`:

| Variable | Type | Notes |
| --- | --- | --- |
| `gallery` | `Gallery` | The resolved gallery entity |
| `settings` | `array` | TypoScript/FlexForm settings |
| `contentObject` | `array` | `tt_content` row |

---

## 5. Picture-Rendering Pipeline

### Overview

All images are stored via TYPO3 FAL (`sys_file_reference`). Rendering delegates to
the `mai:image.picture` ViewHelper from `mai_assets`, which produces a `<picture>`
element with `<source>` elements for avif, webp, and jpeg and a `<img>` fallback.

### Breakpoints

Two rendering contexts define different breakpoint sets:

**Gallery card (cover image — `Gallery/Card.html`)**

| Slot | Width | Height | Crop variant |
| --- | --- | --- | --- |
| 0 (single breakpoint) | 400 px | 300 px | `default` |

`sizes` attribute: `(min-width: 1024px) 400px, 100vw`

**Gallery image in detail view (`Gallery/Image.html`)**

| Slot | Width | Height | Crop variant |
| --- | --- | --- | --- |
| 0 (mobile) | 400 px | 300 px | `default` |
| 1 (desktop) | 800 px | 600 px | `default` |

`sizes` attribute: `(min-width: 1024px) 800px, 100vw`

### Crop variants and the TYPO3 image editor

`cropVariant: 'default'` maps to the built-in TYPO3 image cropping / focus-area
editor. No custom crop variants are declared in the TCA `FileConfig`, so TYPO3
exposes only the `default` crop in the backend image editor.

Editors can set a crop rectangle and a focus point for each image reference.
The focus point is respected by TYPO3's image processor when generating
proportionally cropped variants.

### Output format stack

For each breakpoint slot `mai_assets` generates three formats in order:

```
1. avif   → <source type="image/avif" srcset="…w" sizes="…">
2. webp   → <source type="image/webp" srcset="…w" sizes="…">
3. jpeg   → <img src="…" srcset="…w" sizes="…">    (fallback)
```

Formats that the server cannot produce (e.g. avif when GraphicsMagick lacks the
codec) are silently skipped; the `<source>` element for that format is omitted.

### Above-fold detection and loading attributes

The `mai:image.picture` ViewHelper resolves above-fold status via
`AssetCriticalityResolver`. When `critical="auto"` (the default) and an
`elementUid` is passed:

- **Above fold** → `loading="eager" fetchpriority="high" decoding="sync"` on the
  `<img>` element, plus an HTTP `Link: rel=preload` header for the avif variant.
- **Below fold** (default) → `loading="lazy"` on `<img>`.

The gallery templates do not pass `elementUid`, so all gallery images receive
`loading="lazy"`.

### Alt text

| Context | Source |
| --- | --- |
| Gallery card cover | `{gallery.title}` — the gallery title |
| Gallery detail images | `{image.alternative}` — the FAL `alternative` metadata field |

Editors can set per-image alt text in the TYPO3 backend via the FAL file reference
metadata editor. If left blank, an empty alt string is rendered (treated as
decorative by screen readers).

### Lightbox integration

The detail view wraps the image list in:

```html
<ul data-lightbox-gallery="gallery-{gallery.uid}">
    <li data-lightbox-item>
        <mai:image.picture … />
    </li>
</ul>
```

No JavaScript lightbox library is bundled — `data-lightbox-gallery` and
`data-lightbox-item` are integration hooks for whichever lightbox the theme
provides. `mai_gallery` only injects `gallery.css` (via `<mai:asset.css
identifier="mai-gallery" src="EXT:mai_gallery/Resources/Public/Css/gallery.css" />`).

---

## 6. Year Archive

`GalleryRepository::findDistinctYears()` queries `tx_maigallery_gallery` in raw
mode (`$query->execute(true)`) and collects every distinct `year` value that is
`> 0`, ordered DESC.

The year filter nav is rendered only when `{years}` is non-empty. Selecting a year
sets the URL parameter `year`; selecting "All years" resets it to `0`.

`findByYear(int $year)` is also available for single-year queries if needed by a
custom controller.

---

## 7. FlexForm Configuration

Fields in `Configuration/FlexForms/GalleryPlugin.xml`:

| Field | Type | Default | Notes |
| --- | --- | --- | --- |
| `settings.storagePid` | `group` (pages, max 1) | — | Storage page for gallery records; overrides TypoScript `storagePid` |
| `settings.detailPid` | `group` (pages, max 1) | — | Target page for the show view link on each card |
| `settings.itemsPerPage` | `number` (1–100) | 12 | Overrides the TypoScript constant |

---

## 8. TypoScript Configuration

Constants (with defaults):

```typoscript
plugin.tx_maigallery {
    settings {
        itemsPerPage     = 12
        detailPid        =        # no default — required for show-view links
        listPid          =        # no default — used by the "Back to overview" link
        image {
            maxWidth         = 800
            maxHeight        = 600
            thumbnailWidth   = 400
            thumbnailHeight  = 300
        }
    }
}
```

The `image.*` constants reflect the breakpoint dimensions used in the templates and
can be overridden per site. Note that the templates reference the breakpoint values
directly in the Fluid markup; changing the constants alone does not update the
ViewHelper calls — both must be adjusted together.

---

## 9. Database Tables

### `tx_maigallery_gallery`

| Column | Type | Notes |
| --- | --- | --- |
| `uid` | `int(11)` NOT NULL AUTO_INCREMENT | Primary key |
| `pid` | `int(11)` NOT NULL default 0 | Storage page |
| `tstamp` | `int(11)` NOT NULL default 0 | Last modified (UNIX timestamp) |
| `crdate` | `int(11)` NOT NULL default 0 | Created (UNIX timestamp) |
| `deleted` | `tinyint(4)` NOT NULL default 0 | Soft-delete flag |
| `hidden` | `tinyint(4)` NOT NULL default 0 | Visibility flag |
| `starttime` | `int(11)` NOT NULL default 0 | Access start (UNIX timestamp) |
| `endtime` | `int(11)` NOT NULL default 0 | Access end (UNIX timestamp); 0 = never |
| `sys_language_uid` | `int(11)` NOT NULL default 0 | Translation language |
| `l10n_parent` | `int(11)` NOT NULL default 0 | Default-language record UID |
| `l10n_diffsource` | `mediumblob` | Translation diff cache |
| `t3ver_*` | — | TYPO3 workspace columns |
| `title` | `varchar(255)` NOT NULL default '' | Gallery title |
| `description` | `text` | Rich text body |
| `year` | `int(11)` NOT NULL default 0 | Archive year; 0 = unset |
| `images` | `int(11)` NOT NULL default 0 | FAL reference count |
| `categories` | `int(11)` NOT NULL default 0 | Category relation count |

**Indexes:** `PRIMARY KEY (uid)`, `KEY parent (pid)`, `KEY t3ver_oid (t3ver_oid,
t3ver_wsid)`, `KEY language (l10n_parent, sys_language_uid)`.

Image binaries are stored by TYPO3 FAL in `sys_file` / `sys_file_reference`;
`tx_maigallery_gallery.images` stores only the relation count.

Category links are stored in `sys_category_record_mm` (no custom MM table).

---

## 10. Architecture Constraints

- **No custom category table** — relations must always go through `sys_category` /
  `sys_category_record_mm`.
- **No SCSS** — CSS is provided as a pre-compiled file loaded via `mai:asset.css`;
  SCSS compilation belongs exclusively to `mai_assets`.
- **No mail dispatch** — `mai_gallery` never sends email; it has no dependency on
  `mai_mail`.
- **FAL only** — images must be managed through TYPO3 FAL; no hardcoded file paths.
- **`mai_assets` dependency** — the `mai:image.picture` ViewHelper and
  `mai:asset.css` are provided by `mai_assets`; `mai_gallery` depends on
  `maispace/mai-assets` at runtime.
- **Pagination** — `PaginationTrait` from `mai_base` owns pagination logic; do not
  re-implement pagination in `GalleryController`.
- **Detail-page navigation** — `settings.detailPid` is required for card links;
  `settings.listPid` is required for the "Back to overview" link in the detail
  view. Both are empty by default and must be set per content element or via
  TypoScript.
