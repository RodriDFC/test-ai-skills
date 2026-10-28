const sharp = require("sharp");
const fs = require("fs");
const path = require("path");
const { globSync } = require("glob");

const srcDir = path.join(__dirname, "../src/images");
const distDir = path.join(__dirname, "../dist/images");

if (!fs.existsSync(distDir)) {
    fs.mkdirSync(distDir, { recursive: true });
}

// Normalize Windows backslashes to forward slashes for glob pattern matching
const normalizedSrcDir = srcDir.replace(/\\/g, "/");

// Find all images in src/images including jpg, jpeg, png, gif, svg, webp
const images = globSync(`${normalizedSrcDir}/**/*.{jpg,jpeg,png,gif,svg,webp}`);

// Clean up files in dist/images that no longer exist in src/images
if (fs.existsSync(distDir)) {
    const normalizedDistDir = distDir.replace(/\\/g, "/");
    const distFiles = globSync(`${normalizedDistDir}/**/*.*`);

    distFiles.forEach(distFile => {
        const relativePath = path.relative(distDir, distFile);
        const parsed = path.parse(relativePath);
        let originalExists = false;

        const exactSrc = path.join(srcDir, relativePath);
        if (fs.existsSync(exactSrc)) {
            originalExists = true;
        } else if (parsed.ext === ".webp") {
            const possibleExts = [".jpg", ".jpeg", ".png"];
            for (const ext of possibleExts) {
                const srcWithExt = path.join(srcDir, path.join(parsed.dir, parsed.name + ext));
                if (fs.existsSync(srcWithExt)) {
                    originalExists = true;
                    break;
                }
            }
        }

        if (!originalExists) {
            fs.unlinkSync(distFile);
            console.log(`Deleted stale image in dist: ${relativePath}`);
        }
    });
}

if (images.length === 0) {
    console.log("No images found to optimize.");
}

images.forEach(img => {
    const relativePath = path.relative(srcDir, img);
    const destPath = path.join(distDir, relativePath);
    const destSubdir = path.dirname(destPath);

    if (!fs.existsSync(destSubdir)) {
        fs.mkdirSync(destSubdir, { recursive: true });
    }

    const ext = path.extname(img).toLowerCase();
    const nameWithoutExt = path.parse(img).name;
    const webpPath = path.join(destSubdir, `${nameWithoutExt}.webp`);

    if (ext === ".svg" || ext === ".gif") {
        fs.copyFileSync(img, destPath);
        console.log(`Copied: ${relativePath}`);
    } else if (ext === ".jpg" || ext === ".jpeg") {
        sharp(img)
            .jpeg({ quality: 80 })
            .toFile(destPath)
            .then(() => console.log(`Optimized JPEG: ${relativePath}`))
            .catch(err => console.error(`Error optimizing ${relativePath}:`, err));

        sharp(img)
            .webp({ quality: 85 })
            .toFile(webpPath)
            .then(() => console.log(`Generated WebP: ${nameWithoutExt}.webp`))
            .catch(err => console.error(`Error WebP for ${relativePath}:`, err));
    } else if (ext === ".png") {
        sharp(img)
            .png({ quality: 80 })
            .toFile(destPath)
            .then(() => console.log(`Optimized PNG: ${relativePath}`))
            .catch(err => console.error(`Error optimizing ${relativePath}:`, err));

        sharp(img)
            .webp({ quality: 85 })
            .toFile(webpPath)
            .then(() => console.log(`Generated WebP for ${relativePath}: ${nameWithoutExt}.webp`))
            .catch(err => console.error(`Error WebP for ${relativePath}:`, err));
    } else if (ext === ".webp") {
        fs.copyFileSync(img, destPath);
        console.log(`Copied WebP: ${relativePath}`);
    }
});
