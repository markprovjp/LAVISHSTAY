const { defineConfig } = require("vitest/config");
const react = require("@vitejs/plugin-react");

module.exports = defineConfig({
  plugins: [react()],
  test: {
    globals: true,
    environment: "jsdom",
    setupFiles: "./src/setupTests.js",
  },
});
