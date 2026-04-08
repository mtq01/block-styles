# Block Styles

*There are 2 main for approaches creating block styles:*

**1. [Classic / Manual]:  Steps** 
-`functions.php` to wire everything up manually in PHP (basically how we did today)
- `CSS Files` that live in `assets/css/blocks`
- `register_block_style()` to register the style & add it to the block editor UI
- `wp_enqueue_block_style()` to load the CSS (only when the block is used)
- *This approach does not require an additional `.json` file & if one is used it will conflict with the manual `functions.php` approach.*

**[Important]**:  With Classic, the reason an additional `.json` file is not needed is bcuz we have already **declared** `core/paragraph` in the function file *(See the screenshot, line 27)*.

**2. [Modern  / Automatic]: Steps** 
- Auto registers the block style if you use this **folder** structure: `styles/blocks/arctic-paragraph.json`
- `wp_enqueue_block_style()` is still required for css `assets/css/blocks/arctic-paragraph-glow.css`
- Requires WP v6.6 or higher (Latest WP version is 6.9)

**[Important]**: With the Modern approach, your `.json` file tells WP the new block style exists **without needing** to use `register_block_styles()`

## Conflicts

If the `.json` files are auto-registering a style with the same `name` as what you're calling in `register_block_styles`, WP ends up with duplicate registrations which can cause the following issues:

- Styles appearing twice in the Block Editor
- One registration silently overriding another.
- CSS not loading as expected bcuz WP gets confused about which registered style it needs. (the JSON files end up competing with each other and neither wins)


----------------------------------------------------------------------------------------------------


# Creating Block Styles - Workflow

## Classic / Manual Approach

### Step 1: Set Up Your Folder Structure

open your theme in VSCode and create this folder path:

- `assets/css/blocks/`


### Step 2: Write Your CSS

- create your CSS file, example: `blocktype-style.css` & add your styles.
- consider what you can/cant style in the Block Editor UI

Where possible, use variables (you will see how i used this in `paragprah-glow-style.css`):
```
rgb(from var(--wp--preset--color--glow-color) r g b / 0.5)
```

### Step 3: Register everything in `functions.php`

1. **register the style** — `register_block_style()` adds the style option to the block editor UI
2. **enqueue the CSS** — `wp_enqueue_block_style()` loads your CSS, only on pages where the block is used
3. **sync the editor** — `add_editor_style()` ensures the style previews correctly inside Gutenberg

> If using multiple styles (3+ ish), use the classic version of the universal loader in `functions.php`. it loops through a hardcoded `$styles` array so you don't have to register and enqueue each one manually.

finished. easy.


## Modern / Automatic (JSON)

### Step 1: Set Up Your Folder Structure

Open your theme in VSCode and create these folders:

- `styles/blocks/` to hold your unique `.json` file
- `assets/css/blocks/` to hold the block CSS file

**naming convention:** JSON and CSS filenames must match if you're using the **Modern/Automatic Loop**
- example: `paragraph-glow.json` and `paragraph-glow.css`

> if you're only registering a couple block styles, you can use `bs_register_block_styles()` instead and skip the universal loader entirely.



### Step 2: Define Your Style in JSON

*using the glow paragraph as an example.*

**in `theme.json`:** define any global palette values your style depends on (ex. `glow-color`, `glow-bg`).

**in `styles/blocks/paragraph-glow.json`:**
- set a `title` (ex. `"Glow"`)
- set `blockTypes` to target the correct block (ex. `["core/paragraph"]`)
- map your `styles` to the variables defined in `theme.json`

> linking your JSON styles to `theme.json` variables means users can change the glow colour in the WP blobal styles sidebar and the block updates automatically.



### Step 3: Write Your CSS

create `assets/css/blocks/your-block-style.css` & write CSS for anything JSON can't handle (hovers, transitions, complex box shadows, all the fun stuff.)

where possible, use variables (in `paragraph-glow-style` there is an example of this)
``` exmaple:
rgb(from var(--wp--preset--color--glow-color) r g b / 0.5)
```
this keeps your styles synced with the user's colour choices in Global Styles. takes some critical thinkig to get right

---

### Step 4: Register Everything in `functions.php`

1. **enqueue the CSS** — `wp_enqueue_block_style()` loads your CSS, only on pages where the block is used
2. **sync the editor** — `add_editor_style()` ensures the style previews correctly inside Gutenberg

> `register_block_style()` is **not** needed. the JSON file handles registration automatically.
