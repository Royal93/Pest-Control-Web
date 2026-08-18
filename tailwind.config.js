/** Tailwind config — color tokens matching the SP Pest Control design system */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./app/Livewire/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#147CB9",   // blue — CTAs, links, icons
        secondary: "#354F8E", // navy — hover states
        signal: "#C1272D",    // red — accent only (tags, logo mark, chat launcher)
        signalBright: "#DC3238",
        ink: "#1D2A4D",
        inkMuted: "#5A6B85",
        inkFaint: "#8A97AC",
        line: "#147CB9",
        bgAlt: "#EFF5F9",
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
