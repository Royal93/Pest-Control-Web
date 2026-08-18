/** Tailwind config — SP Pest Control brand palette (terracotta / charcoal / green) */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./app/Livewire/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#D1723C",     // terracotta orange (from logo) — CTAs, links, icons, borders
        primaryDark: "#B45E2E", // darker shade for hover states
        secondary: "#2C343A",   // deep charcoal — dark sections, footer, nav accents
        accent: "#7BAE3C",      // fresh pest-control green — tags, highlights, guarantees
        accentDark: "#679430",
        signal: "#7BAE3C",      // kept as an alias so existing "signal" classes pick up the new accent green
        signalBright: "#8FC24E",
        ink: "#333333",         // dark slate — body text, headings
        inkMuted: "#666666",
        inkFaint: "#8C8C8C",
        line: "#D1723C",        // borders now use the terracotta primary
        bgAlt: "#F5F1EC",       // warm light neutral for alternating sections
      },
      fontFamily: {
        display: ["Oswald", "sans-serif"],
        body: ["Source Sans 3", "sans-serif"],
        mono: ["IBM Plex Mono", "monospace"],
      },
    },
  },
  plugins: [],
};
