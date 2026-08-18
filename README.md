# Grid Layout Fix (Round 2) — Root Cause Fix

## What was actually wrong
Both pest grids used Tailwind's `grid` + `divide-x/divide-y` utilities. Two problems
came from that combination, not from missing photos:

1. **The stray line above each image** — `divide-y` works by adding a `border-top`
   to every grid item except the first in each row. With image content of slightly
   inconsistent rendered height, that border-top can visually read as a thin line
   cutting across the top of the photo.
2. **The empty box under the grid** — with 7 pests in a 6-column grid, the 7th item
   (Rodents) falls into a second row by itself. CSS Grid still reserves the *full*
   row's width and height for that row even though only one of six cells is filled,
   which is the big empty white space you saw.

## The fix
Switched both grids from `grid` + `divide-*` to `flex flex-wrap`, with each
individual cell carrying its own border (`border-r-2 border-b-2`) instead of a
shared divide rule. This has two effects:
- No more divide-line artifact, since there's no shared "not-the-first-item" border.
- No more phantom empty row — flexbox only takes up the space its actual items need,
  so a lone item in the last row just sits there at its normal size, no dangling box.

## Files
- `resources/views/home.blade.php`
- `resources/views/services/residential.blade.php`

## How to apply
```
cp grid-fix2/resources/views/home.blade.php sp-pest-control-app/resources/views/
cp grid-fix2/resources/views/services/residential.blade.php sp-pest-control-app/resources/views/services/
cd sp-pest-control-app
npm run build
```
No migration or seeding needed this time — just these two view files.
