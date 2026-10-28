import { defineConfig } from "vite";
import { resolve } from "path";
import fs from "fs";

export default defineConfig({
    css: {
        preprocessorOptions: {
            scss: {
                api: "modern-compiler",
                quietDeps: true,
                silenceDeprecations: ["import", "global-builtin", "color-functions"]
            }
        }
    },
    plugins: [
        {
            name: "copy-fonts",
            buildStart() {
                const srcFonts = resolve(__dirname, "fonts");
                const distFonts = resolve(__dirname, "dist/fonts");
                if (fs.existsSync(srcFonts)) {
                    fs.cpSync(srcFonts, distFonts, { recursive: true });
                }
            }
        }
    ],
    build: {
        outDir: "dist",
        emptyOutDir: false,

        lib: {
            entry: resolve(__dirname, "src/js/app.js"),
            name: "Kickmaker",
            formats: ["iife"],
            fileName: () => "js/main.js"
        },

        cssCodeSplit: false,

        rollupOptions: {
            output: {
                assetFileNames: assetInfo => {
                    if (assetInfo.name?.endsWith(".css")) {
                        return "css/main.css";
                    }

                    return "assets/[name][extname]";
                }
            }
        }
    }
});
