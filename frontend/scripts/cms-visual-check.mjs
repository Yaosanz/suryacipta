import { chromium } from "playwright-core";
import { join } from "node:path";
import { tmpdir } from "node:os";

const email = process.env.CMS_QA_EMAIL;
const password = process.env.CMS_QA_PASSWORD;
const otp = process.env.CMS_QA_OTP;

if (!email || !password || !otp) {
  throw new Error("CMS_QA_EMAIL, CMS_QA_PASSWORD, and CMS_QA_OTP are required.");
}

const executablePath =
  process.env.EDGE_PATH ??
  "C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe";
const browser = await chromium.launch({ executablePath, headless: true });
const context = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
const page = await context.newPage();
const errors = [];

page.on("console", (message) => {
  if (message.type() === "error") errors.push(message.text());
});
page.on("pageerror", (error) => errors.push(error.message));

await page.goto("http://127.0.0.1:8000/admin/login", { waitUntil: "networkidle" });
await page.locator('input[type="email"]').fill(email);
await page.locator('input[type="password"]').fill(password);
await page.locator('button[type="submit"]').click();
await page.locator('input[autocomplete="one-time-code"]').fill(otp);
await page.locator('button[type="submit"]').click();
await page.waitForURL("**/admin", { timeout: 15_000 });

const inspect = async (name) => {
  await page.waitForLoadState("networkidle");
  const metrics = await page.evaluate(() => {
    const viewportWidth = document.documentElement.clientWidth;
    const overflowing = [...document.querySelectorAll("body *")]
      .filter((element) => {
        const rectangle = element.getBoundingClientRect();
        const intersectsViewport = rectangle.right > 0 && rectangle.left < viewportWidth;
        return (
          intersectsViewport &&
          (rectangle.right > viewportWidth + 1 || rectangle.left < -1)
        );
      })
      .slice(0, 10)
      .map((element) => element.className?.toString().slice(0, 100) ?? element.tagName);
    const visibleButtons = [...document.querySelectorAll("button, a")]
      .filter((element) => element.getBoundingClientRect().width > 0)
      .map((element) => element.textContent?.trim())
      .filter(Boolean);

    return {
      viewportWidth,
      documentWidth: document.documentElement.scrollWidth,
      overflowing,
      visibleButtons,
      fontFamily: getComputedStyle(document.body).fontFamily,
      bodyColor: getComputedStyle(document.body).color,
      bodyBackground: getComputedStyle(document.body).backgroundColor,
    };
  });
  const screenshot = join(tmpdir(), `suryacipta-cms-${name}.png`);
  await page.screenshot({ path: screenshot, fullPage: true });

  return { name, screenshot, ...metrics };
};

const results = [];
results.push(await inspect("dashboard-desktop"));

await page.evaluate(() => localStorage.setItem("theme", "dark"));
await page.reload({ waitUntil: "networkidle" });
results.push(await inspect("dashboard-dark-desktop"));

await page.evaluate(() => localStorage.setItem("theme", "light"));
await page.reload({ waitUntil: "networkidle" });

await page.setViewportSize({ width: 390, height: 844 });
await page.goto("http://127.0.0.1:8000/admin", { waitUntil: "networkidle" });
results.push(await inspect("dashboard-mobile"));

await page.setViewportSize({ width: 1440, height: 1000 });
await page.goto("http://127.0.0.1:8000/admin/products", { waitUntil: "networkidle" });
results.push(await inspect("products-desktop"));

const qaProductRow = page.locator("tr", { hasText: "CMS Media QA Product" });
if (await qaProductRow.count()) {
  await qaProductRow.getByRole("link", { name: "Ubah", exact: true }).click();
  results.push(await inspect("product-media-edit-desktop"));
  await page.goto("http://127.0.0.1:8000/admin/products", { waitUntil: "networkidle" });
}

const viewButton = page.getByRole("link", { name: "Lihat", exact: true }).first();
if (await viewButton.count()) {
  await viewButton.click();
  results.push(await inspect("product-view-desktop"));
}

await page.goto("http://127.0.0.1:8000/admin/team-members", { waitUntil: "networkidle" });
results.push(await inspect("team-members-desktop"));

await page.goto("http://127.0.0.1:8000/admin/inquiries", { waitUntil: "networkidle" });
results.push(await inspect("inquiries-desktop"));

await page.goto("http://127.0.0.1:8000/admin/media-assets", { waitUntil: "networkidle" });
results.push(await inspect("media-assets-desktop"));

await page.goto("http://127.0.0.1:8000/admin/activities", { waitUntil: "networkidle" });
results.push(await inspect("activities-desktop"));
const activityViewButton = page.getByRole("link", { name: "Lihat", exact: true }).first();
if (await activityViewButton.count()) {
  await activityViewButton.click();
  results.push(await inspect("activity-view-desktop"));
}

await page.setViewportSize({ width: 390, height: 844 });
await page.goto("http://127.0.0.1:8000/admin/products", { waitUntil: "networkidle" });
results.push(await inspect("products-mobile"));

await browser.close();
console.log(
  JSON.stringify(
    {
      errors,
      results: results.map(
        ({
          name,
          screenshot,
          viewportWidth,
          documentWidth,
          overflowing,
          fontFamily,
          bodyColor,
          bodyBackground,
        }) => ({
          name,
          screenshot,
          viewportWidth,
          documentWidth,
          overflowing,
          fontFamily,
          bodyColor,
          bodyBackground,
        }),
      ),
    },
    null,
    2,
  ),
);
