import { chromium } from "playwright-core";

const executablePath =
  process.env.EDGE_PATH ??
  "C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe";

const browser = await chromium.launch({ executablePath, headless: true });
const results = [];

for (const viewport of [
  { name: "desktop", width: 1440, height: 1000 },
  { name: "mobile", width: 390, height: 844 },
]) {
  const page = await browser.newPage({
    viewport: { width: viewport.width, height: viewport.height },
    deviceScaleFactor: 1,
  });
  const consoleErrors = [];
  page.on("console", (message) => {
    if (message.type() === "error") consoleErrors.push(message.text());
  });

  await page.goto("http://localhost:3000", { waitUntil: "networkidle" });
  const metrics = await page.evaluate(() => {
    const images = [...document.images];
    const overflowing = [...document.querySelectorAll("body *")]
      .filter((element) => {
        const rect = element.getBoundingClientRect();
        return rect.right > document.documentElement.clientWidth + 1 || rect.left < -1;
      })
      .slice(0, 10)
      .map((element) => ({
        tag: element.tagName,
        className: typeof element.className === "string" ? element.className : "",
        text: element.textContent?.trim().slice(0, 60) ?? "",
      }));

    return {
      viewportWidth: window.innerWidth,
      documentWidth: document.documentElement.scrollWidth,
      brokenImages: images.filter((image) => image.complete && image.naturalWidth === 0).length,
      overflowing,
    };
  });

  await page.screenshot({
    path: `../visual-${viewport.name}.png`,
    fullPage: false,
  });
  results.push({ viewport, ...metrics, consoleErrors });
  await page.close();
}

await browser.close();
console.log(JSON.stringify(results, null, 2));
