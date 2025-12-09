import { defineConfig } from "vitest/config";

export default defineConfig({
  test: {
    environment: "jsdom",
     include: ["tests/unit/**/*.test.ts"],
     exclude: ["tests/e2e/**"],  
    globals: true,
    setupFiles: "./tests/setup.ts",
    coverage: {
      provider: "v8",
      reporter: ["text", "html"],
      reportsDirectory: "./tests/coverage",
      include: ["src/**/*.{ts,tsx}"],
      exclude: ["src/main.ts"],
    },
  },
});
