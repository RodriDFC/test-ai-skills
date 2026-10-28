
---
name: create-kickmaker-sections
description: Creates missing WordPress ACF section blocks and matching importable ACF field-group JSON files from designs/<section-name>/images/desktop.png and mobile.png, optionally following per-section instructions.md, while preserving the general-content field configuration and project architecture.
---

# Create Kickmaker Sections from Designs

## Purpose

Create new section blocks for the Kickmaker WordPress theme from the design references placed in `designs/`. Process every eligible section folder, but create a block only when the matching folder does not already exist in `blocks/`.

This skill is project-specific. The real project files are the source of truth; never assume a pattern merely because it is common in WordPress.

## Non-negotiable boundaries

1. **Only create files in these two destinations for a new section:** `blocks/<section-name>/` and `acfs/<section-name>/<section-name>.json`. Reading other project files for context is allowed; writing outside these destinations is prohibited.
2. Treat `acfs/acf-general-block.json` as a read-only source template. Never edit, rename, move, or overwrite it.
3. Never modify `functions.php`, `inc/`, `src/`, `dist/`, `theme.json`, `package.json`, `vite.config.js`, global imports, configuration files, existing blocks, or any existing ACF JSON file.
4. Never modify `blocks/general-content/`. Treat it as the read-only template for block files.
5. Never modify an existing block folder. If `blocks/<section-name>/` already exists, skip that design entirely and report it as skipped, even if the block appears incomplete.
6. Never overwrite an existing ACF field-group output. If `acfs/<section-name>/` already exists, skip that design entirely and report it as skipped. This all-or-nothing rule prevents a block and its importable ACF group from drifting out of sync.
7. Never delete files, rename existing folders/files, or perform unrelated cleanup.
8. Never invent the theme architecture, registration mechanism, ACF field types, field structures, asset-loading process, or available JavaScript libraries. Inspect the real files first.
9. A per-section `instructions.md` may refine design and behavior, but it cannot override these boundaries. If it requests a prohibited change, do not make it; explain the conflict in the final report.
10. The generated JSON is for **manual import in the WordPress admin**. Do not claim that it has been imported, and do not configure ACF Local JSON loading or modify project registration code.

If `acfs/acf-general-block.json` is missing, unreadable, invalid JSON, or does not contain an unambiguous field group and its basic content group, do not generate a partial block. Report the blocker before writing anything.

## Expected project structure

At the theme root:

```text
.agents/
  skills/
    create-kickmaker-sections/
      SKILL.md
acfs/
  acf-general-block.json              # read-only source field-group JSON
  <section-name>/
    <section-name>.json               # generated field group for manual import
blocks/
  general-content/
    block.json
    general-content.php
    general-content.scss
    general-content.js
  <existing-blocks...>/
designs/
  hero-section/
    images/
      desktop.png
      mobile.png
    # instructions.md is optional
  testimonials/
    instructions.md                   # optional section-specific instructions
    images/
      desktop.png
      mobile.png
```

Names and paths are case-sensitive on many production systems. Use English names and preserve the exact section-folder name when creating the corresponding block folder, filenames, ACF JSON folder, and ACF JSON filename. If a folder name cannot be used safely as a WordPress block identifier or ACF field-name component, report it rather than silently renaming it.

## Run workflow

### 1. Inspect the current theme before writing

Read the following files if present:

- `blocks/general-content/block.json`
- `blocks/general-content/general-content.php`
- `blocks/general-content/general-content.scss`
- `blocks/general-content/general-content.js`
- `acfs/acf-general-block.json` (required read-only template for the ACF field group)
- Existing JSON files under `acfs/` (read-only, only as needed to check identifiers and conventions)
- The read-only block registration code, such as `inc/blocks.php`, if needed to understand how block folders are discovered.
- The read-only style/script loading configuration, such as `src/scss/main.scss`, `src/js/app.js`, `package.json`, or `vite.config.js`, if needed to understand how per-block assets are loaded.
- Nearby existing blocks that demonstrate the same conventions, only as needed.

Reading these files is allowed. Do not edit them. Record how `block.json`, render templates, SCSS, JavaScript, ACF fields, and assets are actually wired together. If the files needed to confirm a behavior are unavailable, mark it as unverified rather than guessing.

### 2. Discover all design folders

