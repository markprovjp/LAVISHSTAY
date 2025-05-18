const { defineConfig } = require("vite");
const react = require("@vitejs/plugin-react");
const path = require("path");

// https://vitejs.dev/config/
module.exports = defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./src"),
    },
    extensions: [".js", ".jsx", ".json"],
  },
  server: {
    port: 3000,
    open: true,
  },
  optimizeDeps: {
    esbuildOptions: {
      loader: {
        ".js": "jsx",
      },
    },
  },
});
