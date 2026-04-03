### Step 1:

**Open your theme in VSCode.**

**Create a modular folder structure:**
- `styles/blocks/` *(for your `.json` block variations).*
- `assets/css/blocks/` *(for the specific CSS that goes with each variation).*    
- **Create a unique `.json` file** (ex. `paragraph-glow.json`) inside `styles/blocks/`


### Step 2:

*using my glow paragraph block as an example*

**In `theme.json`:** Define the **global palette** (colours like `glow-color` and `glow-bg`).

**In your `paragraph-glow.json`:**

- Add the `title` (ex. "Glow").

- Target the `blockTypes` (ex. `["core/paragraph"]`).

- **Map the UI:** set the `styles` to use the variables you defined in `theme.json`.

**Tip:** linking the JSON styles to your `theme.json` variables, allows the user to change the "glow colour" in the WordPress Global Styles sidebar and the block will update automatically.

### Step 3:

- **create a specific CSS file** for this block (e.g., `assets/css/blocks/paragraph-glow.css`).

- **write your "advanced" styles:** use this file for things JSON can't do, like ***hovers, transitions, and complex box shadows.***

- **use relative colour syntax:** Where possible, Instead of hard-coding colours, use `rgb(from var(--wp--preset--color--glow-color) r g b / 0.5)` to ensure your glow stays synced with the user's colour choices. (see the glow paragraph example)

### Step 4:

**In `functions.php`:**

1. **register the style:** use `register_block_style()` so the "glow" button appears in the UI.

2. **enqueue for performance:** use `wp_enqueue_block_style()` to point to your new `paragraph-glow.css` *(ensures the code **only** loads when the block is used)*.

3. **sync the editor:** use `add_editor_style()` so the glow looks perfect inside the Gutenberg editor.