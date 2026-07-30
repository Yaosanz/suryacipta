import { chromium } from "playwright-core";

const executablePath =
  process.env.EDGE_PATH ??
  "C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe";

const browser = await chromium.launch({ executablePath, headless: true });
const page = await browser.newPage({ viewport: { width: 1280, height: 900 } });

await page.goto("http://localhost:3000/kontak", { waitUntil: "networkidle" });
await page.getByLabel("Nama *").fill("Browser E2E Test");
await page.getByLabel("Perusahaan").fill("Launch QA");
await page.getByLabel("Email *").fill("browser-e2e@example.com");
await page.getByLabel("No. HP / WhatsApp *").fill("081282333021");
await page.getByLabel("Jenis proyek *").selectOption({ label: "Perkantoran / Komersial" });
await page.getByLabel("Estimasi luas").fill("75 m2");
await page.getByLabel("Rentang anggaran").selectOption({ label: "Rp50 - 150 juta" });
await page
  .getByLabel("Pesan *")
  .fill("Pengujian browser end to end dari form Next.js menuju API Laravel dan MySQL.");
await page.getByRole("button", { name: "Kirim Permintaan" }).click();
await page.getByText("Permintaan telah diterima. Tim kami akan menghubungi Anda.").waitFor();

console.log("Contact form submitted successfully through the browser.");
await browser.close();