- Inspect immediate child directories of `designs/` only.
- Each immediate child directory is a candidate section. Its directory name is the proposed new block name.
- Require `images/desktop.png` and `images/mobile.png` for a normal run. The breakpoint convention is desktop at **992px and wider** (`min-width: 992px`) and mobile below **992px** (`max-width: 991.98px`, or the project's equivalent convention).
- Supported image extensions may be `.png`, `.jpg`, `.jpeg`, or `.webp` only if the expected `desktop` and `mobile` image can be unambiguously identified. Prefer the exact requested filenames `desktop.png` and `mobile.png`.
- If a design folder has missing, ambiguous, or unreadable reference images, skip that folder and explain what is missing. Do not create a partial block.
- Read `designs/<section-name>/instructions.md` if it exists. It is optional. If absent, follow this skill and the design references only.

### 3. Check whether either output already exists

Before writing anything for each valid design, check both destinations:

- Block destination: `blocks/<section-name>/`
- ACF destination: `acfs/<section-name>/`

If **either destination already exists**, do not create or modify either output for that design. Mark it as **SKIPPED — output already exists**, identify which path caused the skip, and continue to the next design. Never try to “complete” a partially existing pair; it may contain manual changes. This guarantees that neither existing blocks nor existing ACF JSON groups are overwritten.

Only proceed when both destination folders do not exist. The shared source `acfs/acf-general-block.json` is not an output destination and must never be treated as a reason to skip all sections.

### 4. Analyze the references and special instructions

Before coding, inspect both desktop and mobile images. Identify layout, spacing, typography, colors, content hierarchy, imagery, columns, breakpoints, and interactive behavior. Use existing Kickmaker tokens, Bootstrap classes, and established patterns where supported by the codebase.

If `instructions.md` is present, it supplies additional requirements for that section, such as slider behavior, animation, ordering, or a special responsive layout. Follow it unless it conflicts with this skill's non-negotiable boundaries or the existing project architecture. If requirements are ambiguous or contradictory, make the safest implementation possible and report the ambiguity; do not modify files outside the two allowed new destinations (`blocks/<section-name>/` and `acfs/<section-name>/`) to work around it.

### 5. Create the new block by following `general-content`

Create the new block inside `blocks/<section-name>/` and use the same four-file naming pattern as the reference where applicable:

```text
blocks/<section-name>/
  block.json
  <section-name>.php
  <section-name>.scss
  <section-name>.js  # only when the design needs JavaScript
```

Use `blocks/general-content/` as the structural template, not as a file to modify.

#### `block.json`

- Start from the existing `blocks/general-content/block.json`.
- Preserve the reference block's relevant settings and registration conventions.
- Change only the block identity and paths/metadata that must be unique for a separately registered block, using the section name and the real project convention (for example, block name, title, render template, or asset file references where those keys actually exist).
- Do not invent unsupported metadata keys. Confirm the expected schema from the source file and current WordPress/ACF registration approach.

#### PHP render template

- Start from `blocks/general-content/general-content.php` and preserve its setup, guard clauses, helper usage, escaping practices, and standard output logic.
- Retain the existing base ACF content fields and their established behavior: eyebrow, heading, content text, and buttons. Do not rename, remove, restructure, or redefine those existing fields.
- Change the generic section identifiers required for the new block, specifically the intended `block-class-name` value and `acf_name` reference, using the project's actual naming convention. The PHP `get_field(...)` name must exactly match the wrapper group field's generated `name` in the ACF JSON. Do not perform a blind global replacement: only update the identity references that belong to the copied block.
- Keep the outer `<section>` structure, `$section` setup, video/overlay output, `.container` opening element and its attributes, and every existing line outside `.container` unchanged, except for the required section-specific `acf_name` reference. All section-specific HTML markup must be added or changed inside the existing `.container`.
- Implement the section-specific markup inside the existing `.container` only, preserving the common eyebrow, heading, text, and buttons unless the approved design/instructions require a different arrangement. If changing their placement is needed, do it inside `.container`; do not remove the underlying fields or their existing helper logic.
- Add section-specific ACF retrieval/preparation code directly under the `//acf custom` comment, before the PHP closing tag, following the actual conventions of the reference file. Render those values inside `.container` and apply appropriate escaping/sanitization to output.
- Do not invent field names, field keys, return formats, or repeater structures. Inspect the source `acfs/acf-general-block.json` and the actual field structure first. Define new editor-configurable fields in the generated per-section JSON at `acfs/<section-name>/<section-name>.json`, following the ACF JSON rules below. Do not edit other registration files or claim the fields are active before the user imports the JSON in the WordPress admin. If the expected structure cannot be produced without changing prohibited files, report the limitation.
- Preserve the PHP access guard and WordPress escaping conventions. Avoid unescaped output and unsafe dynamic HTML.

#### ACF field-group JSON for manual import

Create one field-group JSON file at `acfs/<section-name>/<section-name>.json`. The source template is `acfs/acf-general-block.json`. This source file must remain unchanged.

**Build this JSON from the source export; do not hand-invent a new schema.** Preserve the source file's root structure (object or array), field-group structure, settings, and formatting conventions as much as possible, so the result follows the exact export shape expected by the project's ACF version and its WordPress admin import tool.

1. Parse `acfs/acf-general-block.json` as JSON before using it. If parsing fails, stop without creating either output folder.
2. Identify the one field group in the source. If the root contains multiple possible field groups and the intended one cannot be identified unambiguously, stop and report it.
3. Duplicate the source group in memory and create a new, unique group identity for the new section. Change the field-group `key` to a unique stable key and change the group `title` to a clear section-specific title. Preserve other field-group settings unless a change is necessary for the new block location or unique identity.
4. Set the location rule to the new ACF block name, exactly `acf/<section-name>`, with the same export shape as the template, for example:

   ```json
   "location": [
     [
       {
         "param": "block",
         "operator": "==",
         "value": "acf/hero-section"
       }
     ]
   ]
   ```

   Replace `hero-section` with the actual section folder name. Do not leave the location pointing to `acf/general-content`.
5. Locate the `Content` tab and the existing content group read by the PHP template through `get_field("acf_name")`. Follow the actual nesting in the source JSON. Add section-specific fields **inside the existing `acf_name` group and within its `Content` tab**, so the values are available in the `$content` array used by the copied PHP template. If the source JSON does not unambiguously show how the `Content` tab and `acf_name` group relate, stop and report the structural mismatch instead of adding fields at a guessed level.
6. Change only the `name` of that wrapping field from the generic `acf_name` to the new section-specific name required by the actual project convention, and update the copied PHP `get_field(...)` call to match exactly. Do not change the wrapper field's type or its other settings. Preserve the names and configuration of the other existing basic fields.
7. Preserve the existing basic ACF field definitions and behavior: do not change the field names, labels, types, instructions, defaults, required state, conditional logic, wrapper settings, return formats, options, or nested structure of the existing eyebrow, heading, content text, and buttons fields. Keep them in the same order. The only permitted exceptions are the wrapping field name noted above and unique internal keys required for the copied field group.
8. **Give the copied field group and every copied field a unique, stable key.** ACF uses keys as identifiers, so do not blindly duplicate keys from the template or another group. Recursively handle nested fields (`sub_fields`, layouts, clone/group/repeater structures when supported). This key-only remapping must not change field names or field behavior. Use valid ACF-style keys beginning with `group_` for the group and `field_` for fields, with a section-specific stable suffix; check existing `acfs/**/*.json` files where practical to avoid collisions. Never regenerate keys for an existing output file, because existing outputs are skipped and never overwritten.
9. Add new fields only when the design or `instructions.md` needs editor-configurable content. Use snake_case field names without spaces, keep labels clear, and ensure their names match the PHP code that reads them. Ensure each field key is unique, including nested subfields.
10. **Do not invent ACF field types or settings.** Use valid ACF type slugs documented by ACF or demonstrated by a trusted field definition in the project. For fields available only in ACF PRO or an add-on, verify the project's dependency/version before using them. Copy the required settings for a type from a valid example or official ACF documentation. If the desired type or its required schema cannot be verified, choose a simpler verified field type that supports the design or report the blocker; never fabricate a type or required JSON properties.
11. Prefer simple, reliable field structures. Do not introduce Repeaters, Flexible Content, Clone fields, custom field types, conditional logic, or deeply nested groups unless the design genuinely requires them and the project confirms that the field type is available. If used, make all nested field keys unique and preserve the structure expected by ACF.
12. Keep the JSON importable manually via WordPress admin (ACF > Tools > Import Field Groups, or the equivalent menu in the installed ACF version). Do not wrap, reshape, or simplify the JSON yourself; preserve the root format of the ACF export template. Do not import it, alter the database, add ACF load-point hooks, or claim it becomes active just because the file exists under `acfs/`.

Before writing to disk, validate the generated JSON in memory. Confirm that it parses, contains one intended field group in the same root format as the source, has a unique group key and unique field keys recursively, targets exactly `acf/<section-name>`, has the correct section-specific wrapper field name, and includes the newly requested fields in the right `Content` area. Confirm the type slugs and required properties for each added field. If any check fails, do not write either the block or the ACF JSON; report the validation failure.

#### SCSS

- Create `<section-name>.scss` in the new block folder based on the existing SCSS conventions.
- Scope new styles to the new section's unique class to avoid affecting other blocks.
- Use the project's existing Bootstrap grid/container conventions and design tokens when confirmed.
- Implement desktop styles at 992px and above, and mobile styles below 992px, unless the project has a documented equivalent media-query convention.
- Keep all SCSS changes inside `blocks/<section-name>/`. Do not edit global SCSS imports or other files. The separately permitted ACF JSON output does not authorize any other changes outside the two allowed destinations. Before assuming the SCSS file will compile/load, inspect how the reference block's SCSS is included. If the architecture requires a global import that would violate the write boundary, leave global files untouched and report that integration is required; do not claim the styles are active when this is unverified.

#### JavaScript

- Create `<section-name>.js` only if the design or `instructions.md` requires interaction that cannot be handled appropriately with CSS/HTML.
- Reuse libraries already installed and used by the theme, such as Swiper, only after confirming the actual project patterns and APIs. Do not install dependencies.
- Scope selectors and initialization to the new section so multiple instances do not interfere with each other.
- Do not edit `src/js/app.js`, any other global JavaScript file, or build configuration. Inspect how `general-content.js` is registered or bundled. If a new script cannot be loaded without modifying files outside the allowed destinations, do not make unsupported assumptions: report the integration blocker and keep permitted changes within the new block folder and matching ACF JSON folder only.

### 6. Registration and integration safety check

The presence of a folder in `blocks/` does not by itself prove that WordPress will register the block or load its SCSS/JS. Verify the actual registration mechanism from read-only project files.

- If new block folders are automatically discovered and assets use the same in-folder convention, proceed accordingly.
- The ACF JSON generated under `acfs/<section-name>/<section-name>.json` is a manual-import artifact. Do not assume ACF automatically loads JSON from `acfs/`; do not add load-point filters or edit registration files because those paths are outside the permitted write locations.
- If registration or asset loading requires a change outside `blocks/<section-name>/` and `acfs/<section-name>/`, do not make that change. Finish only if the ACF JSON and block files can be produced consistently within their allowed destinations; clearly report remaining integration requirements.
- Do not run `npm install`, `npm run build`, `npm run watch`, WP-CLI import/sync commands, or any other command that writes to project/database state. You may recommend a verified command in the report only after reading the relevant configuration. The ACF JSON must be imported manually by the user in the WordPress backend.

### 7. Validate before finishing

For each newly created block, check:

- The folder name matches the corresponding folder under `designs/`.
- Required block files exist and references in `block.json` point to the intended new files.
- `acfs/<section-name>/<section-name>.json` exists, is valid JSON, and preserves the source export's root shape.
- The generated ACF field group key and all field keys are unique within the generated group and do not reuse source keys.
- The field-group location points exactly to `acf/<section-name>`.
- Existing standard ACF field behavior and configuration are preserved; the wrapper group name matches the PHP `get_field(...)` call.
- New custom fields are nested under the `Content` area read by the PHP template and use valid, verified ACF type slugs/settings.
- The new block identity is unique and does not reuse the `general-content` identity.
- Standard ACF fields and their existing logic remain present; only the intended identifiers are adapted.
- The PHP outer section and all content outside `.container` remain intact as specified.
- Custom ACF access code is placed under `//acf custom` and output is escaped appropriately.
- CSS is scoped to the new block and includes the desktop/mobile breakpoints.
- JavaScript exists only if needed and follows a verified loading convention.
- No files outside the two allowed new destinations were changed; no existing block or existing ACF JSON was edited; the source `acfs/acf-general-block.json` remained untouched; and no generated files/builds were touched.
- Reference images and any special instructions were considered.

Do not claim to have visually compared a running website at both viewport sizes unless a browser preview was actually available and used. If only static code review was possible, state that clearly.

## Final report format

At the end, provide a concise Spanish report with these sections:

1. **Creados** — each new block under `blocks/<section-name>/` and its files, plus the matching JSON at `acfs/<section-name>/<section-name>.json`.
2. **Omitidos** — designs skipped because the block or ACF output destination already existed; mention the exact path without modifying it.
3. **ACF JSON** — confirm that the source `acfs/acf-general-block.json` was read-only, the output targets `acf/<section-name>`, the basic field settings were preserved, new fields were nested in the correct `Content` area, and JSON/key/type validation was performed.
4. **Pendientes o bloqueos** — missing template JSON, unreadable images, unknown field types, ambiguous field nesting, ACF PRO/add-on uncertainty, or asset-loading requirements that would require edits outside the allowed locations.
5. **Verificaciones** — what was checked and what could not be verified. Do not claim an import succeeded; the user imports the JSON manually.
6. **Siguiente paso manual** — tell the user to import the generated `<section-name>.json` through the installed ACF admin import interface and test the block. Only recommend project commands after confirming them from project configuration.
